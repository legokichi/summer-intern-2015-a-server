<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <style>
        body {
          min-height: 2000px;
          padding-top: 70px;
        }
    </style>
    <title>Das Werkzeug</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand" href="#">Das Werkzeug</a>
        </div>
      </div>
    </nav>

<div class="container" role="main">

<div class="page-header">
<h1>Tables</h1>
</div>

<p><a href="delete_db.php" class="btn btn-lg btn-danger">初期化</a></p>

<?php
    function output_table($db, $tblname){
        echo '<table class="table table-striped">';
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

<div class="page-header">
<h1>POST Requests</h1>
</div>

<h2>Post Title</h2>
<form class="form-inline" role="form" action="api/post_title.php" method="POST" target="result">
  <div class="form-group">
    <label for="user_id">user_id:</label>
    <input type="number" class="form-control" id="user_id" name="user_id">
  </div>
  <div class="form-group">
    <label for="user_name">user_name:</label>
    <input type="text" class="form-control" id="user_name" name="user_name">
  </div>
  <div class="form-group">
    <label for="title">title:</label>
    <input type="text" class="form-control" id="title" name="title">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<h2>Post Illust</h2>
<form enctype="multipart/form-data" class="form-inline" role="form" action="api/post_illust.php" method="POST" target="result">
  <div class="form-group">
    <label for="title_id">title_id:</label>
    <input type="number" class="form-control" id="title_id" name="title_id">
  </div>
  <div class="form-group">
    <label for="user_id">user_id:</label>
    <input type="number" class="form-control" id="user_id" name="user_id">
  </div>
  <div class="form-group">
    <label for="user_name">user_name:</label>
    <input type="text" class="form-control" id="user_name" name="user_name">
  </div>
  <div class="form-group">
    <label for="file">file:</label>
    <input type="file" class="form-control" name="file" required>
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>


<h2>Like</h2>
<form class="form-inline" role="form" action="api/favorite.php" method="POST" target="result">
  <div class="form-group">
    <label for="illust_id">illust_id:</label>
    <input type="number" value="1" class="form-control" name="illust_id" id="illust_id">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>

<h2>Result</h2>
<iframe name="result"></iframe>

</div>

<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
</body>
</html>

