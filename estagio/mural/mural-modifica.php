<?php

# include_once("mural-autentica.inc");

include_once("../setup.php");

// Pego o numero da instituição
$id_instituicao = $_REQUEST['id_instituicao'];

// Busco a instituição, área e professor
$sql  = "select instituicao, convenio, vagas, beneficios, final_de_semana, ";
$sql .= "cargaHoraria, requisitos, ";
$sql .= "id_area, area, id_professor, nome, horario, dataSelecao, horarioSelecao, dataInscricao, ";
$sql .= "localSelecao, formaSelecao, contato, outras, periodo, mural_estagio.email ";
$sql .= "from mural_estagio ";
$sql .= "left outer join areas_estagio on mural_estagio.id_area = areas_estagio.id ";
$sql .= "left outer join professores on mural_estagio.id_professor = professores.id ";
$sql .= "where mural_estagio.id=$id_instituicao";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela mural_estagio");

while (!$resultado->EOF) {
	$instituicao = $resultado->fields['instituicao'];
	$convenio = $resultado->fields['convenio'];
	$vagas  = $resultado->fields['vagas'];
	$beneficios = $resultado->fields['beneficios'];
	$final_de_semana = $resultado->fields['final_de_semana'];
	$cargaHoraria = $resultado->fields['cargaHoraria'];
	$requisitos = $resultado->fields['requisitos'];
	$id_area = $resultado->fields['id_area'];
	$area = $resultado->fields['area'];
	$id_professor = $resultado->fields['id_professor'];
	$professor = $resultado->fields['nome'];
	$horario = $resultado->fields['horario'];
	$dataSelecao = $resultado->fields['dataSelecao'];
	$horarioSelecao = $resultado->fields['horarioSelecao'];
	$dataInscricao = $resultado->fields['dataInscricao'];
	$localSelecao = $resultado->fields['localSelecao'];
	$formaSelecao = $resultado->fields['formaSelecao'];
	$contato = $resultado->fields['contato'];
	$outras = $resultado->fields['outras'];
	$periodo = $resultado->fields['periodo'];
	$email = $resultado->fields['email'];
	$resultado->MoveNext();
}

// Transformo a data de aaaa-mm-dd para dd-mm-aaaa
if ($dataSelecao == 0) {
	$data_selecao = "00-00-0000";
} else {
	$data_selecao = date("d-m-Y",strtotime($dataSelecao));
}

if ($dataInscricao == 0) {
	$data_inscricao = "00-00-0000";
} else {
	$data_inscricao = date("d-m-Y",strtotime($dataInscricao));
}

// Areas
$sql = "select id, area from areas_estagio order by area";
// echo $sql . "<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela areas_estagio");
$i = 0;
while(!$resultado->EOF) {
	$areas[$i]['id_areas'] = $resultado->fields["id"];
	$areas[$i]['areas'] = $resultado->fields["area"];
	$i++;
	$resultado->MoveNext();
}

// Professores
$sqlProfessores = "select id, nome from professores order by nome";
// echo $sqlProfessores . "<br>";
$resultadoProfessores = $db->Execute($sqlProfessores);
if($resultadoProfessores === false) die ("Não foi possível consultar a tabela professores");
$i = 0;
$professores[$i]['id_professores'] = 0;
$professores[$i]['professores'] = "";
$i++;
while(!$resultadoProfessores->EOF) {
	$professores[$i]['id_professores'] = $resultadoProfessores->fields["id"];
	$professores[$i]['professores'] = $resultadoProfessores->fields["nome"];
	$i++;
	$resultadoProfessores->MoveNext();
}

// Envio os resultados
$smarty = new Smarty_estagio;

$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("convenio",$convenio);
$smarty->assign("vagas",$vagas);
$smarty->assign("beneficios",$beneficios);
$smarty->assign("final_de_semana",$final_de_semana);
$smarty->assign("cargaHoraria",$cargaHoraria);
$smarty->assign("requisitos",$requisitos);
$smarty->assign("id_area",$id_area);
$smarty->assign("area",$area);
$smarty->assign("id_professor",$id_professor);
$smarty->assign("professor",$professor);
$smarty->assign("horario",$horario);
$smarty->assign("dataSelecao",$data_selecao);
$smarty->assign("horarioSelecao",$horarioSelecao);

$smarty->assign("dataInscricao",$data_inscricao);
$smarty->assign("localSelecao",$localSelecao);
$smarty->assign("formaSelecao",$formaSelecao);
$smarty->assign("contato",$contato);
$smarty->assign("outras",$outras);
$smarty->assign("periodo",$periodo);
$smarty->assign("areas",$areas);
$smarty->assign("email",$email);
$smarty->assign("professores",$professores);

// Mostro os resultados
$smarty->display("../../mural/mural-modifica.tpl");

$db->close();

exit;

?>