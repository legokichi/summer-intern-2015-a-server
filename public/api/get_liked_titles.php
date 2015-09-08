<?php

    if(!isset($_GET['user_id']) || !ctype_digit($_GET['user_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $user_id = $_GET['user_id'];

    $db = new SQLite3('../database.db');
    $cols = 'title.title_id, title.user_id, title.user_name, title.title, like_title.date';
    $results = $db->query("SELECT $cols FROM like_title "
                           . 'LEFT JOIN title ON title.title_id = like_title.title_id '
                           . "WHERE like_title.user_id = $user_id");
    
    if(!$results) {
        http_response_code(500);
    } else {
        $titles = array();
        while ($title = $results->fetchArray(SQLITE3_ASSOC)) {
            $illustres = $db->query("SELECT illust_id FROM illust WHERE title_id = '{$title['title_id']}'");
            $title['illusts'] = array();
            while ($illust = $illustres->fetchArray(SQLITE3_ASSOC)) {
                $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/{$illust['illust_id']}";
                $title['illusts'][] = $illust_url;
            }
            $title['count'] = count($title['illusts']);
            $titles []= $title;
        }
        echo json_encode($titles);
    }

?>
