<?php
class TagStyle {
    public function __construct($bg, $light = false, $premium = false, $level = 0) {
        $this->bg = $bg;
        $this->light = $light;
        $this->premium = $premium;
        $this->level = $level;
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
        "#8c8c8c",
        false,
        false,
        0
    ),

    new TagStyle(
        "#dfdfdf",
        true,
        false,
        0
    ),

    new TagStyle(
        "#1a1a1a",
        false,
        false,
        0
    ),

    new TagStyle(
        "#ff75f6",
        true,
        false,
        20
    ),

    new TagStyle(
        "#FF3E96",
        false,
        false,
        60
    ),

    new TagStyle(
        "#8B0A50",
        false,
        false,
        60
    ),

    new TagStyle(
        "#DA70D6",
        false,
        false,
        60
    ),

    new TagStyle(
        "#c135e8",
        false,
        false,
        60
    ),

    new TagStyle(
        "#D8BFD8",
        true,
        false,
        60
    ),

    new TagStyle(
        "#8B008B",
        false,
        false,
        60
    ),

    new TagStyle(
        "#4B0082",
        false,
        false,
        20
    ),

    new TagStyle(
        "#8470FF",
        false,
        false,
        60
    ),

    new TagStyle(
        "#191970",
        false,
        false,
        60
    ),

    new TagStyle(
        "#0000CD",
        false,
        false,
        60
    ),

    new TagStyle(
        "#4287f5",
        false,
        false,
        10
    ),

    new TagStyle(
        "#87CEFF",
        true,
        false,
        60
    ),

    new TagStyle(
        "#00E5EE",
        true,
        false,
        60
    ),

    new TagStyle(
        "#00FA9A",
        true,
        false,
        60
    ),

    new TagStyle(
        "#2abf6d",
        false,
        false,
        10
    ),

    new TagStyle(
        "#008B45",
        false,
        false,
        60
    ),

    new TagStyle(
        "#98FB98",
        true,
        false,
        60
    ),

    new TagStyle(
        "#f5ef3b",
        true,
        false,
        10
    ),

    new TagStyle(
        "#FFFFE0",
        true,
        false,
        60
    ),

    new TagStyle(
        "#CDC9A5",
        true,
        false,
        60
    ),

    new TagStyle(
        "#e09600",
        true,
        false,
        20
    ),

    new TagStyle(
        "#8B6508",
        false,
        false,
        60
    ),

    new TagStyle(
        "#8B4500",
        false,
        false,
        60
    ),

    new TagStyle(
        "#8B7E66",
        false,
        false,
        60
    ),

    new TagStyle(
        "#FF6A6A",
        true,
        false,
        60
    ),

    new TagStyle(
        "red",
        false,
        false,
        10
    ),

    new TagStyle(
        "linear-gradient(to top, #a18cd1 0%, #fbc2eb 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient(120deg, #d4fc79 0%, #96e6a1 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient(to top, #30cfd0 0%, #330867 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(to right, #eea2a2 0%, #bbc1bf 19%, #57c6e1 42%, #b49fda 79%, #7ac5d8 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient(to top, #f43b47 0%, #453a94 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(to top, #ff0844 0%, #ffb199 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(to right, #f83600 0%, #f9d423 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient(to top, #5f72bd 0%, #9b23ea 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(to top, #09203f 0%, #537895 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(to right, #009fff, #ec2f4b)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(-225deg, #2CD8D5 0%, #C5C1FF 56%, #FFBAC3 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient(-225deg, #AC32E4 0%, #7918F2 48%, #4801FF 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(-225deg, #A445B2 0%, #D41872 52%, #FF0066 100%)",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient(-225deg, #231557 0%, #44107A 29%, #FF1361 67%, #FFF800 100%)",
        false,
        true
    ),

    new TagStyle(
        "radial-gradient(circle, rgba(63,251,151,1) 0%, rgba(209,69,98,1) 100%)",
        true,
        true
    ),

    new TagStyle(
        "radial-gradient(circle, rgba(0,0,0,1) 0%, rgba(60,60,60,1) 50%, rgba(0,0,0,1) 100%)",
        false,
        true
    ),

    new TagStyle(
        "radial-gradient(circle, rgba(255,255,255,1) 0%, rgba(190,190,190,1) 50%, rgba(255,255,255,1) 100%)",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient( 109.6deg,  rgba(255,24,134,1) 11.2%, rgba(252,232,68,1) 52%, rgba(53,178,239,1) 100.2% )",
        true,
        true
    ),

    new TagStyle(
        "linear-gradient( 181.3deg,  rgba(134,15,15,1) 24.9%, rgba(183,10,10,1) 46.9%, rgba(210,70,0,1) 85.1% )",
        false,
        true
    ),

    new TagStyle(
        "linear-gradient( 153.4deg,  rgba(160,250,141,1) 25.4%, rgba(253,217,182,1) 59% )",
        true,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2015/05/08/10/31/geometric-757870_960_720.jpg)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2015/11/19/20/02/background-1051850_960_720.jpg)",
        true,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2016/06/11/21/10/pattern-1450836_960_720.png)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2016/05/04/15/58/background-texture-1371996_960_720.jpg)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2017/09/06/13/29/blue-2721464_960_720.jpg)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2016/04/28/23/21/colorful-1359948_960_720.png)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2019/12/16/09/52/illustration-4699020_960_720.jpg)",
        true,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2016/12/13/15/06/seamless-1904277_960_720.jpg)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2017/08/30/11/50/stripe-2696784_960_720.png)",
        false,
        true
    ),

    new TagStyle(
        "url(https://cdn.pixabay.com/photo/2015/05/29/18/32/bokeh-789553_960_720.jpg)",
        false,
        true
    ),
];