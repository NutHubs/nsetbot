<?php
$im = imagecreatefrompng("http://223.27.205.134:12000/emp_image/070001.jpg");

header('Content-Type: image/png');

imagepng($im);
imagedestroy($im);
?>
