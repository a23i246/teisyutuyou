<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
	<style>
    h1{
        color:#ff0000;
        font-weight:  bold;         /* 文字の太さ指定 */
    }    
    body{
        background-color: #ffffe0;
        font-weight:  bold;         /* 文字の太さ指定 */
    }
	td{
        background-color: #cdefff;
		}
    th{
        color: #f0e68c;
        background-color: #0000ff;
        font-weight:  bold;         /* 文字の太さ指定 */
    }
    select{
        background-color: #a9a9a9;/*背景色*/
    }

	nav {
		width: 100%;
		height: 60px;
		background-color: dimgray;
		padding-top: 15px;
		box-sizing: border-box;
	}
	ul {
		display: flex;
	}
	li {
		list-style: none;
	}
	a {
		display: block;
		text-decoration: none;
		color: white;
		margin-right: 35px;
	}
	</style>

</head>
<body>
<nav>
  <ul>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf%20var2.php">今日の家計簿</a></li>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e8%b3%bc%e5%85%a5%e8%a9%b3%e7%b4%b0%20var2.php">購入詳細</a></li>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%90%84%e8%a9%b3%e7%b4%b0%20var3.php">各詳細</a></li>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e6%89%80%e6%8c%81%e9%87%91%e5%85%a5%e5%8a%9b.php">今月の収入</a></li>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e3%81%8a%e9%87%91%e3%81%ae%e4%bd%bf%e3%81%84%e6%96%b9%e5%85%a5%e5%8a%9b.php">お金の使い方</a></li>
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e6%94%af%e6%89%95%e6%96%b9%e6%b3%95%e5%85%a5%e5%8a%9b.php">支払方法</a></li>
	<li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%89%8d%e6%9c%9f%e7%b9%b0%e8%b6%8a.php">前期繰越</a></li>
  </ul>
</nav>

<?php
print "<h1>お金の使い方</h1>\n";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e3%81%8a%e9%87%91%e3%81%ae%e4%bd%bf%e3%81%84%e6%96%b9%e5%85%a5%e5%8a%9b.php\" method=\"post\"> \n";
print "種類: \n";
print "<input type=\"text\" name=\"way\"/><br>\n";
print "削除する商品のID: \n";
print "<input type=\"number\" name=\"name2\"/><br>\n";

print "<input type=\"submit\" value=\"送信\"/> <br/>\n";
print "</form>";

if(!(empty($_POST["name2"]))){
    darete($_POST["name2"]);
}


$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);


if(!(empty($_POST["way"]))){
    
    $ways = $_POST["way"];

    $query = "CREATE TABLE way_use(
        ID INTEGER,
        ways VARCHAR(10))";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "SELECT * FROM way_use";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();
    $num1 = $stmt->rowCount()+1;

    $query = "INSERT INTO way_use(ID,ways) VALUES($num1,'$ways')";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();	

    print"追加しました。<br>";
    
}

$query = "SELECT * FROM way_use";
$stmt = $pdo->prepare($query);
$stmt -> execute();    

while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
    print"{$info['ID']}:{$info['ways']}<br>\n";
}



function darete($ID1){

    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $way1 = [];
    $ID = [];
    $num100 = 0;

    $query = "SELECT * FROM way_use";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $way1[$num100] = $info["ways"];
        $ID[$num100] = $info["ID"];
        $num100++;
    }

    $query = "DROP TABLE way_use";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "CREATE TABLE way_use(
        ID INTEGER,
        ways VARCHAR(10))";
	$stmt = $pdo->prepare($query);
	$stmt -> execute();
    $num = 1;//IDの振り直し

    for($i=0;$i<$num100;$i++){
        $way2=$way1[$i];

        if($ID[$i]!=$ID1){
            $query = "INSERT INTO way_use(ID,ways) VALUES($num,'$way2')";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();	        
            $num++;
        }
    }
}

?>
</body>
</html>
