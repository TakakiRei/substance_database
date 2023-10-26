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

if (isset($_GET["name"]) && $_GET["name"] != "") {
        $name = $_GET["name"];
} else {
        $name = "";
}

if (isset($_GET["sym"]) && $_GET["sym"] != "") {
        $sym = $_GET["sym"];
} else {
        $sym = "";
}

if (isset($_GET["as"]) && $_GET["as"] != "") {
        $as = $_GET["as"];
} else {
        $as = "";
}

if (isset($_GET["phase"]) && $_GET["phase"] != "") {
        $phase = $_GET["phase"];
} else {
        $phase = "";
}

if (isset($_GET["melt_min"]) && $_GET["melt_min"] != "") {
        $melt_min = $_GET["melt_min"];
        $melt_min1 = $_GET["melt_min"];
} else {
        $melt_min1 = "";
}

if (isset($_GET["melt_max"]) && $_GET["melt_max"] != "") {
        $melt_max = $_GET["melt_max"];
        $melt_max1 = $_GET["melt_max"];
} else {
        $melt_max1 = "";
}

if (isset($_GET["boil_min"]) && $_GET["boil_min"] != "") {
        $boil_min = $_GET["boil_min"];
        $boil_min1 = $_GET["boil_min"];
} else {
        $boil_min1 = "";
}

if (isset($_GET["boil_max"]) && $_GET["boil_max"] != "") {
        $boil_max = $_GET["boil_max"];
        $boil_max1 = $_GET["boil_max"];
} else {
        $boil_max1 = "";
}

if (isset($_GET["dens_min"]) && $_GET["dens_min"] != "") {
        $dens_min = $_GET["dens_min"];
        $dens_min1 = $_GET["dens_min"];
} else {
        $dens_min1 = "";
}

if (isset($_GET["dens_max"]) && $_GET["dens_max"] != "") {
        $dens_max = $_GET["dens_max"];
        $dens_max1 = $_GET["dens_max"];
} else {
        $dens_max1 = "";
}

if (isset($_GET["mass_min"]) && $_GET["mass_min"] != "") {
        $mass_min = $_GET["mass_min"];
        $mass_min1 = $_GET["mass_min"];
} else {
        $mass_min1 = "";
}

if (isset($_GET["mass_max"]) && $_GET["mass_max"] != "") {
        $mass_max = $_GET["mass_max"];
        $mass_max1 = $_GET["mass_max"];
} else {
        $mass_max1 = "";
}

if (isset($_GET["mass_type"]) && $_GET["mass_type"] != "") {
        $mass_type = $_GET["mass_type"];
} else {
        $mass_type = "";
}

if (isset($_GET["sort"]) && $_GET["sort"] != "") {
        $sort = $_GET["sort"];
        $sort1 = $_GET["sort"];
} else {
        $sort1 = "";
}
 
if (isset($_GET["order"]) && $_GET["order"] != "") {
        $order = $_GET["order"];
        $order1 = $_GET["order"];
} else {
        $order1 = "";
}

?>

<form action="./search_get1.php" method="get"> 
<table>
	
<tr><td valign="top">物質名：　
      <input type="text" name="name" size="16" style="width:90px;" value=<?php print "'$name'";?> placeholder='物質名'>
    </td>
</tr>
<tr><td valign="top">元素：　　
      <input type="text" name="sym" size="8" value=<?php print "'$sym'";?> placeholder='元素記号/元素名'>
    </td>
</tr>
<tr><td valign="top">水溶液：　
      <select name="as">
         <option value="">全て
	 <option value="酸性" <?php if($as=="酸性"){print "selected";}?>>酸性
	 <option value="中性" <?php if($as=="中性"){print "selected";}?>>中性
	 <option value="塩基性" <?php if($as=="塩基性"){print "selected";}?>>塩基性
      </select>
    </td>
</tr>
<tr><td valign="top">相：　　　
      <select name="phase">
         <option value="">全て
	 <option value="気体" <?php if($phase=="気体"){print "selected";}?>>気体
	 <option value="液体" <?php if($phase=="液体"){print "selected";}?>>液体
	 <option value="固体" <?php if($phase=="固体"){print "selected";}?>>固体
      </select>
    </td>
</tr>
<tr><td valign="top">
    融点：　　最小値・・・
      <input type="text" name="melt_min" size="4" value=<?php print "'$melt_min1'";?>>
    ℃
    </td>
