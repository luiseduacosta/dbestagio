<?php

include_once("../../autentica.inc");

$id_instituicao        = $_POST['id_instituicao'];
$area_instituicao      = $_POST['area_instituicao'];
$nome_instituicao      = $_POST['nome_instituicao'];
$endereco_instituicao  = $_POST['endereco_instituicao'];
$cep_instituicao       = $_POST['cep_instituicao'];
$telefone_instituicao  = $_POST['telefone_instituicao'];
$fax_instituicao       = $_POST['fax_instituicao'];
$beneficio_instituicao = $_POST['beneficio_instituicao'];
$fim_de_semana         = $_POST['fim_de_semana'];
$convenio              = $_POST['convenio'];
// $turma                 = $_POST['turma'];
// $nova_turma            = $_POST['nova_turma'];

$tamanho_instituicao = strlen($nome_instituicao);
if($tamanho_instituicao > 75) {
    echo "Endereço maior de 75 carateres (seu tamanho é $tamanho_instituicao )" . "<br>";
    exit;
}

$tamanho_endereco = strlen($endereco_instituicao);
if($tamanho_endereco > 104) {
    echo "Endereço maior de 105 carateres (seu tamanho é $tamanho_endereco )" . "<br>";
    exit;
}

$tamanho_cep = strlen($cep_instituicao);
if($tamanho_cep > 9) {
    echo "Endereço maior de 9 carateres (seu tamanho é $tamanho_cep )" . "<br>";
    exit;
}

include_once("../../db.inc");

$sql = "update estagio set area='$area_instituicao', instituicao='$nome_instituicao', endereco='$endereco_instituicao', cep='$cep_instituicao', telefone='$telefone_instituicao', fax='$fax_instituicao', beneficio='$beneficio_instituicao', fim_de_semana='$fim_de_semana', convenio='$convenio' where id='$id_instituicao'";
$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível atualizar a tabela estagio");

header("Location:../exibir/ver_cada.php?id_instituicao=$id_instituicao");

exit;

?>