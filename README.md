# summer-intern-2015-a-server

## API仕様

### 新規ユーザーID付与

新しいユーザーIDを返す。IDは数値。

* 場所

`/register_new_user.php`

* パラメータ

なし。

* レスポンス例

```
{
    "user_id" : 5
}
```

### お題一覧の取得

お題の一覧を時間順に返す。投稿ユニークID・投稿時間・お題テキスト・書かれた絵の数

* 場所

`/get_titles.php`

* パラメータ

なし

* レスポンス例

```
[
    {
        "title_id": 2,
        "date": 1441346245, // UNIX Time Stamp
        "user_name": "aaaaaaaaaaa",
        "title": "犬が波動拳を打っている絵が欲しい"
        "illusts" : [ ],
        "count": 0,
    },
    {
        "title_id": 1,
        "user_name": "asdf",
        "date": 1441346174, // UNIX Time Stamp
        "title": "縁側で寝ている猫の絵を下さい",
        "illusts" : [
            "http://hogehoge/img/1",
        ]
        "count": 1,
    }
]
```

### お題に対する絵一覧

お題のidを渡して、絵の一覧を返す。投稿日時・イラストの画像URL・♥の数を返す。

* 場所

`/get_responses.php?title_id=1`

* パラメータ

GET で `/get_titles.php` で得た `title_id` を渡す。

* レスポンス例

```
{
    "id": 1
    "date": 1441346174, // UNIX Time Stamp
    "title": "縁側で寝ている猫の絵を下さい"
    "user_name": "asdf",
    "responses": [ // 配列
        {
            "date": 1441346967 // UNIX TimeStamp
            "user_name": "tomorinao",
            "illust_id": 42,
            "illust_url": "http://hogehoge/img/42.jpg",
            "like": 20 // Likeの数
        }
    ]
}
```

### 通知取得

自分のユーザーIDを渡して、メンション一覧を返す。

返り値の`type`について、
 * `1` -> お題に関する反応 (絵を返してきた)
 * `2` -> 絵に対する反応 (Likeをつけてきた)
 * `3` -> 自分の投稿したお題にLIKEが付けられた（イラスト情報はnullが帰る）

それぞれ、時間・お題のタイトル・お題のID・イラストのID・イラストのURLが含まれる

* 場所

`/get_reactions.php?user_id=1`

* パラメータ

GET で `/register_new_user.php` で得た `user_id` を渡す。

* レスポンス例

```
[
    {
        "type": 2 // 絵にlikeがついた
        "date": 1287427839, // unix time
        "title": "友利奈緒の絵が見たいんですけど",
        "title_id": 3,
        "illust_id": 27,
        "illust_url": "http"//~~~~~~"
    }, {
        "type": 1 // お題に絵が帰ってきた
        "date": 1287427839, // unix time
        "title": "縁側に寝ている猫の絵を下さい",
        "title_id": 2,
        "illust_id": 24,
        "illust_url": "http"//~~~~~~"
    }
]
```

### お題を投稿

お題をPOSTで投稿

* 場所

`/post_title.php`

* パラメータ

POST で `title` (お題の文), `user_id`, `user_name` (名前)  を渡す。

* レスポンス例

```
{
    "title_id": 2,
    "user_name": "tomorinao",
    "date": 1441346245, // UNIX Time Stamp
    "title": "犬が波動拳を打っている絵が欲しい"
    "count": 0,
}
```

### 写真を投稿

写真をPOSTで投稿

* 場所

`/post_illust.php`

* パラメータ

POST で `title_id`, `user_id`, `user_name` (名前) と 画像データ `file` を multi-part/formdata で送信

* レスポンス例

```
{
    "date": 1441346967 // UNIX TimeStamp
    "user_name": "no name",
    "illust_id": 42,
    "illust_url": "http://hogehoge/img/42.jpg",
}
```

### LIKEを付ける

LikeをPOSTで投稿

* 場所

`/favorite.php`

* パラメータ

POST で `illust_id` を送信

* レスポンス例

```
{
    "date": 1441346967 // UNIX TimeStamp
}
```

### お題をLIKEする

お題をLIKEする。

* 場所

`/like_title.php`

* パラメータ

POST で `title_id`, `user_id` を送信

* レスポンス例

```
{
    "date": 1441346967 // UNIX TimeStamp
}
```

### LIKEしたお題の一覧を返す

`user_id` を指定して LIKE したお題の一覧

* 場所

`/get_liked_titles.php`

* パラメータ

GET で `user_id` を送信。 `title_id` でタイトルIDの指定ができる

* レスポンス例

```
[
    {
        "title_id": 2,
        "date": 1441346245, // UNIX Time Stamp
        "user_name": "aaaaaaaaaaa",
        "title": "犬が波動拳を打っている絵が欲しい"
        "illusts" : [ ],
        "count": 0,
    },
]
```

## DB設計

* 今回は超簡潔、正規化は行いません
* 面倒なのでDBはSQLite3です

### ユーザーテーブル

```
CREATE TABLE user(
    user_id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT // 予約
)
```

### お題テーブル

```
CREATE TABLE title(
    title_id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    user_name TEXT,
    date TEXT,
    title TEXT,
)
```

### イラストテーブル

```
CREATE TABLE illust(
    illust_id INTEGER PRIMARY KEY AUTOINCREMENT,
    title_id INTEGER,
    user_name TEXT,
    user_id INTEGER,
    likes INTEGER,
    date TEXT,
)
```

### アクティビティテーブル

```
CREATE TABLE activity(
    _id INTEGER PRIMARY KEY AUTOINCREMENT,
    type TYPE,
    date TEXT,
    illust_id INTEGER,
    title_id INTEGER,
    target_user_id INTEGER
)
```

### お気に入りお題テーブル
```
CREATE TABLE like\_title (
    _id INTEGER PRIMARY KEY AUTOINCREMENT,
    user_id INTEGER,
    title_id INTEGER,
    date TEXT,
)
```
