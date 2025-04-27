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
print "<h1>各詳細 var3</h1>\n";
print "<form action=\"http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e5%90%84%e8%a9%b3%e7%b4%b0%20var3.php\" method=\"post\"> \n";
	print "日付: \n";
	print "<input type=\"number\" name=\"day1\"/>～";
	print "<input type=\"number\" name=\"day2\"/><br>\n";
    print "<input type = \"submit\" value=\"送信\"/><br>\n";
print "</form>";

$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);

$query = "SELECT * FROM way_use";
$stmt = $pdo->prepare($query);
$stmt -> execute();    
$state[0] = "なし";
$nums = 0;
$tyaji;
$tyokinn;
$hikidasi;

$toukei = [];
$hitotuki = 31;
new_total($toukei,$hitotuki);
$hiniti = [];
for($i = 0;$i < 31;$i++){
    $t = $i + 1;
    $hiniti[$i] = $t;
}

while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
	$state[$nums++] = $info['ways'];
    if($info["ways"]=="チャージ"){
        $tyaji = $info["ID"]-1;
    }else if($info["ways"]=="貯金"){
        $tyokinn = $info["ID"]-1;
    }else if($info["ways"]=="引き出し"){
        $hikidasi = $info["ID"]-1;//調整用
    }
}
$state[$nums++] = "合計";

$query = "SELECT * FROM way_money";
$stmt = $pdo->prepare($query);
$stmt -> execute();    
$nums2 = 0;
while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
	$way[$nums2] = $info['moneys'];
    if($info['moneys'] == "現金"){
        $gennkinn = $nums2;
    }else if($info['moneys'] == "PayPay"){
        $payPay = $nums2;
    }else if($info['moneys'] == "クレカ"){
        $kureka = $nums2;
    }else if($info['moneys'] == "貯金"){
        $tyokin_way = $nums2;
    }
    $nums2++;
}
$way[$nums2++] = "合計";

$seeds = count($state);
$ways = count($way);

$total1 = [];
$total2 = [];
$total3 = [];
$zankin = [];
new_total($total1,$seeds);//初期化
new_total($total2,$ways);
new_total($total3,$ways);
new_total($zankin,$ways);
$zennkikurikosi = 0;

if(!(empty($_POST["day1"])||empty($_POST["day2"]))){//日付入力あり
    $day1 = $_POST["day1"];
    $day2 = $_POST["day2"];
    total_count2($total1,$state,$seeds,$total2,$way,$ways,$day1,$day2,$total3,$payPay,$tyokin_way,$hikidasi);
    saihu_count2($total3,$way,$ways,$day1,$day2);
    zankin_math($zankin,$total3,$total2,$total1,$way,$ways,$tyokinn,$tyaji);    
}else{//日付入力なし
    total_count($total1,$state,$seeds,$total2,$way,$ways,$toukei);
    saihu_count($total3,$way,$ways,$zennkikurikosi);
    zankin_math($zankin,$total3,$total2,$total1,$way,$ways,$tyokinn,$tyaji,$hikidasi);    
}

$tota2_count = count($total2);
$tota1_count = count($total1);
$total2[$kureka] -= $total1[$hikidasi];//支払方法　クレカ　マイナス
$total2[$tota2_count-1] -= $total1[$hikidasi];//支払方法　合計　マイナス
$total1[$tota1_count-1] -= $total1[$hikidasi];//種類別　合計　マイナス

$total2[$gennkinn] -= $total1[$tyaji];//支払方法　現金　マイナス
$total2[$tota2_count-1] -= $total1[$tyaji];//支払方法　合計　マイナス
$total1[$tota1_count-1] -= $total1[$tyaji];//種類別　合計　マイナス

$total2[$gennkinn] -= $total1[$tyokinn];//支払方法　貯金　マイナス
$total2[$tota2_count-1] -= $total1[$tyokinn];//支払方法　合計　マイナス
$total1[$tota1_count-1] -= $total1[$tyokinn];//種類別　合計　マイナス 

print"<table>";
print"<tr>";
table_create($total3,$way,$ways,"現在金額");//syojikinn
table_create($total1,$state,$seeds,"種類別金額");//syuruibetu
table_create($total2,$way,$ways,"支払方法");//siharaibetu
table_create($zankin,$way,$ways,"残金");//zannkinn
table_create($toukei,$hiniti,$hitotuki,"日計");
print"</tr>";
print"</table><br>";

$income = count($total3);
$income = $total3[$income-1]-$zennkikurikosi;
print"<h2>今月の収入は{$income}</h2><br>";

function new_total(&$total,$num){
    for($i=0;$i<$num;$i++){
        $total[$i] = 0;
    }
}

function total_count(&$total1,$state,$seed,&$total2,$way,$ways,&$toukei){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $query = "SELECT * FROM ss2";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        if(!($info['product']=="チャージ"||$info['product']=="貯金"||$info['product']=="引き出し")){//要改善
            $index = $info['niti'] - 1;
            $toukei[$index] += $info['Yen1'];
        }

        for($i=0;$i<$seed;$i++){
            if($info["product"] == $state[$i]){
                $total1[$i] += $info["Yen1"];
                $total1[$seed-1] += $info["Yen1"];
            }
        }
        for($j=0;$j<$ways;$j++){
            if($info["ways"]==$way[$j]){
                $total2[$j] += $info["Yen1"];
                $total2[$ways-1]+=$info["Yen1"];
            }
        }
    }	
}

