<?php
    if(!isset($_POST['illust_id'])) {
        http_response_code(500);
        echo json_encode(array('error' => 'missing parameter'));
        exit();
    }

    $illust_id = $_POST['illust_id'];

    $date = time();

    // イラストテーブルを更新
    // アクティビティテーブルを更新
?>
