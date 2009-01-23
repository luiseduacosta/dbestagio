<?php

include_once("../../db.inc");
include_once("../../setup.php");

$ordem = $_GET['ordem'];

if(empty($ordem))
	$ordem = "instituicao";

$smarty = new Smarty_estagio;

// Crio uma tabela temporária para armazenar os dados que serão renderizados
$sql_tabela_temporaria  = "create temporary table instituicoes_estagio ( ";
if($tipo === "mysql") {
	$sql_tabela_temporaria .= "  `id` int(4) NOT NULL auto_increment, ";
	$sql_tabela_temporaria .= "  `area` varchar(30) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  `id_instituicao` int(3) NOT NULL default 0, ";
	$sql_tabela_temporaria .= "  `instituicao` varchar(75) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  `beneficio` varchar(50) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  `fim_de_semana` char(1) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  `turma` varchar(6) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  `q_supervisores` int(2) NOT NULL default 0, ";
	$sql_tabela_temporaria .= "  PRIMARY KEY (`id`))";
}
elseif($tipo === "pgsql")
{
	$sql_tabela_temporaria .= "  id serial NOT NULL, ";
	$sql_tabela_temporaria .= "  area varchar(30) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  id_instituicao integer NOT NULL default 0, ";
	$sql_tabela_temporaria .= "  instituicao varchar(75) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  beneficio varchar(50) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  fim_de_semana char(1) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  turma varchar(6) NOT NULL default '', ";
	$sql_tabela_temporaria .= "  q_supervisores integer NOT NULL default 0)";
}
// echo $sql_tabela_temporaria . "<br>";
$resultado_temporaria = $db->Execute($sql_tabela_temporaria);
if($resultado_temporaria === false) die ("Não foi possível criar a tabela temporaria");

// Inicio a captura dos dados que serão inseridos na tabela temporaria
$sql = "select e.id as num_instituicao, e.instituicao, e.beneficio as bolsa, e.area, max(t.periodo) as turma"
	. " from estagio e "
	. " left outer join estagiarios t "
	. " on e.id = t.id_instituicao "
	. " group by e.instituicao, e.area, beneficio, e.id ";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar a tabela estagio");

$i = 0;
while(!$resultado->EOF) {
	$id_instituicao = $resultado->fields['num_instituicao'];
	$instituicao    = $resultado->fields['instituicao'];
	$beneficio      = $resultado->fields['bolsa'];
	$turma 			= $resultado->fields['turma'];

	// Pego a area a partir do id da area
	$id_area = $resultado->fields['area'];
	if (empty($id_area)) {
	    $area = "sem/dados";
	} else {
	    $sql_area = "select area from areas_estagio where id=$id_area";
	    $resultado_area = $db->Execute($sql_area);
	    if($resultado_area === false) die ("Não foi possível consultar a tabela area_estagio");
		$area = $resultado_area->fields['area'];
	}

	// Calculo a quantidade de supervisores por instituicao
	$sql_supervisores = "select count(*) as q_supervisores from inst_super where id_instituicao=$id_instituicao";
	$resultado_supervisores = $db->Execute($sql_supervisores);
	if($resultado_supervisores === fasle) die ("Não foi possível consultar a tabela inst_super");
	$q_supervisores = $resultado_supervisores->fields['q_supervisores'];

	// Insero os dados capturados na tabela temporaria
	$insere  = "insert into instituicoes_estagio(id_instituicao,instituicao,area,beneficio,turma,q_supervisores) ";
	$insere .= " values('$id_instituicao','$instituicao','$area','$beneficio','$turma','$q_supervisores')";
	$resultado_insere = $db->Execute($insere);
	if($resultado_insere === false) die ("Não foi possivel inserir dados na tabela temporaria");
	// echo $insere . "<br>";

	$i++;

	$resultado->MoveNext();
}

$sql_ver_tabela_temporaria = "select id_instituicao,instituicao,beneficio,turma,q_supervisores,area from instituicoes_estagio order by $ordem";
$resultado_ver_tabela_temporaria = $db->Execute($sql_ver_tabela_temporaria);
if($resultado_ver_tabela_temporaria === false) die ("Não foi possível consultar a tabela temporaria instituicoes_estagio");
$i=0;
while(!$resultado_ver_tabela_temporaria->EOF) {
	$tabela[$i]['id_instituicao'] = $resultado_ver_tabela_temporaria->fields['id_instituicao'];
	$tabela[$i]['instituicao']    = $resultado_ver_tabela_temporaria->fields['instituicao'];
	$tabela[$i]['beneficio']      = $resultado_ver_tabela_temporaria->fields['beneficio'];
	$tabela[$i]['turma']          = $resultado_ver_tabela_temporaria->fields['turma'];
	$tabela[$i]['supervisores']   = $resultado_ver_tabela_temporaria->fields['q_supervisores'];
	$tabela[$i]['area']           = $resultado_ver_tabela_temporaria->fields['area'];
	$resultado_ver_tabela_temporaria->MoveNext();
	$i++;
}

/*
require_once(ADODB.'adodb-pager.inc.php');
$sql_tabela = "select area,instituicao,beneficio,turma,q_supervisores from instituicoes_estagio order by $ordem";
$pager = new ADODB_Pager($db,$sql_tabela);
$pager->Render($rows_per_page='5');
*/

$smarty->assign("pagina_atual",$PHP_SELF);
$smarty->assign("instituicoes",$tabela);
$smarty->display("instituicoes.tlp");

$db->Close();

exit;

?>