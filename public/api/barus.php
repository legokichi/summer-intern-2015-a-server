<form method="POST">
<input type="submit" name="barus" value="バルス">
</form>
<?php

    if(!isset($_POST['barus'])) exit();

    $db = new SQLite3('../database.db');

    $db->exec("BEGIN DEFERRED;");

    $queries = array(
        "DROP TABLE title",
        "DROP TABLE illust",
        "DROP TABLE activity",
        "DROP TABLE user",
        "CREATE TABLE title (title_id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, date TEXT, title TEXT)",
        "CREATE TABLE illust( illust_id INTEGER PRIMARY KEY AUTOINCREMENT, title_id INTEGER, user_id INTEGER, likes INTEGER, date TEXT)",
        "CREATE TABLE activity( _id INTEGER PRIMARY KEY AUTOINCREMENT, type INTEGER, date TEXT, illust_id INTEGER, title_id INTEGER, target_user_id INTEGER)",
        "CREATE TABLE user ( user_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT)"
    );

    try {

        $db->exec("BEGIN DEFERRED;");

        foreach ($query as $queries) {
            if(!$db->exec($query)) {
                $db->exec("ROLLBACK;");
                print 'ERROR;';
                exit();
            }
        }

    } catch (Exception $e) {
        $db->exec("ROLLBACK;");
        print 'ERROR!';
        exit();
    }

    $db->exec("COMMIT;");

    echo ('<img src="http://middle-edge.jp/file/parts/I0000604/abf86a576cf524c2167c5e8614fd271e.jpg">');
?>


