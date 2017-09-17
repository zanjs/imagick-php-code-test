<?php



function code(){
    $codeURL = "http://www.6city.com/d/qrcode?target=http://www.6city.com";
    
    
    $codeURLImage = file_get_contents($codeURL);
    
    $im = new Imagick();
    
    $im->readImageBlob($codeURLImage);
    $im->cropThumbnailImage(300,300); //缩放
    
    return $im;
}


function avatar(){
    $codeURL = "http://img.ui.cn/data/file/0/5/0/1372050.jpg";
    $codeURLImage = file_get_contents($codeURL);
    
    $im = new Imagick();
    
    $im->readImageBlob($codeURLImage);
    $im->cropThumbnailImage(100,100); //缩放
    $im->roundCorners(360,360);
    // $im->blurImage(100,1);

    return $im;
}

function draw(){
    $width = 400;
    $height = 210;
    $draw = new ImagickDraw();
    /* Set purple fill color for ellipse */
    $draw->setFillColor('#777bb4');
    /* Set ellipse dimensions */
    $draw->ellipse($width / 2, $height / 2, $width / 2, $height / 2, 0, 360);
    /* Draw ellipse onto the canvas */
    // $img->drawImage($draw);
    
    /* Reset fill color from purple to black for text (note: we are reusing ImagickDraw object) */
    $draw->setFillColor('black');
    /* Set stroke border to white color */
    $draw->setStrokeColor('white');
    /* Set stroke border thickness */
    $draw->setStrokeWidth(2);
    /* Set font kerning (negative value means that letters are closer to each other) */
    $draw->setTextKerning(-8);
    /* Set font and font size used in PHP logo */
    $draw->setFont('蒙纳漫画体.ttf');
    $draw->setFontSize(150);
    /* Center text horizontally and vertically */
    $draw->setGravity(Imagick::GRAVITY_CENTER);
    return $draw;
    /* Add center "php" with Y offset of -10 to canvas (inside ellipse) */
    //$img->annotateImage($draw, 0, -10, 0, '你好');
    //$img->setImageFormat('png');
}

function bg(){
    $codeURL = "http://img.ui.cn/data/file/0/2/5/1383520.png";
    $codeURLImage = file_get_contents($codeURL);
    
    $im = new Imagick();
    
    $im->readImageBlob($codeURLImage);
    // $im->blurImage(100,1);

    return $im;
}



header( "Content-Type: image/jpg" );

$poster = bg();
$Qrcode = code();
$Avatar = avatar();
$Draw = draw();


$poster->compositeImage($Qrcode,Imagick::COMPOSITE_OVER,275,160);
$poster->compositeImage($Avatar,Imagick::COMPOSITE_OVER,275,160);


$poster->annotateImage($Draw, 0, -10, 0, '你好');
echo $poster;

// Niu7();