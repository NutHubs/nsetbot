<?php
// open the file in a binary mode
$name = 'http://223.27.205.134:12000/emp_image/070001.jpg';
$fp = fopen($name, 'rb');

// send the right headers
header("Content-Type: image/jpg");
header("Content-Length: " . filesize($name));

// dump the picture and stop the script
fpassthru($fp);
exit;
?>
