<?php

include_once("../../db.inc");
include_once("../../setup.php");

$ordem   = $_GET['ordem'];
$assunto = $_REQUEST['assunto'];
$corpo   = $_REQUEST['corpo'];

// echo $assunto . " " . $corpo . "<br>";

$sql  = "select e.id as estagio_id, e.instituicao, max(estagiarios.periodo) as periodo, ";
$sql .= " s.id as supervisor_id, s.cress, s.nome, s.email ";
$sql .= " from supervisores as s ";
$sql .= " left outer join estagiarios on s.id = estagiarios.id_supervisor ";
$sql .= " left outer join inst_super as i on s.id = i.id_supervisor ";
$sql .= " left outer join estagio as e on e.id = i.id_instituicao ";
$sql .= " group by estagiarios.id_supervisor ";
// $sql .= " order by s.nome ";

// echo $sql. "<br>";

$resultado = $db->Execute($sql);
if($resultado == false) die ("Não foi possível consultar as tabelas");
while(!$resultado->EOF) {
	$email = $resultado->fields['email'];
	if(!empty($email)) {
			
		if(empty($ordem))
			$ordem = "nome";
		else
			$indice = $ordem;
			
		$matriz[$i][$ordem]           = $$indice;
		$matriz[$i]['id_instituicao'] = $resultado->fields['estagio_id'];
		$matriz[$i]['id_supervisor']  = $resultado->fields['supervisor_id'];
		$matriz[$i]['nome']           = $resultado->fields['nome'];
		$matriz[$i]['instituicao']    = $resultado->fields['instituicao'];
		$matriz[$i]['email']          = $resultado->fields['email'];
		$matriz[$i]['periodo']        = $resultado->fields['periodo'];

		$i++;
	}		
	$resultado->MoveNext();

}

if (sizeof($matriz) > 0) {
    reset($matriz);
    sort($matriz);
}

/* Debugg
for($i=0;$i<sizeof($matriz);$i++) {
    print $matriz[$i]['id'] . " ";
    print $matriz[$i]['nome'] . " ";
    print $matriz[$i]['instituicao'] . "<br>";
}
*/

$smarty = new Smarty_estagio;

$smarty->assign("supervisores",$matriz);
$smarty->assign("assunto",$assunto);
$smarty->assign("corpo",$corpo);
$smarty->display("supervisores-enviar_email.tpl");

$db->Close();

exit;

?>