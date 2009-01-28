<?php

include_once("../../db.inc");
include_once("../../setup.php");

// Verifico se o usuario esta logado
if(isset($_REQUEST['usuario_senha'])) {
    $usuario = $_REQUEST['usuario_senha'];
    if ($usuario) 
	$logado = 1;
}

$ordem = $_REQUEST["ordem"];
if (empty($ordem))
    $ordem = "nome";

// $periodo = $_REQUEST['periodo'];
$seleciona_nivel = $_REQUEST['seleciona_nivel'];
$seleciona_turno = $_REQUEST['seleciona_turno'];
$seleciona_instituicao = $_REQUEST['seleciona_instituicao'];
$seleciona_periodo = $_REQUEST['seleciona_periodo'];
$seleciona_professor = $_REQUEST['seleciona_professor'];

// echo "Ordem: " . $ordem . " Nivel: " . $seleciona_nivel . " Turno: " . $seleciona_turno . " Inst. " . $seleciona_instituicao . " Periodo " . $seleciona_periodo . " Professor " . $seleciona_professor . "<br>";

$sqlUltimoPeriodo = "select max(periodo) as ultimoPeriodo from estagiarios";
$resultadoMaxPeriodo = $db->Execute($sqlUltimoPeriodo);
if($resultadoMaxPeriodo === false) die ("Não foi possível consultar a tabela estagiarios");
while(!$resultadoMaxPeriodo->EOF) {
	$ultimoPeriodo = $resultadoMaxPeriodo->fields['ultimoPeriodo'];
	$resultadoMaxPeriodo->MoveNext();
}

$sql1 = "select estagiarios.id_aluno, " .
"alunos.registro, ".
"alunos.nome, ".
"alunos.telefone, ".
"alunos.celular, ".
"alunos.email, ".
"estagiarios.id_instituicao, ".
"estagiarios.id, ".
"estagiarios.tc, ".
"estagiarios.turno, ".
"estagiarios.nivel, ".
"estagiarios.periodo, ".
"estagiarios.nota, ".
"estagiarios.ch, ".
"estagio.id as id_instituicao, ".
"estagio.instituicao, ".
"supervisores.id as id_supervisor, ".
"supervisores.nome as nomeSupervisor, ".
"professores.nome as nomeProfessor, ".
"areas_estagio.area ".
// Aluno
"from estagiarios inner join alunos ".
"on estagiarios.id_aluno=alunos.id ".
// Campo de estagio (instituicao)
"left outer join estagio ".
"on estagiarios.id_instituicao=estagio.id ".
// Supervisor
"left outer join supervisores ".
"on estagiarios.id_supervisor=supervisores.id ".
// Professor
"left outer join professores ".
"on estagiarios.id_professor=professores.id ".
// Area de estagio
"left outer join areas_estagio ".
"on estagiarios.id_area=areas_estagio.id ";
// $sql2 = "where estagiarios.periodo='2005-1' ".
// "order by alunos.nome";

// $sql = $sql1 . $sql2;
// echo "Lista " . $sql1 . "<br>";

