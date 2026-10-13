<?php

// include_once("../../autentica.inc");
// require_once("../../db.inc");
require_once(__DIR__ . "/../../libphp/models.php");
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
$sql .= " values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
// echo $sql . "<br>";
$resultado = Supervisor::query($sql, array(
    $nome, $endereco, $municipio, $bairro, $cep, $cress, $regiao, $email, 
    $codigo_tel, $telefone, $codigo_cel, $celular, $escola, $ano_formatura, 
    $outros_estudos, $area_curso, $ano_curso
));
if ($resultado === false) die ("Não foi possível inserir o registro na tabela supervisores");
// die;
// Pego o número de registro de último supervisor ingressado
$res_ultimo = Supervisor::query("select max(id) as ultimo_supervisor from supervisores");
if($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
$ultimo = reset($res_ultimo);
$supervisor_id = (int)$ultimo->ultimo_supervisor;
	
// Insero supervisor e instituicao em inst_super
$instituicao_id = (int)$instituicao_id;
if (!empty($instituicao_id)) {
    $sql_inst_super = "insert into inst_super (supervisor_id, instituicao_id) values (?, ?)";
    $res_inst_super = Instituicao::query($sql_inst_super, array($supervisor_id, $instituicao_id));
    if ($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");
}

$sql_log = "insert into log_supervisores (id_supervisor, cress, nome, ip) values (?, ?, ?, ?)";
// echo $sql_log . '<br>';
$resultado_log = Supervisor::query($sql_log, array($supervisor_id, $cress, $nome, $ip));
if ($resultado_log === false) die ("Não foi possível inserir/atualizar registro na tabela log_supervisores");    		  

// die;
echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?supervisor_id=$supervisor_id'>";

exit;

?>