</tr>
<tr><td valign="top">
    　　　　　最大値・・・
      <input type="text" name="melt_max" size="4" value=<?php print "'$melt_max1'";?>>
    ℃
    </td>
</tr>
<tr><td valign="top">
    沸点：　　最小値・・・
      <input type="text" name="boil_min" size="4" value=<?php print "'$boil_min1'";?>>
    
    ℃</td>
</tr>
<tr><td valign="top">
    　　　　　最大値・・・
      <input type="text" name="boil_max" size="4" value=<?php print "'$boil_max1'";?>>
    ℃
    </td>
</tr>
<tr><td valign="top">
    密度：　　最小値・・・
      <input type="text" name="dens_min" size="4" value=<?php print "'$dens_min1'";?>>
    g/cm^3
    </td>
</tr>
<tr><td valign="top">
    　　　　　最大値・・・
      <input type="text" name="dens_max" size="4" value=<?php print "'$dens_max1'";?>>
    g/cm^3
    </td>
</tr>
<tr><td valign="top">
    モル質量：最小値・・・
      <input type="text" name="mass_min" size="4" value=<?php print "'$mass_min1'";?>>
    </td>
</tr>
<tr><td valign="top">
    　　　　　最大値・・・
      <input type="text" name="mass_max" size="4" value=<?php print "'$mass_max1'";?>>
    </td>
</tr>
<tr><td valign="top">
    　　　　　種類　・・・
      <select name="mass_type">
        <option value="">全て
        <option value="分子量" <?php if($mass_type=="分子量"){print "selected";}?>>分子量
        <option value="式量" <?php if($mass_type=="式量"){print "selected";}?>>式量
      </select>
    </td>
</tr>
<tr><td valign="top">並べ替え:
      <select name="sort">
        <option value="">なし
        <option value="melt" <?php if($sort1=="melt"){print "selected";}?>>融点
        <option value="boil" <?php if($sort1=="boil"){print "selected";}?>>沸点
        <option value="dens" <?php if($sort1=="dens"){print "selected";}?>>密度
        <option value="mass" <?php if($sort1=="mass"){print "selected";}?>>モル質量
      </select>
    　順番:
      <select name="order">
	 <option value="asc" <?php if($order1=="asc"){print "selected";}?>>小さい順
	 <option value="desc" <?php if($order1=="desc"){print "selected";}?>>大きい順
      </select>
    </td>
</tr>
</table>
<input type="submit" value="検索">
</form>
<br><br>

<?php

$sql1 = "
select distinct Substance.formula, Substance.sb_name, mass, Property.as, Property.phase, Property.melt, Property.boil, Property.density, Element.symbol, el_name, weight, number, x1.sb_number, Property.mass_type, elementID 
from Formula natural join Substance natural join Property natural join Element natural join Composition, (
  select formula, COUNT(*) AS sb_number 
  from Composition 
  group by formula) AS x1, (
  select formula 
  from Element natural join Composition 
  where (Element.symbol like :sym1)
  or (Element.el_name like :sym2)) AS x2 
where (Substance.formula=x1.formula)
and (Substance.formula=x2.formula) 
and (Substance.sb_name like :name)
and (Property.as like :as) 
and (Property.phase like :phase) 
and (Property.mass_type like :mass_type) 
";


if(isset($melt_min)) {
  $sql1 = "$sql1 and (Property.melt >= :melt_min)";
}

if(isset($melt_max)) {
  $sql1 = "$sql1 and (Property.melt <= :melt_max)";
}

if(isset($boil_min)) {
  $sql1 = "$sql1 and (Property.boil >= :boil_min)";
}

if(isset($boil_max)) {
  $sql1 = "$sql1 and (Property.boil <= :boil_max)";
}

if(isset($dens_min)) {
  $sql1 = "$sql1 and (Property.density >= :dens_min)";
}

if(isset($dens_max)) {
  $sql1 = "$sql1 and (Property.density <= :dens_max)";
}

if(isset($mass_min)) {
  $sql1 = "$sql1 and (Formula.mass >= :mass_min)";
}

if(isset($mass_max)) {
  $sql1 = "$sql1 and (Formula.mass <= :mass_max)";
}

