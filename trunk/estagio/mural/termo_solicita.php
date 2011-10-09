<?php

// include_once("../../autentica.inc");

include_once("../setup.php");

// $origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";
// $url = $_SERVER['SERVER_NAME'];
// Se o programa foi chamado desde seleciona.php retorna a ele proprio
// if (substr_count($origem,"seleciona.php") == 1) {
//	$origem = $_SERVER['PHP_SELF'];	
// } elseif (substr_count($origem,"listar_dae.php") == 1) {
//	$origem = "http://$url/estagio/alunos/exibir/listar_dae.php";
//}

// if(empty($origem))
//    $origem = $_SERVER['HTTP_REFERER'];

if ($debug == 1) {
    echo $origem . "<br>";
    echo $_SERVER['PHP_SELF'] . "<br>";
}

$submit = $_REQUEST['submit'];
// Alunos
$id_aluno        = $_REQUEST['id_aluno'];
$registro        = $_REQUEST['registro'];
$nome            = $_REQUEST['nome'];
$codigo_telefone = $_REQUEST['codigo_telefone'];
$telefone        = $_REQUEST['telefone'];
$codigo_celular  = $_REQUEST['codigo_celular'];
$celular         = $_REQUEST['celular'];
$email           = $_REQUEST['email'];
$identidade      = $_REQUEST['identidade'];
$orgao           = $_REQUEST['orgao'];
$cpf             = $_REQUEST['cpf'];
$nascimento      = $_REQUEST['nascimento'];
$endereco        = $_REQUEST['endereco'];
$cep             = $_REQUEST['cep'];
$bairro          = $_REQUEST['bairro'];
$municipio       = $_REQUEST['municipio'];
// $observacoes     = $_REQUEST['observacoes'];
$nivel = $_REQUEST['nivel'];
$id_instituicao = $_REQUEST['id_instituicao'];
$id_supervisor = $_REQUEST['id_supervisor'];
// echo $nascimento . "<br>";


if ($debug == 1) {
    // print_r($_REQUEST) . "<br>";
	}

$acao     = $_REQUEST['acao'];
$envio    = $_REQUEST['submit'];
$cadastro = $_REQUEST['valorcadastro'];

if ($debug == 1) {
    echo "Acao " . $acao . "<br>";
    echo "Cadastro " . $cadastro . "<br>";
    echo "Atualizar estagio ". $atualizar_estagio . "<br>";
}

// echo "Id estagiario " . $id_estagiarios . " - " . $_REQUEST['id_estagiarios'] . "<br>";

// Se ja esta cadastrado
if ($submit) {

	if (empty($nivel)) die ("É obrigatório preencher o nivel de estágio para o qual está solicitando o termo de compromisso");

	if (empty($id_instituicao)) die ("É obrigatório selecionar a instituição para o qual está solicitando o termo de compromisso");

	// Para salvar tenho que utilizar o formato aaaa/mm/dd/
	$data_nascimento = date("Y-m-d",strtotime($nascimento));

	$sql_alunos  = "update alunos set registro ='$registro', nome ='$nome', codigo_telefone ='$codigo_telefone', ";
	$sql_alunos .= " telefone ='$telefone', codigo_celular = '$codigo_celular', celular='$celular', email='$email', ";
	$sql_alunos .= " identidade = '$identidade', orgao='$orgao', cpf = '$cpf', nascimento='$data_nascimento', ";
	$sql_alunos .= " endereco='$endereco', cep='$cep', bairro='$bairro', municipio='$municipio', ";
	$sql_alunos .= " observacoes='$observacoes' ";
	$sql_alunos .= " where registro='$registro'";
	// echo $sql_alunos . "<br>";

	// $resultado_insere = $db->Execute($sql_alunos);
	// if($resultado_insere === false) die ("Nao foi possivel atualizar o registro na tabela alunos");
	$sql_instituicao = "select instituicao from estagio where id=$id_instituicao";
	$res_instituicao = $db->Execute($sql_instituicao);
	$instituicao = $res_instituicao->fields['instituicao'];

	$sql_supervisor = "select nome from supervisores where id=$id_supervisor";
	$resultado_supervisor = $db->Execute($sql_supervisor);
	$supervisor = $resultado_supervisor->fields['nome'];
	$data = date("d-m-Y");
	require("../../imprime/termo.php");
/*
	$sql = "select registro from termo where registro=$registro";
	$resultado = $db->Execute($sql);
	$quantidade = $resultado->RecordCount();
	if($quantidade > 0) echo "Termo de compromisso ja foi solicitado";
*/
	$sql_termo  = "insert into termo('registro','nivel','id_instituicao','id_supervisor','data') ";
	$sql_termo .= " values('$registro','$nivel','$id_instituicao','$id_supervisor','$data')";
	// echo $sql_termo . "<br>";
	// $resultado_termo = $db->Execute($sql_termo);
	// if($resultado_termo === false) die ("Nao foi possivel inserir o registro na tabela termo");
	// header("Location: cadastro_termo.php?registro=$registro");
	// die;
	// exit;

}

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro='$registro'";
// echo $sql . "<br>";


if ($debug == 1)
    echo $sql . "<br>";

