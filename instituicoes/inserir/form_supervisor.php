<?php

require("../../autentica.inc");

$id_area       = $_POST["id_area"];
$instituicao   = $_POST["instituicao"];
$endereco      = $_POST["endereco"];
$cep           = $_POST["cep"];
$telefone      = $_POST["telefone"];
$fax           = $_POST["fax"];
$turma         = $_POST["turma"];
$beneficio     = $_POST["beneficios"];
$fim_de_semana = $_POST["final_de_semana"];

$instituicao = strtoupper($instituicao);

$tamanho_instituicao = strlen($instituicao);
if ($tamanho_instituicao > 75) {
    echo "Endereço maior de 75 carateres (seu tamanho é $tamanho_endereco )" . "<br>";
    exit;
}

$tamanho_endereco = strlen($endereco);
if ($tamanho_endereco > 104) {
    echo "Endereço maior de 105 carateres (seu tamanho é $tamanho_endereco )" . "<br>";
    exit;
}

$tamanho_cep = strlen($cep);
if ($tamanho_cep > 9) {
    echo "Endereço maior de 9 carateres (seu tamanho é $tamanho_cep )" . "<br>";
    exit;
}

$tamanho_telefone = strlen($telefone);
if ($tamanho_telefone > 50) {
    echo "Endereço maior de 50 carateres (seu tamanho é $tamanho_telefone )" . "<br>";
    exit;
}

include_once("../../setup.php");

$smarty = new Smarty_estagio;

if ($instituicao) {
	if (empty($cep))
    	$cep = "0";

	$sql = "insert into estagio (area, instituicao, endereco, cep, telefone, fax, beneficio, fim_de_semana) ";
	$sql .= "values('$id_area','$instituicao','$endereco','$cep','$telefone','$fax', '$beneficio', '$fim_de_semana')";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("Não foi possível inserir o registro na tabela estagio");

	/* Pego o número do último registro entrado */
	$res_ultimo = $db->Execute("select max(id) as ultimo_valor from estagio");
	if ($res_ultimo === false) die ("Não foi possível consultar a tabela estagio");
	$ultimo_registro = $res_ultimo->fields["ultimo_valor"];
} else {
	/* Pego o número do último registro entrado */
	$res_ultimo = $db->Execute("select max(id) as ultimo_valor from estagio");
	if ($res_ultimo === false) die ("Não foi possível consultar a tabela estagio");
	$ultimo_registro = $res_ultimo->fields["ultimo_valor"];
	echo "Ultima instituição " . $ultimo_registro . "<br>";
	echo "Acrescentar outro assistente social na instituição " . $id_instituicao;
}

// Obtendo todos os supervisores para a caixa de selecao
$sql_supervisores = "select id, nome from supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if ($res_supervisores === false) die ("Não foi possível consultar a tabela supervisores");
$i = 0;
while (!$res_supervisores->EOF) {
    $num_supervisor[$i] = $res_supervisores->fields['id'];
    $nome_supervisor[$i] = $res_supervisores->fields['nome'];
    $res_supervisores->MoveNext();
    $i++;
}

$smarty->assign("id_instituicao",$ultimo_registro);
$smarty->assign("num_supervisor",$num_supervisor);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->display("supervisor_form.tlp");

?>