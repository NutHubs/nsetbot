<?php
    $file = 'http://223.27.205.134:12000/emp_image/070001.jpg';

    header('Content-Type: image/jpeg');
    header('Content-Length: ' . filesize($file));
    echo file_get_contents($file);
?>
