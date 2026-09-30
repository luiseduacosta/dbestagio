<?php

include_once(__DIR__ . "/../../setup.php");
// include_once('../../autentica.inc');

// Autenticacao desabilitada nesta pagina (mantendo comportamento anterior):
// sem sessao autenticada, o template oculta as colunas de e-mail/celular/telefone.
$sistema_autentica = 0;

$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';
$turma = isset($_GET['turma']) ? $_GET['turma'] : NULL;
$instituicao_id = isset($_GET['instituicao_id']) ? intval($_GET['instituicao_id']) : NULL;

// Periodos existentes: alimentam o filtro da pagina e servem de lista
// fechada de valores aceitos para $turma (protecao contra SQL injection).
$periodos = array();
$res_turma = $db->Execute("select distinct periodo from estagiarios order by periodo");
if ($res_turma === false) die("Não foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
    $periodos[] = $res_turma->fields['periodo'];
    $res_turma->MoveNext();
}

// So aceita periodo que existe no banco
if (!empty($turma) && !in_array($turma, $periodos, true)) {
    $turma = NULL;
}

// Ordenacao: lista fechada de colunas permitidas
$ordens = array(
    'nome'        => 's.nome',
    'cress'       => 's.cress',
    'q_periodos'  => 'q_periodos',
    'email'       => 's.email',
    'celular'     => 's.celular',
    'telefone'    => 's.telefone',
    'instituicao' => 'instituicao',
    'turma'       => 'turma',
    'id_curso'    => 'id_curso',
);
$orderby = isset($ordens[$ordem]) ? $ordens[$ordem] : 's.nome';

// Consulta unica: sem loop de consultas por supervisor (elimina N+1).
// q_periodos e id_curso viram subconsultas; a ordenacao e feita no SQL.
$sql = "select s.id as supervisor_id, s.cress, s.nome, s.telefone, s.celular, s.email"
     . ", min(e.id) as estagio_id, min(e.instituicao) as instituicao"
     . ", max(est.periodo) as turma"
     . ", (select count(distinct periodo) from estagiarios where supervisor_id = s.id) as q_periodos"
     . ", if(s.cress regexp '^[0-9]+$' and s.cress <> '0'"
     . ", (select id from curso_inscricao_supervisor where cress = s.cress limit 1), null) as id_curso"
     . " from supervisores as s"
     . " left outer join inst_super as i on s.id = i.supervisor_id"
     . " left outer join instituicoes as e on e.id = i.instituicao_id"
     . " left outer join estagiarios as est on s.id = est.supervisor_id";

$where = array();
if (!empty($turma))
    $where[] = "est.periodo = " . $db->qstr($turma);
if (!empty($instituicao_id))
    $where[] = "est.instituicao_id = " . $instituicao_id;
if ($where)
    $sql .= " where " . implode(" and ", $where);

$sql .= " group by s.id";
$sql .= " order by " . $orderby;

$resultado = $db->Execute($sql);
if ($resultado === false) die("Não foi possivel consultar as tabelas");

$supervisores = array();
while (!$resultado->EOF) {
    $supervisores[] = array(
        'supervisor_id'  => $resultado->fields['supervisor_id'],
        'cress'          => $resultado->fields['cress'],
        'nome'           => $resultado->fields['nome'],
        'email'          => $resultado->fields['email'],
        'telefone'       => $resultado->fields['telefone'],
        'celular'        => $resultado->fields['celular'],
        'instituicao_id' => $resultado->fields['estagio_id'],
        'instituicao'    => $resultado->fields['instituicao'],
        'turma'          => $resultado->fields['turma'],
        'q_periodos'     => $resultado->fields['q_periodos'],
        'id_curso'       => $resultado->fields['id_curso'],
    );
    $resultado->MoveNext();
}

$smarty = new Smarty_estagio;

$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("instituicao_id", $instituicao_id);
$smarty->assign("turma", $turma);
$smarty->assign("ordem", $ordem);
$smarty->assign("periodos", $periodos);
$smarty->assign("supervisores", $supervisores);
$smarty->display("supervisores_datatables.tpl");

$db->Close();

exit;

?>
