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
       !isset($_FILES['file'])) { // 入力がない
        http_response_code(500);
        die();
    }

    $title_id = $_POST['title_id'];
    $user_id = $_POST['user_id'];
    $filedata = $_FILES['file'];

    $date = time();

    $db = new SQLite3('../database.db');

    $stmt = $db->prepare('INSERT INTO illust (title_id, user_id, likes, date) VALUES (:title_id, :user_id, 0, :date)');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);

    $result = $stmt->execute();
    
    if(!$result) {
        echo 'insert error';
        http_response_code(500);
    } else {
        $illust_id = $db->lastInsertRowID();
        $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/$illust_id";

        if(move_uploaded_file($filedata['tmp_name'], $uploaddir . $illust_id)) {
            echo json_encode(array(
                'illust_id' => $illust_id,
                'illust_url' => $illust_url,
                'date' => $date
            ));
        } else {
            echo 'missing data';
            http_response_code(500);
        }

    }

?>
