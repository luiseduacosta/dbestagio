<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require("../setup.php");

$funcao = isset($_REQUEST['funcao']) ? $_REQUEST['funcao'] : NULL;
$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : PERIODO_ATUAL;

if ($funcao == "sem_professor") {
    // echo "Sem professor" . "<br>";
    $sql_sem_professor = "select * from sem_professor where periodo = '$periodo'";
    // echo $sql_sem_professor. "<br>";
    $res_sem_professor = $db->Execute($sql_sem_professor);
    if ($res_sem_professor === false) die("Não foi possível consulta a vista sem_professor");
    $i = 0;
    while (!$res_sem_professor->EOF) {
        $aluno_sem_professor[$i]['id'] = $res_sem_professor->fields['id'];
        $aluno_sem_professor[$i]['registro'] = $res_sem_professor->fields['registro'];
        $aluno_sem_professor[$i]['nome'] = $res_sem_professor->fields['nome'];
        $aluno_sem_professor[$i]['celular'] = $res_sem_professor->fields['celular'];
        $aluno_sem_professor[$i]['email'] = $res_sem_professor->fields['email'];
        $i++;
        $res_sem_professor->MoveNext();
    }
   
    $smarty = new Smarty_estagio;
    $smarty->assign('funcao', $funcao);
    $smarty->assign('periodo', $periodo);
    $smarty->assign('aluno_sem_professor', $aluno_sem_professor);
    $smarty->display("file:" . RAIZ . "/administracao/controle_db.tpl");

    // echo var_dump($aluno_sem_estagio);    
    exit;
}

if ($funcao == "sem_estagio") {
    // echo "Sem estágio" . "<br>";
    $sql_sem_estagio = "select * from sem_estagio where periodo = '$periodo'";
    // echo $sql_sem_estagio. "<br>";
    $res_sem_estagio = $db->Execute($sql_sem_estagio);
    if ($res_sem_estagio === false) die("Não foi possível consulta a vista sem_estagio");
    $i = 0;
    while (!$res_sem_estagio->EOF) {
        $aluno_sem_estagio[$i]['registro'] = $res_sem_estagio->fields['registro'];
        $aluno_sem_estagio[$i]['nome'] = $res_sem_estagio->fields['nome'];
        $aluno_sem_estagio[$i]['celular'] = $res_sem_estagio->fields['celular'];
        $aluno_sem_estagio[$i]['email'] = $res_sem_estagio->fields['email'];
        $i++;
        $res_sem_estagio->MoveNext();
    }
   
    $smarty = new Smarty_estagio;
    $smarty->assign('funcao', $funcao);
    $smarty->assign('periodo', $periodo);    
    $smarty->assign('aluno_sem_estagio', $aluno_sem_estagio);
    $smarty->display("file:" . RAIZ . "/administracao/controle_db.tpl");

    // echo var_dump($aluno_sem_estagio);    
    exit;
}

if ($funcao == "sem_supervisor") {
    // echo "Sem supervisor" . "<br>";
    $sql_sem_supervisor = "select * from sem_supervisor where periodo = '$periodo'";
    // echo $sql_sem_supervisor. "<br>";
    $res_sem_supervisor = $db->Execute($sql_sem_supervisor);
    if ($res_sem_supervisor === false) die("Não foi possível consulta a vista sem_supervisor");
    $i = 0;
    while (!$res_sem_supervisor->EOF) {
        $aluno_sem_supervisor[$i]['id'] = $res_sem_supervisor->fields['id'];
        $aluno_sem_supervisor[$i]['registro'] = $res_sem_supervisor->fields['registro'];
        $aluno_sem_supervisor[$i]['nome'] = $res_sem_supervisor->fields['nome'];
        $aluno_sem_supervisor[$i]['celular'] = $res_sem_supervisor->fields['celular'];
        $aluno_sem_supervisor[$i]['email'] = $res_sem_supervisor->fields['email'];
        $i++;
        $res_sem_supervisor->MoveNext();
    }
   
    $smarty = new Smarty_estagio;
    $smarty->assign('funcao', $funcao);
    $smarty->assign('periodo', $periodo);
    $smarty->assign('aluno_sem_supervisor', $aluno_sem_supervisor);
    $smarty->display("file:" . RAIZ . "/administracao/controle_db.tpl");

    // echo var_dump($aluno_sem_supervisor);    
    exit;
}

if ($funcao == "sem_devolucao") {
    // echo "Sem devolucao" . "<br>";
    $sql_sem_devolucao = "select * from sem_devolucao_do_tc where periodo = '$periodo'";
    echo $sql_sem_devolucao. "<br>";
    $res_sem_devolucao = $db->Execute($sql_sem_devolucao);
    if ($res_sem_devolucao === false) die("Não foi possível consulta a vista sem_devolucao");
    $i = 0;
    while (!$res_sem_devolucao->EOF) {
        $aluno_sem_devolucao[$i]['id'] = $res_sem_devolucao->fields['id'];
        $aluno_sem_devolucao[$i]['registro'] = $res_sem_devolucao->fields['registro'];
        $aluno_sem_devolucao[$i]['nome'] = $res_sem_devolucao->fields['nome'];
        $aluno_sem_devolucao[$i]['celular'] = $res_sem_devolucao->fields['celular'];
        $aluno_sem_devolucao[$i]['email'] = $res_sem_devolucao->fields['email'];
        $i++;
        $res_sem_devolucao->MoveNext();
    }
   
    $smarty = new Smarty_estagio;
    $smarty->assign('funcao', $funcao);
    $smarty->assign('periodo', $periodo);
    $smarty->assign('aluno_sem_devolucao', $aluno_sem_devolucao);
    $smarty->display("file:" . RAIZ . "/administracao/controle_db.tpl");

    // echo var_dump($aluno_sem_supervisor);    
    exit;
}

