<?php
require_once(__DIR__ . "/../../libphp/models.php");
include_once("../../autentica.inc");

$submit   = isset($_REQUEST['submit']) ? $_REQUEST['submit'] : NULL;
$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

$registro       = isset($_POST['registro']) ? $_POST['registro'] : '';
$nome           = isset($_POST['nome']) ? $_POST['nome'] : '';
$periodo        = isset($_POST['periodo']) ? $_POST['periodo'] : '';
$tc             = isset($_POST['tc']) ? $_POST['tc'] : '';
$nivel          = isset($_POST['nivel']) ? $_POST['nivel'] : '';
$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
$supervisor_id  = isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0;
$professor_id   = isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : 0;
$nota           = isset($_POST['nota']) ? $_POST['nota'] : '';
$ch             = isset($_POST['ch']) ? $_POST['ch'] : '';

if ($submit) {
    $sql_estagiarios  = "insert into estagiarios(aluno_id, registro, nivel, tc, instituicao_id, supervisor_id, professor_id, periodo) ";
    $sql_estagiarios .= "values(?, ?, ?, ?, ?, ?, ?, ?)";
    Estagiario::query($sql_estagiarios, array($aluno_id, $registro, $nivel, $tc, $instituicao_id, $supervisor_id, $professor_id, $periodo));
}

// Pego esta informação para fazer a tabela dos anteriores estágios
$estagiarios = array();
$sql  = "SELECT e.id, e.periodo, e.tc, e.nivel, e.instituicao_id, e.supervisor_id, e.professor_id, e.nota, e.ch, i.instituicao ";
$sql .= "FROM estagiarios e, instituicoes i ";
$sql .= "WHERE e.instituicao_id = i.id AND e.aluno_id = ? ";
$sql .= "ORDER BY e.periodo";
$resultado = Estagiario::query($sql, array($aluno_id));
if ($resultado === false) die ("Não foi possível consultar as tabelas alunos, estagiarios, instituicoes");
$i = 0;
foreach ($resultado as $item) {
    $estagiarios[$i]['id']             = (int)$item->id;
    $estagiarios[$i]['periodo']        = $item->periodo;
    $estagiarios[$i]['tc']             = $item->tc;
    $estagiarios[$i]['nivel']          = $item->nivel;
    $estagiarios[$i]['instituicao_id'] = (int)$item->instituicao_id;
    $estagiarios[$i]['supervisor_id']  = (int)$item->supervisor_id;
    $estagiarios[$i]['professor_id']   = (int)$item->professor_id;
    $estagiarios[$i]['nota']           = $item->nota;
    $estagiarios[$i]['ch']             = $item->ch;
    $estagiarios[$i]['instituicao']    = $item->instituicao;

    $current_supervisor_id = (int)$item->supervisor_id;
    if (empty($current_supervisor_id))
        $current_supervisor_id = 0;

    if ($current_supervisor_id > 0) {
        $sql_supervisor  = "select supervisores.id, supervisores.cress, supervisores.nome, supervisores.email ";
        $sql_supervisor .= "from supervisores ";
        $sql_supervisor .= "where supervisores.id=? ";
        $sql_supervisor .= "order by supervisores.nome";
        $resultado_supervisor = Supervisor::query($sql_supervisor, array($current_supervisor_id));
        foreach ($resultado_supervisor as $sup_item) {
            $estagiarios[$i]['supervisor_nome']  = $sup_item->nome;
            $estagiarios[$i]['supervisor_cress'] = $sup_item->cress;
            $estagiarios[$i]['supervisor_email'] = $sup_item->email;
        }
    }
    $i++;
}

// Alunos
$aluno_registro = '';
$aluno_nome = '';
$sql_alunos = "select id, registro, nome from alunos where id=?";
$resultado_alunos = Aluno::query($sql_alunos, array($aluno_id));
if ($resultado_alunos === false) die ("Não foi possível consultar a tabela alunos");
foreach ($resultado_alunos as $item) {
    $aluno_registro = $item->registro;
    $aluno_nome     = $item->nome;
}

// Capturo a informacao sobre as instituicoes
$instituicoes = array();
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
$supervisores = array();
$sql_supervisores = "select id, nome from supervisores order by nome";
$resultado_supervisores = Supervisor::query($sql_supervisores);
if ($resultado_supervisores === false) die ("Nao foi possivel consultar a tabela supervisores");
$i = 0;
foreach ($resultado_supervisores as $item) {
    $supervisores[$i]['supervisor_id'] = (int)$item->id;
    $supervisores[$i]['supervisor']    = $item->nome;
    $resultado_supervisores->MoveNext();
    $i++;
}

// Capturo a informacao sobre os professores
$professores = array();
$sql_professores = "select id, nome from professores order by nome";
$resultado_professores = Professor::query($sql_professores);
if ($resultado_professores === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
foreach ($resultado_professores as $item) {
    $professores[$i]['professor_id'] = (int)$item->id;
    $professores[$i]['professor']    = $item->nome;
    $i++;
}

$smarty = new Smarty_estagio;

// Tabela de estagios anteriores
$smarty->assign("estagiarios",$estagiarios);
// Tabela inserir novo estágio
$smarty->assign("aluno_id",$aluno_id);
$smarty->assign("registro",$aluno_registro);
$smarty->assign("aluno_nome",$aluno_nome);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("professores",$professores);

$smarty->display("alunos-inserir_acrescentar_estagio.tpl");

exit;

?>