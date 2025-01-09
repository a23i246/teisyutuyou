<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title></head>
<body>
<?php
print "<h1>今日の家計簿 var2</h1>\n";
$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);

$query = "SELECT * FROM way_use";
$stmt = $pdo->prepare($query);
$stmt -> execute();    
$state[0] = "なし";
$nums = 1;
while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
	$state[$nums++] = $info['ways'];
}
$query = "SELECT * FROM way_money";
$stmt = $pdo->prepare($query);
$stmt -> execute();    
$way[0] = "なし";
$nums1 = 1;
while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
	$way[$nums1++] = $info['moneys'];
}

/*元々のテスト用　現在はSQL管理
$state[1] = "食費";
$state[2] = "ゲーセン";
$state[3] = "娯楽";
$state[4] = "グッツ";
$state[5] = "本";
$state[6] = "サブスク";
$state[7] = "貯金";
$state[8] = "チャージ";

$way[0] = "なし";
$way[1] = "現金";
$way[2] = "PayPay";
$way[3] = "クレカ";
$way[4] = "その他";
*/

$menyu[0] = "なし";
$menyu[1] = "購入詳細";
$menyu[2] = "各詳細";
$menyu[3] = "所持金入力";
$menyu[4] = "お金の使い方";
$menyu[5] = "支払方法";
$menyu[6] = "前期繰越";


print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf%20var2.php\" method=\"post\"> \n";
	print "日にち：\n";
	print "<input type=\"number\" name=\"day\"/><br>\n";
	print "金額の種類：\n";
	print "<select size = \"1\" name=\"state\">"; //セレクトボックス作成
	foreach($state as $value){ //文字列を挿入
		print "<option value = {$value}>{$value}</option> \n";
	}
	print "</select><br>";
	print"購入方法：";
	print "<select size = \"1\" name=\"way\">"; //セレクトボックス作成
	foreach($way as $value){ //文字列を挿入
		print "<option value = {$value}>{$value}</option> \n";
	}
	print "</select><br>";
	print "購入店: \n";
	print "<input type=\"text\" name=\"shop\"/><br>\n";
	print "購入品: \n";
	print "<input type=\"text\" name=\"name1\"/><br>\n";
	print "金額: \n";
	print "<input type=\"number\" name=\"yen1\"/><br>\n";
	print "<input type = \"submit\" value=\"送信\"/><br>\n";
	print"詳細：";
	print "<select size = \"1\" name=\"syousai\">"; //セレクトボックス作成
	foreach($menyu as $value){ //文字列を挿入
		print "<option value = {$value}>{$value}</option> \n";
	}
	print "</select><br>";
	print "<input type = \"submit\" value=\"詳細ページへ\"/><br>\n";
	print "</form>";

	if(!(empty($_POST["state"])||empty($_POST["way"])||empty($_POST["name1"])||empty($_POST["yen1"]))){
		write();	
	}

	if(isset($_POST["syousai"])){
		$value = $_POST["syousai"];
		if($value == "購入詳細"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e8%b3%bc%e5%85%a5%e8%a9%b3%e7%b4%b0%20var2.php");
			exit();
		}else if($value == "各詳細"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%90%84%e8%a9%b3%e7%b4%b0%20var3.php");
			exit();
		}else if($value == "所持金入力"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e6%89%80%e6%8c%81%e9%87%91%e5%85%a5%e5%8a%9b.php");
			exit();
		}else if($value == "お金の使い方"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e3%81%8a%e9%87%91%e3%81%ae%e4%bd%bf%e3%81%84%e6%96%b9%e5%85%a5%e5%8a%9b.php");
			exit();
		}else if($value == "支払方法"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e6%94%af%e6%89%95%e6%96%b9%e6%b3%95%e5%85%a5%e5%8a%9b.php");
			exit();
		}else if($value == "前期繰越"){
			header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%89%8d%e6%9c%9f%e7%b9%b0%e8%b6%8a.php");
			exit();
		}

	}
	


function write(){
	if(empty($_POST["day"])){
		$day = (int)date('d');//今日の日付
	}else{
		$day = $_POST["day"];
	}
	$product = $_POST["state"];
	$way1 = $_POST["way"];
	$shops = $_POST["shop"];
	$name = $_POST["name1"];
	$yen = $_POST["yen1"];


	$dbs = 'mysql:dbname=kakeibo;host=localhost';
	$user = 'root';
	$password="";
	$pdo = new PDO($dbs, $user, $password);
	//ss2 関わり　各詳細　購入詳細
	$query = "CREATE TABLE ss2(  
		niti INTEGER(10),
		product VARCHAR(10),
		ways VARCHAR(10),
		shops VARCHAR(30),
		Names1 VARCHAR(30),
		Yen1 INTEGER,
		ID INTEGER)";
	$stmt = $pdo->prepare($query);
	$stmt -> execute();

    $query = "SELECT * FROM ss2";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();
    $num1 = $stmt->rowCount()+1;

	$query = "INSERT INTO ss2(niti,product,ways,shops,Names1,Yen1,ID) VALUES($day,'$product','$way1','$shops','$name',$yen,$num1)";
	$stmt = $pdo->prepare($query);
	$stmt -> execute();	
	print"追加しました。";
}



	
?>
</body>
</html>
