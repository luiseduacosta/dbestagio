<?php

// include_once("../../db.inc");
include_once("../../setup.php");
include_once("../../libphp/jpgraph/src/jpgraph.php");
include_once("../../libphp/jpgraph/src/jpgraph_bar.php");

$chart = new graph(500, 250, "png");
$chart->SetScale("textlin");
$chart->SetShadow();
$chart->title->Set("Alunos em est�gio por per�odo - ESS/UFRJ");

$sql_periodo = "select periodo from estagiarios group by periodo order by periodo";
// echo $sql_periodo . "<br>";
$resultado_periodo = $db->Execute($sql_periodo);
while(!$resultado_periodo->EOF) {

    $periodo = $resultado_periodo->fields['periodo'];
    $periodos[] = $periodo;
    $sql = "select id from estagiarios where periodo='$periodo'";
    // echo $sql . "<br>";
    $resultado = $db->Execute($sql);
    $quantidade[] = $resultado->RecordCount();
    
	$i++;

    $resultado_periodo->MoveNext();
}

$barras = new BarPlot($quantidade);
$barras->SetFillColor("orange");
$barras->SetShadow("darkblue");
$barras->value->Show();

$chart->Add($barras);

$chart->xaxis->SetTickLabels($periodos);
$chart->xaxis->SetTitle("Per�odos", "middle");
$chart->xaxis->SetLabelAlign("rigth", "center");
// $chart->xaxis->title->SetFont(FF_ARIAL, FS_BOLD);

$chart->Stroke("generated/estagio.png");

echo "
<html>
<head></head>
<body>
<img alt='Alunos por per�odo' src='generated/estagio.png' style='border: 1px solid gray;'/>
</body>
</html>
";

?>
