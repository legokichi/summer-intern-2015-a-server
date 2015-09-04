#!/bin/bash

echo "CREATE TABLE title(title_id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, date TEXT, title TEXT);" > dbscheme
echo "CREATE TABLE illust( illust_id INTEGER PRIMARY KEY AUTOINCREMENT, title_id INTEGER, user_id INTEGER, likes INTEGER, date TEXT);" >> dbscheme
echo "CREATE TABLE like( type INTEGER, date TEXT, illust_id INTEGER);" >> dbscheme

rm -f database.db
cat dbscheme | sqlite3 database.db
rm -f dbscheme