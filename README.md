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
        "id": 2,
        "date": 1441346245, // UNIX Time Stamp
        "title": "犬が波動拳を打っている絵が欲しい"
        "count": 0,
    },
    {
        "id": 1,
        "date": 1441346174, // UNIX Time Stamp
        "title": "縁側で寝ている猫の絵を下さい"
        "count": 1,
    }
]
```

### お題に対する絵一覧

お題のidを渡して、絵の一覧を返す。投稿日時・イラストの画像URL・♥の数を返す。

* 場所

`/get_responses.php`

* パラメータ

GET で `/get_titles.php` で得た `id` を渡す。

* レスポンス例

```
{
    "id": 1
    "date": 1441346174, // UNIX Time Stamp
    "title": "縁側で寝ている猫の絵を下さい"
    "responses": [ // 配列
        {
            "date": 1441346967 // UNIX TimeStamp
            "illust_url": "http://hogehoge/img/42.jpg",
            "like": 20 // Likeの数
        }
    ]
}
```
