<?php

include_once("../../autentica.inc");
include_once("../../setup.php");

$indice = $_REQUEST['indice'];
$id_supervisor = $_REQUEST['id_supervisor'];
$periodo = $_REQUEST['periodo'];
// echo "Indice: " . $indice . " Periodo "  . $periodo . "<br />";
// echo "id_supervisor " . $id_supervisor . "<br />";

$sql = "select supervisores.id as num_supervisor, supervisores.cress, supervisores.nome, supervisores.endereco, supervisores.bairro, supervisores.cep, supervisores.municipio, supervisores.codigo_tel, supervisores.telefone, supervisores.codigo_cel, supervisores.celular, supervisores.email, ";
$sql .= " estagio.id, estagio.instituicao, ";
$sql .= " supervisores.observacoes ";
// $sql .= " max(estagiarios.periodo) ";
$sql .= " from supervisores ";
$sql .= " left join inst_super on supervisores.id = inst_super.id_supervisor ";
$sql .= " left join estagio on inst_super.id_instituicao = estagio.id ";
$sql .= " left join estagiarios on supervisores.id = estagiarios.id_supervisor ";
if ($periodo)
    $sql .= " where estagiarios.periodo = '$periodo' ";
$sql .= " group by supervisores.id ";
$sql .= " order by nome, inst_super.id_supervisor ";

// echo $sql . "<br>";

$resultado_total = $db->Execute($sql);
$ultimo = $resultado_total->RecordCount();
// echo $ultimo . "<br>";
// Se estou no final o proximo registro é o primeiro
if ($indice >= $ultimo) {
    $indice = 0;
}

// Se estou no primerio registro o registro anterior é o último
if ($indice < 0) {
    $indice = $ultimo - 1;
}

// Calculo o indice
if (!empty($id_supervisor)) {

    /*
     * Utilizo a consulta anterior
     */
    // echo $sql . "<br />";
    $resultado = $db->Execute($sql);
    $i = 0;
    // echo $i . "<br />";
    while (!$resultado->EOF) {
        $num_supervisor = $resultado->fields['num_supervisor'];
        $nome_supervisor = $resultado->fields['nome'];
        // echo "id_supervisor -> " . $id_supervisor . " num_supervisor -> " . $num_supervisor . " Nome: "  . $nome_supervisor . "<br />";
        if ($num_supervisor == $id_supervisor) {
            $indice = $i;
            // echo "Indice " . $indice . " id_supervisor " . $id_supervisor . "<br />";
            break;
        }
        $i++;
        $resultado->MoveNext();
    }
}

