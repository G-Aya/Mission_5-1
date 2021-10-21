<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title> Mission5</title>
    </head>
     <body>
            <h1>今欲しいもの教えてください</h1>
   
    
    <?php
     // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //テーブル作成 4-2
    $sql = "CREATE TABLE IF NOT EXISTS  Mission5"
    ." ("
    . "id INT AUTO_INCREMENT PRIMARY KEY,"//自動採番列
    . "name char(32),"
    . "comment TEXT,"
    . "date DATETIME," //日時
    . "pass TEXT"
    .");"; //たぶん各種番号は$id = $_POST[]で事足りる
    $stmt = $pdo-> query($sql);
   
     //投稿機能
    //投稿フォームが空でないとき    
    if(empty($_POST["name"]) === false && empty($_POST["comment"]) ===false){
       if(empty($_POST["edit"])===true ){
           
         //新規投稿
         $name = $_POST["name"];//書き込みフォームからの反映をしたい
         $comment = $_POST["comment"]; 
         $date = date("Y/m/d H:m;s");
         $pass = $_POST["pass"];
         $sql = $pdo -> prepare("INSERT INTO  Mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
         $sql -> bindParam(':name', $name, PDO::PARAM_STR);
         $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
         $sql -> bindParam(':date', $date, PDO::PARAM_STR);
         $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
         $sql->execute();//$stmtから$sqlに変更
       }//editがないとき　終
    }//名前とコメントがあるとき終
          //表示
        //$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
        $sql = 'SELECT * FROM Mission5';//なぜここに入るか不明！！！！！！！
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
         foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
             $row['id'].',';
             $row['name'].',';
             $row['comment'].',';
             $row['date'].'<br>';

         
        //編集でフォームへの変換。その後、編集し、表示するときに、このやりかた
        //でないと！！
            //editがあるとき→編集
           if(empty($_POST["name"]) === false && empty($_POST["comment"]) ===false){
            if(empty($_POST["edit"] === false)){
                 //編集
                 //bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
                  $id = $_POST["edit"]; //変更する投稿番号
                 $name = $_POST["name"];//変更したい名前
                 $comment = $_POST["comment"];//変更したいコメント
    
                 //変更したい名前、変更したいコメントは自分で決めること
                 $sql = 'UPDATE  Mission5 SET name=:name,comment=:comment WHERE id=:id';
                 $stmt = $pdo->prepare($sql);
                 $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                 $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                 $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                 $stmt->execute();
            }//foreach終
           }// 名前とコメント　終
         }  //enumのあるとき　終
    //名前とコメントがあったとき終了
    //削除機能
    //dnum(削除番号)とdpass(削除フォーム内パス)有
    if(empty($_POST["dnum"]) === false ){
      if($_POST["dpass"] ==$row["pass"] ){
         //削除
         $id = $_POST["dnum"];
         $sql = 'delete from  Mission5 where id=:id';
         $stmt = $pdo->prepare($sql);
         $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
    }
        }
    /*編集フォームの送信の有無で条件分岐*/
    /*送信があるとき*/
    if(empty($_POST["enum"]) ===false){
        if($_POST["epass"] == $row["pass"]){
            $id = $_POST["enum"];
       $sql = 'SELECT * FROM Mission5 WHERE id = id';
        $stmt = $pdo->query($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
         $stmt->execute();
        $results = $stmt->fetchAll();
        /*配列の数だけループ*/
         foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
           $eenum =  $row['id'];
           $ename =  $row['name'];
           $ecomment = $row['comment'];
         }
         }
    }
    
        ?>
       
    <!--投稿フォーム -->
    <form action = " " method = "post">
       <input type = "text" name = "name" placeholder = "名前"
        value = "<?php if(!empty($ename)){echo $ename;}?>">
        <input type = "text" name = "comment" placeholder = "コメント"
        value = "<?php if(!empty($ecomment)){echo $ecomment ;}?>">
        <input type = "text" name = "pass" placeholder = "パスワード">
        <input type = "hidden" name = "edit"
        value = "<?php if(!empty($eenum)){echo $eenum ;}?>">
        <input type = "submit" name = "submit">
        </form>
        <!--削除フォーム-->
    <form action = " " method = "post">
        <input type = "number" name = "dnum" placeholder = "削除番号">
        <input type = "text" name = "dpass" placeholder = "削除パスワード">
        <input type = "submit" name = "dsub" value = "削除">
        </form>
        <!--編集フォーム -->
    <form action = " " method = "post">
        <input type = "number" name = "enum" placeholder = "編集番号">
        <input type = "text" name = "epass" placeholder = "編集パスワード">
        <input type = "submit" name = "esub" value = "編集"></form>
        <h2>-------------------------------------------------------------</h2>
    <?php
        //表示
        //$rowの添字（[ ]内）は、4-2で作成したカラムの名称に併せる必要があります。
        $sql = 'SELECT * FROM Mission5';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
         foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].',';
            echo $row['date'].'<br>';
            echo "<hr>";
         }
    
?>
</body>
</html>