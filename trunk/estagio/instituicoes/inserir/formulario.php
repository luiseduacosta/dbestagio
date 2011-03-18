<?php

include_once("../../autentica.inc");
include_once("../../setup.php");

$sql = "select * from areas_estagio order by area";
$resultado = $db->Execute($sql);

if ($resultado === false) die ("Não foi possível consultar a tabela areas_estagio");

$i = 0;
while (!$resultado->EOF) {
  $id_area[$i] = $resultado->fields["id"];
  $areas[$i]   = $resultado->fields["area"];
  $i++;
  $resultado->MoveNext();
}

$smarty = new Smarty_estagio;

/* Debugg
for($i=0;$i<sizeof($areas);$i++)
{
    print($areas[$i]) . "<br>";
}
*/

$smarty->assign("id_area",$id_area);
$smarty->assign("areas",$areas);
$smarty->display("instituicao_form.tlp");

?>