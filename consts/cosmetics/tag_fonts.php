<?php
class TagFont {
    public function __construct($style, $premium = false, $level = 0) {
        $this->style = "font-family: ".$style.";";
        $this->premium = $premium;
        $this->level = $level;
    }
}

$tag_fonts = [
    new TagFont(
        "'Roboto', sans-serif",
        false,
        0,
    ),
    
    new TagFont(
        "'Raleway', sans-serif",
        false,
        0,
    ),

    new TagFont(
        "'Maven Pro', sans-serif",
        false,
        12,
    ),

    new TagFont(
        "'Balsamiq Sans', cursive",
        false,
        12,
    ),

    new TagFont(
        "'Patrick Hand', cursive",
        false,
        45,
    ),
    
    new TagFont(
        "'Playfair Display', serif",
        true,
    ),

    new TagFont(
        "'Lexend Tera', sans-serif",
        true,
    ),
    
    new TagFont(
        "'Sriracha', cursive",
        true,
    ),

    new TagFont(
        "'Roboto Mono', monospace",
        true,
    ),

    new TagFont(
        "'Comic Neue', cursive",
        true,
    ),

    new TagFont(
        "'Metal Mania', cursive",
        true,
    ),

    new TagFont(
        "'Pacifico', cursive",
        true,
    ),

    new TagFont(
        "'Comfortaa', cursive",
        true,
    ),

    new TagFont(
        "'Crimson Text', serif",
        true,
    ),

    new TagFont(
        "'MuseoModerno', cursive",
        true,
    ),

    new TagFont(
        "'Piedra', cursive",
        true,
    ),

    new TagFont(
        "'Archivo Black', sans-serif",
        true,
    ),

    new TagFont(
        "'Lobster Two', cursive",
        true,
    ),

    new TagFont(
        "'Cinzel', serif",
        true,
    ),

    new TagFont(
        "'Bungee', cursive",
        true,
    ),

    new TagFont(
        "'Righteous', cursive",
        true,
    ),

    new TagFont(
        "'Fredoka One', cursive",
        true,
    ),

    new TagFont(
        "'Caveat', cursive",
        true,
    ),

    new TagFont(
        "'Permanent Marker', cursive",
        true,
    ),

    new TagFont(
        "'Amatic SC', cursive",
        true,
    ),

    new TagFont(
        "'Holtwood One SC', serif",
        true,
    ),

    new TagFont(
        "'Indie Flower', cursive",
        true,
    ),

    new TagFont(
        "'Bebas Neue', cursive",
        true,
    ),

    new TagFont(
        "'Leckerli One', cursive",
        true,
    ),

    new TagFont(
        "'Orbitron', sans-serif",
        true,
    ),
];