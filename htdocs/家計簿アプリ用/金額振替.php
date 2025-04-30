<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head>
    <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

    <title>購入詳細</title>
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
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e9%87%91%e9%a1%8d%e6%8c%af%e6%9b%bf.php">金額振替</a></li>
  </ul>
</nav>

<?php
print "<h1>金額振替</h1>\n";
$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);

$tableName = "way_money";

$sql = "SHOW TABLES LIKE :tableName";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':tableName', $tableName);
$stmt->execute();

if ($stmt->rowCount() > 0) {
    // テーブルが存在します
    hurikae();
} else {
    // テーブルが存在しません
    print"way_moneyが存在しません。詳しくは説明書へ";
}

function hurikae(){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $query = "SELECT * FROM way_money";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    
    $state[0] = "なし";
    $nums = 1;
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $state[$nums++] = $info['moneys'];
    }    

    print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e9%87%91%e9%a1%8d%e6%8c%af%e6%9b%bf.php\" method=\"post\"> \n";
        print"日にち:\n";
        print "<input type=\"number\" name=\"day\"/><br>\n";
        print "振替前: \n";
        print "<select size = \"1\" name=\"state1\">"; //セレクトボックス作成
        foreach($state as $value){ //文字列を挿入
            print "<option value = {$value}>{$value}</option> \n";
        }
        print "</select><br>";
        print "振替後: \n";
        print "<select size = \"1\" name=\"state2\">"; //セレクトボックス作成
        foreach($state as $value){ //文字列を挿入
            print "<option value = {$value}>{$value}</option> \n";
        }
        print "</select><br>";
        print"金額:\n";
        print "<input type=\"number\" name=\"money\"/><br><br>\n";
        print "削除する支払方法のID: \n";
        print "<input type=\"number\" name=\"ID\"/><br>\n";
        print "<input type = \"submit\" value=\"送信\"/><br>\n";
		print "</form>";

        if(!(empty($_POST["ID"]))){
            darete($_POST["ID"]);
        }        

        if((!(empty($_POST["state1"])||empty($_POST["state2"])||empty($_POST["day"])||empty($_POST["money"])))){
            $day=$_POST["day"];
            $name1=$_POST["state1"];
            $name2=$_POST["state2"];
            $money=$_POST["money"];

            $tableName = "hurikae";

            $sql = "SHOW TABLES LIKE :tableName";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':tableName', $tableName);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                // テーブルが存在します
            } else {
                // テーブルが存在しません
                $query = "CREATE TABLE hurikae(  
                    niti INTEGER,
                    Names1 VARCHAR(30),
                    Names2 VARCHAR(30),
                    moneys INTEGER,
                    ID INTEGER)";
                $stmt = $pdo->prepare($query);
                $stmt -> execute();        
            }

            $query = "SELECT * FROM hurikae";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();

            $num1 = $stmt->rowCount()+1;

            $query = "INSERT INTO hurikae(niti,Names1,Names2,moneys,ID) VALUES($day,'$name1','$name2',$money,$num1)";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();	
            print"追加しました。";        

            $query = "SELECT * FROM hurikae";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();    

            print"<table border=\"1\" style=\"border-collapse: collapse\">\n";
            print"<tr><th>日付</th><th>振替前</th><th>振替後</th><th>金額</th><th>ID</th></tr>\n";	
            while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
                print (" <tr><td>{$info['niti']}</td><td>{$info['Names1']}</td><td>{$info['Names2']}</td><td>{$info['moneys']}</td><td>{$info['ID']}</td></tr>\n");
            }
            print"</table>\n";

        }
        

    

}


function darete($ID1){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $day = [];
    $name1 = [];
    $name2 = [];
    $yen = [];
    $ID = [];
    $num100 = 0;

    $query = "SELECT * FROM hurikae";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $day[$num100] = $info["niti"];
        $name1[$num100] = $info["Names1"];
        $name2[$num100] = $info["Names2"];
        $yen[$num100] = $info["moneys"]; 
        $ID[$num100] = $info["ID"];
        $num100++;
    }

    $query = "DROP TABLE hurikae";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    $query = "CREATE TABLE hurikae(  
        niti INTEGER,
        Names1 VARCHAR(30),
        Names2 VARCHAR(30),
        moneys INTEGER,
        ID INTEGER)";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();      

    $num = 1;//IDの振り直し

    for($i=0;$i<$num100;$i++){
        $days = $day[$i];
        $names1 = $name1[$i];
        $names2 = $name2[$i];
        $moneys = $yen[$i];

        if($ID[$i]!=$ID1){
            $query = "INSERT INTO hurikae(niti,Names1,Names2,moneys,ID) VALUES($days,'$names1','$names2',$moneys,$num)";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();	
            $num++;
        }
    }

    $query = "SELECT * FROM hurikae";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    print"<table border=\"1\" style=\"border-collapse: collapse\">\n";
    print"<tr><th>日付</th><th>振替前</th><th>振替後</th><th>金額</th><th>ID</th></tr>\n";	
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        print (" <tr><td>{$info['niti']}</td><td>{$info['Names1']}</td><td>{$info['Names2']}</td><td>{$info['moneys']}</td><td>{$info['ID']}</td></tr>\n");
    }
    print"</table>\n";


}

?>
</body>
</html>


