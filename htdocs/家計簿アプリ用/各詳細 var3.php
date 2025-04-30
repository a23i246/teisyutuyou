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
    <li><a href="http://localhost/%e5%ae%b6%e8%a8%88%e7%b0%bf%e3%82%a2%e3%83%97%e3%83%aa%e7%94%a8/%e9%87%91%e9%a1%8d%e6%8c%af%e6%9b%bf.php">金額振替</a></li>
  </ul>
</nav>

<?php
print "<h1>各詳細 var3</h1>\n";
/*記号説明
$state 支払先行列
$states 支払方法の行数
$way 支払方法行列
$ways 支払先行列の行数
$hiniti 日付ごとの行列
*/

$dbs = 'mysql:dbname=kakeibo;host=localhost';
$user = 'root';
$password="";
$pdo = new PDO($dbs, $user, $password);

$check = checker();//必要なテーブルが存在しているか確認するプログラム

if($check == true){
    //支払方法行列作成
    $query = "SELECT * FROM way_use";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    
    $state = [];
    $nums = 0;
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $state[$nums++] = $info['ways'];
    }    
    $state[$nums++] = "合計";
    $states = count($state);

    //支払先行列作成
    $query = "SELECT * FROM way_money";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();    
    $nums2 = 0;
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        $way[$nums2] = $info['moneys'];
        $nums2++;
    }
    $way[$nums2++] = "合計";
    $ways = count($way);

    $first = [];//初期所持金
    new_total($first,$ways);
    first_count($first,$way,$ways);

    //今月の収入を入れた合計
    $m_total = $first;
    $income = sum_count($m_total,$way,$ways);

    //支払方法と支払先ごとの支出合計
    $state_sum = [];
    $way_sum = [];
    new_total($state_sum,$states);
    new_total($way_sum,$ways);
    fee_sum($state_sum,$way_sum,$state,$states,$way,$ways);

    //残額
    $zannkinn = [];
    new_total($zannkinn,$ways);
    least($zannkinn,$m_total,$way_sum,$ways);

    //日にちごとの統計を取る用の行列
    $hiniti = [];
    $hitotuki = 31;
    $day = [];
    for($i = 0;$i < $hitotuki;$i++){
        $day[$i] = $i+1;
    }
    new_total($hiniti,$hitotuki);
    toukei($hiniti);

    print"<table>";
    print"<tr>";
    table_create($first,$way,$ways,"前期繰越");//前期繰越金
    table_create($m_total,$way,$ways,"今月合計");//収入と振替をする
    table_create($state_sum,$state,$states,"支払先別費用");//支払先別費用
    table_create($way_sum,$way,$ways,"支払方法別費用");//支払方法別費用
    table_create($zannkinn,$way,$ways,"残金");//支払方法別費用
    table_create($hiniti,$day,$hitotuki,"日計");//支払方法別費用
    print"</tr>";
    print"</table><br>";

    print"<h2>今月の収入は{$income}</h2><br>";    
}

function toukei(&$hiniti){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $tableName = "ss2";

    $sql = "SHOW TABLES LIKE :tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tableName', $tableName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // テーブルが存在します
        $query = "SELECT * FROM ss2";
        $stmt = $pdo->prepare($query);
        $stmt -> execute();
    
        while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
            $hiniti[$info['niti']-1] += $info['Yen1'];
        }    
    }
}

function least(&$zannkinn,$m_total,$way_sum,$ways){
    for($i = 0;$i < $ways;$i++){
        $zannkinn[$i] = $m_total[$i] - $way_sum[$i];
    }
}

function fee_sum(&$state_sum,&$way_sum,$state,$states,$way,$ways){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $tableName = "ss2";

    $sql = "SHOW TABLES LIKE :tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tableName', $tableName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // テーブルが存在します
        $query = "SELECT * FROM ss2";
        $stmt = $pdo->prepare($query);
        $stmt -> execute();
    
        while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
            for($i = 0;$i < $states;$i++){
                if($info['product']==$state[$i]){
                    $state_sum[$i] += $info['Yen1'];
                    $state_sum[$states-1] += $info['Yen1'];
                }
            }
            for($i = 0;$i < $ways;$i++){
                if($info['ways']==$way[$i]){
                    $way_sum[$i] += $info['Yen1'];
                    $way_sum[$ways-1] += $info['Yen1'];
                }
            }
        }    
    }
}

function sum_count(&$m_total,$way,$ways){
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $count = 0;

    $tableName = 'saihu';

    $sql = "SHOW TABLES LIKE :tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tableName', $tableName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {

        $query = "SELECT * FROM saihu";
        $stmt = $pdo->prepare($query);
        $stmt -> execute();
    
        while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
            for($i = 0;$i < $ways;$i++){
                if($info['ways']==$way[$i]){
                    $m_total[$i] += $info['Yen1'];
                    $m_total[$ways-1] += $info['Yen1'];
                    $count += $info['Yen1'];
                }
            }
        }    
    }

    $tableName = 'hurikae';

    $sql = "SHOW TABLES LIKE :tableName";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':tableName', $tableName);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        // テーブルが存在します
        $query = "SELECT * FROM hurikae";
        $stmt = $pdo->prepare($query);
        $stmt -> execute();
    
        while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
            for($i = 0;$i < $ways;$i++){
                if($info['Names1']==$way[$i]){
                    $m_total[$i] -= $info['moneys'];
                }
            }
            for($i = 0;$i < $ways;$i++){
                if($info['Names2']==$way[$i]){
                    $m_total[$i] += $info['moneys'];
                }
            }
        }
    
    } 
    return $count;
}

function first_count(&$first,$way,$ways){//初期金額の合計を記録
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $query = "SELECT * FROM first_saihu";
    $stmt = $pdo->prepare($query);
    $stmt -> execute();
    
    while($info = $stmt -> fetch(PDO::FETCH_ASSOC)){
        for($i = 0;$i < $ways;$i++){
            if($info['ways']==$way[$i]){
                $first[$i] += $info['Yen1'];
                $first[$ways-1] += $info['Yen1'];
            }
        }
    }

}

function table_create($total,$seed,$num,$name){//テーブルを表示する
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


function checker(){//必要なテーブルが存在しているか確認するプログラム
    $dbs = 'mysql:dbname=kakeibo;host=localhost';
    $user = 'root';
    $password="";
    $pdo = new PDO($dbs, $user, $password);

    $ctable[0] = "way_use";
    $ctable[1] = "way_money";
    $ctable[2] = "first_saihu";
    $count_t = count($ctable);
    
    $check = true;
    
    for($a = 0;$a < $count_t;$a++){
        $tableName = $ctable[$a];
    
        $sql = "SHOW TABLES LIKE :tableName";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':tableName', $tableName);
        $stmt->execute();
    
        if ($stmt->rowCount() > 0) {
            // テーブルが存在します
        } else {
            // テーブルが存在しません
            print"{$tableName}テーブルが存在しません。詳しくは説明書へ<br>";
            $check = false;
        }
    }
    return $check;
}

function new_total(&$total,$num){//初期化
    for($i=0;$i<$num;$i++){
        $total[$i] = 0;
    }
}


?>
</body>
</html>


