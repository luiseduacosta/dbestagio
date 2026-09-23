<?php

require("../../autentica.inc");

$area_id       = $_POST["area_id"];
$instituicao   = $_POST["instituicao"];
$endereco      = $_POST["endereco"];
$cep           = $_POST["cep"];
$telefone      = $_POST["telefone"];
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

$smarty = new Smarty_estagio;

if ($instituicao) {
	if (empty($cep))
    	$cep = "0";

	$sql = "insert into instituicoes (area_id, instituicao, endereco, cep, telefone, beneficios, fim_de_semana, cnpj) ";
	$sql .= "values('$area_id','$instituicao','$endereco','$cep','$telefone', '$beneficio', '$fim_de_semana', '')";
	$resultado = $db->Execute($sql);
	if ($resultado === false) die ("Não foi possível inserir o registro na tabela instituicoes");

	/* Pego o número do último registro entrado */
	$res_ultimo = $db->Execute("select max(id) as ultimo_valor from instituicoes");
	if ($res_ultimo === false) die ("Não foi possível consultar a tabela instituicoes");
	$ultimo_registro = $res_ultimo->fields["ultimo_valor"];
} else {
	/* Pego o número do último registro entrado */
	$res_ultimo = $db->Execute("select max(id) as ultimo_valor from instituicoes");
	if ($res_ultimo === false) die ("Não foi possível consultar a tabela instituicoes");
	$ultimo_registro = $res_ultimo->fields["ultimo_valor"];
	echo "Ultima instituição " . $ultimo_registro . "<br>";
	echo "Acrescentar outro assistente social na instituição " . $instituicao_id;
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

$smarty->assign("instituicao_id",$ultimo_registro);
$smarty->assign("num_supervisor",$num_supervisor);
$smarty->assign("nome_supervisor",$nome_supervisor);
$smarty->display("supervisor_form.tpl");

?>