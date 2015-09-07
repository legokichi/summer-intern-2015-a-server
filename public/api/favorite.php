<?php
    if(!isset($_POST['illust_id']) || !ctype_digit($_POST['illust_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $illust_id = $_POST['illust_id'];

    $date = time();

    $db = new SQLite3('../database.db');

    // イラストテーブルを更新
    $stmt = $db->prepare('UPDATE illust SET likes = likes + 1 WHERE illust_id = :illust_id');
    $stmt->bindValue(':illust_id', $illust_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot update number'));
        exit();
    }

    // アクティビティテーブルを更新

    // イラストテーブルからタイトルIDを取得
    $title = $db->querySingle("SELECT title_id, user_id FROM illust WHERE illust_id = $illust_id", true);
    if(!$title) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot get title_id'));
        exit();
    }

    $title_id = $title['title_id'];
    $target_id = $title['user_id'];

    $stmt = $db->prepare('INSERT INTO activity (type, date, illust_id, title_id, target_user_id)'
                        .' VALUES (:type, :date, :illust_id, :title_id, :target_user_id)');
    $stmt->bindValue(':type', 2, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':illust_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':title_id', $illust_id, SQLITE3_INTEGER);
    $stmt->bindValue(':target_user_id', $target_id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot insert activity'));
        exit();
    } 

    echo json_encode(array('date' => $date));
?>
