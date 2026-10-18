<?php
/**
 * SupervisoresController.php — Controlador da tabela supervisores.
 *
 * Reúne todas as ações relacionadas ao cadastro de supervisores de estágio
 * (tabela supervisores). Substitui os scripts legados:
 *
 *   inserir()           <- inserir/form_inserir.php + inserir/inserir.php
 *   atualizar()         <- atualizar/modifica.php + atualizar/atualiza.php
 *   ver_cada()          <- exibir/ver_cada.php
 *   listar()            <- exibir/listar.php
 *   excluir()           <- cancelar/cancela.php
 *   alunos_supervisor() <- exibir/alunos_supervisor.php
 *   seleciona()         <- seleciona.php + exibir/seleciona.php
 *   buscar()            <- buscar/busca.php + buscar/resultado.php
 *   inst_supervisor()   <- cancelar/inst_supervisor.php
 *
 * O controlador cuida de HTTP, autenticação, sanitização e assigns do Smarty.
 * Toda a persistência é feita via model Supervisor (libphp/Supervisor.php).
 *
 * Uso:
 *   require_once(__DIR__ . "/SupervisoresController.php");
 *   $controller = new SupervisoresController();
 *   $controller->listar();
 */
if (!class_exists('SupervisoresController', false)) {

class SupervisoresController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Inclui apenas setup.php (sem forçar login) e carrega os models.
	 * Usado para ações públicas como a listagem.
	 */
	private function semAutenticacao() {
		global $db;
		include_once(__DIR__ . "/../setup.php");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Lê um campo de texto do POST (trim).
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	/**
	 * Lê e valida os campos do formulário do supervisor (POST).
	 */
	private function lerPost() {
		$nome = $this->campoTexto('nome');
		if ($nome === '') {
			die("O campo Nome é obrigatório.");
		}
		if (mb_strlen($nome, 'UTF-8') > 70) {
			die("O nome do supervisor excede 70 caracteres.");
		}

		$codigo_telefone = $this->campoTexto('codigo_telefone');
		$codigo_celular  = $this->campoTexto('codigo_celular');
		$regiao          = $this->campoTexto('regiao');

		return array(
			'nome'            => $nome,
			'cress'           => $this->campoTexto('cress') ?: null,
			'cpf'             => $this->campoTexto('cpf') ?: null,
			'email'           => $this->campoTexto('email') ?: null,
			'codigo_telefone' => ($codigo_telefone !== '') ? (int)$codigo_telefone : null,
			'telefone'        => $this->campoTexto('telefone') ?: null,
			'codigo_celular'  => ($codigo_celular !== '') ? (int)$codigo_celular : null,
			'celular'         => $this->campoTexto('celular') ?: null,
			'endereco'        => $this->campoTexto('endereco') ?: null,
			'bairro'          => $this->campoTexto('bairro') ?: null,
			'municipio'       => $this->campoTexto('municipio') ?: null,
			'cep'             => $this->campoTexto('cep') ?: null,
			'escola'          => $this->campoTexto('escola') ?: null,
			'ano_formacao'    => $this->campoTexto('ano_formacao') ?: null,
			'cargo'           => $this->campoTexto('cargo') ?: null,
			'regiao'          => ($regiao !== '') ? (int)$regiao : 7,
			'observacoes'     => $this->campoTexto('observacoes') ?: null,
		);
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Lista de supervisores com filtros por turma/período e instituição.
	 * Utiliza DataTables no template supervisores-listar.tpl.
	 * Substitui supervisores/exibir/listar.php.
	 */
	public function listar() {
		$this->semAutenticacao();
		global $sistema_autentica;

		$ordem          = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';
		$turma          = isset($_GET['turma']) && $_GET['turma'] !== '0' ? trim($_GET['turma']) : null;
		$instituicao_id = isset($_GET['instituicao_id']) ? (int)$_GET['instituicao_id'] : null;

		// Períodos existentes para o seletor
		$periodos = Estagiario::periodos();

		if ($turma !== null && !in_array($turma, $periodos, true)) {
			$turma = null;
		}

		// Whitelist de colunas para ordenação segura
		$ordens = array(
			'nome'        => 's.nome',
			'cress'       => 's.cress',
			'q_periodos'  => 'q_periodos',
			'email'       => 's.email',
			'celular'     => 's.celular',
			'telefone'    => 's.telefone',
			'instituicao' => 'instituicao',
			'turma'       => 'turma',
			'id_curso'    => 'id_curso',
		);
		$orderby = isset($ordens[$ordem]) ? $ordens[$ordem] : 's.nome';

		$sql = "SELECT s.id AS supervisor_id, s.cress, s.nome, s.telefone, s.celular, s.email, "
			 . "MIN(e.id) AS instituicao_id, MIN(e.instituicao) AS instituicao, "
			 . "MAX(est.periodo) AS turma, "
			 . "(SELECT COUNT(DISTINCT periodo) FROM estagiarios WHERE supervisor_id = s.id) AS q_periodos, "
			 . "IF(s.cress REGEXP '^[0-9]+$' AND s.cress <> '0', "
			 . "(SELECT id FROM curso_inscricao_supervisor WHERE cress = s.cress LIMIT 1), NULL) AS id_curso "
			 . "FROM supervisores AS s "
			 . "LEFT OUTER JOIN inst_super AS i ON s.id = i.supervisor_id "
			 . "LEFT OUTER JOIN instituicoes AS e ON e.id = i.instituicao_id "
			 . "LEFT OUTER JOIN estagiarios AS est ON s.id = est.supervisor_id";

		$where  = array();
		$params = array();
		if (!empty($turma)) {
			$where[]  = "est.periodo = ?";
			$params[] = $turma;
		}
		if (!empty($instituicao_id)) {
			$where[]  = "est.instituicao_id = ?";
			$params[] = $instituicao_id;
		}
		if ($where) {
			$sql .= " WHERE " . implode(" AND ", $where);
		}

		$sql .= " GROUP BY s.id ORDER BY " . $orderby;

		$resultado = Supervisor::query($sql, $params);
		$supervisores = array();
		if (!empty($resultado)) {
			foreach ($resultado as $res) {
				$supervisores[] = array(
					'supervisor_id'  => (int)$res->supervisor_id,
					'cress'          => $res->cress,
					'nome'           => $res->nome,
					'email'          => $res->email,
					'telefone'       => $res->telefone,
					'celular'        => $res->celular,
					'instituicao_id' => (int)$res->instituicao_id,
					'instituicao'    => $res->instituicao,
					'turma'          => $res->turma,
					'q_periodos'     => (int)$res->q_periodos,
					'id_curso'       => $res->id_curso ? (int)$res->id_curso : null,
				);
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("sistema_autentica", isset($sistema_autentica) ? $sistema_autentica : 1);
		$smarty->assign("instituicao_id", $instituicao_id);
		$smarty->assign("turma", $turma);
		$smarty->assign("ordem", $ordem);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("supervisores", $supervisores);
		$smarty->display("supervisores-listar.tpl");
		exit;
	}

	/**
	 * Ficha de um supervisor, navegação registro a registro e estagiários.
	 * Substitui supervisores/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		global $isAdmin;
		$this->comAutenticacao();

		$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
		$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		if ($supervisor_id <= 0 && isset($_REQUEST['id_supervisor'])) {
			$supervisor_id = (int)$_REQUEST['id_supervisor'];
		}
		$periodo       = isset($_REQUEST['periodo']) ? trim($_REQUEST['periodo']) : '';
		if ($periodo === '0') {
			$periodo = '';
		}

		$periodos = Estagiario::periodos();
		if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
			$periodo = '';
		}

		// Lista ordenada dos supervisores para navegação
		$sql    = "SELECT DISTINCT s.id, s.nome FROM supervisores AS s";
		$params = array();
		if ($periodo !== '') {
			$sql .= " INNER JOIN estagiarios AS e ON e.supervisor_id = s.id AND e.periodo = ?";
			$params[] = $periodo;
		}
		$sql .= " ORDER BY s.nome";

		$rs = Supervisor::query($sql, $params);
		$lista = array();
		if (!empty($rs)) {
			foreach ($rs as $item) {
				$lista[] = (int)$item->id;
			}
		}
		$ultimo = count($lista);

		// Resolve posição do supervisor na lista
		if ($supervisor_id > 0) {
			$posicao = array_search($supervisor_id, $lista, true);
			if ($posicao !== false) {
				$indice = (int)$posicao;
			}
		} elseif ($ultimo > 0) {
			if ($indice >= $ultimo) $indice = 0;
			if ($indice < 0)        $indice = $ultimo - 1;
			$supervisor_id = $lista[$indice];
		}

		$supervisor = ($supervisor_id > 0) ? Supervisor::find($supervisor_id) : null;
		if ($supervisor === null) {
			die($supervisor_id > 0
				? "Supervisor não encontrado (id $supervisor_id)."
				: "Nenhum supervisor cadastrado.");
		}

		// Vincula instituição caso submetido o formulário inline
		if (!empty($_POST['num_instituicao'])) {
			$num_instituicao = (int)$_POST['num_instituicao'];
			if ($num_instituicao > 0) {
				$supervisor->vincularInstituicao($num_instituicao);
			}
		}

		// Instituições vinculadas ao supervisor
		$emprego = $supervisor->instituicoes();

		// Alunos supervisionados
		$alunos = array();
		$rsAlunos = Estagiario::query(
			"SELECT a.id AS aluno_id, a.registro, a.nome, e.periodo, e.instituicao_id, i.instituicao "
			. "FROM estagiarios AS e "
			. "INNER JOIN alunos AS a ON a.id = e.aluno_id "
			. "LEFT JOIN instituicoes AS i ON i.id = e.instituicao_id "
			. "WHERE e.supervisor_id = ? "
			. "ORDER BY e.periodo, a.nome",
			array($supervisor_id)
		);
		if (!empty($rsAlunos)) {
			foreach ($rsAlunos as $item) {
				$alunos[] = array(
					'aluno_id'       => (int)$item->aluno_id,
					'registro'       => (int)$item->registro,
					'nome'           => $item->nome,
					'periodo'        => $item->periodo,
					'instituicao_id' => (int)$item->instituicao_id,
					'instituicao'    => ($item->instituicao !== null) ? $item->instituicao : 'Sem dados',
				);
			}
		}

		// Curso do supervisor
		$id_curso = null;
		$cress = (string)$supervisor->cress;
		if ($cress !== '' && $cress !== '0' && ctype_digit($cress)) {
			$curso = Supervisor::query("SELECT id FROM curso_inscricao_supervisor WHERE cress = ? LIMIT 1", array($cress));
			if (!empty($curso)) {
				$id_curso = (int)$curso[0]->id;
			}
		}

		// Instituições para o seletor de novo vínculo
		$instituicoes = Instituicao::listar_todas();

		$smarty = new Smarty_estagio;
		// Necessário para o template decidir se mostra o link [Sincronizar],
		// cujo destino é restrito a administradores.
		$smarty->assign("isAdmin", $isAdmin);
		$smarty->assign("ultimo", ($ultimo > 0) ? $ultimo - 1 : 0);
		$smarty->assign("indice", $indice);
		$smarty->assign("supervisor_id", $supervisor_id);
		$smarty->assign("periodo", $periodo);
		$smarty->assign("cress", $supervisor->cress);
		$smarty->assign("nome", $supervisor->nome);
		$smarty->assign("cpf", $supervisor->cpf);
		$smarty->assign("codigo_telefone", $supervisor->codigo_telefone);
		$smarty->assign("telefone", $supervisor->telefone);
		$smarty->assign("codigo_celular", $supervisor->codigo_celular);
		$smarty->assign("celular", $supervisor->celular);
		$smarty->assign("email", $supervisor->email);
		$smarty->assign("endereco", $supervisor->endereco);
		$smarty->assign("bairro", $supervisor->bairro);
		$smarty->assign("municipio", $supervisor->municipio);
		$smarty->assign("cep", $supervisor->cep);
		$smarty->assign("escola", $supervisor->escola);
		$smarty->assign("ano_formacao", $supervisor->ano_formacao);
		$smarty->assign("cargo", $supervisor->cargo);
		$smarty->assign("regiao", $supervisor->regiao);
		$smarty->assign("observacoes", $supervisor->observacoes);
		$smarty->assign("id_curso", $id_curso);
		$smarty->assign("emprego", $emprego);
		$smarty->assign("alunos", $alunos);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->display("supervisores-ver_cada.tpl");
		exit;
	}

	/**
	 * Formulário de inclusão (GET) e inserção de supervisor (POST).
	 * Substitui supervisores/inserir/form_inserir.php + supervisores/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();
		global $usuario_id;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost();
			$dados['user_id'] = (int)(isset($usuario_id) ? $usuario_id : 0);

			$novo = new Supervisor();
			$novo->preencher($dados);

			if (!$novo->save()) {
				error_log("Erro ao inserir supervisor: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela supervisores. Tente novamente.");
			}

			$novo_id = $novo->getKey();

			// Vincula com a instituição se selecionada
			$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
			if ($instituicao_id > 0) {
				$novo->vincularInstituicao($instituicao_id);
			}

			header("Location: ver_cada.php?supervisor_id=$novo_id");
			exit;
		}

		// GET: formulário de cadastro
		$instituicoes = Instituicao::listar_todas();
		$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;

		$smarty = new Smarty_estagio;
		$smarty->assign("instituicao_id", $instituicao_id);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->display("supervisores-inserir.tpl");
		exit;
	}

	/**
	 * Formulário de edição (GET) e atualização do supervisor (POST).
	 * Substitui supervisores/atualizar/modifica.php + supervisores/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		if ($supervisor_id <= 0) {
			die("Supervisor inválido.");
		}

		$sup = Supervisor::find($supervisor_id);
		if ($sup === null) {
			die("Supervisor não encontrado (id $supervisor_id).");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost();
			$sup->preencher($dados);

			if (!$sup->save()) {
				error_log("Erro ao atualizar supervisor: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar a tabela supervisores. Tente novamente.");
			}

			header("Location: ver_cada.php?supervisor_id=$supervisor_id");
			exit;
		}

		// GET: formulário preenchido
		$smarty = new Smarty_estagio;
		$smarty->assign("supervisor_id", $supervisor_id);
		$smarty->assign("nome", $sup->nome);
		$smarty->assign("cress", $sup->cress);
		$smarty->assign("cpf", $sup->cpf);
		$smarty->assign("email", $sup->email);
		$smarty->assign("codigo_telefone", $sup->codigo_telefone);
		$smarty->assign("telefone", $sup->telefone);
		$smarty->assign("codigo_celular", $sup->codigo_celular);
		$smarty->assign("celular", $sup->celular);
		$smarty->assign("endereco", $sup->endereco);
		$smarty->assign("bairro", $sup->bairro);
		$smarty->assign("municipio", $sup->municipio);
		$smarty->assign("cep", $sup->cep);
		$smarty->assign("escola", $sup->escola);
		$smarty->assign("ano_formacao", $sup->ano_formacao);
		$smarty->assign("cargo", $sup->cargo);
		$smarty->assign("regiao", $sup->regiao);
		$smarty->assign("observacoes", $sup->observacoes);
		$smarty->assign("instituicoes", $sup->instituicoes());
		$smarty->display("supervisores-atualizar.tpl");
		exit;
	}

	/**
	 * Exclui um supervisor, bloqueando se houver estagiários vinculados.
	 * Substitui supervisores/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$supervisor_id = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

		if ($supervisor_id <= 0) {
			die("Supervisor inválido (id $supervisor_id).");
		}

		$sup = Supervisor::find($supervisor_id);
		if ($sup === null) {
			die("Supervisor não encontrado (id $supervisor_id).");
		}

		// Checagem de estagiários vinculados
		$countEstagiarios = $sup->countEstagiarios();
		if ($countEstagiarios > 0) {
			die("Operação abortada: não é possível excluir este supervisor porque existem "
				. "{$countEstagiarios} aluno(s) supervisionado(s) vinculado(s) a ele. "
				. "Remova os vínculos dos alunos antes de excluir.");
		}

		if (!$sup->delete()) {
			error_log("Erro ao excluir supervisor: " . Supervisor::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela supervisores.");
		}

		// Remove vínculos de instituições
		Supervisor::query("DELETE FROM inst_super WHERE supervisor_id = ?", array($supervisor_id));

		header("Location: listar.php");
		exit;
	}

	/**
	 * Lista alunos supervisionados por um supervisor específico.
	 * Substitui supervisores/exibir/alunos_supervisor.php.
	 */
	public function alunos_supervisor() {
		$this->comAutenticacao();

		$supervisor_id   = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		$nome_supervisor = isset($_REQUEST['nome_supervisor']) ? htmlspecialchars(trim($_REQUEST['nome_supervisor']), ENT_QUOTES, 'UTF-8') : '';
		if ($nome_supervisor === '' && $supervisor_id > 0) {
			$sup = Supervisor::find($supervisor_id);
			if ($sup) {
				$nome_supervisor = $sup->nome;
			}
		}
		$ordem           = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'periodo, nome';

		$ordenacoes = array(
			'periodo, nome' => 'periodo, nome',
			'nome'          => 'nome',
			'periodo'       => 'periodo',
			'instituicao'   => 'instituicao',
			'nome_desc'     => 'nome DESC',
		);
		if (!isset($ordenacoes[$ordem])) {
			$ordem = 'periodo, nome';
		}
		$orderby = $ordenacoes[$ordem];

		$sql = "SELECT e.id, e.aluno_id, e.registro, e.periodo, e.instituicao_id, "
			 . "a.nome, a.email, i.instituicao "
			 . "FROM estagiarios AS e "
			 . "JOIN alunos AS a ON e.registro = a.registro "
			 . "JOIN instituicoes AS i ON e.instituicao_id = i.id "
			 . "WHERE e.supervisor_id = ? ORDER BY $orderby";

		$alunos = Estagiario::query($sql, array($supervisor_id));
		$estagiario = array();
		if (!empty($alunos)) {
			foreach ($alunos as $item) {
				$estagiario[] = array(
					'registro'       => $item->registro,
					'aluno_id'       => (int)$item->aluno_id,
					'nome'           => $item->nome,
					'periodo'        => $item->periodo,
					'email'          => $item->email,
					'instituicao_id' => (int)$item->instituicao_id,
					'instituicao'    => $item->instituicao,
				);
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("supervisor_id", $supervisor_id);
		$smarty->assign("id_supervisor", $supervisor_id);
		$smarty->assign("nome_supervisor", $nome_supervisor);
		$smarty->assign("estagiario", $estagiario);
		$smarty->display("supervisores-alunos_supervisor.tpl");
		exit;
	}

	/**
	 * Seletor de supervisor para ações rápidas (modificar/cancelar/ver).
	 * Substitui supervisores/seleciona.php e supervisores/exibir/seleciona.php.
	 */
	public function seleciona() {
		$this->comAutenticacao();

		$opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';
		$res = Supervisor::all("ORDER BY nome");

		$supervisores = array();
		if (!empty($res)) {
			foreach ($res as $item) {
				$supervisores[] = array(
					'id'   => (int)$item->id,
					'nome' => $item->nome,
				);
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("opcao", $opcao);
		$smarty->assign("supervisores", $supervisores);
		$smarty->display("supervisores-seleciona.tpl");
		exit;
	}

	/**
	 * Busca de supervisores por nome.
	 * Substitui supervisores/buscar/busca.php e supervisores/buscar/resultado.php.
	 */
	public function buscar() {
		$this->comAutenticacao();

		$palavra = isset($_REQUEST['palavra']) ? trim($_REQUEST['palavra']) : '';
		$supervisores = array();

		if ($palavra !== '') {
			$res = Supervisor::query(
				"SELECT id, nome, cress, email, telefone, celular FROM supervisores WHERE nome LIKE ? ORDER BY nome",
				array("%$palavra%")
			);
			if (!empty($res)) {
				foreach ($res as $item) {
					$supervisores[] = array(
						'supervisor_id'   => (int)$item->id,
						'nome_supervisor' => $item->nome,
						'cress'           => $item->cress,
						'email'           => $item->email,
						'telefone'        => $item->telefone,
						'celular'         => $item->celular,
					);
				}
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("palavra", $palavra);
		$smarty->assign("supervisores", $supervisores);
		$smarty->display("supervisores-busca.tpl");
		exit;
	}

	/**
	 * Desvincula uma instituição de um supervisor e redireciona de volta.
	 * Substitui supervisores/cancelar/inst_supervisor.php.
	 */
	public function inst_supervisor() {
		$this->comAutenticacao();

		$supervisor_id  = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;

		if ($supervisor_id > 0 && $instituicao_id > 0) {
			$sup = Supervisor::find($supervisor_id);
			if ($sup) {
				$sup->desvincularInstituicao($instituicao_id);
			}
		}

		header("Location: ../instituicoes/ver_cada.php?instituicao_id=$instituicao_id");
		exit;
	}

}

}

?>
