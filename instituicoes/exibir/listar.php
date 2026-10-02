<?php

include_once("../../setup.php");
require_once("../../libphp/models.php");

// Àreas de filtro (todas parametrizadas - sem SQL injection).
// A ordenação é feita pelo DataTables (lado cliente), por isso não há coluna de ordenação.
$turma        = isset($_GET['turma']) ? $_GET['turma'] : Instituicao::$db->GetOne("SELECT MAX(periodo) FROM estagiarios");
$instituicao  = isset($_REQUEST['instituicao']) ? trim($_REQUEST['instituicao']) : '';
$naturezaFilt = isset($_GET['natureza']) ? $_GET['natureza'] : '';
$todos_periodos = 0;
$total_supervi  = 0;

// ---------- Totais gerais ----------
$total_alunos      = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT registro) FROM estagiarios");
$total_instituicoes = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT instituicao_id) FROM estagiarios WHERE instituicao_id IS NOT NULL");
$total_professores = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT professor_id) FROM estagiarios WHERE professor_id IS NOT NULL");

// ---------- Lista de instituições (filtrada por turma e nome/natureza) ----------
$wheres = array();
$params = array();
if ($turma !== '' && $turma !== null) {
    $wheres[] = "t.periodo = ?";
    $params[] = $turma;
}
if ($instituicao !== '') {
    $wheres[] = "e.instituicao LIKE ?";
    $params[] = "%$instituicao%";
}
if ($naturezaFilt !== '' && $naturezaFilt !== '0') {
    $wheres[] = "e.natureza = ?";
    $params[] = $naturezaFilt;
}
$where_sql = $wheres ? ('WHERE ' . implode(' AND ', $wheres)) : '';

$sql = "SELECT e.id, e.instituicao, e.seguro, e.convenio, e.natureza, e.area AS id_area,
               e.beneficios AS beneficio, a.area
        FROM instituicoes AS e
        LEFT JOIN areas AS a ON e.area = a.id
        LEFT JOIN estagiarios AS t ON e.id = t.instituicao_id
        $where_sql
        GROUP BY e.instituicao, e.area, e.beneficios, e.id";
$resultado = Instituicao::$db->Execute($sql, $params);
if ($resultado === false) {
    error_log("Erro ao consultar instituicoes: " . Instituicao::$db->ErrorMsg());
    die("Não foi possível consultar a tabela instituicoes.");
}

$matriz = array();
while (!$resultado->EOF) {
    $id          = (int)$resultado->fields['id'];
    $inst_nome   = $resultado->fields['instituicao'];
    $convenio    = $resultado->fields['convenio'];
    $seguro      = $resultado->fields['seguro'];
    $beneficio   = $resultado->fields['beneficio'];
    $id_area     = $resultado->fields['id_area'];
    $area        = $resultado->fields['area'];
    $natureza    = $resultado->fields['natureza'];

    // Supervisores distintos no período (e no total quando turma=vazio).
    $superSql   = "SELECT COUNT(DISTINCT supervisor_id) FROM estagiarios WHERE instituicao_id = ?";
    $superArgs  = array($id);
    if ($turma !== '' && $turma !== null) {
        $superSql .= " AND periodo = ?";
        $superArgs[] = $turma;
    }
    $q_supervi  = (int)Instituicao::$db->GetOne($superSql, $superArgs);
    $total_supervi += $q_supervi;

    // Última turma com estagiários na instituição.
    $turma_inst = Instituicao::$db->GetOne(
        "SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
        array($id)
    );
    $turma_inst = $turma_inst ?: '';

    // Alunos (distintos por registro) no período selecionado.
    $aluSql   = "SELECT COUNT(DISTINCT registro) FROM estagiarios WHERE instituicao_id = ?";
    $aluArgs  = array($id);
    if ($turma !== '' && $turma !== null) {
        $aluSql .= " AND periodo = ?";
        $aluArgs[] = $turma;
    }
    $q_alunos = (int)Instituicao::$db->GetOne($aluSql, $aluArgs);

    // Total de períodos (valores distintos de periodo) para a instituição.
    $n_periodos = Instituicao::$db->GetOne(
        "SELECT COUNT(DISTINCT periodo) FROM estagiarios WHERE instituicao_id = ?",
        array($id)
    );
    $todos_periodos += (int)$n_periodos;

    $row = array(
        'instituicao_id' => $id,
        'instituicao'    => $inst_nome,
        'convenio'       => $convenio,
        'seguro'         => $seguro,
        'beneficio'      => $beneficio,
        'area'           => $area,
        'natureza'       => $natureza,
        'supervisores'   => $q_supervi,
        'turma'          => $turma_inst,
        'alunos'         => $q_alunos,
        'periodos'       => (int)$n_periodos,
    );
    $matriz[] = $row;

    $resultado->MoveNext();
}

// ---------- Períodos e naturezas para os filtros ----------
$periodos = array();
$rs_turma = Instituicao::$db->Execute("SELECT DISTINCT periodo FROM estagiarios WHERE periodo IS NOT NULL ORDER BY periodo DESC");
if ($rs_turma) {
    while (!$rs_turma->EOF) {
        $periodos[] = $rs_turma->fields['periodo'];
        $rs_turma->MoveNext();
    }
}

$naturezas = array();
$rs_nat = Instituicao::$db->Execute("SELECT DISTINCT natureza FROM instituicoes WHERE natureza IS NOT NULL ORDER BY natureza");
if ($rs_nat) {
    while (!$rs_nat->EOF) {
        $naturezas[] = $rs_nat->fields['natureza'];
        $rs_nat->MoveNext();
    }
}

$smarty = new Smarty_estagio;

$smarty->assign("turma", $turma);
$smarty->assign("periodos", $periodos);
$smarty->assign("natureza", $naturezaFilt);
$smarty->assign("naturezas", $naturezas);
$smarty->assign("instituicao", $instituicao);
$smarty->assign("instituicoes", $matriz);
$smarty->assign("total_professores", $total_professores);
$smarty->assign("total_instituicoes", $total_instituicoes);
$smarty->assign("total_supervisores", $total_supervi);
$smarty->assign("total_alunos", $total_alunos);
$smarty->assign("total_periodos", $todos_periodos);
$smarty->display("instituicoes.tpl");

exit;

?>