<?php
class NavbarBackground {
    public function __construct($style, $dark, $premium = false) {
        $this->style = $style;
        $this->dark = $dark;
        $this->premium = $premium;
    }
}

$navbar_backgrounds = [
    new NavbarBackground("#d1271b", false, false), //This one is the default, it'll show for non-logged-in users and it's the only one chosen for new users
    new NavbarBackground("#838383", false, false),
    new NavbarBackground("#4287f5", false, false),
    new NavbarBackground("#f0f0f0", false, false),
    new NavbarBackground("#1c1c1c", true, false),
    new NavbarBackground("#56ed42", false, false),
    new NavbarBackground("#f2ff00", false, false),
    new NavbarBackground("#ff0898", false, false),
    new NavbarBackground("#ff7ac8", false, false),
    new NavbarBackground("#a126ff", false, false),
    new NavbarBackground("#004cff", true, false),
    new NavbarBackground("#ffaa17", false, false),
    new NavbarBackground("#ffdd75", false, false),
    new NavbarBackground("#a6882e", false, false),
    new NavbarBackground("#105421", true, false),
    new NavbarBackground("#24c74d", false, false),
    new NavbarBackground("#19ffd1", false, false),
    new NavbarBackground("#4b5fc4", true, false),
    new NavbarBackground("#a80f2e", true, false),
    new NavbarBackground("#382c4a", true, false),
    new NavbarBackground("linear-gradient( 111.5deg,  rgba(249,230,1,1) 9.9%, rgba(249,144,1,1) 19.4%, rgba(255,22,22,1) 29.2%, rgba(255,22,133,1) 37.7%, rgba(255,22,197,1) 47.7%, rgba(232,22,255,1) 53.9%, rgba(162,22,255,1) 60%, rgba(80,22,255,1) 68.8%, rgba(22,104,255,1) 74.2%, rgba(22,168,255,1) 77.6%, rgba(22,255,220,1) 86.7%, rgba(22,255,179,1) 92.5%, rgba(22,255,109,1) 97.1%, rgba(92,255,22,1) 103.5% )", false, true),
    new NavbarBackground("linear-gradient(180deg, rgba(255,255,255,1) 0%, rgba(255,255,255,0) 100%)", false, true),
    new NavbarBackground("linear-gradient(180deg, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 100%)", true, true),
    new NavbarBackground("linear-gradient(146deg, rgba(255,88,88,1) 0%, rgba(153,25,25,1) 100%)", false, true),
    new NavbarBackground("linear-gradient(to bottom right, rgb(10, 16, 88), rgb(2, 0, 24))", true, true),
    new NavbarBackground("linear-gradient(21deg, rgba(37,173,181,1) 0%, rgba(26,242,255,1) 72%, rgba(255,252,0,1) 81%, rgba(255,203,70,1) 100%)", false, true),
    new NavbarBackground("linear-gradient( 135deg, #FFF886 10%, #F072B6 100%)", false, true),
    new NavbarBackground("linear-gradient(90deg, #4b6cb7 0%, #182848 100%)", false, true),
    new NavbarBackground("linear-gradient(90deg, #fcff9e 0%, #c67700 100%)", false, true),
    new NavbarBackground("linear-gradient( 109.6deg,  rgba(8,8,8,1) 11.2%, rgba(201,3,3,1) 91.1% )", true, true),
    new NavbarBackground("radial-gradient( circle farthest-corner at 22.4% 21.7%,  rgba(4,189,228,1) 0%, rgba(2,83,185,1) 100.2% )", false, true),
    new NavbarBackground("linear-gradient( 111.1deg,  rgba(0,40,70,1) -4.8%, rgba(255,115,115,1) 82.7%, rgba(255,175,123,1) 97.2% )", true, true),
    new NavbarBackground("radial-gradient( circle farthest-corner at 10% 20%,  rgba(50,172,109,1) 0%, rgba(209,251,155,1) 100.2% )", false, true),
    new NavbarBackground("linear-gradient( 99.6deg,  rgba(112,128,152,1) 10.6%, rgba(242,227,234,1) 32.9%, rgba(234,202,213,1) 52.7%, rgba(220,227,239,1) 72.8%, rgba(185,205,227,1) 81.1%, rgba(154,180,212,1) 102.4% )", false, true),
    new NavbarBackground("linear-gradient( 164deg,  rgba(21,13,107,1) 1.1%, rgba(188,16,80,1) 130.5% )", true, true),
    new NavbarBackground("url(https://www.toptal.com/designers/subtlepatterns/patterns/wormz.png)", false, true),
    new NavbarBackground("url(https://www.toptal.com/designers/subtlepatterns/patterns/memphis-colorful.png)", false, true),
    new NavbarBackground("url(https://www.toptal.com/designers/subtlepatterns/patterns/pink%20dust.png)", true, true),
    new NavbarBackground("url(https://www.toptal.com/designers/subtlepatterns/patterns/ep_naturalblack.png)", true, true),
    new NavbarBackground("url(https://www.toptal.com/designers/subtlepatterns/patterns/topography.png)", false, true),
];