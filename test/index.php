<?php
require_once "rcroper.php";
$src = "demo.jpeg";
$size = array(
  array(720,480),
  array(640,480),
  array(320,240),
  array(160,160)
);
$test = new rcroper($src);
$result = $test->crop($size,'',IMAGETYPE_PNG,5);
var_dump($result);
$crop->destory();
?>
