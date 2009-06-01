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
$id_inst_super = $_POST['id_inst_super'];
$id_instituicao = $_POST['id_instituicao'];

// echo "id_supervisor: " . $id_supervisor . "<br>";

$ip = $_SERVER['REMOTE_ADDR'];
$data = date('Y-m-d');

echo "Inserindo supervisor<br>";
$sql  = "insert into supervisores (nome, endereco, municipio, bairro, cep, cress, regiao, email, codigo_tel, telefone, codigo_cel, celular, escola, ano_formatura, outros_estudos, area_curso, ano_curso) "; 
$sql .= " values ('$nome', '$endereco', '$municipio', '$bairro','$cep','$cress', '$regiao', '$email','$codigo_tel', '$telefone','$codigo_cel','$celular','$escola','$ano_formatura','$outros_estudos','$area_curso','$ano_curso')";
// echo $sql . "<br>";
die;
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível inserir o registro na tabela supervisores");
// die;
// Pego o número de registro de último supervisor ingressado
$res_ultimo = $db->Execute("select max(id) as ultimo_supervisor from supervisores");
if($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
$id_supervisor = $res_ultimo->fields['ultimo_supervisor'];
	
// Insero supervisor e instituicao em inst_super
if (!empty($id_instituicao)) {
    $sql_inst_super = "insert into inst_super (id_supervisor, id_instituicao) values ('$id_supervisor', '$id_instituicao')";
    $res_inst_super = $db->Execute($sql_inst_super);
    if($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");
}

$sql_log = "insert into log_supervisores (id_supervisor, cress, nome, ip) values ('$id_supervisor', '$cress', '$nome', '$ip')";
// echo $sql_log . '<br>';
$resultado_log = $db->Execute($sql_log);
if($resultado_log === false) die ("Não foi possível inserir/atualizar registro na tabela log_supervisores");    		  

// die;
echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?id_supervisor=$id_supervisor'>";

exit;

?>