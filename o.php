<?php
/* Read the image */
header("Content-Type: image/png");

$url = "http://img.ui.cn/data/file/3/8/7/1378783.png";

$image = file_get_contents($url);


$img = new Imagick();
$img->readImageBlob($image);


/* Thumbnail the image */
$img->thumbnailImage(200, null);

/* Create a border for the image */
$img->borderImage(new ImagickPixel("white"), 5, 5);

/* Clone the image and flip it */
$reflection = clone $img;
$reflection->flipImage();

/* Create gradient. It will be overlayed on the reflection */
$gradient = new Imagick();

/* Gradient needs to be large enough for the image and the borders */
$gradient->newPseudoImage($reflection->getImageWidth() + 10, $reflection->getImageHeight() + 10, "gradient:transparent-black");

/* Composite the gradient on the reflection */
$reflection->compositeImage($gradient, imagick::COMPOSITE_OVER, 0, 0);

/* Add some opacity. Requires ImageMagick 6.2.9 or later */
$reflection->setImageOpacity( 0.3 );

/* Create an empty canvas */
$canvas = new Imagick();

/* Canvas needs to be large enough to hold the both images */
$width = $img->getImageWidth() + 40;
$height = ($img->getImageHeight() * 2) + 30;
$canvas->newImage($width, $height, new ImagickPixel("black"));
$canvas->setImageFormat("png");

/* Composite the original image and the reflection on the canvas */
$canvas->compositeImage($img, imagick::COMPOSITE_OVER, 20, 10);
$canvas->compositeImage($reflection, imagick::COMPOSITE_OVER, 20, $img->getImageHeight() + 10);

/* Output the image*/

echo $canvas;
?>