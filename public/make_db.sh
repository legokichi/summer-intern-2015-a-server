#!/bin/bash

echo "CREATE TABLE title(title_id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, date TEXT, title TEXT);" > dbscheme
echo "CREATE TABLE illust( illust_id INTEGER PRIMARY KEY AUTOINCREMENT, title_id INTEGER, user_id INTEGER, likes INTEGER, date TEXT);" >> dbscheme
echo "CREATE TABLE activity( _id INTEGER PRIMARY KEY AUTOINCREMENT, type INTEGER, date TEXT, illust_id INTEGER, title_id INTEGER, target_user_id INTEGER);" >> dbscheme
echo "CREATE TABLE user ( user_id INTEGER PRIMARY KEY AUTOINCREMENT, name TEXT);" > dbscheme

rm -f database.db
cat dbscheme | sqlite3 database.db
chmod 777 database.db
rm -f dbscheme
