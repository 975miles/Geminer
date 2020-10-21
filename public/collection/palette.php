<?php
require_once $_SERVER['DOCUMENT_ROOT']."/../start.php";
gen_top("Palette");
?>
<h2>Using other image editors</h2>
<p>Go to <a href="/dash/mining">the mine</a> to see the gems you have, then use your image editor of choice to create and save an image in one of the <a href="/collection/create">sizes available to you</a> and input it into <a href="/collection/imgconverter">the image converter</a>.</p>
<a id="gimpPalette" download="geminer.gpl">Download GIMP palette file</a>
<br>
<a id="pdnPalette" download="geminerpalette.txt">Download Paint.NET palette file</a>
<br>
<img class="collection-img" id="paletteImage" style="width: 10em">
<p id="colours">The colours in the above image (from left to right, top to bottom): <br></p>
<script src="/a/js/palette.js"></script>
<?php gen_bottom(); ?>