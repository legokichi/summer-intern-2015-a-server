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
       !isset($_FILES['file']) ||
       !ctype_digit($_POST['user_id'])
    ) { // 入力がない
        http_response_code(500);
        echo json_encode(array('error' => 'Invalid parameter'));
        die();
    }

    $title_id = $_POST['title_id'];
    $user_id = $_POST['user_id'];
    $filedata = $_FILES['file'];

    $date = time();

    $db = new SQLite3('../database.db');

    // title の作者を得る -> 無かったらエラー
    $target_id = $db->querySingle("SELECT user_id FROM title WHERE user_id = '$user_id'");
    if(!$target_id) {
        http_response_code(500);
        echo json_encode(array('error' => 'No such title'));
        exit();
    }

    $stmt = $db->prepare('INSERT INTO illust (title_id, user_id, likes, date) VALUES (:title_id, :user_id, 0, :date)');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'Cannot insert illust'));
        exit();
    }

    $illust_id = $db->lastInsertRowID();
    $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/$illust_id";

    // upload file

    if(!move_uploaded_file($filedata['tmp_name'], $uploaddir . $illust_id)) {
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
        'date' => $date
    ));
?>
