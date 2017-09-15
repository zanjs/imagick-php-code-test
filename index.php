<?php

header("content-type: image/png");
// $handle = fopen('http://i3.tietuku.com/2afd667e3eded5e0.png', 'rb');
// $img = new Imagick();
// $img->readImageFile($handle);
// $img->resizeImage(128, 128, 0, 0);
$url = 'http://img.ui.cn/data/file/0/2/6/1377620.png';
$image = file_get_contents($url);
// $handle = fopen('http://i3.tietuku.com/2afd667e3eded5e0.png', 'rb');
$img = new Imagick();
$img->readImageBlob($image);
$img->roundCorners(360,360);
// $img->resizeImage(128, 128, 0, 0);
$img->writeImage('./foo.png');
echo $img;

