<?php

include_once("../../db.inc");
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

</body>
</html>
";

?>