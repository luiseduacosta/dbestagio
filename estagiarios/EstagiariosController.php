<?php
/**
 * EstagiariosController.php — Controlador da tabela estagiarios.
 *
 * Reúne as ações relacionadas ao cadastro de estágios (tabela estagiarios).
 * Cada método substitui um script legado:
 *
 *   inserir()    <- inserir/form.php + inserir/inserir.php
 *   atualizar()  <- atualizar/atualiza.php (exibição do form E gravação)
 *   ver_cada()   <- exibir/ver_cada.php
 *   listar()     <- exibir/listar.php
 *   excluir()    <- cancelar/cancela.php
 *
 * Os helpers compartilhados que ficavam em estagiarios/validar.php viraram
 * métodos privados:
 *   normalizaData()      <- estagiario_data()
 *   validarPost()        <- estagiario_ler_post()
 *   preencheFormulario() <- (assigns comuns de inserir/form.php e
 *                            atualizar/atualiza.php)
 *
 * O controlador cuida apenas de HTTP (parâmetros, validação, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Estagiario (libphp/Estagiario.php), que
 * grava com parâmetros ligados via ADODB_Model — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/EstagiariosController.php");
 *   $controller = new EstagiariosController();
 *   $controller->listar();
 *
 * Permissões (replicando o comportamento dos scripts legados):
 *  - todas as ações exigem login (autentica.inc).
 *  - salvarNotaCh: além do login, exige ser admin ou o professor responsável
 *    pelo estágio (nota e carga horária são lançadas pelo professor).
 */
