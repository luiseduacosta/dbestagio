<?php

require("../setup.php");
require_once(__DIR__ . "/../libphp/models.php");

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

	$sql = "select periodo from inscricoes group by periodo order by periodo";
	$resultado = Inscricao::query($sql);
	$i = 0;
	$historico = array();
	foreach ($resultado as $item) {
		$periodo = $item->periodo;

		$sql_periodo = "SELECT count(distinct registro) as subtotal FROM inscricoes WHERE periodo = ?";
		$resultado_periodo = Inscricao::query($sql_periodo, array($periodo));
		$subtotal = $resultado_periodo[0]->subtotal;
		$historico[$i]['subtotal'] = (int)$subtotal;
		$historico[$i]['periodo'] = $periodo;

		$historico[$i]['vagas'] = Mural::totalVagasPorPeriodo($periodo);

		$alunos_periodo = alunos_por_periodo($periodo);
		$historico[$i]['alunos_sem_estagio'] = $alunos_periodo[1];
		
		$i++;
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

	$sql_instituicoes = "select instituicao, vagas from mural_estagios where mural_estagios.periodo = ? order by instituicao";
	// echo "<br>";
	$resultado_instituicoes = Mural::query($sql_instituicoes, array($selecao));
	$j = 0;
	$instituicoes = array();
	foreach($resultado_instituicoes as $item) {
		
		$instituicoes[$j]['instituicao'] = $item->instituicao;
	  	$instituicoes[$j]['vagas'] = (int)$item->vagas;

	  	$total_vagas = $total_vagas + $instituicoes[$j]['vagas'];
	  	$j++;
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
	
	$sql_alunos = "select inscricoes.registro, alunos.nome 
	from inscricoes 
	left outer join alunos on inscricoes.registro = alunos.registro 
	where periodo=? 
	group by inscricoes.registro 
	order by alunos.nome ";
	// echo $sql_alunos . "<br>";

	if (empty($ordem)) {
		$ordem = 'nome';
	} else {
		$indice = $ordem;
	}
	
	$resultado_alunos = Inscricao::query($sql_alunos, array($periodo));
	$j = 0;
	$k = 0;	// Sem estagio
	$alunos = array();
	$alunos_sem_estagio = array();
	$total_sem_estagio = 0;
	$nivel0 = $nivel1 = $nivel2 = $nivel3 = $nivel4 = 0;
	foreach ($resultado_alunos as $item) {
		
		$alunos[$j][$ordem] = $$indice;
		$alunos[$j]['id_aluno'] = $item->registro;
	  	$alunos[$j]['nome'] = $item->nome;
		$alunos[$j]['situacao'] = 1; // Com estagio
	  	
	  	$id_aluno = $item->registro;
	  	$nome = $item->nome;

        // Busco se está estagiando
	  	$sql_estagiario = "select nivel from estagiarios where registro = ? and periodo = ?";
		$resultado_estagiario = Estagiario::query($sql_estagiario, array($id_aluno, $periodo));
		$nivel = isset($resultado_estagiario[0]->nivel) ? (int)$resultado_estagiario[0]->nivel : 0;
		if (empty($nivel)) $nivel0++;
		if ($nivel == 1) $nivel1++;
		if ($nivel == 2) $nivel2++;
		if ($nivel == 3) $nivel3++;
		if ($nivel == 4) $nivel4++;

		// echo $nivel0 . " " . $nivel1 . " " . $nivel2 . " " . $nivel3 . " " . $nivel4;
		// echo "<br>";
		
		$alunos[$j]['nivel'] = $nivel;
	  	// echo "<br>";
	  	
		// Se nao estah em alunos estagiarios entao busco em alunos
	  	if (empty($nome)) {
			// echo "Aluno sem estagio<br>";
	  		$sql_aluno_novo = "select nome, email, telefone, celular from alunos where registro=? order by nome";
			$resultado_aluno_novo = Aluno::query($sql_aluno_novo, array($id_aluno));
			$sem_estagio = count($resultado_aluno_novo);
			$total_sem_estagio = $total_sem_estagio + $sem_estagio;
			// echo "<br>";

			foreach ($resultado_aluno_novo as $aluno_item) {
				
				$alunos[$j]['id_aluno'] = $id_aluno;
	  			$alunos[$j]['nome'] = $aluno_item->nome;
				$alunos[$j]['situacao'] = 0; // Sem estagio

	  			$alunos_sem_estagio[$k]['nome'] = $aluno_item->nome;
	  			$alunos_sem_estagio[$k]['email'] = $aluno_item->email;
       	  			$alunos_sem_estagio[$k]['telefone'] = $aluno_item->telefone;
                                $alunos_sem_estagio[$k]['celular'] = $aluno_item->celular;
                                $alunos_sem_estagio[$k]['id_aluno'] = $id_aluno;

	  			$k++;
				}
			}

	  	$j++;
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