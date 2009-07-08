<?php

include_once("../setup.php");

// $sql = "select id, registro from estagiarios where nivel = 1 and periodo = '2007-1'";
$sql_estagiarios = "select id, registro, nivel, periodo from estagiarios group by registro order by periodo, registro";
echo $sql_estagiarios . "<br>";
$estagiarios = $db->Execute($sql_estagiarios);
while(!$estagiarios->EOF) {
    $registro = $estagiarios->fields['registro'];
    $id = $estagiarios->fields['id'];
    $nivel = $estagiarios->fields['nivel'];
    $periodo = $estagiarios->fields['periodo'];
    $estagiarios->MoveNext();
    // echo $registro . " ";
    $sql_alunos = "select id, nome from alunos where registro = $registro";
    // echo $sql_alunos . "<br>";
    $alunos = $db->Execute($sql_alunos);
    $quantidade = $alunos->RecordCount();
    // echo $quantidade . "<br>";
    // Aluno existe se quantidade e diferente de 0
    if ($quantidade >= 1) {
	$nome = $alunos->fields['nome'];
	// echo $quantidade . " " . $registro . " " . $nome . " ". $nivel . " ". $periodo . "<br>";
    } elseif ($quantidade === 0) {
	$sql_novos = "select registro, nome, codigo_telefone, telefone, codigo_celular, celular, email, "
	. "cpf, identidade, nascimento, "
	. "endereco, cep, municipio, bairro, observacoes "
	. "from alunosNovos where registro = $registro";
	// echo "Novos: " . $sql_novos . "<br>";
	$alunos_novos = $db->Execute($sql_novos);
	if ($alunos_novos == false) die ("N�o foi poss��vel consultar a tabela alunosNovos");
	$numero_novos = $alunos_novos->RecordCount();
	// echo "Numero novos " . 	$numero_novos . "<br>";
	// Alunos novos que estao na tabela alunosNovos
	if ($numero_novos > 0) {
	    while(!$alunos_novos->EOF) {
	        $registro_aluno = $alunos_novos->fields['registro'];
		$nome_aluno = $alunos_novos->fields['nome'];
	        $codigo_telefone = $alunos_novos->fields['codigo_telefone'];
	        $telefone = $alunos_novos->fields['telefone'];
	        $codigo_celular = $alunos_novos->fields['codigo_celular'];
		$celular = $alunos_novos->fields['celular'];
	        $email = $alunos_novos->fields['email'];
		$cpf = $aluno_nNovos->fields['cpf'];
	        $identidade = $alunos_novos->fields['identidade'];
		$nascimento = $alunos_novos->fields['nascimento'];
		$endereco = $alunos_novos->fields['endereco'];
		$cep = $alunos_novos->fields['cep'];
		$municipio = $alunos_novos->fields['municipio'];
		$bairro = $alunos_novos->fields['bairro'];
		$observacoes = $alunos_novos->fields['observacoes'];
		$alunos_novos->MoveNext();
	    }
	    echo "<p style='background-color:yellow'>" . $id . " " . $quantidade . " " . $registro_aluno . " "  . "Aluno não cadastrado "  . $nivel . " " . $periodo . "</p>";
	    $sql = "insert into alunos(registro, nome, codigo_telefone, telefone, codigo_celular, celular, "
	    . "email, cpf, identidade, nascimento, endereco, cep, municipio, bairro, observacoes) "
	    . "values($registro_aluno, '$nome_aluno', $codigo_telefone, '$telefone', $codigo_celular, '$celular', "
	    . "'$email', '$cpf', '$identidade', '$nascimento', '$endereco', '$cep', '$municipio', '$bairro', '$observacoes')"; 
	    echo $id . " " . $sql . "<br>";
	    $resultado = $db->Execute($sql);
	    if ($resultado == false) die ("Não foi possível inserir os registros na tabela alunos");
	    echo "<hr>";
	} else {
	    echo "<p style='background-color:lightgreen'>" . $id . " " . $quantidade . " " . $registro_aluno . " "  . "Aluno não cadastrado "  . $nivel . " " . $periodo . "</p>";
	    echo "<hr>";
	}
	// echo "<hr>";
/*
        $nome_aluno = '';
        $codigo_telefone = 0;
        $telefone = 0;
        $codigo_celular = 0;
        $celular = 0;
        $email = '';
        $cpf = '';
        $identidade = '';
        $nascimento = 0;
        $endereco = '';
        $cep = '';
        $municipio = '';
        $bairro = '';
        $observacoes = '';
*/	
	// $resultado = $db->Execute($sql);
	// if($resultado == false) die ("Não foi possível atualizar a tabela estagiarios");
    }
}

?>