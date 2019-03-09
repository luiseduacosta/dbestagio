<?php

require("../setup.php");

$periodo = isset($_REQUEST['periodo']) ? $_REQUEST['periodo'] : NULL; 
$selecao = isset($_REQUEST['selecao']) ? $_REQUEST['selecao'] : NULL; 
$periodo_sem_estagio = isset($_REQUEST['periodo_sem_estagio']) ? $_REQUEST['periodo_sem_estagio'] : NULL;

if ($periodo) {
	alunos_periodo($periodo);
} elseif ($selecao) {
	instituicoes_periodo($selecao); 
} elseif ($periodo_sem_estagio) {
	// echo "Sem estagio: " . $periodo_sem_estagio;
	alunos_sem_estagio($periodo_sem_estagio);
} else {

	$sql = "select periodo from mural_inscricao group by periodo order by periodo";
	$resultado = $db->Execute($sql);
	$i = 0;
	while (!$resultado->EOF) {
		$periodo = $resultado->fields['periodo'];

		$sql_periodo = "SELECT count(distinct id_aluno) as subtotal FROM mural_inscricao WHERE periodo = '$periodo'";
		$resultado_periodo = $db->Execute($sql_periodo);
		$subtotal = $resultado_periodo->fields['subtotal'];
		$historico[$i]['subtotal'] = $subtotal;
		$historico[$i]['periodo'] = $periodo;

		$sql_instituicoes = "select sum(vagas) as total_vagas from mural_estagio where mural_estagio.periodo = '$periodo' ";
		$resultado_instituicoes = $db->Execute($sql_instituicoes);
		$historico[$i]['vagas'] = $resultado_instituicoes->fields['total_vagas'];

		$alunos_periodo = alunos_por_periodo($periodo);
		$historico[$i]['alunos_sem_estagio'] = $alunos_periodo[1];
		
		$i++;
		$resultado->MoveNext();
	}
	
	$smarty = new Smarty_estagio;
	$smarty->assign("historico",$historico);
	$smarty->display("../../mural/historico.tpl");
}

function alunos_periodo($periodo) {

	require("../db.inc");

	$alunos_periodo = alunos_por_periodo($periodo);

	$alunos = $alunos_periodo[0];
	$total_sem_estagio = $alunos_periodo[1];
	$niveis = $alunos_periodo[3];
	
	reset($alunos);
	sort($alunos);
	
	$smarty = new Smarty_estagio;
	$smarty->assign("periodo",$periodo);
	$smarty->assign("sem_estagio",$total_sem_estagio);
	$smarty->assign("niveis",$niveis);
	$smarty->assign("alunos",$alunos);
	$smarty->display("../../mural/historico_alunos.tpl");
	
	return;
}

function instituicoes_periodo($selecao) {

	require("../db.inc");

	$sql_instituicoes = "select instituicao, vagas from mural_estagio where mural_estagio.periodo = '$selecao' order by instituicao";
	// echo "<br>";
	$resultado_instituicoes = $db->Execute($sql_instituicoes);
	$j = 0;
	while(!$resultado_instituicoes->EOF) {
		
		$instituicoes[$j]['instituicao'] = $resultado_instituicoes->fields['instituicao'];
	  	$instituicoes[$j]['vagas'] = $resultado_instituicoes->fields['vagas'];

	  	$total_vagas = $total_vagas + $instituicoes[$j]['vagas'];
	  	$j++;
		$resultado_instituicoes->MoveNext();
	}
	
	$smarty = new Smarty_estagio;
	$smarty->assign("periodo",$selecao);
	$smarty->assign("instituicoes",$instituicoes);
	$smarty->assign("total_vagas",$total_vagas);
	$smarty->display("../../mural/historico_instituicoes.tpl");
	
	return;
}

