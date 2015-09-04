<?php
    
    /*
        {
            "title_id": 2,
            "date": 1441346245, // UNIX Time Stamp
            "title": "犬が波動拳を打っている絵が欲しい"
            "count": 0,
        }
    */
    if(!isset($_POST['title']) || !isset($_POST['user_id'])) { // 入力がない
        http_response_code(500);
        die();
    }

    $title = $_POST['title'];
    $user_id = $_POST['user_id'];

    $date = time();

    $db = new SQLite3('../database.db');

    $stmt = $db->prepare('INSERT INTO title (user_id, date, title) VALUES (:user_id ,:date, :title)');
    $stmt->bindValue(':user_id', $user_id, SQLITE3_INTEGER);
    $stmt->bindValue(':date', $date, SQLITE3_TEXT);
    $stmt->bindValue(':title', $title, SQLITE3_TEXT);

    $result = $stmt->execute();
    
    if(!$result) {
        http_response_code(500);
    } else {
        /*
         lastInsertRowID is relative to the database connection. Hence if there are two instances of a php script (with distinct $db connections) there is no risk that the RowID of the one instance will effect the result of the other instance.
        */
        echo json_encode(array(
            'title_id' => $db->lastInsertRowId(),
            'date' => $date,
            'title' => $title,
            'count' => 0
        ));
    }

?>
