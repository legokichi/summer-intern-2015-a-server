
<h1>Das Werkzeug</h1>

<p><a href="api/barus.php">初期化</a></p>

<h2>Tables</h2>
<?php
    function output_table($db, $tblname){
        echo '<table border="3">';
        echo "<caption>$tblname</caption>";
        $result = $db->query('SELECT * FROM ' . $tblname);

        $cols = $result->numColumns();
        echo '<tr>';
        for ($i = 0; $i < $cols; $i++) { 
            echo '<th>' . $result->columnName($i) . '</th>'; 
        } 
        echo '</tr>';
        
        while ($row = $result->fetchArray(SQLITE3_NUM)) {
            echo '<tr>';
            foreach ($row as $col) {
                echo "<td>$col</td>";
            }
            echo '</tr>';
        }
        echo '</table>';
    }

    $db = new SQLite3('database.db');
    // output_table($db, 'user');
    output_table($db, 'title');
    output_table($db, 'illust');
    output_table($db, 'activity');
?>

<h2>Post Title</h2>
<form action="api/post_title.php" method="POST" target="result">
    <p>User ID: <input type="number" name="user_id" value="1" required></p>
    <p>User Name: <input type="text" name="user_name" value="noname" required></p>
    <p>Title: <input type="text" name="title" required></p>
    <p><input type="submit"></p>
</form>

<h2>Post Illust</h2>
<form action="api/post_illust.php" method="POST" target="result" enctype="multipart/form-data">
    <p>User ID: <input type="number" name="user_id" value="1" required></p>
    <p>Title ID: <input type="number" name="title_id" value="1" required></p>
    <p>User Name: <input type="text" name="user_name" value="noname" required></p>
    <p>File: <input type="file" name="file" required></p>
    <p><input type="submit"></p>
</form>

<h2>Like</h2>
<form action="api/favorite.php" method="POST" target="result" enctype="multipart/form-data">
    <p>Illust ID: <input type="number" name="illust_id" value="1" required></p>
    <p><input type="submit"></p>
</form>

<h2>Result</h2>
<iframe name="result">
