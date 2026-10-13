<?php
require_once(__DIR__ . "/../../libphp/models.php");

if (isset($debug) && $debug == 1)
	echo $_SERVER['PHP_SELF'] . "<br>";

include_once("../../autentica.inc");

// Cast all numeric inputs to int to prevent SQL injection
$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

// Initialize all variables to prevent undefined warnings
$registro = isset($_POST['registro']) ? $_POST['registro'] : '';
$nome     = isset($_POST['nome']) ? $_POST['nome'] : '';
$telefone = isset($_POST['telefone']) ? $_POST['telefone'] : '';
$celular  = isset($_POST['celular']) ? $_POST['celular'] : '';
$email    = isset($_POST['email']) ? $_POST['email'] : '';

$periodo        = isset($_POST['periodo']) ? $_POST['periodo'] : '';
$nivel          = isset($_POST['nivel']) ? $_POST['nivel'] : '';
$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
$supervisor_id  = isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0;

// Initialize arrays to prevent undefined warnings
$estagiarios = array();
$instituicoes = array();
$supervisores = array();

// Pego esta informacao para fazer a tabela dos anteriores estagios
$sql  = "SELECT e.id, e.periodo, e.nivel, e.instituicao_id, e.supervisor_id, i.instituicao ";
$sql .= "FROM estagiarios e, instituicoes i ";
$sql .= "WHERE e.instituicao_id = i.id AND e.aluno_id = ? ";
$sql .= "ORDER BY e.periodo";
// Use model query instead of raw $db->Execute with parameter binding
$resultado = Estagiario::query($sql, array($aluno_id));
if ($resultado === false) die ("Nao foi possivel consultar as tabelas alunos, estagiarios, instituicoes");
$i = 0;
// Replace legacy EOF loop with modern foreach loop
foreach ($resultado as $item) {
    $estagiarios[$i]['id']             = (int)$item->id;
    $estagiarios[$i]['periodo']        = $item->periodo;
    $estagiarios[$i]['nivel']          = $item->nivel;   
    $estagiarios[$i]['instituicao_id'] = (int)$item->instituicao_id;
    $estagiarios[$i]['supervisor_id']  = isset($item->supervisor_id) ? (int)$item->supervisor_id : 0;
    $estagiarios[$i]['instituicao']    = $item->instituicao;

    $current_supervisor_id = $estagiarios[$i]['supervisor_id'];
    if (empty($current_supervisor_id))
        $current_supervisor_id = 0;

    $sql_supervisor  = "select s.id, s.cress, s.nome, s.email ";
    $sql_supervisor .= "from supervisores s ";
    $sql_supervisor .= "where s.id=? ";
    $sql_supervisor .= "order by s.nome";
    // Use parameterized query with model method
    $resultado_supervisor = Supervisor::query($sql_supervisor, array($current_supervisor_id));
    while ($resultado_supervisor && !$resultado_supervisor->EOF) {
        $supervisor_item = $resultado_supervisor->fetch();
        $estagiarios[$i]['supervisor_nome']  = $supervisor_item['nome'];
        $estagiarios[$i]['supervisor_cress'] = $supervisor_item['cress'];
        $estagiarios[$i]['supervisor_email'] = $supervisor_item['email'];
    }
    $i++;
}

// Capturo a informacao sobre os alunos
$aluno_registro = '';
$aluno_nome = '';
$aluno_telefone = '';
$aluno_celular = '';
$aluno_email = '';
$sql_alunos = "select id, registro, nome, telefone, celular, email from alunos where id=?";
$resultado_alunos = Aluno::query($sql_alunos, array($aluno_id));
if ($resultado_alunos === false) die ("Nao foi possivel consultar a tabela alunos");
foreach ($resultado_alunos as $item) {
    $aluno_id       = (int)$item->id;
    $aluno_registro = $item->registro;
    $aluno_nome     = $item->nome;
    $aluno_telefone = $item->telefone;
    $aluno_celular  = $item->celular;
    $aluno_email    = $item->email;
}

// Capturo a informacao sobre as instituicooes
$sql = "select id, instituicao from instituicoes order by instituicao";
$resultado = Instituicao::query($sql);
if ($resultado === false) die ("Nao foi possivel consultar a tabela instituicoes");
$i = 0;
foreach ($resultado as $item) {
    $instituicoes[$i]['instituicao_id'] = (int)$item->id;
    $instituicoes[$i]['instituicao']    = $item->instituicao;
    $i++;
}

// Capturo a informacao sobre os supervisores
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = Supervisor::query($sql_supervisores);
if ($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
foreach ($resultado_supervisores as $item) {
    $supervisores[$i]['supervisor_id'] = (int)$item->id;
    $supervisores[$i]['supervisor']    = $item->nome;
    $i++;
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estagio
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$aluno_registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("telefone",$aluno_telefone);
$smarty->assign("celular",$aluno_celular);
$smarty->assign("email",$aluno_email);

$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);

$smarty->display("alunos-atualizar_modificar_estagio.tpl");

exit;

?>