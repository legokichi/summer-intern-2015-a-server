<?php
    if(!isset($_POST['title_id']) || !ctype_digit($_POST['title_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $title_id = $_POST['title_id'];
    $user_id = $_POST['user_id'];

    $date = time();

    $db = new SQLite3('../database.db');

    // イラストテーブルからユーザーIDを取得
    $target_id = $db->querySingle("SELECT user_id FROM title WHERE title_id = $title_id");
    if(!$target_id) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot get title_id'));
        exit();
    }

    // like_title に insert

    $stmt = $db->prepare('INSERT INTO like_title (user_id, title_id, date)'
                        .' VALUES (:user_id, :title_id, :date)');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot insert like_title'));
        exit();
    } 

    // activity に insert

    $stmt = $db->prepare('INSERT INTO activity (type, date, title_id, target_user_id)'
                        .' VALUES (:type, :date, :title_id, :target_user_id)');
    $stmt->bindValue(':type', 3, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $stmt->bindValue(':target_user_id', $target_id, SQLITE3_INTEGER);

    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot insert activity'));
        exit();
    } 

    echo json_encode(array('date' => $date));
?>