/* Sem professor */
$sql = "select periodo, count(*) as quantidade from sem_professor group by periodo";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possivel consultar a vista sem_professor");
$i = 0;
while (!$res_sql->EOF) {
    
    $sem_professor[$i]['periodo'] = $res_sql->fields['periodo'];
    $sem_professor[$i]['quantidade'] = $res_sql->fields['quantidade'];
    $i++;
    $res_sql->MoveNext();
    
}

/*
echo "<br>";
echo "<p>Sem professor</p>";
echo var_dump($sem_professor);
*/

/* Sem supervisor */
$sql = "select periodo, count(*) as quantidade from sem_supervisor group by periodo";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possivel consultar a vista sem_supervisor");
$i = 0;
while (!$res_sql->EOF) {
    
    $sem_supervisor[$i]['periodo'] = $res_sql->fields['periodo'];
    $sem_supervisor[$i]['quantidade'] = $res_sql->fields['quantidade'];
    $i++;
    $res_sql->MoveNext();
    
}

/*
echo "<br>";
echo "<p>Sem supervisor de estágio</p>";
echo var_dump($sem_supervisor);
*/

/* Sem termo de compromisso */
// $periodo = '2009-1';
$sql_periodo = "select periodo from estagiarios group by periodo order by periodo";
$res_periodo = $db->Execute($sql_periodo);
$i = 0;
while (!$res_periodo->EOF) {
    $periodos[$i]['periodo'] = $res_periodo->fields['periodo'];
    $i++;
    $res_periodo->MoveNext();  
}
// var_dump($periodos);
$sql = "select `estagiarios`.`id_aluno` AS `id`,`estagiarios`.`registro` AS `registro`,`alunos`.`nome` AS `nome`,`alunos`.`celular` AS `celular`,`alunos`.`email` AS `email` from (`estagiarios` join `alunos` on((`estagiarios`.`id_aluno` = `alunos`.`id`))) where ((not(`estagiarios`.`id_aluno` in (select `estagiarios`.`id_aluno` from `estagiarios` where (`estagiarios`.`periodo` = '$periodo')))) and (`estagiarios`.`periodo` = (select max(periodo) from estagiarios where periodo < '$periodo')) and (`estagiarios`.`nivel` < 4)) group by `estagiarios`.`id_aluno` order by `alunos`.`nome`";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possivel consultar a vista sem_termo_de_compromisso");
$i = 0;
while (!$res_sql->EOF) {

    $sem_tc[$i]['id'] = $res_sql->fields['id'];
    $sem_tc[$i]['registro'] = $res_sql->fields['registro'];
    $sem_tc[$i]['nome'] = $res_sql->fields['nome'];
    $sem_tc[$i]['celular'] = $res_sql->fields['celular'];
    $sem_tc[$i]['email'] = $res_sql->fields['email'];

    $i++;
    $res_sql->MoveNext();
    
}

/*
echo "<br>";
echo "<p>Sem termo de compromisso</p>";
echo var_dump($sem_tc);
*/

/* Sem devolução de termo de compromiso */
$sql = "select periodo, count(*) as quantidade from sem_devolucao_do_tc group by periodo";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possivel consultar a vista sem_devolucao_do_tc");
$i = 0;
while (!$res_sql->EOF) {
    
    $sem_devolucao[$i]['periodo'] = $res_sql->fields['periodo'];
    $sem_devolucao[$i]['quantidade'] = $res_sql->fields['quantidade'];
    $i++;
    $res_sql->MoveNext();
    
}

/*
echo "<br>";
echo "<p>Sem devolução do TC</p>";
echo var_dump($sem_devolucao);
*/

/* Sem estagio */
$sql = "select periodo, count(*) as quantidade from sem_estagio group by periodo";
// echo $sql . "<br>";
$res_sql = $db->Execute($sql);
if ($res_sql === false) die("Não foi possivel consultar a vista sem_estagio");
$i = 0;
while (!$res_sql->EOF) {
    
    $sem_estagio[$i]['periodo'] = $res_sql->fields['periodo'];
    $sem_estagio[$i]['quantidade'] = $res_sql->fields['quantidade'];
    $i++;
    $res_sql->MoveNext();
    
}

/*
echo "<br>";
echo "<p>Sem estágio</p>";
echo var_dump($sem_estagio);
*/

$smarty = new Smarty_estagio;
$smarty->assign('periodo', $periodo);
$smarty->assign('periodos', $periodos);
$smarty->assign('sem_professor', $sem_professor);
$smarty->assign('sem_supervisor', $sem_supervisor);
$smarty->assign('sem_tc', $sem_tc);
$smarty->assign('sem_devolucao', $sem_devolucao);
$smarty->assign('sem_estagio', $sem_estagio);
$smarty->display("file:" . RAIZ . "/administracao/controle_db.tpl");


?>