if (!class_exists('EstagiariosController', false)) {

class EstagiariosController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php, incluído
	 * por autentica.inc, escreve $db; assim o valor cai em $GLOBALS e
	 * libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $isProfessor, $usuario_id, $usuario_professor_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	// ------------------------------------------------------------------
	// Auxiliares (antigos helpers de estagiarios/validar.php)
	// ------------------------------------------------------------------

	/**
	 * Lê um campo de texto do POST (trim). Devolve string vazia quando
	 * ausente, para evitar notices em comparações com ''.
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	/**
	 * Normaliza uma data de formulário para AAAA-MM-DD (ou '' se vazia/ inválida).
	 * Substitui a antiga função estagiario_data().
	 */
	private function normalizaData($valor) {
		$valor = trim((string)$valor);
		if ($valor === '' || strtolower($valor) === '0000-00-00' || strtolower($valor) === 'null') {
			return '';
		}
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $valor, $m)) {
			return $m[3] . '-' . $m[2] . '-' . $m[1];
		}
		if (preg_match('#^(\d{4})-(\d{2})-(\d{2})#', $valor, $m)) {
			return $m[1] . '-' . $m[2] . '-' . $m[3];
		}
		return '';
	}

	/**
	 * Lê e valida os campos do formulário de estágio (POST), devolvendo os
	 * dados prontos para usar nas operações de inserção/edição.
	 * Substitui a antiga função estagiario_ler_post().
	 */
	private function validarPost() {
		$aluno_id = isset($_POST['aluno_id']) ? (int)$_POST['aluno_id'] : 0;
		if ($aluno_id <= 0) {
			die("Selecione o aluno.");
		}

		$periodo = $this->campoTexto('periodo');
		if ($periodo === '') {
			die("Informe o período.");
		}

		$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
		if ($instituicao_id <= 0) {
			die("Selecione a instituição.");
		}

		$nivel = $this->campoTexto('nivel');
		if ($nivel === '') {
			die("Selecione o nível.");
		}

		// Data opcional.
		$tc_solicitacao = $this->normalizaData(isset($_POST['tc_solicitacao']) ? $_POST['tc_solicitacao'] : '');

		// Benefícios (checkboxes).
		$benetransporte  = isset($_POST['benetransporte']) ? 1 : 0;
		$benealimentacao = isset($_POST['benealimentacao']) ? 1 : 0;

		// Registra o registro do aluno automaticamente a partir da tabela alunos.
		$registro = 0;
		$aluno = Aluno::find($aluno_id);
		if ($aluno !== null) {
			$registro = (int)$aluno->registro;
		}

		return array(
			'registro'         => $registro,
			'aluno_id'         => $aluno_id,
			'nivel'            => $nivel,
			'tc'               => isset($_POST['tc']) ? (int)$_POST['tc'] : 0,
			'tc_solicitacao'   => ($tc_solicitacao === '') ? null : $tc_solicitacao,
			'instituicao_id'   => $instituicao_id,
			'supervisor_id'    => isset($_POST['supervisor_id']) ? (int)$_POST['supervisor_id'] : 0,
			'professor_id'     => isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : 0,
			'periodo'          => $periodo,
			'nota'             => (isset($_POST['nota']) && $_POST['nota'] !== '') ? $_POST['nota'] : null,
			'ch'               => (isset($_POST['ch']) && $_POST['ch'] !== '') ? (int)$_POST['ch'] : null,
			'ajuste2020'       => isset($_POST['ajuste2020']) ? (string)(int)$_POST['ajuste2020'] : '0',
			'benetransporte'   => $benetransporte,
			'benealimentacao'  => $benealimentacao,
			'benebolsa'        => (isset($_POST['benebolsa']) && $_POST['benebolsa'] !== '') ? $_POST['benebolsa'] : null,
			'observacoes'      => isset($_POST['observacoes']) ? trim($_POST['observacoes']) : null,
		);
	}

	/**
	 * Preenche os seletores e variáveis comuns aos formulários de inserção e
	 * edição de estágio.
	 */
	private function preencheFormulario($smarty, $v, $acao, $titulo, $e_edicao) {
		$smarty->assign("alunos",       Estagiario::alunos());
		$smarty->assign("instituicoes", Instituicao::listar_todas());
		$smarty->assign("supervisores", Supervisor::listar('nome'));
		$smarty->assign("professores",  Professor::listar('', '', 'p.nome'));
		$smarty->assign("niveis",       Estagiario::niveis());
		$smarty->assign("v", $v);
		$smarty->assign("acao", $acao);
		$smarty->assign("titulo", $titulo);
		$smarty->assign("e_edicao", $e_edicao);
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção (GET) e, quando há POST, grava o estágio.
	 * Substitui estagiarios/inserir/form.php + estagiarios/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->validarPost();
			$dados['id'] = 0;

			// Evita duplicidade (mesmo aluno + período + nível).
			$dup = Estagiario::duplicado($dados['aluno_id'], $dados['periodo'], $dados['nivel'], null);
			if ($dup !== null) {
				die("Este aluno já possui um estágio neste período e nível (id $dup).");
			}

			$estagiario = new Estagiario();
			$estagiario->preencher($dados);

			if (!$estagiario->save()) {
				error_log("Erro ao inserir estagio: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela estagiarios.");
			}

			$id = $estagiario->getKey();
			header("Location: ver_cada.php?id=$id");
			exit;
		}

		// GET: renderiza o formulário de inclusão (pode vir pré-preenchido).
		$periodo  = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
		$aluno_id = isset($_GET['aluno_id']) ? (int)$_GET['aluno_id'] : 0;

		$v = array(
			'aluno_id'         => $aluno_id,
			'periodo'          => $periodo,
			'nivel'            => '',
			'tc'               => 0,
			'tc_solicitacao'   => '',
			'instituicao_id'   => 0,
			'supervisor_id'    => 0,
			'professor_id'     => 0,
			'nota'             => '',
			'ch'               => '',
			'ajuste2020'       => '0',
			'benetransporte'   => 0,
			'benealimentacao'  => 0,
			'benebolsa'        => '',
			'observacoes'      => '',
		);

		$smarty = new Smarty_estagio;
		$this->preencheFormulario($smarty, $v, "inserir.php", "Novo estágio", false);
		$smarty->display("estagiarios-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) e, quando há POST, grava as
	 * alterações. Substitui estagiarios/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

		// ----- Exibição do formulário de edição -----
		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			$est = Estagiario::find($id);
			if ($est === null) {
				die("Estágio não encontrado (id $id).");
			}
			$data = $est->toArray();

			$v = array(
				'aluno_id'         => (int)$data['aluno_id'],
				'periodo'          => $data['periodo'],
				'nivel'            => $data['nivel'],
				'tc'               => (int)$data['tc'],
				'tc_solicitacao'   => ($data['tc_solicitacao'] !== null && $data['tc_solicitacao'] !== '0000-00-00') ? $data['tc_solicitacao'] : '',
				'instituicao_id'   => (int)$data['instituicao_id'],
				'supervisor_id'    => (int)$data['supervisor_id'],
				'professor_id'     => (int)$data['professor_id'],
				'nota'             => ($data['nota'] !== null) ? $data['nota'] : '',
				'ch'               => ($data['ch'] !== null) ? (int)$data['ch'] : '',
				'ajuste2020'       => $data['ajuste2020'],
				'benetransporte'   => (int)$data['benetransporte'],
				'benealimentacao'  => (int)$data['benealimentacao'],
				'benebolsa'        => $data['benebolsa'],
				'observacoes'      => $data['observacoes'],
			);

			$smarty = new Smarty_estagio;
			$this->preencheFormulario($smarty, $v, "atualizar.php", "Editar estágio", true);
			$smarty->display("estagiarios-atualizar.tpl");
			exit;
		}

		// ----- Gravação da edição -----
		$dados = $this->validarPost();

		$est = Estagiario::find($id);
		if ($est === null) {
			die("Estágio não encontrado (id $id).");
		}

		$dup = Estagiario::duplicado($dados['aluno_id'], $dados['periodo'], $dados['nivel'], $id);
		if ($dup !== null) {
			die("Este aluno já possui outro estágio neste período e nível (id $dup).");
		}

		$est->preencher($dados);

		if (!$est->save()) {
			error_log("Erro ao atualizar estagio: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível atualizar o registro na tabela estagiarios.");
		}

		header("Location: ver_cada.php?id=$id");
		exit;
	}

	/**
	 * Grava apenas a nota e a carga horária de um estágio.
	 *
	 * É a ação central do professor: ele lança a nota e a carga horária dos
	 * seus próprios estagiários. Restrita a admin ou ao professor responsável
	 * pelo estágio (e.professor_id). Chamada pelo formulário inline da ficha
	 * do professor (professores/ver_cada.php).
	 */
	public function salvarNotaCh() {
		global $isAdmin, $isProfessor, $usuario_professor_id;
		$this->comAutenticacao();

		$estagiario_id = isset($_POST['estagiario_id']) ? (int)$_POST['estagiario_id'] : 0;

		$est = Estagiario::find($estagiario_id);
		if ($est === null) {
			die("Estágio não encontrado (id $estagiario_id).");
		}

		$professor_id = (int)$est->professor_id;

		// Autorização: admin lança em qualquer estágio; o professor somente
		// nos estágios dos quais é o responsável.
		if (!$isAdmin) {
			if (!$isProfessor || (int)$usuario_professor_id !== $professor_id) {
				die("Somente o professor responsável (ou um administrador) pode lançar nota e carga horária.");
			}
		}

		$nota = isset($_POST['nota']) ? trim((string)$_POST['nota']) : '';
		$ch   = isset($_POST['ch']) ? trim((string)$_POST['ch']) : '';

		$est->nota = ($nota === '') ? null : $nota;
		$est->ch   = ($ch === '') ? null : (int)$ch;

		if (!$est->save()) {
			error_log("Erro ao salvar nota/ch: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível salvar a nota e a carga horária.");
		}

		// Volta para a ficha do professor responsável.
		header("Location: ../professores/ver_cada.php?professor_id=$professor_id");
		exit;
	}

	/**
	 * Ficha de um estágio, com aluno, instituição, supervisor e professor já
	 * resolvidos na consulta. Substitui estagiarios/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

		$dados = Estagiario::buscar($id);
		if ($dados === null) {
			header("Location: listar.php");
			exit;
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("e", $dados);
		$smarty->display("estagiarios-ver_cada.tpl");

		exit;
	}

	/**
	 * Lista os estágios com filtros por período/busca e ordenação segura.
	 * Substitui estagiarios/exibir/listar.php.
	 */
	public function listar() {
		$this->comAutenticacao();

		$periodo  = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
		$busca    = isset($_GET['busca']) ? trim($_GET['busca']) : '';
		$ordem    = isset($_GET['orderby']) ? trim($_GET['orderby']) : 'periodo_desc';

		// Whitelist de colunas de ordenação.
		$colunas_ordem = array(
			'periodo_desc' => 'e.periodo DESC, a.nome',
			'periodo_asc'  => 'e.periodo ASC, a.nome',
			'aluno'        => 'a.nome',
			'registro'     => 'e.registro',
			'instituicao'  => 'i.instituicao',
			'supervisor'   => 's.nome',
			'professor'    => 'p.nome',
		);
		if (!isset($colunas_ordem[$ordem])) {
			$ordem = 'periodo_desc';
		}
		$orderby = $colunas_ordem[$ordem];

		// Valida período contra a lista fechada.
		$periodos = Estagiario::periodos();
		if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
			$periodo = '';
		}

		$estagiarios = Estagiario::listar($periodo, $busca, $orderby);

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo", $periodo);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("busca", $busca);
		$smarty->assign("estagiarios", $estagiarios);
		$smarty->display("estagiarios-listar.tpl");

		exit;
	}

	/**
	 * Exclui um estágio. Substitui estagiarios/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

		$est = Estagiario::find($id);
		if ($est === null) {
			die("Estágio não encontrado (id $id).");
		}

		if (!$est->delete()) {
			error_log("Erro ao excluir estagio: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela estagiarios.");
		}

		header("Location: listar.php");
		exit;
	}

}

}

?>