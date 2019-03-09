<?php
/*
 * Created on 06/03/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL;

require_once("../../pommo_config.php");
require_once("../../pommo_tabelas.php");

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
(1, 'on', 0, 'nome', 'Nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'registro', 'Registro', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 2, 'periodo', 'Período', '', 'a:0:{}', 'off', 'text'),
(4, 'on', 3, 'nivel', 'Nivel', '', 'a:0:{}', 'off', 'text'),
(5, 'on', 4, 'instituicao', 'Instituição', '', 'a:0:{}', 'off', 'text'),
(6, 'on', 5, 'supervisor', 'Supervisor', '', 'a:0:{}', 'off', 'text'),
(7, 'on', 6, 'professor', 'Professor', '', 'a:0:{}', 'off', 'text'),
(8, 'on', 7, 'monografia', 'Monografia', '','a:0:{}', 'off', 'text');
";
// echo "<br>" . $sql_pommo_campos . "<br>";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../../setup.php");

$sql  = "select id_aluno, alunos.registro, alunos.nome, alunos.email "; 
$sql .= " , estagiarios.periodo, max(estagiarios.nivel) as nivel  ";
$sql .= " , estagio.instituicao, supervisores.nome as supervisor, professores.nome as professor ";
$sql .= " , num_monografia ";
$sql .= " from estagiarios ";
$sql .= " join alunos on estagiarios.registro = alunos.registro ";
$sql .= " join estagio on estagiarios.id_instituicao = estagio.id ";
$sql .= " join supervisores on estagiarios.id_supervisor = supervisores.id ";
$sql .= " join professores on estagiarios.id_professor = professores.id ";
$sql .= " left join tcc_alunos on alunos.registro = tcc_alunos.registro ";
if ($periodo) $sql .= " where periodo = '$periodo' ";
$sql .= " group by id_aluno";
// order by $ordem";
// echo $sql ."<br>";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios");
$i = 0;
while (!$resultado->EOF) {
	$email = $resultado->fields['email'];
	$registro = $resultado->fields['registro'];
	$nome = addslashes($resultado->fields['nome']);
	$nivel = $resultado->fields['nivel'];
	$instituicao = addslashes($resultado->fields['instituicao']);
	$supervisor = addslashes($resultado->fields['supervisor']);
	$professor = addslashes($resultado->fiedls['professor']);
	$monografia = $resultado->fields['num_monografia'];
	$periodo = $resultado->fields['periodo'];

	// echo "$email . ' ' . $registro . ' '. $nome . ' ' . $nivel . ' '. $instituicao . ' ' . $supervisor . ' ' . $professor . ' '  . $periodo . '<br>'";

	if ($email) {
		include("../../pommo_config.php");
		$sql_email = "insert into pommo_subscribers (email,status) values (\"$email\",1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email);
		if($res_email === false) die ("0 Não foi possível inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,\"$nome\",$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome);
		if($res_email_nome === false) die ("1 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_registro = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$registro',$subscriber_id)";
		// echo $sql_email_registro . "<br>";
		$res_email_registro = $db_pommo->Execute($sql_email_registro);
		if($res_email_registro === false) die ("2 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,\"$periodo\",$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo);
		if($res_email_periodo === false) die ("5 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_nivel = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$nivel',$subscriber_id)";
		// echo $sql_email_nivel . "<br>";
		$res_email_nivel = $db_pommo->Execute($sql_email_nivel);
		if($res_email_nivel === false) die ("6 Não foi possível inserir dados na tabela pommo_subscribers");

		$sql_email_instituicao = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$instituicao',$subscriber_id)";
		// echo $sql_email_nivel . "<br>";
		$res_email_instituicao = $db_pommo->Execute($sql_email_instituicao);
		if($res_email_instituicao === false) die ("7 Não foi possível inserir dados na tabela pommo_subscribers");
		
		$sql_email_supervisor = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (6,'$supervisor',$subscriber_id)";
		echo $sql_email_supervisor . "<br>";
		$res_email_supervisor = $db_pommo->Execute($sql_email_supervisor);
		if($res_email_supervisor === false) die ("8 Não foi possível inserir dados na tabela pommo_subscribers");
				
		$sql_email_professor = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (7,'$professor',$subscriber_id)";
		// echo $sql_email_nivel . "<br>";
		$res_email_professor = $db_pommo->Execute($sql_email_professor);
		if($res_email_professor === false) die ("9 Não foi possível inserir dados na tabela pommo_subscribers");
		
		if ($monografia) {
			$monografia = 1;
		} else {
			$monografia = 0;
		}
		$sql_email_monografia = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (8,\"$monografia\",$subscriber_id)";
		// echo $sql_email_monografia . "<br>";
		$res_email_monografia = $db_pommo->Execute($sql_email_monografia);
		if($res_email_monografia === false) die ("10 Não foi possível inserir dados na tabela pommo_subscribers");

	}

	// echo "<br>";

	$i++;
	$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=../../../pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";

?>
