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

    $db = new SQLite3('../database.db');

    $results = $db->query('SELECT * FROM title ORDER BY title_id DESC LIMIT 100');
    
    if(!$results) {
        http_response_code(500);
    } else {
        $rows = array();
        while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
            $rows []= $row;
        }
        echo json_encode($rows);
    }

?>
