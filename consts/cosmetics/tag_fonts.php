<?php
class TagFont {
    public function __construct($style, $premium = false) {
        $this->style = "font-family: ".$style.";";
        $this->premium = $premium;
    }
}

$tag_fonts = [
    new TagFont(
        $style = "'Roboto', sans-serif",
        $premium = false,
    ),
    
    new TagFont(
        $style = "'Raleway', sans-serif",
        $premium = false,
    ),

    new TagFont(
        $style = "'Maven Pro', sans-serif",
        $premium = false,
    ),

    new TagFont(
        $style = "'Balsamiq Sans', cursive",
        $premium = false,
    ),

    new TagFont(
        $style = "'Patrick Hand', cursive",
        $premium = false,
    ),
    
    new TagFont(
        $style = "'Playfair Display', serif",
        $premium = true,
    ),

    new TagFont(
        $style = "'Lexend Tera', sans-serif",
        $premium = true,
    ),
    
    new TagFont(
        $style = "'Sriracha', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Roboto Mono', monospace",
        $premium = true,
    ),

    new TagFont(
        $style = "'Comic Neue', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Metal Mania', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Pacifico', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Comfortaa', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Crimson Text', serif",
        $premium = true,
    ),

    new TagFont(
        $style = "'MuseoModerno', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Piedra', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Archivo Black', sans-serif",
        $premium = true,
    ),

    new TagFont(
        $style = "'Lobster Two', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Cinzel', serif",
        $premium = true,
    ),

    new TagFont(
        $style = "'Bungee', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Righteous', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Fredoka One', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Caveat', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Permanent Marker', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Amatic SC', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Holtwood One SC', serif",
        $premium = true,
    ),

    new TagFont(
        $style = "'Indie Flower', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Bebas Neue', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Leckerli One', cursive",
        $premium = true,
    ),

    new TagFont(
        $style = "'Orbitron', sans-serif",
        $premium = true,
    ),
];