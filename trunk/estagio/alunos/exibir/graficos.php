<?php

// include_once("../../db.inc");
include_once("../../setup.php");
include_once("../../libphp/libchart-1.2.1/libchart/classes/libchart.php");

echo "
<html>
<head></head>
<body>
<img alt='Alunos por período' src='generated/estagio.png' style='border: 1px solid gray;'/>
<table border='1'>
<thead>
<tr><th>Período</th><th>Alunos</th></tr>
</thead>
<tbody>
";

$chart = new VerticalBarChart(500, 250);
$dataSet = new XYDataSet();

$sql_periodo = "select periodo from estagiarios group by periodo order by periodo";
// echo $sql_periodo . "<br>";
$resultado_periodo = $db->Execute($sql_periodo);
while(!$resultado_periodo->EOF) {

	$periodo = $resultado_periodo->fields['periodo'];
	$sql = "select id from estagiarios where periodo='$periodo'";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	$quantidade = $resultado->RecordCount();

	$periodos[] = $periodo;
	$alunos[]   = $quantidade;

	$dataSet->addPoint(new Point("$periodo", $quantidade));

	echo "
    <tr><td style='text-align:center'>$periodo</td><td style='text-align:right'>$quantidade</td></tr>
    ";

	$resultado_periodo->MoveNext();
}

$chart->setDataSet($dataSet);

$chart->setTitle("Alunos em estágio por período - ESS/UFRJ");
$chart->render("generated/estagio.png");

echo "
</tbody>
</table>
";
// print_r($alunos);

for ($i=0;$i<sizeof($alunos);$i++) {
	if ($i > 0) {
		$variavel .= "," . $alunos[$i];
	} else {
		$variavel = $alunos[$i];
	}
}

for ($i=0;$i<sizeof($periodos);$i++) {
	if ($i > 0) {
		$var_periodo .= "|" . $periodos[$i];
	} else {
		$var_periodo = $periodos[$i];
	}
}

echo "
<img src=\"http://chart.apis.google.com/chart?
cht=bvg&amp
chco=4D89F9&amp
chs=500x200&amp
chtt=Alunos+em+estagio+por+periodo&amp
chd=t:$variavel&amp
chds=0,350&amp
chxt=x,x,y,y&amp
chxl=0:|$var_periodo|1:|Periodos|3:|Alunos&amp
chxp=1,50|3,50&amp
chxr=2,0,300&amp
chbh=20,1,20&amp
chm=N,FF0000,-1,,10\">
";

echo "
</body>
</html>
";

?>
