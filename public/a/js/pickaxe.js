const imgSize = 100;
const parts = ["handle", "head", "binding"];

const modifierDescriptions = [
    ["Rubber bands", "Makes the pickaxe weigh a little less."],
    ["Wings", "Makes the pickaxe weigh a lot less."],
    ["Energising grip", `Gives you a ${energyGripGainChance * 100}% chance to get ${energyGripEnergyGain} energy back.`],
    ["Softening grip", "Reduces the roughness of the handle."],
    ["Paper", `Allows you to have ${paperModifiers - 1} extra modifier${paperModifiers == 2 ? "" : "s"} on the pickaxe, but it loses ${paperLevelDebuff} level${paperLevelDebuff == 1 ? "" : "s"}.`],
    ["Diamond-tipped drill", "Increases the strength of the pickaxe a lot."],
    ["Bayonet", "Increases the strength of the pickaxe a little."],
    ["Clover", "Increases the luck of the pickaxe."],
    ["Reinforcement", "Increases the durability of the pickaxe, but makes it slightly heavier."],
    ["Level-up", "Increases the level of the pickaxe by {5}."],
];

async function colourise(src, gem, imageData = new ImageData(imgSize, imgSize)) {
    await gemsInfo;
    
    let canvas = $("<canvas>")[0];
    let ctx = canvas.getContext('2d');
    canvas.width = imgSize;
    canvas.height = imgSize;

    let img = new Image();
    await new Promise((res, rej) => {
        img.onload = res;
        img.src = src;
    });

    ctx.drawImage(img, 0, 0, imgSize, imgSize);
    let partData = ctx.getImageData(0, 0, imgSize, imgSize).data;

    let gemColour = hexToRgb("#"+gemsInfo[gem].colour);

    let colours = ["r", "g", "b"];
    for (let i = 0; i < partData.length; i += 4) {
        let alpha = partData[i+3];
        if (alpha > 0) {
            for (let c in colours)
                imageData.data[i+Number(c)] = Math.round(gemColour[colours[c]] * (partData[i] / 255));
            imageData.data[i+3] = alpha;
        }
    }

    return imageData;
}

async function placeOnImageData(imageData, src) {
    let canvas = $("<canvas>")[0];
    let ctx = canvas.getContext('2d');
    canvas.width = imgSize;
    canvas.height = imgSize;
    ctx.putImageData(imageData, 0, 0);

    let img = new Image();
    await new Promise((res, rej) => {
        img.onload = res;
        img.src = src;
    });
    
    ctx.drawImage(img, 0, 0, imgSize, imgSize);

    return ctx.getImageData(0, 0, imgSize, imgSize);
}

function imageDataToURL(imageData) {
    let canvas = $("<canvas>")[0];
    let ctx = canvas.getContext('2d');
    canvas.width = imgSize;
    canvas.height = imgSize;
    ctx.putImageData(imageData, 0, 0);
    return canvas.toDataURL();
}

var partTypes = new Promise (async (res, rej) => {
    partTypes = await $.getJSON("/a/data/parts.json");
    res();
});
var materials = new Promise (async (res, rej) => {
    materials = await $.getJSON("/a/data/materials.json");
    res();
});

class Part {
    constructor(type, material) {
        this.typeName = type;
        this.materialId = material;
        this.promise = new Promise(async (res, rej) => {
            await this.load();
            res();
        });
    }

    async load() {
        await materials;
        this.material = materials[this.materialId];
        await gemsInfo;
        this.gem = gemsInfo[this.materialId]
        await partTypes;
        this.type = partTypes[this.typeName];
    }

    get weight() {
        return this.material.weight * this.type.weightModifier;
    }

    get stats() {
        let stats = `level: ${this.material.level}<br>`;
        for (let i of this.type.stats)
            stats += `${i}: ${this.material[i]}<br>`;
        stats += `weight: ${this.weight}`;
        return stats;
    }

    async render() {
        return imageDataToURL(await colourise(`/a/i/parts/${this.typeName}.png`, this.materialId));
    }
}

class Pickaxe {
    constructor(handle, head, binding, modifiers = "", used = 0) {
        this.handle = handle;
        this.head = head;
        this.binding = binding;
        this.modifiers = modifiers.split(",", (modifiers.length == 0 ? 0 : undefined));
        for (let i in this.modifiers)
            this.modifiers[i] = Number(this.modifiers[i]);
        this.used = Number(used);
    }

    get level() {
        let level = 0;
        for (let i of parts)
            level += this[i].material.level;
        if (this.modifiers.includes(9))
            level += levelUpAmount;
        if (this.modifiers.includes(4))
            level -= paperLevelDebuff;
        if (level < 0)
            level = 0;
        
        return level;
    }

    get weight() {
        let weight = 0;
        for (let i of parts)
            weight += this[i].weight;
        if (this.modifiers.includes(0))
            weight *= bandsMultiplier;
        if (this.modifiers.includes(1))
            weight *= wingsMultiplier;
        if (this.modifiers.includes(8))
            weight *= reinforcementWeightMultiplier;
        if (weight < 0)
            weight = 0;
        return Math.round(weight);
    }

    get fullDurability() {
        let durability = this.head.material.durability * this.binding.material.hardness * this.handle.material.toughness;
        if (durability < 0)
            durability = 1;
        if (this.modifiers.includes(8))
            durability *= reinforcementMultiplier;
        return Math.round(durability);
    }
    
    get durabilityLeft() {
        return this.fullDurability - this.used;
    }

    get durability() {
        return `${this.durabilityLeft}/${this.fullDurability} (${this.durabilityPercentage})`;
    }

    get durabilityPercentage() {
        return `${Math.round(this.durabilityLeft/this.fullDurability*100)}%`;
    }

    get strength() {
        let strength = this.head.material.strength;
        if (this.modifiers.includes(5))
            strength *= drillMultipler;
        if (this.modifiers.includes(6))
            strength *= bayonetMultiplier;
        return strength;
    }

    get roughness() {
        let roughness = this.handle.material.luck;
        if (this.modifiers.includes(3))
            roughness *= softGripMultiplier;
        return roughness;
    }

    get luck() {
        let luck = this.binding.material.luck;
        if (this.modifiers.includes(7))
            luck *= cloverMultiplier;
        return luck;
    }

    get stats() {
        let stats = [];
        for (let i of ["level", "durability", "weight", "strength", "roughness", "luck"])
            stats.push(`${i}: ${this[i]}`);
        return stats.join("<br>");
    }
    
    async render() {
        let imageData = new ImageData(imgSize, imgSize);
        for (let partName of parts)
            imageData = await colourise(`/a/i/pickaxe/${partName}.png`, this[partName].materialId, imageData);
        for (let modifier of this.modifiers)
            imageData = await placeOnImageData(imageData, `/a/i/pickaxe/modifiers/${modifier}.png`);
        return imageDataToURL(imageData);
    }
}