<?php
    $db = new SQLite3('../database.db');

    if(!isset($_GET['user_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $user_id = $_GET['user_id'];

    $stmt = $db->prepare('SELECT type, activity.date, activity.title_id, title, illust_id' .
                         ' FROM activity LEFT JOIN title ON title.title_id = activity.title_id' .
                         ' WHERE target_user_id=:user_id' .
                         ' ORDER BY _id DESC LIMIT 100');
    $stmt->bindValue('user_id', $user_id, SQLITE3_INTEGER);

    $results = $stmt->execute();
    
    if(!$results) {
        http_response_code(500);
        die();
    } else {
        $rows = array();
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $rows []= $row;
        }
        echo json_encode($rows);
    }
?>
