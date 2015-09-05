<?php
    $db = new SQLite3('../database.db');

    $stmt = $db->prepare('INSERT INTO user (name) VALUES (:name)');
    $stmt->bindValue(':name', 'noname', SQLITE3_INTEGER);

    $result = $stmt->execute();
    
    if(!$result) {
        http_response_code(500);
        echo json_encode(array(
            'error' => 'cannot create a user'
        ));
        exit();
    } else {
        echo json_encode(array(
            'user_id' => $db->lastInsertRowId(),
        ));
    }
?>