function total_count2(&$total1,$state,$seed,&$total2,$way,$ways,$day1,$day2,&$total3,$tyaji,$tyokinn,$hikidasi){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);    

    $query = "SELECT * FROM ss2";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        if($day1<=$info["niti"]&&$day2>=$info["niti"]){
            for($i=0;$i<$seed;$i++){
                if($info["product"] == $state[$i]){
                    $total1[$i] += $info["Yen1"];
                    $total1[$seed-1] += $info["Yen1"];
                }
            }
            for($j=0;$j<$ways;$j++){
                if($info["ways"]==$way[$j]){
                    $total2[$j] += $info["Yen1"];
                    $total2[$ways-1]+=$info["Yen1"];
                }
            }    
        }else{
            for($j=0;$j<$ways;$j++){
                if($info["ways"]==$way[$j]){
                    $total3[$j] -= $info["Yen1"];
                    $total3[$ways-1]-=$info["Yen1"];
                }
            }    

            if($info["product"]=="チャージ"){
                $total3[$tyaji]+=$info["Yen1"];
            }else if($info["product"]=="貯金"){
                $total3[$tyokinn]+=$info["Yen1"];
            }else if($info["product"]=="引き出し"){
                $total3[$hikidasi]+=$info["Yen1"];
            }
        }
        
    }	
}


function saihu_count(&$total3,$way,$ways,&$zennkikurikosi){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $query = "SELECT * FROM first_saihu";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        for($i = 0;$i < $ways;$i++){
            if($info['ways'] == $way[$i]){
                $total3[$i] += $info['Yen1'];
                $total3[$ways-1] += $info['Yen1'];
            }
        }
    }

    $zennkikurikosi = $total3[$ways-1];//収入計算用
    table_create2($total3,$way,$ways,"前期繰越");//zennkikurikosikinn

    $query = "SELECT * FROM saihu";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        for($j=0;$j<$ways;$j++){
            if($info["ways"]==$way[$j]){
                $total3[$j] += $info["Yen1"];
                $total3[$ways-1]+=$info["Yen1"];
            }    
        }    
    }
}

function saihu_count2(&$total3,$way,$ways,$day2){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $query = "SELECT * FROM first_saihu";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        for($i = 0;$i < $ways;$i++){
            if($info['ways'] == $way[$i]){
                $total3[$i] += $info['Yen1'];
                $total3[$ways-1] += $info['Yen1'];
            }
        }
    }

    table_create2($total3,$way,$ways,"前期繰越");//syojikinn


    $query = "SELECT * FROM saihu";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();

    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        if($day2>=$info["niti"]){
            for($j=0;$j<$ways;$j++){
                if($info["ways"]==$way[$j]){
                    $total3[$j] += $info["Yen1"];
                    $total3[$ways-1]+=$info["Yen1"];
                }    
            }        
        }
    }
}


function table_create($total,$seed,$num,$name){
    $sd[0] =$name;
    $sd[1] ="金額";
    $sds = count($sd);
    print"<td valign=\"top\">";
    print"<table border=\"3\" align=\"left\" style=\"border-collapse: collapse\">";
    print"<tr>";
    for($j=0;$j<$sds;$j++){
        print"<th>{$sd[$j]}</th>";
    }
    print"</tr>";
    for($j=0;$j<$num;$j++){
        print"<tr><td>{$seed[$j]}</td><td>{$total[$j]}</td></tr>";
    }
    print"</table>\n";
    print"</td>";
}

function table_create2($total,$seed,$num,$name){
    $sd[0] =$name;
    $sd[1] ="金額";
    $sds = count($sd);
    print"<table border=\"3\" align=\"left\" style=\"border-collapse: collapse\">";
    print"<tr>";
    for($j=0;$j<$sds;$j++){
        print"<th>{$sd[$j]}</th>";
    }
    print"</tr>";
    for($j=0;$j<$num;$j++){
        print"<tr><td>{$seed[$j]}</td><td>{$total[$j]}</td></tr>";
    }
    print"</table>\n";
}


function zankin_math(&$zankin,$total3,$total2,$total1,$way,$ways,$tyokinn,$tyaji,$hikidasi){
    for($i=0;$i<$ways-1;$i++){//合計を含めない
        if($way[$i]=="貯金"){
            $zankin[$i] = $total3[$i]-$total2[$i]+$total1[$tyokinn];
            $zankin[$ways-1]+=$zankin[$i];
        }else if($way[$i]=="PayPay"){
            $zankin[$i] = $total3[$i]-$total2[$i]+$total1[$tyaji];
            $zankin[$ways-1]+=$zankin[$i];
        }else if($way[$i]=="現金"){
            $zankin[$i] = $total3[$i]-$total2[$i]+$total1[$hikidasi];
            $zankin[$ways-1]+=$zankin[$i];
        }else{
            $zankin[$i] = $total3[$i]-$total2[$i];  
            $zankin[$ways-1]+=$zankin[$i];
        }
        
    }
}


?>
</body>
</html>


