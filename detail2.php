<!DOCTYPE html>
<html lang="ja">

<head>
<meta charset="UTF-8">
<title>Database</title>
</head>
<body bgcolor="white">
<div class="header">
<header>
<h1>Search Substance Database</h1>
</header>
</div>


<?php
header('Content-Type:text/html; charset=UTF-8');

require('login1.php');

$sin=0;

if (isset($_GET["el_name"]) && $_GET["el_name"] != "") {
        $el_name = $_GET["el_name"];
} else {
        $el_name = "";
}

$sql="
select distinct Composition.formula, sb_name
from Substance natural join Element natural join Composition 
where (el_name like :el_name) 
and (Composition.formula=Composition.symbol) 
";

try {
	$stmh=$pdo->prepare($sql);
	$stmh->bindvalue(":el_name","%{$el_name}%",PDO::PARAM_STR);
	$stmh->execute();
} catch(PDOException $Exception) {
	die("DB検索エラー:".$Exception->getMessage());
}

$result=$stmh->fetchAll(PDO::FETCH_ASSOC);
foreach($result as $row) {
  if($el_name==$row["sb_name"]){
    $sin=1;
  } else {
    $sin=0;
  }
}

$sql1 = "select distinct";

if($sin==1){
  $sql1 = "$sql1 Substance.formula, mass, Property.as, Property.phase, Property.melt, Property.boil, Property.density, Property.mass_type,";
}

$sql1 = "$sql1 Element.symbol, Element.elementID, Element.weight, Element.group, Element.period 
from Formula natural join Substance natural join Property natural join Element 
where (el_name like :el_name)
";

$sql2="
select distinct Substance.formula, Element.symbol, sb_name, el_name, number 
from Substance natural join Element natural join Composition 
where (el_name like :el_name) 
";

try {
	$stmh1=$pdo->prepare($sql1);
	$stmh1->bindvalue(":el_name","%{$el_name}%",PDO::PARAM_STR);
	$stmh1->execute();
} catch(PDOException $Exception) {
	die("DB検索エラー1:".$Exception->getMessage());
}

?>

<h2>

<?php

$result1=$stmh1->fetchAll(PDO::FETCH_ASSOC);
foreach($result1 as $row) {
  print htmlspecialchars($el_name,ENT_QUOTES);
  print "</h2><font size=4>";
  print "記号：";
  print htmlspecialchars($row["symbol"],ENT_QUOTES);
  print "</font><br><br><font size=4>";
  if($sin==1){
    print htmlspecialchars($row["mass_type"],ENT_QUOTES);
    print "：";
    print htmlspecialchars($row["mass"],ENT_QUOTES);
    print "</font><br><br><font size=4>";
    print "水溶液：";
    print htmlspecialchars($row["as"],ENT_QUOTES);
    print "</font><br><br><font size=4>";
    print "相：";
    print htmlspecialchars($row["phase"],ENT_QUOTES);
    print "</font><br><br><font size=4>";
    print "融点：";
    print htmlspecialchars($row["melt"],ENT_QUOTES);
    print "℃";
    print "</font><br><br><font size=4>";
    print "沸点：";
    print htmlspecialchars($row["boil"],ENT_QUOTES);
    print "℃";
    print "</font><br><br><font size=4>";
    print "密度：";
    print htmlspecialchars($row["density"],ENT_QUOTES);
    print "g/cm^3";
    print "</font><br><br><br><font size=4>";
  }
  print "原子番号：";
  print htmlspecialchars($row["elementID"],ENT_QUOTES);
  print "</font><br><br><font size=4>";
  print "原子量：";
  print htmlspecialchars($row["weight"],ENT_QUOTES);
  print "</font><br><br><font size=4>";
  print "族：";
  print htmlspecialchars($row["group"],ENT_QUOTES);
  print "　周期：";
  print htmlspecialchars($row["period"],ENT_QUOTES);
  $symbol=$row["symbol"];
  break;
}

?>

</font><br><br>
<center>

<?php

try {
	$stmh2=$pdo->prepare($sql2);
	$stmh2->bindvalue(":el_name","%{$el_name}%",PDO::PARAM_STR);
	$stmh2->execute();
	$count=$stmh2->rowCount();
	print "$el_name 原子($symbol)を含む物質($count 種類)<br>";
} catch(PDOException $Exception) {
	die("DB検索エラー2:".$Exception->getMessage());
}

?>

<table border='1' cellpadding='2' cellspacing='0'>
<thead><tr bgcolor="#00CCCC"><th>

<?php

print "化学式";
print "</th><th>";
print "物質名";
print "</th><th>";
print "個数";
print "</th></tr></thead><tbody>";

$result2=$stmh2->fetchAll(PDO::FETCH_ASSOC);

foreach($result2 as $row) {
  print "<tr><td>";
  print htmlspecialchars($row["formula"],ENT_QUOTES);
  print "</td><td>";
  if(strcmp($row["formula"],$row["symbol"])==0 && strcmp($row["sb_name"],$row["el_name"])==0){
    print "<a href=./detail2.php?el_name=".$row["el_name"].">";
  } else {
    print "<a href=./detail1.php?sb_name=".$row["sb_name"].">";
  }
  print htmlspecialchars($row["sb_name"],ENT_QUOTES);
  print "</a>";
  print "</td><td>";
  print htmlspecialchars($row["number"],ENT_QUOTES);
  print "</td></tr>\n";
}

?>

</tbody></table>
<br><a href=./search_form1.html>TOPに戻る</a>
</center>
</body>
</html>
