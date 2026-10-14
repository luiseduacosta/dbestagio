<?php
/**
 * UsersController.php — Controlador da tabela users.
 *
 * Reúne as ações relacionadas aos usuários do sistema (tabela users). Cada
 * método substitui um script legado:
 *
 *   inserir()   <- inserir/formulario.php + inserir/inserir.php
 *   atualizar() <- atualizar/modifica.php + atualizar/atualiza.php
 *   ver_cada()  <- exibir/ver_cada.php
 *   listar()    <- exibir/listar.php
 *   excluir()   <- cancelar/cancela.php
 *
 * As funções auxiliares de validar.php (users_validar_post e
 * users_form_options) viraram os métodos privados lerPost() e
 * opcoesFormulario().
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a persistência é
 * feita via model User (libphp/User.php), com parâmetros ligados via
 * ADODB_Model — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/UsersController.php");
 *   $controller = new UsersController();
 *   $controller->listar();
 *
 * Permissões: todas as ações exigem login (autentica.inc).
 */
if (!class_exists('UsersController', false)) {

class UsersController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php,
	 * incluído por autentica.inc, escreve $db; assim o valor cai em
	 * $GLOBALS e libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	// ------------------------------------------------------------------
	// Auxiliares
	// ------------------------------------------------------------------

	/**
	 * Lê um campo de texto do POST (trim). Devolve string vazia quando
	 * ausente, para evitar notices em comparações com ''.
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	/**
	 * Lê e valida os campos do formulário de usuário (POST), devolvendo um
	 * array pronto para atribuir ao model User. Era a função
	 * users_validar_post() de users/validar.php.
	 *
	 * A senha é tratada de forma diferente entre inserção (obrigatória) e
	 * edição (opcional: só atualiza se preenchida).
	 */
	private function lerPost($senhaObrigatoria = false) {
		$email         = strtolower($this->campoTexto('email'));
		$nome          = $this->campoTexto('nome');
		$role          = $this->campoTexto('role');
		$categoria     = $this->campoTexto('categoria');
		$identificacao = $this->campoTexto('identificacao');
		$ativo         = isset($_POST['ativo']) ? (int)$_POST['ativo'] : 1;
		$senha         = isset($_POST['password']) ? $_POST['password'] : '';

		$roles      = array('admin', 'supervisor', 'professor', 'aluno');
		$categorias = array('1', '2', '3', '4');

		$categoria_para_role = array(
			'1' => 'admin',
			'2' => 'aluno',
			'3' => 'professor',
			'4' => 'supervisor',
		);
		$role_para_categoria = array_flip($categoria_para_role);

		if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			die("Informe um e-mail válido.");
		}
		if ($nome === '') {
			die("Informe o nome do usuário.");
		}
		if (!in_array($role, $roles, true)) {
			die("Categoria (role) inválida.");
		}
		if (!in_array($categoria, $categorias, true)) {
			die("Categoria (1-4) inválida.");
		}

		$roleEsperada      = $categoria_para_role[$categoria];
		$categoriaEsperada = $role_para_categoria[$role];

		if ($role !== $roleEsperada) {
			$labelMap = array(
				'1' => 'Administrador',
				'2' => 'Aluno',
				'3' => 'Professor',
				'4' => 'Supervisor',
			);
			die(
				"Incompatibilidade entre Categoria e Role. " .
				"Categoria {$categoria} ({$labelMap[$categoria]}) exige Role='{$roleEsperada}', " .
				"Role '{$role}' exige Categoria={$categoriaEsperada} ({$labelMap[$categoriaEsperada]})."
			);
		}

		if ($identificacao === '') {
			$identificacao = null;
		}
		if (!in_array($ativo, array(0, 1), true)) {
			$ativo = 1;
		}
		if ($senhaObrigatoria && $senha === '') {
			die("Informe uma senha.");
		}
		if ($senha !== '' && mb_strlen($senha, 'UTF-8') < 6) {
			die("A senha deve ter ao menos 6 caracteres.");
		}

		return array(
			'email'         => $email,
			'nome'          => $nome,
			'role'          => $role,
			'categoria'     => $categoria,
			'identificacao' => $identificacao,
			'ativo'         => $ativo,
			'password'      => $senha,
		);
	}

	/**
	 * Opções estáticas dos formulários de usuário (roles, categorias e
	 * situação). Era a função users_form_options() de users/validar.php.
	 */
	private function opcoesFormulario() {
		return array(
			'roles' => array(
				'admin'      => 'Administrador',
				'supervisor' => 'Supervisor',
				'professor'  => 'Professor',
				'aluno'      => 'Aluno',
			),
			'categorias' => array(
				'1' => 'Categoria 1 — Administrador',
				'2' => 'Categoria 2 — Aluno',
				'3' => 'Categoria 3 — Professor',
				'4' => 'Categoria 4 — Supervisor',
			),
			'ativo_opcoes' => array(
				'1' => 'Ativo',
				'0' => 'Inativo',
			),
		);
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Lista os usuários do sistema (tabela DataTables na view).
	 * Substitui users/exibir/listar.php.
	 */
	public function listar() {
		$this->comAutenticacao();

		$usuarios = User::listar();

		$smarty = new Smarty_estagio;
		$smarty->assign("usuarios", $usuarios);
		$smarty->display("users-listar.tpl");
		exit;
	}

	/**
	 * Ficha de um usuário, com os vínculos de aluno/supervisor/professor.
	 * Substitui users/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$id    = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$dados = User::verCadaCompleto($id);

		if ($dados === null) {
			die("Usuário não encontrado (id $id).");
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("u", $dados);
		$smarty->display("users-ver_cada.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de inserção (GET) e, quando há POST, grava o
	 * usuário. Substitui users/inserir/formulario.php +
	 * users/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		// ---------------- POST: grava ----------------
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost(true); // senha obrigatória na inserção

			// Impede e-mail duplicado.
			$existente = User::firstWhere('email', $dados['email']);
			if ($existente !== null) {
				die("Já existe um usuário com o e-mail {$dados['email']}.");
			}

			$novo = new User();
			$novo->email         = $dados['email'];
			$novo->nome          = $dados['nome'];
			$novo->role          = $dados['role'];
			$novo->categoria     = $dados['categoria'];
			$novo->identificacao = $dados['identificacao'];
			$novo->ativo         = $dados['ativo'];
			$novo->setPassword($dados['password']);

			if (!$novo->save()) {
				error_log("Erro ao inserir usuario: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela users.");
			}

			header("Location: listar.php");
			exit;
		}

		// ---------------- GET: formulário em branco ----------------
		$v = array(
			'id'            => null,
			'email'         => '',
			'nome'          => '',
			'role'          => 'aluno',
			'categoria'     => '2',
			'identificacao' => '',
			'ativo'         => 1,
			'password'      => '',
		);

		$smarty = new Smarty_estagio;
		$smarty->assign('opts', $this->opcoesFormulario());
		$smarty->assign("v", $v);
		$smarty->display("users-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) e, quando há POST, grava a
	 * alteração. Substitui users/atualizar/modifica.php +
	 * users/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$id      = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$usuario = User::find($id);

		if ($usuario === null) {
			die("Usuário não encontrado (id $id).");
		}

		// ---------------- POST: grava ----------------
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost(false); // na edição a senha é opcional

			// Se o e-mail mudou, evita duplicidade com outro usuário.
			if ($dados['email'] !== $usuario->email) {
				$existente = User::firstWhere('email', $dados['email']);
				if ($existente !== null && $existente->getKey() != $id) {
					die("Já existe um usuário com o e-mail {$dados['email']}.");
				}
			}

			$usuario->email         = $dados['email'];
			$usuario->nome          = $dados['nome'];
			$usuario->role          = $dados['role'];
			$usuario->categoria     = $dados['categoria'];
			$usuario->identificacao = $dados['identificacao'];
			$usuario->ativo         = $dados['ativo'];

			// Só altera a senha se o campo foi preenchido.
			if ($dados['password'] !== '') {
				$usuario->setPassword($dados['password']);
			}

			if (!$usuario->save()) {
				error_log("Erro ao atualizar usuario: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar o registro na tabela users.");
			}

			header("Location: listar.php");
			exit;
		}

		// ---------------- GET: formulário pré-preenchido ----------------
		$v = $usuario->toArray();
		foreach (array('password', 'criado_em', 'atualizado_em') as $ignorar) {
			unset($v[$ignorar]);
		}
		$v['password'] = '';

		$smarty = new Smarty_estagio;
		$smarty->assign('opts', $this->opcoesFormulario());
		$smarty->assign("v", $v);
		$smarty->display("users-atualizar.tpl");
		exit;
	}

	/**
	 * Exclui um usuário. Substitui users/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$id      = isset($_REQUEST['id']) ? (int)$_REQUEST['id'] : 0;
		$usuario = User::find($id);

		if ($usuario === null) {
			die("Usuário não encontrado (id $id).");
		}

		if (!$usuario->delete()) {
			error_log("Erro ao excluir usuario: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela users.");
		}

		header("Location: listar.php");
		exit;
	}

}

}

?>
