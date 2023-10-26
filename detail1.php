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

if (isset($_GET["sb_name"]) && $_GET["sb_name"] != "") {
        $sb_name = $_GET["sb_name"];
} else {
        $sb_name = "";
}

$sql1 = "
select distinct Substance.formula, mass, Property.as, Property.phase, Property.melt, Property.boil, Property.density, Property.mass_type 
from Formula natural join Substance natural join Property
where (Substance.sb_name like :sb_name)
";

$sql2="
select distinct Element.symbol, el_name, elementID, number 
from Substance natural join Element natural join Composition 
where (Substance.sb_name like :sb_name)
";

try {
	$stmh1=$pdo->prepare($sql1);
	$stmh1->bindvalue(":sb_name","%{$sb_name}%",PDO::PARAM_STR);
	$stmh1->execute();
} catch(PDOException $Exception) {
	die("DB検索エラー1:".$Exception->getMessage());
}

?>

<h2>

<?php

$result1=$stmh1->fetchAll(PDO::FETCH_ASSOC);
foreach($result1 as $row) {
  print htmlspecialchars($sb_name,ENT_QUOTES);
  print "</h2><font size=4>";
  print "化学式：";
  print htmlspecialchars($row["formula"],ENT_QUOTES);
  print "</font><br><br><font size=4>";
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
  $formula=$row["formula"];
}

?>

</font><br><br>
<center>

<?php

try {
	$stmh2=$pdo->prepare($sql2);
	$stmh2->bindvalue(":sb_name","%{$sb_name}%",PDO::PARAM_STR);
	$stmh2->execute();
	$count=$stmh2->rowCount();
	print "$sb_name($formula)に含まれる原子($count 種類)<br>";
} catch(PDOException $Exception) {
	die("DB検索エラー2:".$Exception->getMessage());
}

?>

<table border='1' cellpadding='2' cellspacing='0'>
<thead><tr bgcolor="#00CCCC"><th>

<?php

print "原子記号";
print "</th><th>";
print "元素名";
print "</th><th>";
print "個数";
print "</th></tr></thead><tbody>";

$result2=$stmh2->fetchAll(PDO::FETCH_ASSOC);

foreach($result2 as $row) {
  print "<tr><td>";
  print htmlspecialchars($row["symbol"],ENT_QUOTES);
  print "</td><td>";
  print "<a href=./detail2.php?el_name=".$row["el_name"].">";
  print htmlspecialchars($row["el_name"],ENT_QUOTES);
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
