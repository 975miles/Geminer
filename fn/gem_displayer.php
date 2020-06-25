<?php
function gem_displayer($gem_id) {
    return "<script>(async () => replaceScript($(document.currentScript), await displayGem($gem_id, \"sm\")))()</script>";
}