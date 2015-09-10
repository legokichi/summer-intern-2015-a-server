<?php
    $uploaddir = '../img/';
    /*
        {
            "title_id": 2,
            "date": 1441346245, // UNIX Time Stamp
            "title": "犬が波動拳を打っている絵が欲しい"
            "count": 0,
        }
    */
    if(!isset($_POST['title_id']) ||
       !isset($_POST['user_id']) || 
       !isset($_POST['user_name']) || 
       !isset($_FILES['file']) ||
       !ctype_digit($_POST['user_id'])
    ) { // 入力がない
        http_response_code(500);
        echo json_encode(array('error' => 'Invalid parameter', 'post' => print_r($_POST, true)));
        die();
    }


    $title_id = $_POST['title_id'];
    $user_name = $_POST['user_name'];
    $user_id = $_POST['user_id'];
    $filedata = $_FILES['file'];

    if($filedata['error']){
        http_response_code(500);
        echo json_encode(array('error' => 'Failed to Upload'));
        die();
    }

    $date = time();

    $db = new SQLite3('../database.db');

    // title の作者を得る -> 無かったらエラー
    $target_id = $db->querySingle("SELECT user_id FROM title WHERE title_id = '$title_id'");
    if(!$target_id) {
        http_response_code(500);
        echo json_encode(array('error' => 'No such title'));
        exit();
    }

    $image_size = getimagesize($filedata['tmp_name']);

    if(!$image_size) {
        http_response_code(500);
        echo json_encode(array('error' => 'Cannot get size'));
        exit();
    }

    list($width, $height, $type, $attr) = $image_size;
    $exif_data = exif_read_data($filedata['tmp_name']);

    if($exif_data['Orientation'] >= 5) {
        list($height, $width) = array($width, $height);
    }


    $stmt = $db->prepare('INSERT INTO illust (title_id, user_id, user_name, likes, date, width, height) VALUES (:title_id, :user_id, :user_name, 0, :date, :width, :height)');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_name', $user_name, SQLITE3_TEXT);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':width', $width, SQLITE3_INTEGER);
    $stmt->bindValue(':height', $height, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'Cannot insert illust'));
        exit();
    }

    $illust_id = $db->lastInsertRowID();
    $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/$illust_id";

    // upload file
    $uploadfile = $uploaddir . $illust_id;
    if(!move_uploaded_file($filedata['tmp_name'], $uploadfile)) {
        http_response_code(500);
        echo json_encode(array('error' => 'Cannot save illust'));
        exit();
    }

    // update activity table
    $stmt = $db->prepare('INSERT INTO activity (type, date, illust_id, title_id, target_user_id)'
                        .' VALUES (:type, :date, :illust_id, :title_id, :target_user_id)');

    $stmt->bindValue(':type', 1, SQLITE3_INTEGER);
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':illust_id', $illust_id, SQLITE3_INTEGER);
    $stmt->bindValue(':target_user_id', $target_id, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'Cannot insert activity'));
        exit();
    }

    // responce
    echo json_encode(array(
        'illust_id' => $illust_id,
        'illust_url' => $illust_url,
        'user_name' => $user_name,
        'date' => $date
    ));
?>
