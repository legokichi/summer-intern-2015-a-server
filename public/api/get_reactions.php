<?php
    $db = new SQLite3('../database.db');

    $results = $db->query('SELECT type, activity.date, activity.title_id, title, illust_id' .
                          ' FROM activity LEFT JOIN title ON title.title_id = activity.title_id' .
                          ' ORDER BY _id DESC LIMIT 100');
    
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
