<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// echo "<h1>Aguarde: preparando a lista dos supervisores para o envio de e-mail.</h1>";

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;
// Ajusto os periodos para consultar a tabela de estagiarios
$periodo_atual = $periodo;
$_periodo_atual = explode("-",$periodo_atual);
if ($_periodo_atual[1] == 2) $periodo_proximo = $_periodo_atual[0] + 1 . "-1";
if ($_periodo_atual[1] == 1) $periodo_proximo = $_periodo_atual[0] . "-2";

require_once("../pommo_config.php");
require_once("../pommo_tabelas.php");

// Apago toda a informacao das tabelas
$sql_subs = "truncate table pommo_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if($res_subs === false) die ("Não foi possível limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table pommo_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if($res_subs_data === false) die ("Não foi possível limpar a tabela pommo_subscribers_data");

$sql_pommo_fields = "truncate table pommo_fields";
$res_pommo_fields = $db_pommo->Execute($sql_pommo_fields);
if($res_pommo_fields === false) die ("Não foi possível limpar a tabela pommo_fields");

// Insero os campos
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 1, 'supervisor', 'Supervisor', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 2, 'cress', 'Cress', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 3, 'instituicao', 'Instituição', '', 'a:0:{}', 'on', 'text'),
(4, 'on', 4, 'aluno', 'Aluno', '', 'a:0:{}', 'off', 'text'),
(5, 'on', 5, 'dre', 'DRE', '', 'a:0:{}', 'off', 'text'),
(6, 'on', 6, 'tc', 'TC', '', 'a:0:{}', 'off', 'text'),
(7, 'on', 7, 'periodo', 'Período', '', 'a:0:{}', 'off', 'text'),
(8, 'on', 8, 'nivel', 'Nível', '', 'a:0:{}', 'off', 'text');
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../setup.php");

$sql = "select id_aluno, alunos.registro, alunos.nome, 
alunos.email, alunos.observacoes, 
estagiarios.periodo, estagiarios.nivel, 
supervisores.email as super_email, supervisores.nome as supervisor, supervisores.cress, 
estagio.instituicao 
from estagiarios 
join alunos on estagiarios.registro = alunos.registro 
left join supervisores on estagiarios.id_supervisor = supervisores.id 
left join estagio on estagiarios.id_instituicao = estagio.id 
where nivel != 4 and periodo = '$periodo' 
group by id_aluno";
// order by $ordem";
// echo $sql ."<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios");
$i = 0;
while (!$resultado->EOF) {
	$id_aluno = $resultado->fields['id_aluno'];
	$registro = $resultado->fields['registro'];
	$aluno = $resultado->fields['nome'];
	$email = $resultado->fields['email'];
	$nivel = $resultado->fields['nivel'];
	$periodo = $resultado->fields['periodo'];
	$super_email = $resultado->fields['super_email'];
	$supervisor = $resultado->fields['supervisor'];
	$cress = $resultado->fields['cress'];
	$instituicao = $resultado->fields['instituicao'];

	// echo $i  . " " . $supervisor . " " . $super_email . " ". $instituicao . "<br>";
	
	include("../db.inc");
	
	$sql_tc = "select tc_solicitacao, tc from estagiarios where registro = '$registro' and periodo = '$periodo_proximo'";
	// echo "$sql_tc <br>";
	$resultado_tc = $db->Execute($sql_tc);
	if ($resultado_tc === false) die ("Não foi possível consultar a tabela estagiarios");
	$tc_solicitacao = $resultado_tc->fields['tc_solicitacao'];
	// if ($tc_solicitacao == 0) $tc_solicitacao = "";
	$tc_solicitacao = ($tc_solicitacao == 0) ? NULL : $tc_solicitacao;
	$tc = $resultado_tc->fields['tc'];
	// echo $tc_solicitacao . " " . $tc . "<br>";

	if ($super_email) {
		include("../pommo_config.php");
		$sql_email = "insert into pommo_subscribers (email,status) values ('$super_email',1)";
		// echo $n++ . " " . $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email);
		if($res_email === false) die ("0 Não foi possível inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		// 1 Supervisor
		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,\"$supervisor\",$subscriber_id)";
		// echo 1 . " " . $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome);
		if($res_email_nome === false) die ("1 Não foi possível inserir dados na tabela pommo_subscribers");

		// 2 CRESS
		$sql_email_cress = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$cress',$subscriber_id)";
		// echo 2 . " " . $sql_email_cress . "<br>";
		$res_email_cress = $db_pommo->Execute($sql_email_cress);
		if($res_email_cress === false) die ("2 Não foi possível inserir dados na tabela pommo_subscribers");

		// 3 Instituicao
		$sql_email_inst = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,\"$instituicao\",$subscriber_id)";
		// echo 3 . " " . $sql_email_inst . "<br>";
		$res_email_inst = $db_pommo->Execute($sql_email_inst);
		if($res_email_inst === false) die ("3 Não foi possível inserir dados na tabela pommo_subscribers");

		// 4 Aluno
		$sql_email_aluno = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,\"$aluno\",$subscriber_id)";
		// echo 4 . " " . $sql_email_aluno . "<br>";
		$res_email_aluno = $db_pommo->Execute($sql_email_aluno);
		if($res_email_aluno === false) die ("4 Não foi possível inserir dados na tabela pommo_subscribers");
		
		// 5 DRE
		$sql_email_dre = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$registro',$subscriber_id)";
		// echo 5 . " " . $sql_email_dre . "<br>";
		$res_email_dre = $db_pommo->Execute($sql_email_dre);
		if($res_email_dre === false) die ("5 Não foi possível inserir dados na tabela pommo_subscribers");

		// 6 TC
		$sql_email_tc = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (6,'$tc',$subscriber_id)";
		// echo 6 . " " . $sql_email_tc . "<br>";
		$res_email_tc = $db_pommo->Execute($sql_email_tc);
		if($res_email_tc === false) die ("4 Não foi possível inserir dados na tabela pommo_subscribers");

		// 7 Periodo
		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (7,'$periodo',$subscriber_id)";
		// echo 7 . " " . $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo);
		if($res_email_periodo === false) die ("5 Não foi possível inserir dados na tabela pommo_subscribers");

		// 8 Nivel
		$sql_email_nivel = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (8,'$nivel',$subscriber_id)";
		// echo 8 . " " . $sql_email_nivel . "<br>";
		$res_email_nivel = $db_pommo->Execute($sql_email_nivel);
		if($res_email_nivel === false) die ("6 Não foi possível inserir dados na tabela pommo_subscribers");

	}

	// echo "<br>";

	$i++;
	$resultado->MoveNext();
}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";
// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://desenvolvimento/pommo/'>";

?>
