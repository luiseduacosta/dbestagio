<?php
/**
 * VisitasController.php — Controlador da tabela visitas.
 *
 * Reúne as ações relacionadas ao registro de visitas às instituições
 * conveniadas (tabela visitas). Cada método substitui um script legado:
 *
 *   inserir()    <- inserir/inserir.php + inserir/formulario.php
 *   atualizar()  <- atualizar/atualiza.php + atualizar/modifica.php
 *   ver_cada()   <- exibir/ver_cada.php
 *   listar()     <- exibir/listar.php
 *   excluir()    <- cancelar/cancela.php
 *
 * Os helpers compartilhados que ficavam em visitas/validar.php viraram
 * métodos privados:
 *   validarPost()        <- visitas_validar_post()
 *   preencheFormulario() <- visitas_html_common()
 *
 * O controlador cuida apenas de HTTP (parâmetros, validação, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Visita (libphp/Visita.php), que grava
 * com parâmetros ligados via ADODB_Model — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/VisitasController.php");
 *   $controller = new VisitasController();
 *   $controller->listar();
 *
 * Permissões (replicando o comportamento dos scripts legados):
 *  - inserir / atualizar / ver_cada / excluir: exigem login (autentica.inc)
 *  - listar: público (apenas setup.php)
 */
if (!class_exists('VisitasController', false)) {

class VisitasController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php, incluído
	 * por autentica.inc, escreve $db; assim o valor cai em $GLOBALS e
	 * libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Inclui apenas setup.php (sem forçar login) e carrega os models.
	 * Usado pela listagem pública, como no script legado.
	 */
	private function semAutenticacao() {
		global $db;
		include_once(__DIR__ . "/../setup.php");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	// ------------------------------------------------------------------
	// Auxiliares (antigos helpers de visitas/validar.php)
	// ------------------------------------------------------------------

	/**
	 * Lê um campo de texto do POST (trim). Devolve string vazia quando
	 * ausente, para evitar notices em comparações com ''.
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	/**
	 * Lê e valida os campos do formulário (POST), devolvendo um array pronto
	 * para atribuir ao model Visita. Morre com mensagem quando inválido.
	 * Substitui a antiga função visitas_validar_post().
	 */
	private function validarPost() {
		$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
		$professor_id   = isset($_POST['professor_id']) ? (int)$_POST['professor_id'] : 0;
		$data           = $this->campoTexto('data');
		$motivo         = $this->campoTexto('motivo');
		$responsavel    = $this->campoTexto('responsavel');
		$descricao      = $this->campoTexto('descricao');
		$avaliacao      = $this->campoTexto('avaliacao');

		if ($instituicao_id <= 0) {
			die("Selecione uma instituição.");
		}
		if ($data === '' || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
			die("Informe uma data válida (AAAA-MM-DD).");
		}
		if ($motivo === '') {
			die("Informe o motivo da visita.");
		}
		if ($responsavel === '') {
			die("Informe o responsável pela visita.");
		}

		// Limites de tamanho conforme a tabela.
		if (mb_strlen($motivo, 'UTF-8') > 256)      $motivo = mb_substr($motivo, 0, 256, 'UTF-8');
		if (mb_strlen($responsavel, 'UTF-8') > 50)  $responsavel = mb_substr($responsavel, 0, 50, 'UTF-8');
		if (mb_strlen($avaliacao, 'UTF-8') > 50)    $avaliacao = mb_substr($avaliacao, 0, 50, 'UTF-8');

		return array(
			'instituicao_id' => $instituicao_id,
			'professor_id'   => $professor_id > 0 ? $professor_id : null,
			'data'           => $data,
			'motivo'         => $motivo,
			'responsavel'    => $responsavel,
			'descricao'      => $descricao !== '' ? $descricao : null,
			'avaliacao'      => $avaliacao,
		);
	}

	/**
	 * Preenche os seletores comuns aos formulários de inserção e edição.
	 * Substitui a antiga função visitas_html_common().
	 */
	private function preencheFormulario($smarty, $visitaDados = array()) {
		$smarty->assign("instituicoes", Instituicao::listar_todas());
		$smarty->assign("professores",  Visita::listar_professores());
		$smarty->assign("motivos",      Visita::motivos());
		$smarty->assign("avaliacoes",   Visita::avaliacoes());
		$smarty->assign("v", $visitaDados);
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção (GET) e, quando há POST, grava a visita.
	 * Substitui visitas/inserir/formulario.php + visitas/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dadosVisita = $this->validarPost();

			$nova = new Visita();
			foreach ($dadosVisita as $campo => $valor) {
				$nova->$campo = $valor;
			}

			if (!$nova->save()) {
				error_log("Erro ao inserir visita: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela visitas.");
			}

			header("Location: listar.php");
			exit;
		}

		// GET: renderiza o formulário de inclusão.
		$smarty = new Smarty_estagio;

		$dados_vazio = array(
			'id'             => null,
			'data'           => date('Y-m-d'),
			'instituicao_id' => isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0,
			'professor_id'   => 0,
			'motivo'         => '',
			'responsavel'    => '',
			'descricao'      => '',
			'avaliacao'      => 'Boa',
		);

		$this->preencheFormulario($smarty, $dados_vazio);
		$smarty->assign("acao", "inserir.php");
		$smarty->assign("titulo", "Inserir visita");
		$smarty->display("visitas-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) e, quando há POST, grava as
	 * alterações. Substitui visitas/atualizar/modifica.php +
	 * visitas/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$visita = Visita::find($id);
			if ($visita === null) {
				die("Visita não encontrada (id $id).");
			}

			$dadosVisita = $this->validarPost();
			foreach ($dadosVisita as $campo => $valor) {
				$visita->$campo = $valor;
			}

			if (!$visita->save()) {
				error_log("Erro ao atualizar visita: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar o registro na tabela visitas.");
			}

			header("Location: listar.php");
			exit;
		}

		// GET: renderiza o formulário de edição (pré-preenchido).
		$v = Visita::find($id);
		if ($v === null) {
			die("Visita não encontrada (id $id).");
		}

		$smarty = new Smarty_estagio;

		$dados = array(
			'id'             => $v->getKey(),
			'data'           => $v->data,
			'instituicao_id' => (int)$v->instituicao_id,
			'professor_id'   => (int)$v->professor_id,
			'motivo'         => $v->motivo,
			'responsavel'    => $v->responsavel,
			'descricao'      => $v->descricao,
			'avaliacao'      => $v->avaliacao,
		);

		$this->preencheFormulario($smarty, $dados);
		$smarty->assign("acao", "atualizar.php");
		$smarty->assign("titulo", "Editar visita");
		$smarty->display("visitas-atualizar.tpl");
		exit;
	}

	/**
	 * Ficha de uma visita, com os nomes de instituição e professor resolvidos.
	 * Substitui visitas/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$v = Visita::find($id);

		if ($v === null) {
			die("Visita não encontrada (id $id).");
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("v", $v->toArray());
		$smarty->assign("nome_instituicao", $v->instituicaoNome());
		$smarty->assign("nome_professor", $v->professorNome());
		$smarty->display("visitas-ver_cada.tpl");

		exit;
	}

	/**
	 * Lista as visitas registradas, com filtro opcional por instituição.
	 * Substitui visitas/exibir/listar.php.
	 */
	public function listar() {
		$this->comAutenticacao();

		// Filtro opcional por instituição.
		$filtro_instituicao = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;

		$visitas = Visita::listar($filtro_instituicao);

		$smarty = new Smarty_estagio;
		$smarty->assign("visitas", $visitas);
		$smarty->assign("filtro_instituicao", $filtro_instituicao);
		$smarty->display("visitas-listar.tpl");

		exit;
	}

	/**
	 * Exclui uma visita. Substitui visitas/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;

		$visita = Visita::find($id);
		if ($visita === null) {
			die("Visita não encontrada (id $id).");
		}

		if (!$visita->delete()) {
			error_log("Erro ao excluir visita: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela visitas.");
		}

		header("Location: listar.php");
		exit;
	}

}

}

?>