if(isset($sort)) {
  switch($sort) {
    case 'melt':
      $sql1 = "$sql1 order by melt";
      break;
    case 'boil':
      $sql1 = "$sql1 order by boil";
      break;
    case 'dens':
      $sql1 = "$sql1 order by density";
      break;
    case 'mass':
      $sql1 = "$sql1 order by mass";
      break;
    default:
      break;
  }
  if($order=="asc") {
    $sql1 = "$sql1 asc";
  } else {
    $sql1 = "$sql1 desc";
  }
}

$sql2="
select distinct y.sb_name
from ($sql1) AS y
";

try{

        $stmh1=$pdo->prepare($sql1);

	$stmh1->bindvalue(":name","%{$name}%",PDO::PARAM_STR);
	$stmh1->bindvalue(":sym1","%{$sym}%",PDO::PARAM_STR);
	$stmh1->bindvalue(":sym2","%{$sym}%",PDO::PARAM_STR);
	$stmh1->bindvalue(":as","%{$as}%",PDO::PARAM_STR);
	$stmh1->bindvalue(":phase","%{$phase}%",PDO::PARAM_STR);
	$stmh1->bindvalue(":mass_type","%{$mass_type}%",PDO::PARAM_STR);

	if(isset($melt_min)) {
                $stmh1->bindvalue(":melt_min","$melt_min",PDO::PARAM_INT);
        }

        if(isset($melt_max)) {
                $stmh1->bindvalue(":melt_max","$melt_max",PDO::PARAM_INT);
        }
	
	if(isset($boil_min)) {
                $stmh1->bindvalue(":boil_min","$boil_min",PDO::PARAM_INT);
        }
	
	if(isset($boil_max)) {
                $stmh1->bindvalue(":boil_max","$boil_max",PDO::PARAM_INT);
        }
        
        if(isset($dens_min)) {
                $stmh1->bindvalue(":dens_min","$dens_min",PDO::PARAM_INT);
        }
	
	if(isset($dens_max)) {
                $stmh1->bindvalue(":dens_max","$dens_max",PDO::PARAM_INT);
        }

	if(isset($mass_min)) {
                $stmh1->bindvalue(":mass_min","$mass_min",PDO::PARAM_INT);
        }
	
	if(isset($mass_max)) {
                $stmh1->bindvalue(":mass_max","$mass_max",PDO::PARAM_INT);
        }
	
        $stmh1->execute();

	$stmh2=$pdo->prepare($sql2);

	$stmh2->bindvalue(":name","%{$name}%",PDO::PARAM_STR);
	$stmh2->bindvalue(":sym1","%{$sym}%",PDO::PARAM_STR);
	$stmh2->bindvalue(":sym2","%{$sym}%",PDO::PARAM_STR);
	$stmh2->bindvalue(":as","%{$as}%",PDO::PARAM_STR);
	$stmh2->bindvalue(":phase","%{$phase}%",PDO::PARAM_STR);
	$stmh2->bindvalue(":mass_type","%{$mass_type}%",PDO::PARAM_STR);

	if(isset($melt_min)) {
                $stmh2->bindvalue(":melt_min","$melt_min",PDO::PARAM_INT);
        }

        if(isset($melt_max)) {
                $stmh2->bindvalue(":melt_max","$melt_max",PDO::PARAM_INT);
        }
	
	if(isset($boil_min)) {
                $stmh2->bindvalue(":boil_min","$boil_min",PDO::PARAM_INT);
        }
	
	if(isset($boil_max)) {
                $stmh2->bindvalue(":boil_max","$boil_max",PDO::PARAM_INT);
        }
        
        if(isset($dens_min)) {
                $stmh2->bindvalue(":dens_min","$dens_min",PDO::PARAM_INT);
        }
	
	if(isset($dens_max)) {
                $stmh2->bindvalue(":dens_max","$dens_max",PDO::PARAM_INT);
        }

	if(isset($mass_min)) {
                $stmh2->bindvalue(":mass_min","$mass_min",PDO::PARAM_INT);
        }
	
	if(isset($mass_max)) {
                $stmh2->bindvalue(":mass_max","$mass_max",PDO::PARAM_INT);
        }
	
        $stmh2->execute();

        $count=$stmh2->rowCount();

        print "検索結果は{$count}件です。<br><br>";

} catch(PDOException $Exception){
        die("DB検索エラー:".$Exception->getMessage());

}

?>

