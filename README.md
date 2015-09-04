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
