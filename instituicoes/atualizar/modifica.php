<?php

require_once("../../autentica.inc");
// include_once("../../autentica.inc");
// Pego o numero da instituição
$instituicao_id = isset($_REQUEST['instituicao_id']) ? $_REQUEST['instituicao_id'] : (isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL);

// Busco a instituição, área e supervisor
$sql  = "select e.instituicao, e.endereco, e.cep, e.telefone, ";
$sql .= "e.area as id_area, e.beneficios, e.fim_de_semana, e.convenio, a.area ";
$sql .= "from instituicoes as e "; 
$sql .= "left outer join areas as a ";
$sql .= "on e.area=a.id ";
$sql .= "where e.id=$instituicao_id";

// include_once("../../db.inc");
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível consultar a tabela instituicoes");

while (!$resultado->EOF) {
	$nome_instituicao      = $resultado->fields['instituicao'];
	$endereco_instituicao  = $resultado->fields['endereco'];
	$cep_instituicao       = $resultado->fields['cep'];
	$telefone_instituicao  = $resultado->fields['telefone'];
	$id_area_instituicao   = $resultado->fields['id_area'];
	$area_instituicao      = $resultado->fields['area'];
	$beneficio_instituicao = $resultado->fields['beneficios'];
	$fim_de_semana         = $resultado->fields['fim_de_semana'];
	$convenio              = $resultado->fields['convenio'];
	
	// Pego os supervisores por instituicao
	$sql_super_por_instituicao  = "select s.id, s.nome, s.cress from supervisores as s, inst_super as j ";
	$sql_super_por_instituicao .= "where j.supervisor_id=s.id and j.instituicao_id=$instituicao_id order by nome";
	$resultado_super_por_instituicao = $db->Execute($sql_super_por_instituicao);
	if ($resultado_super_por_instituicao === false) die ("Não foi possível consultar a tabela supervisores");
	$i = 0;
	while (!$resultado_super_por_instituicao->EOF) {
	    $inst_supervisores[$i]['id']    = $resultado_super_por_instituicao->fields['id'];
	    $inst_supervisores[$i]['cress'] = $resultado_super_por_instituicao->fields['cress'];
	    $inst_supervisores[$i]['nome']  = $resultado_super_por_instituicao->fields['nome'];
	    $resultado_super_por_instituicao->MoveNext();
	    $i++;
	}

	// Pego a turma por instituicao
	$sql_turma = "select max(periodo) as turma from estagiarios where instituicao_id=$instituicao_id";
	$resultado = $db->Execute($sql_turma);
	if ($resultado === false) die ("Não foi possível consultar a tabela estagiarios");
	$turma = $resultado->fields['turma'];
	// echo "turma: " . $turma . "<br>";

	$resultado->MoveNext();
}

// Pego as áreas das instituições para ser enviadas para o formulário
$sql_areas = "select id, area from areas order by area";
$res_areas = $db->Execute($sql_areas);
if ($res_areas === false) die ("Não foi possível consultar a tabela areas");
$i = 0;
while (!$res_areas->EOF) {
    $matriz_areas[$i]["id_area"] = $res_areas->fields['id'];
    $matriz_areas[$i]["area"]    = $res_areas->fields['area'];
    $i++;
    $res_areas->MoveNext();
}

// Pego a listagem de todos os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if ($res_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
$i = 0;
while (!$res_supervisores->EOF) {
    $supervisores[$i]['id']   = $res_supervisores->fields['id'];
    $supervisores[$i]['nome'] = $res_supervisores->fields['nome'];
	$i++;
    $res_supervisores->MoveNext();
}

// Envio os resultados
$smarty = new Smarty_estagio;

$smarty->assign("instituicao_id",$instituicao_id);
$smarty->assign("nome_instituicao",$nome_instituicao);
$smarty->assign("endereco_instituicao",$endereco_instituicao);
$smarty->assign("cep_instituicao",$cep_instituicao);
$smarty->assign("telefone_instituicao",$telefone_instituicao);
$smarty->assign("id_area_instituicao",$id_area_instituicao);
$smarty->assign("beneficio_instituicao",$beneficio_instituicao);
$smarty->assign("fim_de_semana",$fim_de_semana);
$smarty->assign("area_instituicao",$area_instituicao);
$smarty->assign("convenio",$convenio);
$smarty->assign("inst_supervisores",$inst_supervisores);
$smarty->assign("matriz_areas",$matriz_areas);
$smarty->assign("turma",$turma);
$smarty->assign("periodo_estagio",$periodo_estagio);
$smarty->assign("ultimo_periodo",$ultimo_periodo);
$smarty->assign("ultimo_periodo_estagio",$ultimo_periodo_estagio);
$smarty->assign("supervisores",$supervisores);
// Mostro os resultados
$smarty->display("instituicao_modifica.tpl");

$db->close();

exit;

?>