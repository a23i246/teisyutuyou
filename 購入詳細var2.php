<html>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<head><title>mysql TEST</title></head>
<body>
<?php

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

print "<h1>購入表 var2</h1>\n";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e8%b3%bc%e5%85%a5%e8%a9%b3%e7%b4%b0%20var2.php\" method=\"post\"> \n";
    print "種類別での表示: \n";
    print "<select size = \"1\" name=\"state\">"; //セレクトボックス作成
	foreach($state as $value){ //文字列を挿入
		print "<option value = {$value}>{$value}</option> \n";
	}
	print "</select><br>";

    print "削除する商品のID: \n";
	print "<input type=\"text\" name=\"name2\"/><br>\n";
    print "<input type = \"submit\" value=\"送信\"/><br>\n";
    print"<button type=\"submit\" name=\"back\">戻る</button><br>";
    print "</form>";

    if(isset($_POST["back"])){
        header("Location:http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e4%bb%8a%e6%97%a5%e3%81%ae%e5%ae%b6%e8%a8%88%e7%b0%bf%20var2.php");
        exit();
    }    

    if(!(empty($_POST["name2"]))){
        darete($_POST["name2"]);
    }


    $query = "SELECT * FROM ss2 ORDER BY niti ASC";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    if(!(empty($_POST["state"]))&& $_POST["state"]!="なし"){ //種類別のソートをするif文
    print"<table border=\"1\" style=\"border-collapse: collapse\">\n";
    print"<tr><th>日付</th><th>品目</th><th>金額</th><th>購入店舗</th><th>支払方法</th><th>購入種類</th><th>ID</th></tr>\n";	
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        if($info["product"]==$_POST["state"]){
            print (" <tr><td>{$info['niti']}</td><td>{$info['Names1']}</td><td>{$info['Yen1']}</td><td>{$info['shops']}</td><td>{$info['ways']}</td><td>{$info['product']}</td><td>{$info["ID"]}</td></tr>\n");
        }
    }		
    print"</table>\n";
    }else{
    print"<table border=\"1\" style=\"border-collapse: collapse\">\n";
    print"<tr><th>日付</th><th>品目</th><th>金額</th><th>購入店舗</th><th>支払方法</th><th>購入種類</th><th>ID</th></tr>\n";	
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        print (" <tr><td>{$info['niti']}</td><td>{$info['Names1']}</td><td>{$info['Yen1']}</td><td>{$info['shops']}</td><td>{$info['ways']}</td><td>{$info['product']}</td><td>{$info["ID"]}</td></tr>\n");
    }		
    print"</table>\n";
    }



function darete($ID1){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $day = [];
    $product = [];
    $way1 = [];
    $name = [];
    $yen = [];
    $Id = [];
    $num100 = 0;

    $query = "SELECT * FROM ss2";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $day[$num100] = $info["niti"];
        $product[$num100] = $info["product"];
        $way1[$num100] = $info["ways"];
        $name[$num100] = $info["Names1"];
        $yen[$num100] = $info["Yen1"]; 
        $ID[$num100] = $info["ID"];
        $shops2[$num100]=$info["shops"];
        $num100++;
    }

    $query = "DROP TABLE ss2";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

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
    $num = 1;//IDの振り直し

    for($i=0;$i<$num100;$i++){
        $day1=$day[$i];
        $product1=$product[$i];
        $way2=$way1[$i];
        $name1=$name[$i];
        $yen1=$yen[$i];
        $shops=$shops2[$i];

        if($ID[$i]!=$ID1){
            $query = "INSERT INTO ss2(niti,product,ways,shops,Names1,Yen1,ID) VALUES($day1,'$product1','$way2','$shops','$name1',$yen1,$num)";
            $stmt = $pdo->prepare($query);
            $stmt -> execute();	        
            $num++;
        }
    }

}

?>
</body>
</html>
