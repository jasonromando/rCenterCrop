Image Center Crop
=================

Bulk center crop image with specific size.

Image Formats Support
---------------------

- PNG (IMAGETYPE_PNG) - default output format
- GIF (IMAGETYPE_GIF)
- JPEG/JPG (IMAGETYPE_JPEG)

Usage
-----

//define variable
$src = "test/demo.jpeg";  <- image soruce
$destination = "";        <- location for save  cropped image
$size = array(            <- output size array.
  array(720,480),            array($width,$height),
  array(640,480),
  array(320,240),
  array(160,160)
);
$format = IMAGETYPE_PNG;  <- output format
$quality = 5;             <- output quality

//sample code
$test = new rcroper($src);
$result = $test->crop($size,$destination,$format,5);
var_dump($result);        <- crop function will return the file name.
$crop->destory();         <- destory GD image, release ram.

Server requirement
------------------

- PHP 5.2+
- PHP GD Library
