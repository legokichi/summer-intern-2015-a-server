<?php
$width = 200;
$id = $_GET["illust_id"];
if(!ctype_digit($id)) exit();
$url = "../img/$id";

// no cache -> create thumbnail
if(!file_exists("../img/thumb/$id")){
    list($image_w, $image_h) = getimagesize($url);

    $proportion = $image_w / $image_h;
    $height = $width / $proportion;
    if($proportion < 1){
        $height = $width;
        $width = $width * $proportion;
    }

    $canvas = imagecreatetruecolor($width, $height);

    $image = imagecreatefromjpeg($url);


    if(isset($exif_datas['Orientation']) && $exif_datas['Orientation'] == 6){
        $image = imagerotate($image, 270, 0);
    }

    imagecopyresampled($canvas,
        $image,
        0, 0, 0, 0,
        $width, $height, $image_w, $image_h
    );

    // 画像を出力する
    imagejpeg($canvas,
        "../img/thumb/$id",
        50
    );

    imagedestroy($canvas);
}

header("Location: /img/thumb/$id");
