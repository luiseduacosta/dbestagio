<?php

$palavra = isset($_POST['palavra']) ? trim($_POST['palavra']) : null;

include_once(__DIR__ . "/../../autentica.inc");
include_once(__DIR__ . "/../../libphp/models.php");

if ($palavra === null) {
    die("Nenhuma palavra foi informada.");
}

$alunos = Aluno::buscarPorNome($palavra);

if (count($alunos) === 0) {
    echo "Não há registros com a palavra: $palavra";
    exit;
} else {
    $i = 0;
    foreach ($alunos as $aluno) {
        $alunos[$i]['aluno_id'] = $aluno->id;
        $alunos[$i]['nome']     = $aluno->nome;
        $alunos[$i]['registro'] = $aluno->registro;
        $alunos[$i]['email']    = $aluno->email;
        $i++;
    }
    $smarty = new Smarty_estagio;
    $smarty->assign("alunos",$alunos);
    $smarty->display("alunos-busca_resultado.tpl");
}

?>