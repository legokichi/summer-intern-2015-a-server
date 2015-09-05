<?php
    /*
        [
            {
                "title_id": 2,
                "date": 1441346245, // UNIX Time Stamp
                "title": "犬が波動拳を打っている絵が欲しい"
                "count": 0,
            },
            {
                "title_id": 1,
                "date": 1441346174, // UNIX Time Stamp
                "title": "縁側で寝ている猫の絵を下さい"
                "count": 1,
            }
        ]
    */

    if(!isset($_GET['title_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $title_id = $_GET['title_id'];

    $db = new SQLite3('../database.db');

    $stmt = $db->prepare('SELECT title, date FROM title WHERE title_id = :title_id');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $result = $stmt->execute();
    
    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'no such title'));
        exit();
    }

    $title = $result->fetchArray();
    $title_date = $title['date'];
    $title_text = $title['title'];

    $stmt = $db->prepare('SELECT illust_id, title_id, user_id, likes, date FROM illust WHERE title_id = :title_id');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot get illust'));
        exit();
    }

    $responses = array();
    while ($response = $result->fetchArray(SQLITE3_ASSOC)) {
        $responses []= $response;
    }

    echo json_encode(array(
        'id' => $title_id,
        'date' => $title_date,
        'title' => $title_text,
        'responses' => $responses
    ));

?>
