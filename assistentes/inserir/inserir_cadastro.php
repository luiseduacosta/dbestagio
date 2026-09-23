<?php

// include_once("../../autentica.inc");
// require_once("../../db.inc");
require_once("../../setup.php");

// $id_supervisor = isset($_POST['id_supervisor']) ? $_POST['id_supervisor'] : NULL;

$cress = $_POST['cress'];
$regiao = $_POST['regiao'];
$nome  = $_POST['nome'];
$endereco = $_POST['endereco'];
$bairro = $_POST['bairro'];
$municipio = $_POST['municipio'];
$cep = $_POST['cep'];
$email = $_POST['email'];
$codigo_tel = $_POST['codigo_tel'];
$telefone = $_POST['telefone'];
$codigo_cel = $_POST['codigo_cel'];
$celular = $_POST['celular'];
$escola = $_POST['escola'];
$ano_formatura = $_POST['ano_formatura'];
$outros_estudos = $_POST['outros_estudos'];
$area_curso = $_POST['area_curso'];
$ano_curso = $_POST['ano_curso'];
$inst_super_id = $_POST['inst_super_id'];
$instituicao_id = $_POST['instituicao_id'];

// echo "id_supervisor: " . $id_supervisor . "<br>";

$ip = $_SERVER['REMOTE_ADDR'];
$data = date('Y-m-d');

echo "Inserindo supervisor<br>";
$sql  = "insert into supervisores (nome, endereco, municipio, bairro, cep, cress, regiao, email, codigo_tel, telefone, codigo_cel, celular, escola, ano_formatura, outros_estudos, area_curso, ano_curso) "; 
$sql .= " values ('$nome', '$endereco', '$municipio', '$bairro','$cep','$cress', '$regiao', '$email','$codigo_tel', '$telefone','$codigo_cel','$celular','$escola','$ano_formatura','$outros_estudos','$area_curso','$ano_curso')";
// echo $sql . "<br>";
die;
$resultado = $db->Execute($sql);
if ($resultado === false) die ("Não foi possível inserir o registro na tabela supervisores");
// die;
// Pego o nmero de registro de ltimo supervisor ingressado
$res_ultimo = $db->Execute("select max(id) as ultimo_supervisor from supervisores");
if($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
$supervisor_id = $res_ultimo->fields['ultimo_supervisor'];
	
// Insero supervisor e instituicao em inst_super
if (!empty($instituicao_id)) {
    $sql_inst_super = "insert into inst_super (supervisor_id, instituicao_id) values ('$supervisor_id', '$instituicao_id')";
    $res_inst_super = $db->Execute($sql_inst_super);
    if ($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");
}

$sql_log = "insert into log_supervisores (id_supervisor, cress, nome, ip) values ('$supervisor_id', '$cress', '$nome', '$ip')";
// echo $sql_log . '<br>';
$resultado_log = $db->Execute($sql_log);
if ($resultado_log === false) die ("Não foi possível inserir/atualizar registro na tabela log_supervisores");    		  

// die;
echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?supervisor_id=$supervisor_id'>";

exit;

?>