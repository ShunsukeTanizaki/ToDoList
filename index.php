<?php

$errors = array();

if (isset($_POST['submit'])) {

  $name = $_POST['name'];
  $memo = $_POST['memo'];
  
  $name = htmlspecialchars($name, ENT_QUOTES);
  $memo = htmlspecialchars($memo, ENT_QUOTES);

  if ($name === '') {
    $errors['name'] = 'お名前が入力されていません。';
  }

  if ($memo === '') {
    $errors['memo'] = 'メモが入力されていません。';
  }
  
  if (count($errors) === 0) {
    $dsn = 'mysql:dbname=phpkiso;host=localhost';
    $user = "root";
    $password = "root";
    $dbh = new PDO ($dsn, $user,$password);
    $dbh->query('SET NAMES utf8');

    $sql = 'INSERT INTO tasks (name, memo) VALUES  (?, ?)';
    $stmt = $dbh->prepare($sql);

    $stmt->bindValue(1, $name, PDO::PARAM_STR);
    $stmt->bindValue(2, $memo, PDO::PARAM_STR);
    $stmt->execute();
    
    $dbh = null;

    unset($name, $memo);
  }
  
}  
?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

  <title>TODO</title>
  <style>
    .ichiran_todo {float: left; margin-right: 20px;}
    .ichiran_memo {float: left; margin-right: 20px;}
    .delete_button{float: left;}
    .checkbox{margin-right: 10px;}
    .contents {margin-bottom: 15px;}
     /* #exampleInputtext { color:red; opacity:0.6;} */
  </style>
</head>

<body>
  <div class="container">
    <h1>ToDoリスト</h1>
    <form action="index.php" method = "post">
      <div class="form-group">
        <input type="text" class="form-control col-md-3 mb-3 new_todo" id="exampleInputtext" placeholder="新規予定" name="name">
      </div>
      <div class="form-group">
        <textarea type="textarea" class="form-control" id="exampleInputPassword1" name="memo" placeholder="メモ"></textarea>
      </div>
      <button type="submit" name="submit" class="btn btn-primary col-md-1 mb-1">保存</button>
    </form>

    <hr>

    <div class="ichiran">
    <?php
    
    // 接続
    $dsn = 'mysql:dbname=phpkiso;host=localhost';
    $user = "root";
    $password = "root";
    $dbh = new PDO ($dsn, $user,$password);
    $dbh -> query('SET NAMES utf8');
    // 指令
    $sql = 'SELECT * FROM tasks WHERE del_flg=false ORDER BY code DESC';
    $stmt = $dbh->prepare($sql);
    $stmt -> execute();

    while (1) {
      $rec = $stmt->fetch(PDO::FETCH_ASSOC);  //1レコード呼び出し
      
      if ($rec == false) {
        break;
      }
      // 表示
      $num = $rec['code'];
      print '<div class="contents">';
      print '<div class="form-control col-md-3 mb-1 ichiran_todo" id="exampleInputtext">';
      print '<form method = "post"><input type="checkbox" class="checkbox" name="Checkbox'.$num.'" value="checkbox'.$num.'" aria-label="Checkbox for following text input">';
      print $rec['name'];
      print '</form></div>';
      print '<div class="form-control col-md-7 mb-3 ichiran_memo" id="exampleInputtext">';
      print $rec['memo'];
      print '</div>';
      print '<form action="index.php"  method = "post"><button type="submit" name="delete" class="btn btn-primary col-md-1 mb-1 delete_button"  id="delete_button" value="'.$num.'">削除</button></form>';
      print '</div>';
      print '<br/>';
      
    }
      $dbh = null;

      // 削除の処理
  if (isset($_POST['delete'])) {
    $num = $_POST['delete'];
    $dsn = 'mysql:dbname=phpkiso;host=localhost';
    $user = "root";
    $password = "root";
    $dbh = new PDO ($dsn, $user,$password);
    $dbh->query('SET NAMES utf8');

    $sql = "UPDATE tasks SET del_flg = 1 WHERE code = $num";
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    
     // 切断
    $dbh = null;
    // header("Location: localhost/index.php");
  }

    ?>

    
</body>

  <!-- Optional JavaScript -->
  <!-- jQuery first, then Popper.js, then Bootstrap JS -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>