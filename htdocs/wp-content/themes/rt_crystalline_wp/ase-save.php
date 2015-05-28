<?php

$colors = $_POST['ase-colors-list'];
$colors = explode(",", $colors);

$name = "RokPalette-" . time();

makeASE($colors, $name, false);

/**
 * @desc Make an Adobe Swatch Exchange file
 * @author Chris Williams - For COLOURlovers.com
 * @param array $hexs Hexs must be 6 chars long!
 * @param array $names
 * @license Free, use it as you wish!
 * @return void
 */
function makeASE($hexs, $palettename) {
    define("NUL", chr(0)); # NULL Byte
    define("SOH", chr(1)); # START OF HEADER Byte

    $names = $hexs;
    $cleaned_hexes = array();
    foreach ($hexs  as $hex){
        $hex = ltrim($hex,"#");
        $cleaned_hexes[] =$hex;
    }
    $hexs = $cleaned_hexes;

    $numHexs = count($hexs);

    $ase = "ASEF" . NUL . SOH . NUL . NUL; # ASE Header
    for ($i = 24; $i >= 0; $i -= 8) {
        $ase .= chr(($numHexs >> $i) & 0xFF); # $numHexs Being the number of swatches, of course
    }
    $ase .= NUL;

    for ($i = 0; $i < $numHexs; $i++) {
        $ase .= SOH . NUL . NUL . NUL; # Swatch header
        $ase .= chr((((strlen($names[$i]) + 1) * 2) + 20)) . NUL; # (((num chars in str + 1) * 2) + 20) ... this is more than likely the length of the whole swatch "package"
        $ase .= chr(strlen($names[$i]) + 1) . NUL; # num chars in str + 1

        # Add name of the swatch:
        for ($j = 0; $j < strlen($names[$i]); $j++) {
            $ase .= $names[$i]{$j} . NUL;
        }

        # Big endian, single-precision floating point numbers:
        # The precision isn't exact, but the values will round out.
        list($rDec, $gDec, $bDec) = sscanf($hexs[$i], "%2x%2x%2x");
        $r = pack("f", ($rDec / 255));
        $g = pack("f", ($gDec / 255));
        $b = pack("f", ($bDec / 255));

        # We're using RGB here :-)
        $ase .= NUL . "RGB "; # Keep trailing space!
        $ase .= $r{3} . $r{2} . $r{1} . NUL;
        $ase .= $g{3} . $g{2} . $g{1} . NUL;
        $ase .= $b{3} . $b{2} . $b{1} . NUL;
        if (($i + 1) != $numHexs) {
            # Swatch seperator:
            $ase .= NUL . NUL . NUL;
        }
    }
    # Terminate file
    $ase .= NUL . NUL;

	header("Content-Type: force-download");
    header("Content-type: application/octet-stream");
	header('Content-Disposition: attachment; filename="'.$palettename.'.ase"');
	echo $ase;
}