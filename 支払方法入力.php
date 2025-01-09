<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title></head>
<body>
<?php
print "<h1>支払方法</h1>\n";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e6%94%af%e6%89%95%e6%96%b9%e6%b3%95%e5%85%a5%e5%8a%9b.php\" method=\"post\"> \n";
print "支払方法: \n";
print "<input type=\"text\" name=\"way\"/><br>\n";
print "削除する支払方法のID: \n";
print "<input type=\"number\" name=\"name2\"/><br>\n";

print "<input type=\"submit\" value=\"送信\"/> <br/>\n";
print"<button type=\"submit\" name=\"back\">戻る</button><br>";
print "</form>";

if(isset($_POST["back"])){
    header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf%20var2.php");
    exit();
}    

if(!(empty($_POST["name2"]))){
    darete($_POST["name2"]);
}

$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);


if(!(empty($_POST["way"]))){
    
    $money = $_POST["way"];

    $query = "CREATE TABLE way_money(
        ID INTEGER,
        moneys VARCHAR(10))";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "SELECT * FROM way_money";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();
    $num1 = $stmt->rowCount()+1;

    $query = "INSERT INTO way_money(ID,moneys) VALUES($num1,'$money')";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();	

    print"追加しました。<br>";
    
}


$query = "SELECT * FROM way_money";
$stmt = $pdo->prepare($query);
$stmt -> execute();    

while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
    print"{$info['ID']}:{$info['moneys']}<br>\n";
}

function darete($ID1){

    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $way1 = [];
    $Id = [];
    $num100 = 0;

    $query = "SELECT * FROM way_money";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $way1[$num100] = $info["moneys"];
        $ID[$num100] = $info["ID"];
        $num100++;
    }

    $query = "DROP TABLE way_money";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "CREATE TABLE way_money(
            ID INTEGER,
        moneys VARCHAR(10))";
	$stmt = $pdo->prepare($query);
	$stmt -> execute();
    $num = 1;//IDの振り直し

    for($i=0;$i<$num100;$i++){
        $way2=$way1[$i];

        if($ID[$i]!=$ID1){
            $query = "INSERT INTO way_money(ID,moneys) VALUES($num,'$way2')";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();	        
            $num++;
        }
    }
}



?>
</body>
</html>


