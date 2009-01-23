<?php

include_once("../db.inc");
include_once("../setup.php");

// $sql = "select id, registro from estagiarios where nivel = 1 and periodo = '2007-1'";
$sql_estagiarios = "select id, registro, nivel, periodo from estagiarios group by registro order by periodo, registro";
echo $sql_estagiarios . "<br>";
$estagiarios = $db->Execute($sql_estagiarios);
while(!$estagiarios->EOF)
{
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
	echo "Novos: " . $sql_novos . "<br>";
	$alunosNovos = $db->Execute($sql_novos);
	if ($alunosNovos == false) die ("Não foi possível consultar a tabela alunosNovos");
	$numero_novos = $alunosNovos->RecordCount();
	echo "Numero novos " . 	$numero_novos . "<br>";
	// Alunos novos que estão na tabela alunosNovos
	if ($numero_novos > 0) {
	    while(!$alunosNovos->EOF) {
	        $registro_aluno = $alunosNovos->fields['registro'];
		$nome_aluno = $alunosNovos->fields['nome'];
	        $codigo_telefone = $alunosNovos->fields['codigo_telefone'];
	        $telefone = $alunosNovos->fields['telefone'];
	        $codigo_celular = $alunosNovos->fields['codigo_celular'];
		$celular = $alunosNovos->fields['celular'];
	        $email = $alunosNovos->fields['email'];
		$cpf = $alunosNovos->fields['cpf'];
	        $identidade = $alunosNovos->fields['identidade'];
		$nascimento = $alunosNovos->fields['nascimento'];
		$endereco = $alunosNovos->fields['endereco'];
		$cep = $alunosNovos->fields['cep'];
		$municipio = $alunosNovos->fields['municipio'];
		$bairro = $alunosNovos->fields['bairro'];
		$observacoes = $alunosNovos->fields['observacoes'];
		$alunosNovos->MoveNext();
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