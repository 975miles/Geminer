<?php
class ProfileBackground {
    public function __construct($bg, $premium = false, $dark = false, $is_image = false) {
        $this->text_colour = ($dark ? "white" : "black");
        $this->bgshort = $bg;
        $this->bg = $this->bgshort." repeat center center fixed !important".($is_image ? ";-webkit-background-size: cover;-moz-background-size: cover;-o-background-size: cover;background-size: cover" : "");
        $this->style = $this->bg."; color: ".$this->text_colour;
        $this->premium = $premium;
    }

    function style_tag() {
        return "<style>body {background: ".$this->style.";}</style>";
    }
}

$profile_backgrounds = [
    new ProfileBackground("#ff564a", false, false),
    new ProfileBackground("#5fcfe8", false, false),
    new ProfileBackground("#eded72", false, false),
    new ProfileBackground("#54ff8d", false, false),
    new ProfileBackground("orange", false, false),
    new ProfileBackground("purple", false, false),
    new ProfileBackground("#403415", false, true),
    new ProfileBackground("pink", false, false),
    new ProfileBackground("#fafafa", false, false),
    new ProfileBackground("#0f0f0f", false, true),
    new ProfileBackground("linear-gradient(120deg, #f093fb 0%, #f5576c 100%)", true, false),
    new ProfileBackground("linear-gradient(to top, #e8198b 0%, #c7eafd 100%)", true, false),
    new ProfileBackground("linear-gradient(to top, #d299c2 0%, #fef9d7 100%)", true, false),
    new ProfileBackground("linear-gradient(to top, #feada6 0%, #f5efef 100%)", true, false),
    new ProfileBackground("linear-gradient(to bottom right, #c1c161 0%, #c1c161 0%, #d4d4b1 100%)", true, false),
    new ProfileBackground("linear-gradient(to right, #16222a, #3a6073)", true, true),
    new ProfileBackground("linear-gradient(to right, #232526, #414345)", true, true),
    new ProfileBackground("linear-gradient(to top left, #de6262, #ffb88c)", true, false),
    new ProfileBackground("linear-gradient(to top right, #c21500, #ffc500)", true, false),
    new ProfileBackground("linear-gradient(to right, #50c9c3, #96deda)", true, false),
    new ProfileBackground("linear-gradient(to top left, #5d4157, #a8caba)", true, false),
    new ProfileBackground("linear-gradient(to bottom left, #870000, #190a05)", true, true),
    new ProfileBackground("linear-gradient(110deg, #780206, #061161)", true, true),
    new ProfileBackground("linear-gradient(to bottom, #70e1f5, #ffd194)", true, false),
    new ProfileBackground("linear-gradient(to bottom left, #fdfc47, #24fe41)", true, false),
    new ProfileBackground("linear-gradient(to top left, #e53935, #e35d5b)", true, false),
    new ProfileBackground("linear-gradient(to top left, #833ab4, #fd1d1d, #fcb045)", true, false),
    new ProfileBackground("linear-gradient(to left, #ff0084, #33001b)", true, true),
    new ProfileBackground("linear-gradient(to bottom right, #834d9b, #d04ed6)", true, false),
    new ProfileBackground("linear-gradient(to right, #0f0c29, #302b63, #24243e)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2016/11/13/14/27/texture-1821125_960_720.jpg)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2017/02/01/14/32/seamless-2030343_960_720.jpg)", true, false),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2015/12/09/19/08/seamless-pattern-1085297_960_720.png)", true, false),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2017/02/02/19/50/seamless-2033674_960_720.jpg)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2017/02/01/14/29/seamless-2030341_960_720.jpg)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2016/03/21/15/15/circle-1270673_960_720.png)", true, false),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2017/08/30/12/23/black-2696879_960_720.jpg)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2016/09/09/16/20/sand-1657466_960_720.jpg)", true, false),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2016/05/04/13/11/brick-wall-1371349_960_720.jpg)", true, true),
    new ProfileBackground("url(https://cdn.pixabay.com/photo/2016/09/09/16/20/rocks-1657467_960_720.jpg)", true, true),
    
    
];