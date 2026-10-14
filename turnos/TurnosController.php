<?php
/**
 * TurnosController.php — Controlador da tabela turnos.
 *
 * Reúne as ações relacionadas ao cadastro de turnos de alunos (tabela turnos).
 * Cada método substitui os scripts legados:
 *
 *   inserir()   <- inserir/formulario.php + inserir/inserir.php
 *   atualizar() <- atualizar/modifica.php + atualizar/atualiza.php
 *   ver_cada()  <- exibir/ver_cada.php
 *   listar()    <- exibir/listar.php
 *   excluir()   <- exclusão com proteção contra turnos com alunos vinculados
 *
 * O controlador cuida de HTTP (parâmetros, sanitização, autenticação, assigns
 * do Smarty e redirecionamentos). A persistência é feita via model Turno
 * (libphp/Turno.php).
 *
 * Uso:
 *   require_once(__DIR__ . "/TurnosController.php");
 *   $controller = new TurnosController();
 *   $controller->listar();
 */
if (!class_exists('TurnosController', false)) {

class TurnosController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Lê um campo de texto do POST (trim).
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Lista de turnos com a quantidade de alunos vinculados a cada um.
	 * Substitui turnos/exibir/listar.php.
	 */
	public function listar() {
		$this->comAutenticacao();

		$turnos = Turno::listar();

		$smarty = new Smarty_estagio;
		$smarty->assign("turnos", $turnos);
		$smarty->display("turnos-listar.tpl");
		exit;
	}

	/**
	 * Ficha de um turno, exibindo seus dados e a lista de alunos matriculados
	 * neste turno. Substitui turnos/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$turno = Turno::find($id);

		if ($turno === null) {
			die("Turno não encontrado (id $id).");
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("t", $turno->toArray());
		$smarty->assign("num_alunos", $turno->countAlunos());
		$smarty->assign("alunos", $turno->alunos());
		$smarty->display("turnos-ver_cada.tpl");
		exit;
	}

	/**
	 * Formulário de inclusão (GET) e gravação de novo turno (POST).
	 * Substitui turnos/inserir/formulario.php + turnos/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nomeTurno = $this->campoTexto('turno');

			if ($nomeTurno === '') {
				die("Informe o nome do turno.");
			}

			if (Turno::duplicado($nomeTurno) !== null) {
				die("Esse turno já está cadastrado.");
			}

			$novo = new Turno();
			$novo->id    = Turno::proximoId(); // id não é auto_increment.
			$novo->turno = $nomeTurno;

			if (!$novo->save()) {
				error_log("Erro ao inserir turno: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela turnos.");
			}

			header("Location: ver_cada.php?id=" . $novo->id);
			exit;
		}

		// GET: exibe formulário vazio
		$v = array(
			'id'    => null,
			'turno' => '',
		);

		$smarty = new Smarty_estagio;
		$smarty->assign("v", $v);
		$smarty->assign("acao", "inserir.php");
		$smarty->assign("titulo", "Inserir turno");
		$smarty->display("turnos-inserir.tpl");
		exit;
	}

	/**
	 * Formulário de edição (GET) e gravação da alteração do turno (POST).
	 * Substitui turnos/atualizar/modifica.php + turnos/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$turno = Turno::find($id);

		if ($turno === null) {
			die("Turno não encontrado (id $id).");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$nomeTurno = $this->campoTexto('turno');

			if ($nomeTurno === '') {
				die("Informe o nome do turno.");
			}

			if (Turno::duplicado($nomeTurno, $id) !== null) {
				die("Já existe outro turno com esse nome.");
			}

			$turno->turno = $nomeTurno;

			if (!$turno->save()) {
				error_log("Erro ao atualizar turno: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar o registro na tabela turnos.");
			}

			header("Location: ver_cada.php?id=$id");
			exit;
		}

		// GET: exibe formulário pré-preenchido
		$v = array(
			'id'    => $turno->getKey(),
			'turno' => $turno->turno,
		);

		$smarty = new Smarty_estagio;
		$smarty->assign("v", $v);
		$smarty->assign("acao", "atualizar.php");
		$smarty->assign("titulo", "Editar turno");
		$smarty->display("turnos-atualizar.tpl");
		exit;
	}

	/**
	 * Exclui um turno, verificando se há alunos vinculados.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$id = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$turno = Turno::find($id);

		if ($turno === null) {
			die("Turno não encontrado (id $id).");
		}

		$numAlunos = $turno->countAlunos();
		if ($numAlunos > 0) {
			die("Não é possível excluir este turno: há {$numAlunos} aluno(s) vinculado(s) a ele. "
				. "Altere o turno dos alunos antes de excluir.");
		}

		if (!$turno->delete()) {
			error_log("Erro ao excluir turno: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela turnos.");
		}

		header("Location: listar.php");
		exit;
	}

}

}

?>