<center>
<table border='1' cellpadding='2' cellspacing='0'>
<thead>
<tr bgcolor="#00CCCC"><th>化学式</th><th>物質名・元素名</th><th>

<?php

switch($mass_type){
  case '分子量':
    print "モル質量<br>（分子量）";
    break;
  case '式量':
    print "モル質量<br>（式量）";
    break;
  default:
    print "モル質量<br>（分子量）";
    print "</th><th>";
    print "モル質量<br>（式量）";
    break;
}
print "</th><th>";
print "水溶液";
print "</th><th>";
print "相";
print "</th><th>";
print "融点<br>（単位：℃）";
print "</th><th>";
print "沸点<br>（単位：℃）";
print "</th><th>";
print "密度<br>（単位：g/cm^3）";
print "</th><th>";
print "原子記号";
print "</th><th>";
print "元素名";
print "</th><th>";
print "原子量";
print "</th><th>";
print "化学式中の<br>原子の個数";

?>

</th></tr>
</thead>
<tbody>

<?php
 
$result=$stmh1->fetchAll(PDO::FETCH_ASSOC);
$i=1;
$a_num=0;
$a=[];

foreach($result as $row) {
  $bond_num=htmlspecialchars($row["sb_number"],ENT_QUOTES);
  $mass_type2=htmlspecialchars($row["mass_type"],ENT_QUOTES);
  print "<tr>";
  if($i==1){
    print "<td rowspan=$bond_num>"; 
    print htmlspecialchars($row["formula"],ENT_QUOTES);
    print "</td><td rowspan=$bond_num>";
    if($row["formula"]==$row["symbol"] && $row["sb_name"]==$row["el_name"]){
      print "<a href=./detail2.php?el_name=".$row["el_name"].">";
    } else {
      print "<a href=./detail1.php?sb_name=".$row["sb_name"].">";
    }
    print htmlspecialchars($row["sb_name"],ENT_QUOTES);
    print "</a>";
    print "</td><td rowspan=$bond_num>";
    if($mass_type=="分子量" || $mass_type=="式量") {
      print htmlspecialchars($row["mass"],ENT_QUOTES);
    } else {
      if($mass_type2=="分子量") {
	  print htmlspecialchars($row["mass"],ENT_QUOTES);
	  print "</td><td rowspan=$bond_num>";
      } else {
  	  print "</td><td rowspan=$bond_num>";
	  print htmlspecialchars($row["mass"],ENT_QUOTES);
      }
    } 
    print "</td><td rowspan=$bond_num>";
    print htmlspecialchars($row["as"],ENT_QUOTES);
    print "</td><td rowspan=$bond_num>";
    print htmlspecialchars($row["phase"],ENT_QUOTES);
    print "</td><td rowspan=$bond_num>";
    print htmlspecialchars($row["melt"],ENT_QUOTES);
    print "</td><td rowspan=$bond_num>";
    print htmlspecialchars($row["boil"],ENT_QUOTES);
    print "</td><td rowspan=$bond_num>";
    print htmlspecialchars($row["density"],ENT_QUOTES);
    print "</td>";
  }
  print "<td>";
  if(!($row["sb_number"]==1 && $row["number"]==1)) {
    print htmlspecialchars($row["symbol"],ENT_QUOTES);
  }
  print "</td><td>";
  if($a_num==0) {
    print "<a href=./detail2.php?el_name=".$row["el_name"].">";
    $link=1;
    $a[]=$row["elementID"];
  }
  for($j=0;$j<$a_num;$j++) {
    if($a[$j]==$row["elementID"]) {
      break;
    }
    if($j==$a_num-1) {
      print "<a href=./detail2.php?el_name=".$row["el_name"].">";
      $link=1;
      $a[]=$row["elementID"];
    }
  }
  print htmlspecialchars($row["el_name"],ENT_QUOTES);
  if($link==1) {
    print "</a>";
    $link=0;
    $a_num++;
  }
  print "</td><td>";
  print htmlspecialchars($row["weight"],ENT_QUOTES);
  print "</td><td>";
  if(!($row["sb_number"]==1 && $row["number"]==1)) {
    print htmlspecialchars($row["number"],ENT_QUOTES);
  }
  print "</td></tr>\n";
  if($i==$bond_num){
    $i=0;
  } 
  $i++;
}

?>


</tbody></table>
<br><a href=./search_form1.html>TOPに戻る</a>
</center>
</body>
</html>
