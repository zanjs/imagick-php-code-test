<?php

class weiqi
{
    /**
    * 根据微信用户特定id生成专属二维码
    */
    public static function getTicket($scene_id)
    {
        $qrcode = '{"expire_seconds": 2592000, "action_name": "QR_SCENE", "action_info": {"scene": {"scene_id": $scene_id }}}';  //二维码信息
        $access_token = self::getToken();   //公众号token，这个要获取自己公众号的
        $getticket_url = "https://api.weixin.qq.com/cgi-bin/qrcode/create?			   access_token=$access_token";
        $ticketinfo = self::request_by_curl($getticket_url, $qrcode);
        return $ticketinfo['ticket']; //专属二维码的ticken
    }

    /**
    *
    */
    public static function request_by_curl($remote_server, $post_string = '')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("Expect: "));
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        $content = curl_exec($ch);
        curl_close($ch);
        $reArr=json_decode($content, true);
        return $reArr;
    }

    public function CompositeImage($ticket, $wxnick, $userId)
    {
        $Qrcode = new Imagick("https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket=$ticket");
        $Qrcode->setImageResolution(0.1, 0.3);      //设置图片分辨率
        $QrcodeWH = $Qrcode->getImageGeometry();   //获取源图片宽和高
        if ($QrcodeWH['width']>200) {
            $QrcodeW['width'] = 200;
            $QrcodeH['height'] = $QrcodeW['width']/$QrcodeWH['width']*$QrcodeWH['height'];
        } else {
            $QrcodeW['width'] = $QrcodeWH['width'];
            $QrcodeH['height'] = $QrcodeWH['height'];
        }
          $Qrcode->thumbnailImage( $QrcodeW['width'], $QrcodeWH['height'], true );  //按照选定的比例进行缩放
        
        // 预先下载微信头像，再生成合成信息
       
          $curl   = curl_init($wxnick);
          $wxnickpath = "upload/wxnick/".$userId.".jpg";
          curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
          $imageData = curl_exec($curl);
          curl_close($curl);
          $tp = @fopen($wxnickpath, 'a');
          fwrite($tp, $imageData);
          fclose($tp);
    
          $weixin = new Imagick($wxnickpath);
          $weixin->setImageResolution(0.1, 0.3);
          $weixin->roundCorners(360, 360);          //圆角处理
          $wxWH = $weixin->getImageGeometry();
        if ($wxWH['width']>200) {
            $wxW['width'] = 200;
            $wxH['height'] = $wxW['width']/$wxWH['width']*$wxWH['height'];
        } else {
            $wxW['width'] = $wxWH['width'];
            $wxH['height'] = $wxWH['height'];
        }
          $weixin->thumbnailImage( $wxW['width'], $wxWH['height'], true );//等比例缩放
    
          //创建一个Imagick对象，同时获取要处理的背景图  /data/wenda/htdocs/upload
        $poster = new Imagick( "/data/wenda/htdocs/upload/poster.png" );
        $posterWH  = $poster->getImageGeometry();
        $posterW['width'] = $posterWH['width'];
        $posterH['height'] = $posterWH['height'];
        // 按照缩略图大小创建一个有颜色的图片
        $canvas = new Imagick();
        $canvas->newImage( $posterW['width'], $posterH['height'], 'black', 'jpg' );
              
        //二维码 微信头像 背景 合成
        $poster->compositeImage($Qrcode, Imagick::COMPOSITE_OVER, 275, 960);
        $poster->compositeImage($weixin, Imagick::COMPOSITE_OVER, 275, 402);
                        
        $canvas->compositeImage( $poster, imagick::COMPOSITE_OVER, 0, 0);
        $canvas->setImageCompressionQuality(60); //压缩质量
        $canvas->writeImage( "/upload/poster/$userId.jpg" ); //生成图片
        return $canvas;  //返回图片流信息
    }
    
}


// header( "Content-Type: image/jpg" );    //输出图片
// $posterimg = $this->CompositeImage($Fticket, $Fwnick, $userId);
// echo $posterimg //输出图片