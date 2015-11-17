<?php
/*

Author: Jason Romando
Create at: 2015-11-16
Last Modified: 2015-11-16 11:11AM
Description: Bulk center crop image with specific size.

Usage:

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

*/

class rcroper{
    protected $img;
    protected $srcWidth;
    protected $srcHeight;
    protected $srcCenterX;
    protected $srcCenterY;
    protected $result;


    public function __construct($img = null){
      try{

        if (!extension_loaded('gd') && !function_exists('gd_info')) {
            throw new Exception("PHP GD library is NOT installed on your web server");
        }

        if(!is_file($img)){
          throw new Exception("No image file import.");
        }

        list ($this->srcWidth, $this->srcHeight, $type) = getimagesize($img);

        switch ($type) {
            case IMAGETYPE_GIF  :
                $this->img = imagecreatefromgif($img);
                break;
            case IMAGETYPE_JPEG :
                $this->img = imagecreatefromjpeg($img);
                break;
            case IMAGETYPE_PNG  :
                $this->img = imagecreatefrompng($img);
                break;
            default             :
                throw new InvalidArgumentException("Image type $type not supported");
        }

        $this->srcCenterX = round($this->srcWidth / 2);
        $this->srcCenterY = round($this->srcHeight / 2);

      } catch (Exception $e) {
          echo "Fatal Error: ".$e->getMessage();
      }
    }

    public function crop($size = null, $dest = '', $format = IMAGETYPE_PNG, $quality = ''){
      try{
        if($size == null){
          throw new Exception("Output size is invald. <p> The size should be array. <p> \$size = new array(array(720,480),array(150,150))");
        }
        $result = array();
        for ($i=0; $i < count($size); $i++) {
          $resultFileName = $dest.md5(mt_rand().time());
          switch ($format) {
              case IMAGETYPE_GIF  :
                  $resultFile = $resultFileName.'.gif';
                  break;
              case IMAGETYPE_JPEG :
                  $resultFile = $resultFileName.'.jpg';
                  break;
              case IMAGETYPE_PNG  :
                  $resultFile = $resultFileName.'.png';
                  break;
              default             :
                  throw new Exception("Support gif, jpg and png only.");
          }
          $cropWidth  = $size[$i][0];
          $cropHeight = $size[$i][1];
          $cropWidthHalf  = round($cropWidth / 2);
          $cropHeightHalf = round($cropHeight / 2);

          if($this->srcWidth < $cropWidth || $this->srcHeight < $cropHeight){
            $bgimg = imagecreatetruecolor ($cropWidth,$cropHeight);
            $x = ($cropWidth - $this->srcWidth) / 2;
            $y = ($cropHeight - $this->srcHeight) / 2;
            imagecopymerge($bgimg,$this->img, $x,$y, 0,0, $this->srcWidth, $this->srcHeight,100);

            switch ($format) {
                case IMAGETYPE_GIF  :
                    imagegif($bgimg,$resultFile);
                    break;
                case IMAGETYPE_JPEG :
                    if($quality == ""){
                      $quality = 75;
                    }
                    imagejpeg($bgimg,$resultFile,$quality);
                    break;
                case IMAGETYPE_PNG  :
                    if($quality == ""){
                      $quality = 5;
                    }
                    imagepng($bgimg,$resultFile,$quality);
                    break;
                default             :
                    throw new Exception("Support gif, jpg and png only.");
            }
            $this->destory($bgimg);
          } else {
            $x = max(0, $this->srcCenterX - $cropWidthHalf);
            $y = max(0, $this->srcCenterY - $cropHeightHalf);
            $temp = imagecreatetruecolor($cropWidth, $cropHeight);
            imagecopy($temp, $this->img, 0, 0, $x, $y, $cropWidth, $cropHeight);
            switch ($format) {
                case IMAGETYPE_GIF  :
                    imagegif($temp,$resultFile);
                    break;
                case IMAGETYPE_JPEG :
                    if($quality == ""){
                      $quality = 75;
                    }
                    imagejpeg($temp,$resultFile,$quality);
                    break;
                case IMAGETYPE_PNG  :
                    if($quality == ""){
                      $quality = 5;
                    }
                    imagepng($temp,$resultFile,$quality);
                    break;
                default             :
                    throw new Exception("Support gif, jpg and png only.");
            }
            $this->destory($temp);
          }
            $result[] = $resultFile;
        }
        return $result;
      } catch (Exception $e) {
          echo "Fatal Error: ".$e->getMessage();
      }
    }

    public function getRes(){
      return $this->img;
    }

    public function getWidth(){
      return $this->srcWidth;
    }

    public function getHeight(){
      return $this->srcHeight;
    }

    public function getCenterX(){
      return $this->srcCenterX;
    }

    public function getCenterY(){
      return $this->srcCenterY;
    }

    public function destory($img = ""){
      if($img == ""){
        imagedestroy($this->img);
      } else {
        imagedestroy($img);
      }
      return true;
    }
}

?>
