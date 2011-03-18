<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// echo "<h1>Aguarde: preparando a lista dos supervisores para o envio de e-mail.</h1>";

$turma = isset($_REQUEST['turma']) ? $_REQUEST['turma'] : NULL;

include("../pommo_config.php");

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

// Insero os campos na tabela
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'nome', 'Nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'cress', 'CRESS', '', 'a:0:{}', 'on', 'text'),
(3, 'on', 2, 'escola', 'Escola', '', 'a:0:{}', 'on', 'text'),
(4, 'on', 3, 'ano_formatura', 'Ano formatura', '', 'a:0:{}', 'on', 'text'),
(5, 'on', 4, 'turma', 'Turma', '', 'a:0:{}', 'on', 'text');
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("Não foi possível inserir na tabela pommo_fields");

include("../setup.php");

// Busco todos os supervisores de todos os periodos para serem inseridos nas tabelas
$sql = "select email, cress, nome, escola, ano_formatura, curso_turma ";
$sql .= "from curso_inscricao_supervisor ";
if ($turma) $sql .= " where curso_turma = '$turma' ";
// $sql .= " group by cress ";
$sql .= " order by nome ";
// echo $sql . "<br>";

$resultado = $db->Execute($sql); 
while (!$resultado->EOF) {
	$email = $resultado->fields['email'];
	$nome = $resultado->fields['nome'];
	$cress = $resultado->fields['cress'];
	$escola = $resultado->fields['escola'];
	$ano_formatura = $resultado->fields['ano_formatura'];
	$turma = $resultado->fields['curso_turma'];

// Somente aqueles que tem e-mail
	if ($email) {
		
		include("../pommo_config.php");
		
		$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email); 
		if($res_email === false) die ("Não foi possível inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome); 
		
		$sql_email_cress = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$cress',$subscriber_id)";
		// echo $sql_email_inst . "<br>";
		$res_email_cress = $db_pommo->Execute($sql_email_cress);

		$sql_email_escola = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'$escola',$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_escola = $db_pommo->Execute($sql_email_escola);

		$sql_email_ano_formatura = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$ano_formatura',$subscriber_id)";
		// echo $sql_email_area . "<br>";
		$res_email_ano_formatura = $db_pommo->Execute($sql_email_ano_formatura);

		$sql_email_turma = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (5,'$turma',$subscriber_id)";
		// echo $sql_email_area . "<br>";
		$res_email_turma = $db_pommo->Execute($sql_email_turma);

	}
	
	$resultado->MoveNext();
}

// echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://desenvolvimento/pommo/'>";
echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";

?>
