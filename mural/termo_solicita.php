<?php
require_once(__DIR__ . "/../libphp/models.php");

// include_once("../../autentica.inc");

include_once("../setup.php");

// $origem = $_REQUEST['origem'];
// echo $_SERVER['PHP_SELF'] . " " . $origem . "<br>";
// $url = $_SERVER['SERVER_NAME'];

// if(empty($origem))
//    $origem = $_SERVER['HTTP_REFERER'];

if ($debug == 1) {
    echo $origem . "<br>";
    echo $_SERVER['PHP_SELF'] . "<br>";
}

$submit = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : '';
// Alunos
$id_aluno        = isset($_REQUEST['id_aluno']) ? (int)$_REQUEST['id_aluno'] : 0;
$registro        = isset($_REQUEST['registro']) ? $_REQUEST['registro'] : '';
$nome            = isset($_REQUEST['nome']) ? $_REQUEST['nome'] : '';
$codigo_telefone = isset($_REQUEST['codigo_telefone']) ? $_REQUEST['codigo_telefone'] : '';
$telefone        = isset($_REQUEST['telefone']) ? $_REQUEST['telefone'] : '';
$codigo_celular  = isset($_REQUEST['codigo_celular']) ? $_REQUEST['codigo_celular'] : '';
$celular         = isset($_REQUEST['celular']) ? $_REQUEST['celular'] : '';
$email           = isset($_REQUEST['email']) ? $_REQUEST['email'] : '';
$identidade      = isset($_REQUEST['identidade']) ? $_REQUEST['identidade'] : '';
$orgao           = isset($_REQUEST['orgao']) ? $_REQUEST['orgao'] : '';
$cpf             = isset($_REQUEST['cpf']) ? $_REQUEST['cpf'] : '';
$nascimento      = isset($_REQUEST['nascimento']) ? $_REQUEST['nascimento'] : '';
$endereco        = isset($_REQUEST['endereco']) ? $_REQUEST['endereco'] : '';
$cep             = isset($_REQUEST['cep']) ? $_REQUEST['cep'] : '';
$bairro          = isset($_REQUEST['bairro']) ? $_REQUEST['bairro'] : '';
$municipio       = isset($_REQUEST['municipio']) ? $_REQUEST['municipio'] : '';
$observacoes     = isset($_REQUEST['observacoes']) ? $_REQUEST['observacoes'] : '';
$nivel = isset($_REQUEST['nivel']) ? $_REQUEST['nivel'] : '';
$id_instituicao = isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0;
$id_supervisor = isset($_REQUEST['id_supervisor']) ? (int)$_REQUEST['id_supervisor'] : 0;
// echo $nascimento . "<br>";


if ($debug == 1) {
    // print_r($_REQUEST) . "<br>";
	}

$acao     = isset($_REQUEST['acao']) ? $_REQUEST['acao'] : '';
$envio    = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : '';
$cadastro = isset($_REQUEST['valorcadastro']) ? $_REQUEST['valorcadastro'] : '';
$atualizar_estagio = isset($_REQUEST['atualizar_estagio']) ? (int)$_REQUEST['atualizar_estagio'] : 0;

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

	// Update student record (using Aluno model for consistency, even though this line was commented in original)
	$sql_alunos  = "update alunos set registro = ?, nome = ?, codigo_telefone = ?, ";
	$sql_alunos .= " telefone = ?, codigo_celular = ?, celular=?, email=?, ";
	$sql_alunos .= " identidade = ?, orgao=?, cpf = ?, nascimento=?, ";
	$sql_alunos .= " endereco=?, cep=?, bairro=?, municipio=?, ";
	$sql_alunos .= " observacoes=? ";
	$sql_alunos .= " where registro=?";
	Aluno::query($sql_alunos, array($registro, $nome, $codigo_telefone, $telefone, $codigo_celular, $celular, $email, $identidade, $orgao, $cpf, $data_nascimento, $endereco, $cep, $bairro, $municipio, $observacoes, $registro));
	
	// Get institution name
	$sql_instituicao = "select instituicao from instituicoes where id=?";
	$res_instituicao = Instituicao::query($sql_instituicao, array($id_instituicao));
	$instituicao = '';
	foreach ($res_instituicao as $item) {
	    $instituicao = $item->instituicao;
	}

	// Get supervisor name
	$sql_supervisor = "select nome from supervisores where id=?";
	$resultado_supervisor = Supervisor::query($sql_supervisor, array($id_supervisor));
	$supervisor = '';
	foreach ($resultado_supervisor as $item) {
	    $supervisor = $item->nome;
	}
	$data = date("d-m-Y");
	require("../../imprime/termo.php");
