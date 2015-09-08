<?php
    $queries = array(
        "DROP TABLE IF EXISTS title;",
        "DROP TABLE IF EXISTS illust;",
        "DROP TABLE IF EXISTS activity;",
        "DROP TABLE IF EXISTS user;",
        "DROP TABLE IF EXISTS like_title;",
        "CREATE TABLE title (title_id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, user_name TEXT, date TEXT, title TEXT);",
        "CREATE TABLE illust( illust_id INTEGER PRIMARY KEY AUTOINCREMENT, title_id INTEGER, user_id INTEGER, user_name TEXT, likes INTEGER, date TEXT);",
        "CREATE TABLE activity( _id INTEGER PRIMARY KEY AUTOINCREMENT, type INTEGER, date TEXT, illust_id INTEGER, title_id INTEGER, target_user_id INTEGER);",
        "CREATE TABLE user ( user_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT);",
        "CREATE TABLE like_title ( _id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, title_id INTEGER, date TEXT);"
    );

    try {
        system('echo "' . implode('', $queries) . '" | sqlite3 database.db');
    } catch (Exception $e) {
        print 'ERROR!';
        exit();
    }

    // remove all images
    array_map('unlink', glob("../img/*"));

    header('Location: ./debug.php');
?>


