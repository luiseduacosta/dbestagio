<?php
/**
 * InscricoesController.php — Controlador da tabela inscricoes.
 *
 * Reúne as ações relacionadas às inscrições de alunos nas ofertas do mural
 * (tabela inscricoes). Cada ação substitui um script legado:
 *
 *   inserir()    <- inserir/inserir.php + inserir/formulario.php
 *   atualizar()  <- atualizar/atualiza.php + atualizar/modifica.php
 *   excluir()    <- cancelar/cancela.php
 *   ver_cada()   <- exibir/ver_cada.php
 *   listar()     <- exibir/listar.php
 *
 * O controlador cuida apenas de HTTP (parâmetros, validação, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Inscricao (libphp/Inscricao.php) —
 * grava com parâmetros ligados via ADODB_Model, sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/InscricoesController.php");
 *   $controller = new InscricoesController();
 *   $controller->listar();
 */
if (!class_exists('InscricoesController', false)) {

class InscricoesController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php, incluído
	 * por autentica.inc, escreve $db; assim o valor cai em $GLOBALS e
	 * libphp/models.php consegue ligar a conexão aos modelos.
	 *
	 * Também seta $isAdmin e $isAluno a partir do cookie/role, replicando o
	 * comportamento das flags que existiam nos scripts antigos (vindas do
	 * autentica.inc original).
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_aluno_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	// ------------------------------------------------------------------
	// Permissões e helpers de aluno logado
	// (antigas funções inscricao_exigir_permissao/inscricao_aluno_* /
	//  inscricao_e_do_aluno de inscricoes/validar.php)
	// ------------------------------------------------------------------

	/**
	 * Permissões do módulo de inscrições:
	 *  - admin: acesso total;
	 *  - aluno: apenas as próprias inscrições;
	 *  - demais: morre com mensagem.
	 */
	private function exigirPermissao() {
		global $isAdmin, $isAluno;
		if (empty($isAdmin) && empty($isAluno)) {
			die("Acesso restrito: apenas administradores e alunos podem acessar as inscrições.");
		}
	}

	/** Id do aluno logado (tabela alunos), ou 0 quando não aplicável. */
	private function alunoIdLogado() {
		global $usuario_aluno_id;
		return (int)($usuario_aluno_id ? $usuario_aluno_id : 0);
	}

	/** Registro (matrícula) do aluno logado, ou '' quando não aplicável. */
	private function alunoRegistroLogado() {
		$aluno_id = $this->alunoIdLogado();
		if ($aluno_id <= 0 || !ADODB_Model::$db) {
			return '';
		}
		$aluno = Aluno::find($aluno_id);
		$registro = $aluno !== null ? $aluno->registro : '';
		return (string)$registro;
	}

	/** True se a inscrição pertence ao aluno logado. */
	private function eDoAluno($ins) {
		$aluno_id = $this->alunoIdLogado();
		if ($aluno_id > 0 && (int)$ins['aluno_id'] === $aluno_id) {
			return true;
		}
		$registro = $this->alunoRegistroLogado();
		return ($registro !== '' && (string)$ins['registro'] === $registro);
	}

	// ------------------------------------------------------------------
	// Validação do POST
	// (antiga função inscricao_validar_post de inscricoes/validar.php)
	// ------------------------------------------------------------------

	/**
	 * Lê e valida os campos do formulário (POST), devolvendo os dados prontos
	 * para usar nas operações de inserção/edição.
	 */
	private function validarPost() {
		$registro        = isset($_POST['registro'])        ? (int)$_POST['registro'] : 0;
		$muralestagio_id = isset($_POST['muralestagio_id']) ? (int)$_POST['muralestagio_id'] : 0;
		$data            = isset($_POST['data'])            ? trim($_POST['data']) : '';
		$periodo         = isset($_POST['periodo'])         ? trim($_POST['periodo']) : '';

		if ($registro <= 0) {
			die("Informe o registro (matrícula) do aluno.");
		}
		if ($muralestagio_id <= 0) {
			die("Selecione a oferta do mural.");
		}
		if ($data === '') {
			$data = date('Y-m-d');
		} elseif (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
			die("A data deve estar no formato AAAA-MM-DD (ou vazia para usar hoje).");
		}
		if ($periodo === '') {
			$periodo = defined('PERIODO_ATUAL') ? PERIODO_ATUAL : '';
		}

		return array(
			'registro'        => $registro,
			'muralestagio_id' => $muralestagio_id,
			'data'            => $data,
			'periodo'         => $periodo,
		);
	}

	// ------------------------------------------------------------------
	// Seletores (periodo, oferta, aluno)
	// (antigas funções inscricao_periodos de inscricoes/validar.php)
	// ------------------------------------------------------------------

	/** Informa os períodos disponíveis (dos modelos Mural e Inscricao) para o formulário. */
	private function periodosDisponiveis() {
		$periodos = Inscricao::periodos();
		$atuais   = Mural::periodos();
		return array_values(array_unique(array_merge($periodos, $atuais)));
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção e, quando há POST, grava a inscrição.
	 * Substitui inscricoes/inserir/inserir.php + inscricoes/inserir/formulario.php.
	 */
	public function inserir() {
		global $isAdmin, $isAluno;
		$this->comAutenticacao();
		$this->exigirPermissao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			if ($isAluno) {
				$_POST['registro'] = $this->alunoRegistroLogado();
			}

			$dados = $this->validarPost();

			// Impede inscrição duplicada do mesmo aluno na mesma oferta/período.
			if (Inscricao::duplicada($dados['registro'], $dados['muralestagio_id'], $dados['periodo']) !== null) {
				die("Este aluno já está inscrito nesta oferta ({$dados['periodo']}).");
			}

			$aluno = Inscricao::alunoPorRegistro($dados['registro']);
			if ($aluno === null) {
				die("Nenhum aluno encontrado com o registro {$dados['registro']}.");
			}

			$nova = new Inscricao();
			$nova->registro        = $dados['registro'];
			$nova->muralestagio_id = $dados['muralestagio_id'];
			$nova->data            = $dados['data'];
			$nova->periodo         = $dados['periodo'];
			$nova->aluno_id        = $aluno['id'];
			$nova->timestamp       = date('Y-m-d H:i:s');

			if (!$nova->save()) {
				error_log("Erro ao inserir inscricao: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela inscricoes.");
			}

			header("Location: listar.php?periodo=" . urlencode($dados['periodo'])
			     . "&muralestagio_id={$dados['muralestagio_id']}");
			exit;
		}

		// GET: renderiza o formulário de inclusão.
		$periodo   = isset($_GET['periodo']) ? trim($_GET['periodo']) : PERIODO_ATUAL;
		$periodos  = $this->periodosDisponiveis();
		if (!in_array($periodo, $periodos, true)) {
			$periodo = PERIODO_ATUAL;
		}
		$ofertas = Inscricao::ofertasPorPeriodo($periodo);

		$v = array(
			'muralestagio_id' => isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0,
			'registro'        => isset($_GET['registro']) ? trim($_GET['registro']) : '',
			'periodo'         => $periodo,
			'data'            => date('Y-m-d'),
		);

		// Aluno: a inscrição é sempre a própria (registro travado).
		$alunos = array();
		if ($isAluno) {
			$v['registro'] = $this->alunoRegistroLogado();
		} else {
			$alunos = Inscricao::alunosLista();
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("periodos", $periodos);
		$smarty->assign("ofertas", $ofertas);
		$smarty->assign("alunos", $alunos);
		$smarty->assign("e_aluno", $isAluno);
		$smarty->assign("v", $v);
		$smarty->assign("acao", "inserir.php");
		$smarty->assign("titulo", "Inscrever aluno");
		$smarty->assign("e_edicao", false);
		$smarty->display("inscricoes-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) ou grava a edição confirmada (POST).
	 * Substitui inscricoes/atualizar/atualiza.php + inscricoes/atualizar/modifica.php.
	 */
	public function atualizar() {
		global $isAdmin, $isAluno;
		$this->comAutenticacao();
		$this->exigirPermissao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
			$inscricao = Inscricao::find($id);
			if ($inscricao === null) {
				die("Inscrição não encontrada (id $id).");
			}
			if ($isAluno) {
				$ins_arr = Inscricao::buscar($id);
				if ($ins_arr === null || !$this->eDoAluno($ins_arr)) {
					die("Esta inscrição não pertence ao seu usuário.");
				}
				$_POST['registro'] = $this->alunoRegistroLogado();
			}

			$dados = $this->validarPost();

			// Evita duplicidade salvo na própria inscrição em edição.
			$dup = Inscricao::duplicada(
				$dados['registro'],
				$dados['muralestagio_id'],
				$dados['periodo'],
				$id
			);
			if ($dup !== null) {
				die("Este aluno já está inscrito nesta oferta ({$dados['periodo']}).");
			}

			$aluno = Inscricao::alunoPorRegistro($dados['registro']);
			if ($aluno === null) {
				die("Nenhum aluno encontrado com o registro {$dados['registro']}.");
			}

			$inscricao->registro        = $dados['registro'];
			$inscricao->muralestagio_id = $dados['muralestagio_id'];
			$inscricao->data            = $dados['data'];
			$inscricao->periodo         = $dados['periodo'];
			$inscricao->aluno_id        = $aluno['id'];

			if (!$inscricao->save()) {
				error_log("Erro ao atualizar inscricao: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar o registro na tabela inscricoes.");
			}

			header("Location: listar.php?periodo=" . urlencode($dados['periodo'])
			     . "&muralestagio_id={$dados['muralestagio_id']}");
			exit;
		}

		// GET: renderiza o formulário de edição.
		$id  = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$ins = Inscricao::buscar($id);
		if ($ins === null) {
			die("Inscrição não encontrada (id $id).");
		}
		if ($isAluno && !$this->eDoAluno($ins)) {
			die("Esta inscrição não pertence ao seu usuário.");
		}

		$periodo  = $ins['periodo'];
		$periodos = $this->periodosDisponiveis();
		$ofertas  = Inscricao::ofertasPorPeriodo($periodo);

		$v = array(
			'id'              => $ins['id'],
			'muralestagio_id' => $ins['muralestagio_id'],
			'registro'        => $ins['registro'],
			'periodo'         => $periodo,
			'data'            => $ins['data_iso'],
		);

		// Aluno: o registro fica travado no próprio aluno.
		$alunos = array();
		if ($isAluno) {
			$v['registro'] = $this->alunoRegistroLogado();
		} else {
			$alunos = Inscricao::alunosLista();
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("periodos", $periodos);
		$smarty->assign("ofertas", $ofertas);
		$smarty->assign("alunos", $alunos);
		$smarty->assign("e_aluno", $isAluno);
		$smarty->assign("v", $v);
		$smarty->assign("acao", "atualizar.php");
		$smarty->assign("titulo", "Editar inscrição");
		$smarty->assign("e_edicao", true);
		$smarty->display("inscricoes-atualizar.tpl");
		exit;
	}

	/**
	 * Exclui uma inscrição (DELETE). Substitui inscricoes/cancelar/cancela.php.
	 */
	public function excluir() {
		global $isAluno;
		$this->comAutenticacao();
		$this->exigirPermissao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$inscricao = Inscricao::find($id);
		if ($inscricao === null) {
			die("Inscrição não encontrada (id $id).");
		}
		if ($isAluno) {
			$ins_arr = Inscricao::buscar($id);
			if ($ins_arr === null || !$this->eDoAluno($ins_arr)) {
				die("Esta inscrição não pertence ao seu usuário.");
			}
		}

		if (!$inscricao->delete()) {
			error_log("Erro ao excluir inscricao: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela inscricoes.");
		}

		$periodo         = isset($_GET['periodo'])         ? trim($_GET['periodo']) : '';
		$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

		header("Location: listar.php?periodo=" . urlencode($periodo)
		     . "&muralestagio_id=$muralestagio_id");
		exit;
	}

	/**
	 * Exibe uma inscrição. Substitui inscricoes/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		global $isAluno;
		$this->comAutenticacao();
		$this->exigirPermissao();

		$id  = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$ins = Inscricao::buscar($id);
		if ($ins === null) {
			die("Inscrição não encontrada (id $id).");
		}
		if ($isAluno && !$this->eDoAluno($ins)) {
			die("Esta inscrição não pertence ao seu usuário.");
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("ins", $ins);
		$smarty->display("inscricoes-ver_cada.tpl");
		exit;
	}

	/**
	 * Lista inscrições (index do módulo). Substitui inscricoes/exibir/listar.php.
	 */
	public function listar() {
		global $isAluno;
		$this->comAutenticacao();
		$this->exigirPermissao();

		// Período selecionado (filtra). Vazio = PERIODO_ATUAL.
		$periodo = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
		if ($periodo === '') {
			$periodo = PERIODO_ATUAL;
		}

		// Oferta específica (quando acessado a partir do mural: mural/inscritos.php).
		$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

		$periodos = $this->periodosDisponiveis();
		if (!in_array($periodo, $periodos, true)) {
			$periodo = PERIODO_ATUAL;
		}

		// Aluno: a listagem mostra apenas as próprias inscrições.
		if ($isAluno) {
			$filtro_aluno_id  = $this->alunoIdLogado();
			$filtro_aluno_reg = $this->alunoRegistroLogado();
		} else {
			$filtro_aluno_id  = 0;
			$filtro_aluno_reg = '';
		}
		$inscritos = Inscricao::listar(
			$periodo,
			$muralestagio_id,
			$filtro_aluno_id,
			$filtro_aluno_reg
		);

		// Instituição da oferta (exibida no título quando filtrado por oferta).
		$titulo_oferta = '';
		if ($muralestagio_id > 0) {
			foreach ($inscritos as $ins) {
				if ($ins['muralestagio_id'] == $muralestagio_id) {
					$titulo_oferta = $ins['oferta_instituicao'];
					break;
				}
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo", $periodo);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("muralestagio_id", $muralestagio_id);
		$smarty->assign("titulo_oferta", $titulo_oferta);
		$smarty->assign("inscritos", $inscritos);
		$smarty->display("inscricoes-listar.tpl");
		exit;
	}

}

}

?>