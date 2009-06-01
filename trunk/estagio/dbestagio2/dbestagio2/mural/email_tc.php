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

// Apago toda a informacao das tabelas
$sql_subs = "truncate table pommo_subscribers";
$res_subs = $db_pommo->Execute($sql_subs);
if($res_subs === false) die ("N�o foi poss�vel limpar a tabela pommo_subscribers");

$sql_subs_data = "truncate table pommo_subscriber_data";
$res_subs_data = $db_pommo->Execute($sql_subs_data);
if($res_subs_data === false) die ("N�o foi poss�vel limpar a tabela pommo_subscribers_data");

$sql_pommo_fields = "truncate table pommo_fields";
$res_pommo_fields = $db_pommo->Execute($sql_pommo_fields);
if($res_pommo_fields === false) die ("N�o foi poss�vel limpar a tabela pommo_fields");

// Insero os campos
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 1, 'Nome', 'nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 2, 'Registro', 'registro', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 3, 'Tipo', 'categoria', '', 'a:0:{}', 'off', 'text'),
(4, 'on', 4, 'TC', 'tc', '', 'a:0:{}', 'off', 'text'),
(5, 'on', 5, 'Periodo', 'periodo', '', 'a:0:{}', 'off', 'text'),
(6, 'on', 6, 'Nivel', 'nivel', '', 'a:0:{}', 'off', 'text');
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("N�o foi poss�vel inserir na tabela pommo_fields");

include("../setup.php");

$sql = "select id_aluno, alunos.registro, alunos.nome, 
alunos.email, alunos.observacoes, 
estagiarios.periodo, estagiarios.nivel 
from estagiarios 
join alunos on estagiarios.registro = alunos.registro 
where nivel != 4 and periodo = '$periodo' 
group by id_aluno";
// order by $ordem";
// echo $sql ."<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("N�o foi poss�vel consultar as tabelas alunos, estagiarios");
$i = 0;
while (!$resultado->EOF) {
	$id_aluno = $resultado->fields['id_aluno'];
	$registro = $resultado->fields['registro'];
	$nome = $resultado->fields['nome'];
	$email = $resultado->fields['email'];
	$nivel = $resultado->fields['nivel'];
	$periodo = $resultado->fields['periodo'];
	
	include("../db.inc");
	
	$sql_tc = "select tc_solicitacao, tc from estagiarios where registro = '$registro' and periodo = '$periodo_proximo'";
	// echo "$sql_tc <br>";
	$resultado_tc = $db->Execute($sql_tc);
	if ($resultado_tc === false) die ("N�o foi poss�vel consultar a tabela estagiarios");
	$tc_solicitacao = $resultado_tc->fields['tc_solicitacao'];
	// if ($tc_solicitacao == 0) $tc_solicitacao = "";
	$tc_solicitacao = ($tc_solicitacao == 0) ? NULL : $tc_solicitacao;
	$tc = $resultado_tc->fields['tc'];
	// echo $tc_solicitacao . " " . $tc . "<br>";

	if ($email) {
		include("../pommo_config.php");
		$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email);
		if($res_email === false) die ("0 N�o foi poss�vel inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome);
		if($res_email_nome === false) die ("1 N�o foi poss�vel inserir dados na tabela pommo_subscribers");

		$sql_email_registro = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$registro',$subscriber_id)";
		// echo $sql_email_registro . "<br>";
		$res_email_registro = $db_pommo->Execute($sql_email_registro);
		if($res_email_registro === false) die ("2 N�o foi poss�vel inserir dados na tabela pommo_subscribers");

		$sql_email_tipo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'aluno_tc',$subscriber_id)";
		// echo $sql_email_tipo . "<br>";
		$res_email_tipo = $db_pommo->Execute($sql_email_tipo);
		if($res_email_tipo === false) die ("3 N�o foi poss�vel inserir dados na tabela pommo_subscribers");

		$sql_email_tc = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$tc',$subscriber_id)";
		// echo $sql_email_tc . "<br>";
		$res_email_tc = $db_pommo->Execute($sql_email_tc);
		if($res_email_tc === false) die ("4 N�o foi poss�vel inserir dados na tabela pommo_subscribers");

		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$periodo',$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo);
		if($res_email_periodo === false) die ("5 N�o foi poss�vel inserir dados na tabela pommo_subscribers");

		$sql_email_nivel = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (6,'$nivel',$subscriber_id)";
		// echo $sql_email_nivel . "<br>";
		$res_email_nivel = $db_pommo->Execute($sql_email_nivel);
		if($res_email_nivel === false) die ("6 N�o foi poss�vel inserir dados na tabela pommo_subscribers");
	}

	// echo "<br>";

	$i++;
	$resultado->MoveNext();
}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";

?>
