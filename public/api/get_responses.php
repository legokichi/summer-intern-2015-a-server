<?php

    if(!isset($_GET['title_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $title_id = $_GET['title_id'];

    $db = new SQLite3('../database.db');

    $stmt = $db->prepare('SELECT title, date, user_name FROM title WHERE title_id = :title_id');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    $title = $result->fetchArray();
    $title_date = $title['date'];
    $title_text = $title['title'];
    $title_user = $title['user_name'];

    $stmt = $db->prepare('SELECT illust_id, title_id, user_id, user_name, likes, date FROM illust WHERE title_id = :title_id');
    $stmt->bindValue(':title_id', $title_id, SQLITE3_INTEGER);
    $result = $stmt->execute();

    if(!$result) {
        http_response_code(500);
        echo json_encode(array('error' => 'cannot get illust'));
        exit();
    }

    $responses = array();
    while ($response = $result->fetchArray(SQLITE3_ASSOC)) {
        $illust_url = "http://{$_SERVER['HTTP_HOST']}/img/{$response['illust_id']}";
        $response['illust_url'] = $illust_url;
        $responses []= $response;
    }

    echo json_encode(array(
        'id' => $title_id,
        'date' => $title_date,
        'user_name' => $title_user,
        'title' => $title_text,
        'responses' => $responses
    ));
?>
