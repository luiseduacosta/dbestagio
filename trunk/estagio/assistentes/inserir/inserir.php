<?php

include_once("../../autentica.inc");
// require_once("../../db.inc");
require_once("../../setup.php");

$num_instituicao = isset($_POST['num_instituicao']) ? $_POST['num_instituicao'] : NULL;
$id_supervisor  = isset($_POST['id_supervisor']) ? $_POST['id_supervisor'] : NULL;

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

// echo "id_instituicao: " . $id_instituicao . "<br>";
// echo " Num. super " . $num_supervisor = $_REQUEST['num_supervisor'];

// Se não foi selecionado um supervisor já existente entra nesta rotina
if (empty($num_supervisor)) {
    echo "Inserindo supervisor<br>";
    $sql  = "insert into supervisores (nome, endereco, municipio, bairro, cep, cress, regiao, email, codigo_tel, telefone, codigo_cel, celular, escola, ano_formatura, outros_estudos, area_curso, ano_curso) "; 
    $sql .= " values ('$nome', '$endereco', '$municipio', '$bairro','$cep','$cress', '$regiao', '$email','$codigo_tel', '$telefone','$codigo_cel','$celular','$escola','$ano_formatura','$outros_estudos','$area_curso','$ano_curso')";
    echo "Insirindo novo supervisor " . $sql . "<br>";
    $resultado = $db->Execute($sql);
    if($resultado === false) die ("Não foi possível inserir o registro na tabela supervisores");
    // die;
    // Pego o número de registro de último supervisor ingressado
    $res_ultimo = $db->Execute("select max(id) as ultimo_supervisor from supervisores");
    if($res_ultimo === false) die ("Não foi possível consultar a tabela supervisores");
    $id_supervisor = $res_ultimo->fields['ultimo_supervisor'];

    // Insero supervisor e instituicao em inst_super
    // $sql_inst_super = "insert into inst_super (id_supervisor, id_instituicao) values ('$id_supervisor', '$id_instituicao')";
    // $res_inst_super = $db->Execute($sql_inst_super);
    // if($res_inst_super === false) die ("Não foi possível inserir o registro na tabela inst_super");
} else {
    echo "Atualizando supervisor<br>";
    $sql  = "update supervisores "; 
    $sql .= " set nome='$nome', endereco='$endereco', municipio='$municipio', bairro='$bairro', cep='$cep', cress='$cress', regiao='$regiao', email='$email', codigo_tel='$codigo_tel', telefone='$telefone', codigo_cel='$codigo_cel', celular='$celular', escola='$escola', ano_formatura='$ano_formatura', outros_estudos='$outros_estudos', area_curso='$area_curso', ano_curso='$ano_curso'";
    $sql .= " where id=$_POST[num_supervisor]";
    echo $sql . "<br>";
    $resultado = $db->Execute($sql);
    if($resultado === false) die ("Não foi possível atualizar o registro na tabela supervisores");
    $id_supervisor = $_POST['num_supervisor'];

}

echo "<meta HTTP-EQUIV='refresh' CONTENT='0,URL=../exibir/ver_cada.php?id_supervisor=$id_supervisor'>";

exit;

?>
