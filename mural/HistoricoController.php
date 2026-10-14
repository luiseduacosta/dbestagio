<?php
/**
 * HistoricoController.php — Controlador do histórico do mural de estágios.
 *
 * Reúne as funções que antes ficavam em mural/historico.php:
 *   - resumo()          : tabela agregada por período (inscrições, vagas, sem estágio)
 *   - alunosPeriodo()   : detalhe dos alunos inscritos em um período
 *   - instituicoesPeriodo(): detalhe das instituições e vagas ofertadas em um período
 *   - alunosSemEstagio(): detalhe dos alunos que não conseguiram estágio
 *
 * Uso (em historico.php):
 *   require_once("HistoricoController.php");
 *   $h = new HistoricoController();
 *   $h->resumo();
 *   $h->alunosPeriodo($periodo);
 */
if (!class_exists('HistoricoController', false)) {

class HistoricoController {

	/**
	 * Resumo agregado de todos os períodos do mural em uma única query.
	 *
	 * Retorna, por período:
	 *   - periodo
	 *   - subtotal        : COUNT(DISTINCT registro) em inscricoes
	 *   - vagas           : SUM(vagas) das ofertas em mural_estagios
	 *   - alunos_sem_estagio : inscrições sem registro correspondente em
	 *                          estagiarios (mesmo período)
	 */
	public function resumo() {
		global $db;

		$sql = "
			SELECT
				i.periodo,
				COUNT(DISTINCT i.registro) AS subtotal,
				(SELECT COALESCE(SUM(m.vagas), 0)
				   FROM mural_estagios m
				  WHERE m.periodo = i.periodo) AS vagas,
				COUNT(DISTINCT CASE WHEN e.registro IS NULL
				                    THEN i.registro END) AS alunos_sem_estagio
			FROM inscricoes i
			LEFT JOIN estagiarios e
			       ON e.registro = i.registro
			      AND e.periodo  = i.periodo
			GROUP BY i.periodo
			ORDER BY i.periodo DESC
		";

		// GetAll retorna array de arrays associativos — formato que o template
		// muralestagios-historico.tpl espera ({$historico[i].subtotal} etc.).
		$linhas = $db->GetAll($sql, array());

		$historico = array();
		foreach ($linhas as $l) {
			$historico[] = array(
				'periodo'            => $l['periodo'],
				'subtotal'           => (int)$l['subtotal'],
				'vagas'              => (int)$l['vagas'],
				'alunos_sem_estagio' => (int)$l['alunos_sem_estagio'],
			);
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("historico", $historico);
		$smarty->display("muralestagios-historico.tpl");
	}

	/**
	 * Detalhe dos alunos inscritos em um período, com níveis de estágio.
	 */
	public function alunosPeriodo($periodo) {
		$dados = $this->alunosPorPeriodo($periodo);

		$alunos             = $dados[0];
		$total_sem_estagio  = $dados[1];
		$niveis             = $dados[3];

		// Ordena os alunos por nome antes de exibir.
		usort($alunos, function($a, $b) {
			return strcmp($a['nome'], $b['nome']);
		});

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo", $periodo);
		$smarty->assign("sem_estagio", $total_sem_estagio);
		$smarty->assign("niveis", $niveis);
		$smarty->assign("alunos", $alunos);
		$smarty->display("muralestagios-historico_alunos.tpl");
	}

	/**
	 * Detalhe das instituições e suas vagas ofertadas em um período.
	 */
	public function instituicoesPeriodo($periodo) {
		global $db;

		$sql = "SELECT instituicao, vagas
		          FROM mural_estagios
		         WHERE periodo = ?
		      ORDER BY instituicao";

		$linhas = $db->GetAll($sql, array($periodo));

		$instituicoes = array();
		$total_vagas = 0;
		foreach ($linhas as $l) {
			$instituicoes[] = array(
				'instituicao' => $l['instituicao'],
				'vagas'       => (int)$l['vagas'],
			);
			$total_vagas += (int)$l['vagas'];
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo", $periodo);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->assign("total_vagas", $total_vagas);
		$smarty->display("muralestagios-historico_instituicoes.tpl");
	}

	/**
	 * Detalhe dos alunos que se inscreveram mas não conseguiram estágio
	 * em um determinado período.
	 */
	public function alunosSemEstagio($periodo) {
		$dados = $this->alunosPorPeriodo($periodo);
		$sem_estagio = $dados[2];

		// Ordena por nome.
		usort($sem_estagio, function($a, $b) {
			return strcmp($a['nome'], $b['nome']);
		});

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo", $periodo);
		$smarty->assign("sem_estagio", $sem_estagio);
		$smarty->display("muralestagios-historico_sem_estagio.tpl");
	}

	// ------------------------------------------------------------------
	// Interno
	// ------------------------------------------------------------------

	/**
	 * Monta a lista de alunos de um período com seus níveis de estágio.
	 *
	 * Retorna um array:
	 *   [0] => array de alunos (cada aluno tem id_aluno, nome, situacao, nivel)
	 *   [1] => total de alunos sem estágio (int)
	 *   [2] => array de alunos sem estágio (nome, email, telefone, celular, id_aluno)
	 *   [3] => array de contagem por nível (nivel0..nivel4, total)
	 */
	private function alunosPorPeriodo($periodo) {
		global $db;

		$sql = "SELECT DISTINCT i.registro, a.nome
		          FROM inscricoes i
		     LEFT JOIN alunos a ON i.registro = a.registro
		         WHERE i.periodo = ?
		      ORDER BY a.nome";

		$linhas = $db->GetAll($sql, array($periodo));

		$alunos             = array();
		$alunos_sem_estagio = array();
		$total_sem_estagio  = 0;
		$nivel0 = $nivel1 = $nivel2 = $nivel3 = $nivel4 = 0;

		foreach ($linhas as $l) {
			$id_aluno = $l['registro'];
			$nome     = $l['nome'];

			// Nível de estágio do aluno neste período.
			// $nivel === 0 significa que NÃO há registro em estagiarios para
			// este aluno/período — ou seja, "sem estágio". Esta é a mesma
			// definição usada em resumo() (LEFT JOIN estagiarios).
			$nivel = (int)$db->GetOne(
				"SELECT nivel FROM estagiarios WHERE registro = ? AND periodo = ?",
				array($id_aluno, $periodo)
			);
			if ($nivel === 0)      $nivel0++;
			elseif ($nivel === 1)  $nivel1++;
			elseif ($nivel === 2)  $nivel2++;
			elseif ($nivel === 3)  $nivel3++;
			elseif ($nivel === 4)  $nivel4++;

			$sem_estagio = ($nivel === 0);

			$aluno = array(
				'id_aluno' => $id_aluno,
				'nome'     => $nome,
				'situacao' => $sem_estagio ? 0 : 1,
				'nivel'    => $nivel,
			);

			// Alunos sem estágio: busca dados de contato na tabela alunos.
			if ($sem_estagio) {
				$dados_aluno = $db->GetRow(
					"SELECT nome, email, telefone, celular FROM alunos WHERE registro = ?",
					array($id_aluno)
				);
				if ($dados_aluno) {
					$aluno['nome'] = $dados_aluno['nome'];
					$alunos_sem_estagio[] = array(
						'nome'      => $dados_aluno['nome'],
						'email'     => $dados_aluno['email'],
						'telefone'  => $dados_aluno['telefone'],
						'celular'   => $dados_aluno['celular'],
						'id_aluno'  => $id_aluno,
					);
				} else {
					// Não está em alunos: registra só com o registro, para não
					// perder a contagem nem deixar o nome em branco.
					$alunos_sem_estagio[] = array(
						'nome'      => '(sem cadastro)',
						'email'     => '',
						'telefone'  => '',
						'celular'   => '',
						'id_aluno'  => $id_aluno,
					);
				}
				$total_sem_estagio++;
			}

			$alunos[] = $aluno;
		}

		$nivel_total = $nivel0 + $nivel1 + $nivel2 + $nivel3 + $nivel4;

		return array(
			$alunos,
			$total_sem_estagio,
			$alunos_sem_estagio,
			array($nivel0, $nivel1, $nivel2, $nivel3, $nivel4, $nivel_total),
		);
	}

} // class HistoricoController

} // if !class_exists
