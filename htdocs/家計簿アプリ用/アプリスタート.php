<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title></head>
<body>
<?php
print "<h1>月初めデータリセット</h1>\n";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf.php\" method=\"post\"> \n";
print"<button type=\"submit\" name=\"back\">リセット</button>";
print "</form>";


$dbs = 'mysql:dbname=kakeibo;host=localhost';//データベース接続
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);

$query = "DROP TABLE ss2";
$stmt = $pdo->prepare($query);
$stmt -> execute();    

$query = "DROP TABLE saihu";
$stmt = $pdo->prepare($query);
$stmt -> execute();    

$query = "DROP TABLE first_saihu";
$stmt = $pdo->prepare($query);
$stmt -> execute();    

?>
</body>
</html>