$resultado = $db->Execute($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
// Verifico se o aluno está cadastrado
$quantidade = $resultado->RecordCount();

if ($quantidade == 0) {

	$sql_novo = "select * from alunosNovos";
	$resultado_novo = $db->Execute($sql_novo);
	if ($resultado_novo === false) die ("Nao foi possivel consultar a tabela alunosNovos");
	$quantidade_novo = $resultado_novo->RecordCount();
	if ($quantidade_novo == 0) {
	    echo "Aluno não cadastrado em estágio <br />";
	    echo "Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a>";
	    exit;
	}

	while (!$resultado_novo->EOF) {
	    // $id_aluno = $resultado->fields['id'];
	    $registro = $resultado_novo->fields['registro'];
	    $nome = $resultado_novo->fields['nome'];
	    $codigo_telefone = $resultado_novo->fields['codigo_telefone'];
	    $telefone = $resultado_novo->fields['telefone'];
	    $codigo_celular = $resultado_novo->fields['codigo_celular'];
	    $celular = $resultado_novo->fields['celular'];
	    $email = $resultado_novo->fields['email'];
	    $cpf = $resultado_novo->fields['cpf'];
	    $identidade = $resultado_novo->fields['identidade'];
	    $orgao = $resultado_novo->fields['orgao'];
	    $nascimento = $resultado_novo->fields['nascimento'];
	    // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
	    $nova_data = ereg_replace("-","/",$nascimento);
	    // echo "Nova data: ". $nova_data . "<br>";
	    $dataCorrigida = explode("/",$nova_data);
	    $data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
	    // echo $data_sql . "<br>";

	    $endereco = $resultado_novo->fields['endereco'];
	    $cep = $resultado_novo->fields['cep'];
	    $bairro = $resultado_novo->fields['bairro'];
	    $municipio = $resultado_novo->fields['municipio'];
	    $observacoes = $resultado_novo->fields['observacoes'];
	    // echo $observacoes . "<br>";
	    $aluno_novo = 0; // Aluno buscando estagio
	    $resultado_novo->MoveNext();
	}
}

while (!$resultado->EOF) {
	// $id_aluno = $resultado->fields['id'];
	$registro = $resultado->fields['registro'];
	$nome = $resultado->fields['nome'];
	$codigo_telefone = $resultado->fields['codigo_telefone'];
	$telefone = $resultado->fields['telefone'];
	$codigo_celular = $resultado->fields['codigo_celular'];
	$celular = $resultado->fields['celular'];
	$email = $resultado->fields['email'];
	$cpf = $resultado->fields['cpf'];
	$identidade = $resultado->fields['identidade'];
	$orgao = $resultado->fields['orgao'];
	$nascimento = $resultado->fields['nascimento'];
	// Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
	$nova_data = ereg_replace("-","/",$nascimento);
	// echo "Nova data: ". $nova_data . "<br>";
	$dataCorrigida = explode("/",$nova_data);
	$data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
	// echo $data_sql . "<br>";

	$endereco = $resultado->fields['endereco'];
	$cep = $resultado->fields['cep'];
	$bairro = $resultado->fields['bairro'];
	$municipio = $resultado->fields['municipio'];
	$observacoes = $resultado->fields['observacoes'];
	$aluno_novo = 1; // Aluno estagiando
	// echo $observacoes . "<br>";
	$resultado->MoveNext();
}

// Capturo as instituicoes
$sql_instituicoes = "select id, instituicao from estagio order by instituicao";
$resposta_instituicoes = $db->Execute($sql_instituicoes);
if ($resposta_instituicoes === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 1;
while (!$resposta_instituicoes->EOF) {
    $instituicoes[$i]['id'] = $resposta_instituicoes->fields['id'];
    $instituicoes[$i]['instituicao'] = $resposta_instituicoes->fields['instituicao'];
    $resposta_instituicoes->MoveNext();
    $i++;
}

// Capturo os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resposta_supervisores = $db->Execute($sql_supervisores);
if ($resposta_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 1;
while (!$resposta_supervisores->EOF) {
    $supervisores[$i]['id']   = $resposta_supervisores->fields['id'];
    $supervisores[$i]['nome'] = $resposta_supervisores->fields['nome'];
    $resposta_supervisores->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("origem",$origem);
// Aluno
$smarty->assign("id_aluno",$id_aluno);
$smarty->assign("registro",$registro);
$smarty->assign("aluno_nome",$nome);
$smarty->assign("codigo_telefone",$codigo_telefone);
$smarty->assign("telefone",$telefone);
$smarty->assign("codigo_celular",$codigo_celular);
$smarty->assign("celular",$celular);
$smarty->assign("email",$email);
$smarty->assign("cpf",$cpf);
$smarty->assign("identidade",$identidade);
$smarty->assign("orgao",$orgao);
$smarty->assign("nascimento",$data_sql);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("bairro",$bairro);
$smarty->assign("municipio",$municipio);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("aluno_novo",$aluno_novo);
// Instituicoes
$smarty->assign("instituicoes",$instituicoes);
// Supervisores
$smarty->assign("supervisores",$supervisores);

$smarty->display("alunos-atualizar_atualiza_termo.tpl");
// $smarty->display("mural-termo_solicita.tpl");

exit;

?>
