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
        $titles = array();
        while ($title = $results->fetchArray(SQLITE3_ASSOC)) {
            $title['last_action'] = $title['date'];
            $illustres = $db->query("SELECT illust_id, date FROM illust WHERE title_id = '{$title['title_id']}' ORDER BY illust_id DESC");
            $title['illusts'] = array();
            $title['illust_ids'] = array();
            while ($illust = $illustres->fetchArray(SQLITE3_ASSOC)) {
                $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/{$illust['illust_id']}";
                $title['illusts'][] = $illust_url;
                $title['illust_ids'][] = $illust['illust_id'];
                $title['last_action'] = $illust['date'];
            }
            $title['count'] = count($title['illusts']);
            $titles []= $title;
        }

        $titles = array_multisort(array_column($titles, 'last_action'), SORT_DESC, $titles);

        echo json_encode($titles);
    }

?>