// Rotina para acrescentar uma instituicao
if (!empty($_POST['num_instituicao'])) {
    // echo "Acrescentar instituicao<br>";
    $sql_inserir = "insert into inst_super (id_supervisor,id_instituicao) values('$id_supervisor','$_POST[num_instituicao]')";
    // echo $sql . "<br>";
    $res_inserir = $db->Execute($sql_inserir);
    if ($res_inserir === false)
        die("Não foi possível inserir dados na tabela inst_super");
} else {
    // echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

// echo $indice . " " . $sql . "<br>";
$resultado = $db->SelectLimit($sql, 1, $indice);
if ($resultado === false)
    die("1 Não foi possível consultar a tabela supervisores");
while (!$resultado->EOF) {
    $id_supervisor = $resultado->fields['num_supervisor'];
    $cress = $resultado->fields['cress'];
    $nome = $resultado->fields['nome'];
    $endereco = $resultado->fields['endereco'];
    $bairro = $resultado->fields['bairro'];
    $cep = $resultado->fields['cep'];
    $municipio = $resultado->fields['municipio'];
    $codigo_tel = $resultado->fields['codigo_tel'];
    $telefone = $resultado->fields['telefone'];
    $codigo_cel = $resultado->fields['codigo_cel'];
    $celular = $resultado->fields['celular'];
    $email = $resultado->fields['email'];
    $id_instituicao = $resultado->fields['id'];
    // $instituicao = $resultado->fields['instituicao'];
    $observacoes = $resultado->fields['observacoes'];

    // Capturo as instituicoes campo de emprego do supervisor
    $sql_instituicoes = "select estagio.id, estagio.instituicao from estagio ";
    $sql_instituicoes .= " inner join inst_super on estagio.id=inst_super.id_instituicao ";
    $sql_instituicoes .= "where inst_super.id_supervisor='$id_supervisor'";
    // echo $sql_instituicoes . "<br>";
    $resultado = $db->Execute($sql_instituicoes);
    if ($resultado === false)
        die("Não foi possível consultar a tabela estagio");
    $i = 0;
    while (!$resultado->EOF) {
        $inst_emprego[$i]['id_instituicao'] = $resultado->fields['id'];
        $inst_emprego[$i]['instituicao'] = $resultado->fields['instituicao'];
        // echo "Instituicao: " . $inst_estagio[$i]['instituicao'] . "<br>";
        $i++;
        $resultado->MoveNext();
    }

    // Verifico se fez inscricao para o curso
    if (ctype_digit($cress)) {
        if ($cress != 0) {
            $sqlcurso = "select id from curso_inscricao_supervisor where cress=$cress";
            // echo $sqlcurso . "<br />";
            $supervisores_curso = $db->Execute($sqlcurso);
            if ($supervisores_curso === false)
                die("Não foi possivel consultar a tabela curso_inscricao_supervisores");
            $id_curso = $supervisores_curso->fields['id'];
            // echo "Id curso: " . $id_curso . "<br>";
        }
    }

    // Alunos supervisionados pelo supervisor
    $sqlalunos = "select alunos.id, alunos.registro, alunos.nome, estagiarios.periodo, estagiarios.id_instituicao from alunos ";
    $sqlalunos .= " inner join estagiarios on estagiarios.registro = alunos.registro ";
    $sqlalunos .= " where estagiarios.id_supervisor = $id_supervisor";
    $sqlalunos .= " order by estagiarios.periodo, alunos.nome";
    // echo $sqlalunos . "<br>";

    $res_alunos = $db->Execute($sqlalunos);
    if ($res_alunos === false)
        die("Não foi possível consultar a tabela alunos");
    $i = 0;
    while (!$res_alunos->EOF) {
        $alunos[$i]['id_aluno'] = $res_alunos->fields['id'];
        $alunos[$i]['registro'] = $res_alunos->fields['registro'];
        $alunos[$i]['nome'] = $res_alunos->fields['nome'];
        $alunos[$i]['periodo'] = $res_alunos->fields['periodo'];
        $alunos[$i]['id_instituicao'] = $res_alunos->fields['id_instituicao'];

        $id_instituicao = $res_alunos->fields['id_instituicao'];
        $sql_aluno_instituicao = "select instituicao from estagio where id = $id_instituicao";
        // echo $sql_aluno_instituicao . "<br>";
        $res_aluno_instituicao = $db->Execute($sql_aluno_instituicao);

        $alunos[$i]['instituicao'] = $res_aluno_instituicao->fields['instituicao'];
        // echo $res_aluno_instituicao->fields['instituicao'];

        $i++;
        // echo $i;
        $res_alunos->MoveNext();
    }

    $resultado->MoveNext();
}
// die;
// Instituicoes
$sql = "select id, instituicao from estagio order by instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false)
    die("Não foi possível consultar a tabela estagio");
$i = 0;
while (!$resultado->EOF) {
    $instituicoes[$i]['id_instituicao'] = $resultado->fields['id'];
    $instituicoes[$i]['instituicao'] = $resultado->fields['instituicao'];
    $resultado->MoveNext();
    $i++;
}

// Pego a informacao sobre as turma de alunos
$sqlturma = "select id, periodo from estagiarios group by periodo";
// echo $sqlturma . "<br>";
$res_turma = $db->Execute($sqlturma);
if ($res_turma === false)
    die("Não foi possivel consultar a tabela estagiarios");
while (!$res_turma->EOF) {
    $periodos[] = $res_turma->fields['periodo'];
    $res_turma->MoveNext();
}

$smarty = new Smarty_estagio;
$smarty->assign("sistema_autentica", $sistema_autentica);
$smarty->assign("ultimo", $ultimo - 1);
$smarty->assign("indice", $indice);
$smarty->assign("id_supervisor", $id_supervisor);
$smarty->assign("periodo", $periodo);
$smarty->assign("cress", $cress);
$smarty->assign("nome", $nome);
$smarty->assign("endereco", $endereco);
$smarty->assign("bairro", $bairro);
$smarty->assign("cep", $cep);
$smarty->assign("municipio", $municipio);
$smarty->assign("codigo_tel", $codigo_tel);
$smarty->assign("telefone", $telefone);
$smarty->assign("codigo_cel", $codigo_cel);
$smarty->assign("celular", $celular);
$smarty->assign("email", $email);
$smarty->assign("id_instituicao", $id_instituicao);
$smarty->assign("emprego", $inst_emprego);
$smarty->assign("id_curso", $id_curso);
$smarty->assign("observacoes", $observacoes);
$smarty->assign("alunos", $alunos);
$smarty->assign("periodos", $periodos);
$smarty->assign("instituicoes", $instituicoes);
$smarty->display("supervisores_ver_cada.tpl");

$db->Close();

exit;
?>