/*
	$sql = "select registro from termo where registro=?";
	$resultado = Termo::query($sql, array($registro));
	$quantidade = $resultado->RecordCount();
	if($quantidade > 0) echo "Termo de compromisso ja foi solicitado";
*/
	// Insert new termo record
	$sql_termo  = "insert into termo(registro,nivel,id_instituicao,id_supervisor,data) ";
	$sql_termo .= " values(?,?,?,?,?)";
	Termo::query($sql_termo, array($registro, $nivel, $id_instituicao, $id_supervisor, $data));

}

// Aluno
$sql  = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, cpf, identidade, orgao, nascimento, ";
$sql .= "endereco, cep, bairro, municipio, observacoes from alunos where registro=?";

if ($debug == 1)
    echo $sql . "<br>";

$resultado = Aluno::query($sql, array($registro));
if ($resultado === false) die ("Nao foi possivel consultar a tabela alunos");
// Verifico se o aluno está cadastrado
$quantidade = $resultado->RecordCount();
$aluno_novo = 0;

if ($quantidade == 0) {

	$sql_novo = "select * from alunos";
	$resultado_novo = Aluno::query($sql_novo);
	if ($resultado_novo === false) die ("Nao foi possivel consultar a tabela alunos");
	$quantidade_novo = $resultado_novo->RecordCount();
	if ($quantidade_novo == 0) {
	    echo "Aluno não cadastrado em estágio <br />";
	    echo "Favor enviar um e-mail para <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a>";
	    exit;
	}

	foreach ($resultado_novo as $item) {
	    // $id_aluno = $resultado->fields['id'];
	    $registro = $item->registro;
	    $nome = $item->nome;
	    $codigo_telefone = $item->codigo_telefone;
	    $telefone = $item->telefone;
	    $codigo_celular = $item->codigo_celular;
	    $celular = $item->celular;
	    $email = $item->email;
	    $cpf = $item->cpf;
	    $identidade = $item->identidade;
	    $orgao = $item->orgao;
	    $nascimento = $item->nascimento;
	    // Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
	    $nova_data = ereg_replace("-","/",$nascimento);
	    // echo "Nova data: ". $nova_data . "<br>";
	    $dataCorrigida = explode("/",$nova_data);
	    $data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
	    // echo $data_sql . "<br>";

	    $endereco = $item->endereco;
	    $cep = $item->cep;
	    $bairro = $item->bairro;
	    $municipio = $item->municipio;
	    $observacoes = $item->observacoes;
	    // echo $observacoes . "<br>";
	    $aluno_novo = 0; // Aluno buscando estagio
	}
}

foreach ($resultado as $item) {
	// $id_aluno = $resultado->fields['id'];
	$registro = $item->registro;
	$nome = $item->nome;
	$codigo_telefone = $item->codigo_telefone;
	$telefone = $item->telefone;
	$codigo_celular = $item->codigo_celular;
	$celular = $item->celular;
	$email = $item->email;
	$cpf = $item->cpf;
	$identidade = $item->identidade;
	$orgao = $item->orgao;
	$nascimento = $item->nascimento;
	// Transformo a data do BD de aaaa-mm-dd para dd/mm/aaaa
	$nova_data = ereg_replace("-","/",$nascimento);
	// echo "Nova data: ". $nova_data . "<br>";
	$dataCorrigida = explode("/",$nova_data);
	$data_sql = $dataCorrigida[2] . "/" . $dataCorrigida[1] . "/" . $dataCorrigida[0];
	// echo $data_sql . "<br>";

	$endereco = $item->endereco;
	$cep = $item->cep;
	$bairro = $item->bairro;
	$municipio = $item->municipio;
	$observacoes = $item->observacoes;
	$aluno_novo = 1; // Aluno estagiando
	// echo $observacoes . "<br>";
}

// Capturo as instituicoes
$sql_instituicoes = "select id, instituicao from instituicoes order by instituicao";
$resposta_instituicoes = Instituicao::query($sql_instituicoes);
if ($resposta_instituicoes === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 1;
$instituicoes = array();
foreach ($resposta_instituicoes as $item) {
    $instituicoes[$i]['id'] = (int)$item->id;
    $instituicoes[$i]['instituicao'] = $item->instituicao;
    $i++;
}

// Capturo os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resposta_supervisores = Supervisor::query($sql_supervisores);
if ($resposta_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 1;
$supervisores = array();
foreach ($resposta_supervisores as $item) {
    $supervisores[$i]['id']   = (int)$item->id;
    $supervisores[$i]['nome'] = $item->nome;
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