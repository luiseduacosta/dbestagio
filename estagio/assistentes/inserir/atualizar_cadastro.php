<?php

// include_once("../../autentica.inc");
// require_once("../../db.inc");
require_once("../../setup.php");

$id_supervisor = isset($_POST['id_supervisor']) ? $_POST['id_supervisor'] : NULL;

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

// Se não foi selecionado um supervisor já existente entra nesta rotina
echo "Atualizando supervisor<br>";
$sql  = "update supervisores "; 
$sql .= " set nome='$nome', endereco='$endereco', municipio='$municipio', bairro='$bairro', cep='$cep', cress='$cress', regiao='$regiao', email='$email', codigo_tel='$codigo_tel', telefone='$telefone', codigo_cel='$codigo_cel', celular='$celular', escola='$escola', ano_formatura='$ano_formatura', outros_estudos='$outros_estudos', area_curso='$area_curso', ano_curso='$ano_curso'";
$sql .= " where id=$id_supervisor";
// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível atualizar o registro na tabela supervisores");

// Atualizo instituicao em inst_super
if ((!empty($id_instituicao)) and (!empty($id_inst_super))) {
	$sql_atualiza = "update inst_super set id_instituicao='$id_instituicao'	where id='$id_inst_super'";
	// echo $sql_atualiza . "<br>";
	$res_atualiza = $db->Execute($sql_atualiza);
	if($res_atualiza === false) die ("Não foi possível atualizar o registro na tabela inst_super");
}
// die;
$sql = "select id from log_supervisores where id_supervisor = $id_supervisor";
$resultado = $db->Execute($sql);
$quantidade = $resultado->RecordCount();
if ($quantidade > 0) {
    $sql_log = "update log_supervisores set cress='$cress', id_supervisor='$id_supervisor', nome='$nome', ip='$ip'  where id_supervisor='$id_supervisor'"; 
} else {
    $sql_log = "insert into log_supervisores (cress, id_supervisor, nome, ip) values ('$cress', '$id_supervisor', '$nome', '$ip')";
}
// $sql_log . '<br>';
$resultado_log = $db->Execute($sql_log);
if($resultado_log === false) die ("Não foi possível inserir/atualizar registro na tabela log_supervisores");    		  

// die;
echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?id_supervisor=$id_supervisor'>";

exit;

?>