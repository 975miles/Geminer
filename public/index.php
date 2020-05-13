<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("GEMiner", "Mine digital gems for real distraction.");
?>

<h1>GEMiner</h1>
<p>hi</p>

<div class="container-fluid rounded bg-success">
    <h2>Miner's dashboard</h2>
    <?php if ($is_logged_in) {?>
        <div class="container-fluid rounded bg-danger">
            <button class="btn btn-secondary" onclick="mine()">Mine</button>
            <h3>Your gems</h3>
            <div id="gems">loading...</div>
        </div>

        <script>
            $(document).ready(async ()=>{
                var gemsInfo = await loadGems();
                $("#gems").html("");
                for (i of Object.keys(user)) {
                    if (!isNaN(Number(i))) {
                        let gem_amount_p = $(`<p>${gemsInfo[i].name}: <span id="gem_${gemsInfo[i].id}_amount">${user[i]}</span>mP</p>"`);
                        $("#gems").append(gem_amount_p);
                    }
                }
            });

            async function mine() {
                var gemsInfo = await loadGems();
                $.get(
                    {
                        url: "/api/do/mine.php",
                        success: data => {
                            data = JSON.parse(data);
                            if (typeof data == "string")
                                return showInfo("aaa");
                            
                            let output = "";
                            for (i of data) {
                                output += `You got ${i.amount}mP of ${gemsInfo[i.gem].name}!<br>`
                                let amountDisplay = $(`#gem_${i.gem}_amount`);
                                amountDisplay.html(Number(amountDisplay.html()) + i.amount);
                            }
                            showInfo(output, "Yields!");
                            //$("body").append();
                        }
                    }
                );
            }
        </script>
    <?php } else { ?>
        <p class="bg-danger rounded">You must be logged in to access the miner's dashboard.</p>
    <?php } ?>
</div>



<?php gen_bottom(); ?>