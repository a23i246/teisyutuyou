<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title></head>
<body>
<?php
print "<h1>前期繰越金</h1>\n";
$way[0] = "現金";
$way[1] = "PayPay";
$way[2] = "クレカ";
$way[3] = "貯金";
$way[4] = "その他";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%89%8d%e6%9c%9f%e7%b9%b0%e8%b6%8a.php\" method=\"post\"> \n";
print"購入方法：";
print "<select size = \"1\" name=\"way\">"; //セレクトボックス作成
foreach($way as $value){ //文字列を挿入
    print "<option value = {$value}>{$value}</option> \n";
}
print "</select><br>";
print "金額: \n";
print "<input type=\"number\" name=\"yen1\"/><br>\n";
print "<input type=\"submit\" value=\"送信\"/> <br/>\n";
print"<button type=\"submit\" name=\"back\">戻る</button><br>";

print "</form>";

if(isset($_POST["back"])){
    header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf%20var2.php");
    exit();
}    


if(!(empty($_POST["yen1"]))){
    $ways = $_POST["way"];
    $yens = $_POST["yen1"];

    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);
    
    $query = "CREATE TABLE first_saihu(
        ways VARCHAR(10),
        Yen1 INTEGER)";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "INSERT INTO first_saihu(ways,Yen1) VALUES('$ways',$yens)";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();	
    print"追加しました。";
    
}

?>
</body>
</html>