function alunos_por_periodo($periodo) {

	require("../db.inc");
	
	$sql_alunos = "select mural_inscricao.id_aluno, alunos.nome 
	from mural_inscricao 
	left outer join alunos on mural_inscricao.id_aluno = alunos.registro 
	where periodo='$periodo' 
	group by mural_inscricao.id_aluno 
	order by alunos.nome ";
	// echo $sql_alunos . "<br>";

	if (empty($ordem)) {
		$ordem = 'nome';
	} else {
		$indice = $ordem;
	}
	
	$resultado_alunos = $db->Execute($sql_alunos);
	$j = 0;
	$k = 0;	// Sem estagio
	while (!$resultado_alunos->EOF) {
		
		$alunos[$j][$ordem] = $$indice;
		$alunos[$j]['id_aluno'] = $resultado_alunos->fields['id_aluno'];
	  	$alunos[$j]['nome'] = $resultado_alunos->fields['nome'];
		$alunos[$j]['situacao'] = 1; // Com estagio
	  	
	  	$id_aluno = $resultado_alunos->fields['id_aluno'];
	  	$nome = $resultado_alunos->fields['nome'];

        // Busco se está estagiando
	  	$sql_estagiario = "select nivel from estagiarios where registro = '$id_aluno' and periodo = '$periodo'";
		$resultado_estagiario = $db->Execute($sql_estagiario);
		$nivel = $resultado_estagiario->fields['nivel'];
		if (empty($nivel)) $nivel0++;
		if ($nivel == 1) $nivel1++;
		if ($nivel == 2) $nivel2++;
		if ($nivel == 3) $nivel3++;
		if ($nivel == 4) $nivel4++;

		// echo $nivel0 . " " . $nivel1 . " " . $nivel2 . " " . $nivel3 . " " . $nivel4;
		// echo "<br>";
		
		$alunos[$j]['nivel'] = $resultado_estagiario->fields['nivel'];
	  	// echo "<br>";
	  	
		// Se nao estah em alunos estagiarios entao busco em alunosNovos
	  	if (empty($nome)) {
			// echo "Aluno sem estagio<br>";
	  		$sql_aluno_novo = "select nome, email, telefone, celular from alunosNovos where registro='$id_aluno' order by nome";
			$resultado_aluno_novo = $db->Execute($sql_aluno_novo);
			$sem_estagio = $resultado_aluno_novo->RecordCount();
			$total_sem_estagio = $total_sem_estagio + $sem_estagio;
			// echo "<br>";

			while (!$resultado_aluno_novo->EOF) {
				
				$alunos[$j]['id_aluno'] = $id_aluno;
	  			$alunos[$j]['nome'] = $resultado_aluno_novo->fields['nome'];
				$alunos[$j]['situacao'] = 0; // Sem estagio

	  			$alunos_sem_estagio[$k]['nome'] = $resultado_aluno_novo->fields['nome'];
	  			$alunos_sem_estagio[$k]['email'] = $resultado_aluno_novo->fields['email'];
       	  			$alunos_sem_estagio[$k]['telefone'] = $resultado_aluno_novo->fields['telefone'];
                                $alunos_sem_estagio[$k]['celular'] = $resultado_aluno_novo->fields['celular'];
                                $alunos_sem_estagio[$k]['id_aluno'] = $id_aluno;

	  			$k++;
	  			
				$resultado_aluno_novo->MoveNext();
				}
			}

	  	$j++;
		$resultado_alunos->MoveNext();
	}

	$nivel_total = $nivel0 + $nivel1 + $nivel2 + $nivel3 + $nivel4;
	
	$alunos_periodo[0] = $alunos;
	$alunos_periodo[1] = $total_sem_estagio;
	$alunos_periodo[2] = $alunos_sem_estagio;
	$alunos_periodo[3] = array($nivel0,$nivel1,$nivel2,$nivel3,$nivel4,$nivel_total);
	
	return $alunos_periodo;

}

function alunos_sem_estagio($periodo_sem_estagio) {
	
	$alunos = alunos_por_periodo($periodo_sem_estagio);
	$sem_estagio = $alunos[2];

	reset($sem_estagio);
	sort($sem_estagio);
	
	$smarty = new Smarty_estagio;
	$smarty->assign("periodo",$periodo_sem_estagio);
	$smarty->assign("sem_estagio",$sem_estagio);
	$smarty->display("../../mural/historico_sem_estagio.tpl");

}

?>