/* Primeira linha */
// Seleciona turno
if ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' "; // and nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + instituicao
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_instituicao='$seleciona_instituicao' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + professor
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_professor='$seleciona_professor' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + periodo
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and periodo='$seleciona_periodo' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + instituicao
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + professor
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_professor='$seleciona_professor' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + periodo
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and periodo='$seleciona_periodo' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona instituicao + professor
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona instituicao + periodo
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona professor + periodo
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_professor='$seleciona_professor' and periodo='$seleciona_periodo' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + instituicao
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + professor
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' and id_professor='$seleciona_professor' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + periodo
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' and periodo='$seleciona_periodo' "; // and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + instituicao + professor
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + professor + periodo
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo'"; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + instituicao + periodo
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + instituicao + professor
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_instituicao ='$seleciona_instituicao' and id_professor='$seleciona_professor'"; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + instituicao + periodo
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_instituicao ='$seleciona_instituicao' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + professor + periodo
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_professor ='$seleciona_professor' and periodo='$seleciona_periodo'"; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona instituicao + professor + periodo
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_instituicao='$seleciona_instituicao' and id_professor ='$seleciona_professor' and periodo='$seleciona_periodo'"; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + instituicao + professor
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + instituicao + professor + periodo
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona nivel + instituicao + professor + periodo
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + professor + periodo
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$turno', nivel='$seleciona_nivel' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + instituicao + professor + periodo
elseif ((!empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$turno', id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' "; // and id_professor='$id_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona turno + nivel + instituicao + professor + periodo
elseif ((!empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where turno='$seleciona_turno' and nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' and id_professor='$seleciona_professor' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
/* Termina primeira linha */
// Seleciona nivel
elseif ((empty($seleciona_turno)) and (!empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where nivel='$seleciona_nivel' "; // and nivel='$seleciona_nivel' and id_instituicao='$seleciona_instituicao' and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona instituicao
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (!empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_instituicao='$seleciona_instituicao' "; // and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona professor
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (!empty($seleciona_professor)) and (empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where id_professor='$seleciona_professor' "; // and periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
}
// Seleciona periodo
elseif ((empty($seleciona_turno)) and (empty($seleciona_nivel)) and (empty($seleciona_instituicao)) and (empty($seleciona_professor)) and (!empty($seleciona_periodo))) {
    $sqlLista  = $sql1;
    $sqlLista .= "where periodo='$seleciona_periodo' ";
    $sqlLista .= "order by $ordem";
} else {
    $sqlLista  = $sql1;
    $sqlLista .= "where periodo='$ultimoPeriodo' ";
    $sqlLista .= "order by $ordem";
}
$resultadoLista = $db->Execute($sqlLista);
// echo $sqlLista . "<br>";

if($resultadoLista === false) die ("Nao foi possivel consultar as tabelas estagiarios, alunos");
$i=0;
while(!$resultadoLista->EOF) {
	$estagiarios[$i]['id_estagiario']  = $resultadoLista->fields['id'];
	$estagiarios[$i]['id_aluno']       = $resultadoLista->fields['id_aluno'];
	$estagiarios[$i]['registro']       = $resultadoLista->fields['registro'];
	$estagiarios[$i]['tc']             = $resultadoLista->fields['tc'];
	$estagiarios[$i]['nome']           = $resultadoLista->fields['nome'];
	$estagiarios[$i]['email']          = $resultadoLista->fields['email'];
	$estagiarios[$i]['celular']        = $resultadoLista->fields['celular'];
	$estagiarios[$i]['telefone']       = $resultadoLista->fields['telefone'];
	$estagiarios[$i]['nivel'] 	   	   = $resultadoLista->fields['nivel'];
	$estagiarios[$i]['turno']          = $resultadoLista->fields['turno'];
	$estagiarios[$i]['periodo']        = $resultadoLista->fields['periodo'];
	$estagiarios[$i]['nota']           = $resultadoLista->fields['nota'];
	$estagiarios[$i]['ch']             = $resultadoLista->fields['ch'];
	$estagiarios[$i]['id_instituicao'] = $resultadoLista->fields['id_instituicao'];
	$estagiarios[$i]['instituicao']    = $resultadoLista->fields['instituicao'];
	$estagiarios[$i]['id_supervisor']  = $resultadoLista->fields['id_supervisor'];
	$estagiarios[$i]['supervisor']     = $resultadoLista->fields['nomeSupervisor'];
	$estagiarios[$i]['area']           = $resultadoLista->fields['area'];
	$estagiarios[$i]['professor']      = $resultadoLista->fields['nomeProfessor'];

	$id_aluno = $resultadoLista->fields['id_aluno'];
	$registro = $resultadoLista->fields['registro'];
	$nome     = $resultadoLista->fields['nome'];
	$nivel    = $resultadoLista->fields['nivel'];

	// Capturo a informacao para ser exibida nos niveis
	$sqlNivel = "select nivel, id_instituicao from estagiarios where id_aluno=$id_aluno";
	// echo $sqlNivel . "<br>";
	$resultadoNivel = $db->Execute($sqlNivel);
	if($resultadoNivel === false) die ("Nao foi possivel consultar a tabela estagiarios");
	$nivel1 = NULL;
	$nivel2 = NULL;
	$nivel3 = NULL;
	$nivel4 = NULL;
	while(!$resultadoNivel->EOF) {
		$nivelCadaAluno = $resultadoNivel->fields['nivel'];
		$id_instituicao = $resultadoNivel->fields['id_instituicao'];
		// echo $id_aluno . " " .$nivelCadaAluno . "<br>";

		if ($nivelCadaAluno == 1) {
			$estagiarios[$i]['nivel1'] = $id_instituicao;
			$nivel1 = $id_instituicao;
			// echo $nivel1 . " ";
		}

		if ($nivelCadaAluno == 2) {
			$estagiarios[$i]['nivel2'] = $id_instituicao;
			$nivel2 = $id_instituicao;
			// echo $nivel2 . " ";
		}

		if ($nivelCadaAluno == 3) {
			$estagiarios[$i]['nivel3'] = $id_instituicao;
			$nivel3 = $id_instituicao;
			// echo $nivel3 . " ";
		}

		if ($nivelCadaAluno == 4) {
			$estagiarios[$i]['nivel4'] = $id_instituicao;
			$nivel4 = $id_instituicao;
			// echo $nivel4 . " ";
		}

		$resultadoNivel->MoveNext();

	}

	// Se os quatro niveis de estagio estao preenchidos
	if ((!empty($nivel1)) and (!empty($nivel2)) and (!empty($nivel3)) and (!empty($nivel4))) {
	    // echo "aluno cursou 4 niveis de estagio <br>";
	    if (($nivel1 == $nivel2) and ($nivel2 == $nivel3) and ($nivel3 == $nivel4)) $codigo = 0;
	    if (($nivel1 != $nivel2) and ($nivel2 != $nivel3) and ($nivel3 != $nivel4)) $codigo = 1;
	    // Padrao
	    if (($nivel1 == $nivel2) and ($nivel2 != $nivel3) and ($nivel3 == $nivel4)) $codigo = 2;
	    // Dois consecutivos
	    if (($nivel1 == $nivel2) and ($nivel2 != $nivel3) and ($nivel3 != $nivel4)) $codigo = 3;
	    if (($nivel1 != $nivel2) and ($nivel2 == $nivel3) and ($nivel3 != $nivel4)) $codigo = 4;
	    if (($nivel1 != $nivel2) and ($nivel2 != $nivel3) and ($nivel3 == $nivel4)) $codigo = 5;
	    // Tres consecutivos
	    if (($nivel1 == $nivel2) and ($nivel2 == $nivel3) and ($nivel3 != $nivel4)) $codigo = 6;
	    if (($nivel1 != $nivel2) and ($nivel2 == $nivel3) and ($nivel3 == $nivel4)) $codigo = 7;
	    // echo "$nome cursou 4 niveis de estagio: $codigo <br>";
	
	    if ($codigo == 0) $codigo_0++;
	    if ($codigo == 1) $codigo_1++;	
	    if ($codigo == 2) $codigo_2++;
	    if ($codigo == 3) $codigo_3++;
	    if ($codigo == 4) $codigo_4++;
	    if ($codigo == 5) $codigo_5++;
	    if ($codigo == 6) $codigo_6++;		
	    if ($codigo == 7) $codigo_7++;		

	    $estagiarios[$i]['codigo'] = $codigo;
	}


	// TCC
	$sql_tcc = "select num_monografia from tcc_alunos where registro='$registro'";
	// echo $sql_tcc . "<br>";
	$resultado_tcc = $db->Execute($sql_tcc);
	if($resultado_tcc === false) die ("Nao foi possivel consultar a tabela tcc_alunos");
	$num_monografia = $resultado_tcc->fields['num_monografia'];
	$estagiarios[$i]['num_monografia'] = $num_monografia;
	// echo $num_monografia . "<br>";
	
	$i++;
	$resultadoLista->MoveNext();
}

// Tabela provissoria
$total = $codigo_0 + $codigo_1 + $codigo_2 + $codigo_3 + $codigo_4 + $codigo_5 + $codigo_6 + $codigo_7;

/*
echo "Cod   " . "Quant. " . "<br>";
echo "0     " . $codigo_0 . "<br>";
echo "1     " . $codigo_1 . "<br>";
echo "2     " . $codigo_2 . "<br>";
echo "3     " . $codigo_3 . "<br>";
echo "4     " . $codigo_4 . "<br>";
echo "5     " . $codigo_5 . "<br>";
echo "6     " . $codigo_6 . "<br>";
echo "7     " . $codigo_7 . "<br>";
echo "Total " . $total . "<br>";
*/

// Pego a listagem das instituicoes ativas para formulario de select
$sqlInstituicao =  "select distinct estagio.id, estagio.instituicao from estagiarios " ;
$sqlInstituicao .= "left outer join estagio on estagiarios.id_instituicao=estagio.id ";
$sqlInstituicao .= "order by estagio.instituicao";
$res_estagio = $db->Execute($sqlInstituicao);
if($res_estagio === false) die ("Nao foi possivel consultar a tabela estagio");
$i = 0;
while(!$res_estagio->EOF) {
    $instituicoes[$i]['id_instituicao'] = $res_estagio->fields['id'];
    $instituicoes[$i]['instituicao']    = $res_estagio->fields['instituicao'];
    $i++;
    $res_estagio->MoveNext();
}

// Pego a lista dos professores
$sqlProfessor  = "select professores.id, professores.nome from professores ";
$sqlProfessor .= " inner join estagiarios on professores.id = estagiarios.id_professor ";
$sqlProfessor .= " group by estagiarios.id_professor ";
$sqlProfessor .= " order by professores.nome";
// echo $sqlProfessor . "<br>";
$res_professor = $db->Execute($sqlProfessor);
if($res_professor === false) die ("Nao foi possivel consultar a tabela professores");
$i = 0;
while(!$res_professor->EOF) {
    $professores[$i]['id_professor'] = $res_professor->fields['id'];
    $professores[$i]['nome']         = $res_professor->fields['nome'];
    $i++;
    $res_professor->MoveNext();
    }

// Pego o nome e o numero da instituicao para o cabecalho da tabela
if(!empty($seleciona_instituicao)) {
    $sql_instituicao = "select id, instituicao from estagio where id=$seleciona_instituicao order by instituicao";
    $resultado_instituicao = $db->Execute($sql_instituicao);
    if($resultado_instituicao === false) die ("Não foi possível consultar a tabela estagio");
    while(!$resultado_instituicao->EOF) {
        $num_instituicao  = $resultado_instituicao->fields['id'];
        $nome_instituicao = $resultado_instituicao->fields['instituicao'];
        $resultado_instituicao->MoveNext();
    }
}

// Pego o nome e o numero do professor para o cabecalho da tabela
if(!empty($seleciona_professor)) {
    $sql_professor = "select id, nome from professores where id=$seleciona_professor order by nome";
    // echo $sql_professor . "<br>";
    $resultado_professor = $db->Execute($sql_professor);
    if($resultado_professor === false) die ("Nao foi possivel consultar a tabela professores");
    while(!$resultado_professor->EOF) {
        $num_professor  = $resultado_professor->fields['id'];
        $nome_professor = $resultado_professor->fields['nome'];
        $resultado_professor->MoveNext();
    }
}

// Pego os periodos para listar as instituicoes
$sql_periodo = "select distinct periodo from estagiarios order by periodo";
$resultado_periodo = $db->Execute($sql_periodo);
if($resultado_periodo === false) die ("Não foi possível consultar a tabela estagiarios");
$i = 0;
while(!$resultado_periodo->EOF) {
    $matriz_periodo[$i]['turma'] = $resultado_periodo->fields['periodo'];
    $resultado_periodo->MoveNext();
    $i++;
}

$smarty = new Smarty_estagio;
$smarty->assign("logado",$logado);
$smarty->assign("instituicoes",$instituicoes);
$smarty->assign("professores",$professores);

$smarty->assign("seleciona_turno",$seleciona_turno);
$smarty->assign("seleciona_nivel",$seleciona_nivel);
$smarty->assign("seleciona_instituicao",$seleciona_instituicao);
$smarty->assign("seleciona_professor",$seleciona_professor);
$smarty->assign("seleciona_periodo",$seleciona_periodo);

// $smarty->assign("lista",$lista);
$smarty->assign("lista",$estagiarios);

$smarty->assign("nome_instituicao",$nome_instituicao);
$smarty->assign("nome_professor",$nome_professor);
$smarty->assign("matriz_periodo",$matriz_periodo);
$smarty->assign("periodo",$periodo);

$smarty->assign("codigo_0",$codigo_0);
$smarty->assign("codigo_1",$codigo_1);
$smarty->assign("codigo_2",$codigo_2);
$smarty->assign("codigo_3",$codigo_3);
$smarty->assign("codigo_4",$codigo_4);
$smarty->assign("codigo_5",$codigo_5);
$smarty->assign("codigo_6",$codigo_6);
$smarty->assign("codigo_7",$codigo_7);
$smarty->assign("total",$total);

$smarty->display("alunos-exibir_listar.tpl");

exit;

?>