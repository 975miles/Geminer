<?php
class TagStyle {
    public function __construct($bg, $light = false, $premium = false) {
        $this->bg = $bg;
        $this->light = $light;
        $this->premium = $premium;
    }

    function get_style() {
        return "background: ".$this->bg.";";
    }

    function get_classes() {
        return "btn-custom".($this->light ? " btn-custom-light" : "");
    }
}

$tag_styles = [
    new TagStyle(
        $bg = "#8c8c8c",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#dfdfdf",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#1a1a1a",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#ff75f6",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#FF3E96",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8B0A50",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#DA70D6",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#c135e8",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#D8BFD8",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8B008B",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#4B0082",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8470FF",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#191970",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#0000CD",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#4287f5",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#87CEFF",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#00E5EE",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#00FA9A",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#2abf6d",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#008B45",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#98FB98",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#f5ef3b",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#FFFFE0",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#CDC9A5",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#e09600",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8B6508",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8B4500",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#8B7E66",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "#FF6A6A",
        $light = true,
        $premium = false
    ),

    new TagStyle(
        $bg = "red",
        $light = false,
        $premium = false
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #a18cd1 0%, #fbc2eb 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #30cfd0 0%, #330867 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to right, #eea2a2 0%, #bbc1bf 19%, #57c6e1 42%, #b49fda 79%, #7ac5d8 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #f43b47 0%, #453a94 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #ff0844 0%, #ffb199 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to right, #f83600 0%, #f9d423 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #5f72bd 0%, #9b23ea 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to top, #09203f 0%, #537895 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(to right, #009fff, #ec2f4b)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(-225deg, #2CD8D5 0%, #C5C1FF 56%, #FFBAC3 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(-225deg, #A445B2 0%, #D41872 52%, #FF0066 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient(-225deg, #231557 0%, #44107A 29%, #FF1361 67%, #FFF800 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "radial-gradient(circle, rgba(63,251,151,1) 0%, rgba(209,69,98,1) 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(60,60,60,1) 50%, rgba(0,0,0,1) 100%)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(190,190,190,1) 50%, rgba(255,255,255,1) 100%)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient( 109.6deg,  rgba(255,24,134,1) 11.2%, rgba(252,232,68,1) 52%, rgba(53,178,239,1) 100.2% )",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient( 181.3deg,  rgba(134,15,15,1) 24.9%, rgba(183,10,10,1) 46.9%, rgba(210,70,0,1) 85.1% )",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "linear-gradient( 153.4deg,  rgba(160,250,141,1) 25.4%, rgba(253,217,182,1) 59% )",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2015/05/08/10/31/geometric-757870_960_720.jpg)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2015/11/19/20/02/background-1051850_960_720.jpg)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2016/06/11/21/10/pattern-1450836_960_720.png)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2016/05/04/15/58/background-texture-1371996_960_720.jpg)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2017/09/06/13/29/blue-2721464_960_720.jpg)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2016/04/28/23/21/colorful-1359948_960_720.png)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2019/12/16/09/52/illustration-4699020_960_720.jpg)",
        $light = true,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2016/12/13/15/06/seamless-1904277_960_720.jpg)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2017/08/30/11/50/stripe-2696784_960_720.png)",
        $light = false,
        $premium = true
    ),

    new TagStyle(
        $bg = "url(https://cdn.pixabay.com/photo/2015/05/29/18/32/bokeh-789553_960_720.jpg)",
        $light = false,
        $premium = true
    ),
];