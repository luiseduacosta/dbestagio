<?php
/*
 * Created on 29/01/2009
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

// echo "<h1>Aguarde: preparando a lista dos supervisores para o envio de e-mail.</h1>";

include("../../pommo_config.php");

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

// Insero a informacao dos campos
$sql_pommo_campos = "
INSERT INTO `pommo_fields` (`field_id`, `field_active`, `field_ordering`, `field_name`, `field_prompt`, `field_normally`, `field_array`, `field_required`, `field_type`) VALUES
(1, 'on', 0, 'nome', 'Nome', '', 'a:0:{}', 'on', 'text'),
(2, 'on', 1, 'instituicao', 'Institui��o', '', 'a:0:{}', 'off', 'text'),
(3, 'on', 2, 'periodo', 'Per�odo', '', 'a:0:{}', 'off', 'text'),
(4, 'on', 3, 'area', 'Area', '', 'a:0:{}', 'off', 'text');
";
$res_pommo_campos = $db_pommo->Execute($sql_pommo_campos);
if($res_pommo_campos === false) die ("N�o foi poss�vel inserir na tabela pommo_fields");

include("../../setup.php");

// Busco todos os supervisores de todos os periodos para serem inseridos nas tabelas
$sql = "select supervisores.email, supervisores.id, supervisores.nome, max(estagiarios.periodo) as periodo, estagio.instituicao, areas_estagio.area
from estagiarios
join supervisores on estagiarios.id_supervisor = supervisores.id
join estagio on estagiarios.id_instituicao = estagio.id
join areas_estagio on estagiarios.id_area = areas_estagio.id
group by estagiarios.id_supervisor
order by supervisores.nome ";

// echo $sql . "<br>";
$resultado = $db->Execute($sql); 
while (!$resultado->EOF) {
	$id_supervisor = $resultado->fields['id'];
	$email = $resultado->fields['email'];
    $nome = $resultado->fields['nome'];
	$instituicao = $resultado->fields['instituicao'];
	$area = $resultado->fields['area'];
	$periodo = $resultado->fields['periodo'];

    // $i++;
    // echo $i . " " . $nome . " " . $email . " " . $periodo . " " . $instituicao . " " . $area . "<br>";

	// Somente aqueles que tem e-mail
	if ($email) {
		
		include("../../pommo_config.php");
		
		$sql_email = "insert into pommo_subscribers (email,status) values ('$email',1)";
		// echo $sql_email . "<br>";
		$res_email = $db_pommo->Execute($sql_email); 
		if($res_email === false) die ("N�o foi poss�vel inserir dados na tabela pommo_subscribers");
		$subscriber_id = $db_pommo->Insert_ID();

		$sql_email_nome = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (1,'$nome',$subscriber_id)";
		// echo $sql_email_nome . "<br>";
		$res_email_nome = $db_pommo->Execute($sql_email_nome); 
		
		$sql_email_inst = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (2,'$instituicao',$subscriber_id)";
		// echo $sql_email_inst . "<br>";
		$res_email_inst = $db_pommo->Execute($sql_email_inst); 

 		$sql_email_periodo = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (3,'$periodo',$subscriber_id)";
		// echo $sql_email_periodo . "<br>";
		$res_email_periodo = $db_pommo->Execute($sql_email_periodo);

		$sql_email_area = "insert into pommo_subscriber_data (field_id, value, subscriber_id) values (4,'$area',$subscriber_id)";
		// echo $sql_email_area . "<br>";
		$res_email_area = $db_pommo->Execute($sql_email_area); 

	}

	$resultado->MoveNext();
}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=http://web.intranet.ess.ufrj.br/pommo/'>";

?>
