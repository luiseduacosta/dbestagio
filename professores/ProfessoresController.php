<?php
/**
 * ProfessoresController.php — Controlador da tabela professores.
 *
 * Reúne as ações relacionadas ao cadastro de professores orientadores (tabela
 * professores). Cada método substitui um script legado:
 *
 *   inserir()      <- inserir/inserir.php + inserir/form.php
 *   atualizar()    <- atualizar/atualiza.php
 *   ver_cada()     <- exibir/ver_cada.php
 *   listar()       <- exibir/listar.php
 *   excluir()      <- cancelar/cancela.php
 *   instituicoes() <- exibir/instituicoes.php
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Professor (libphp/Professor.php), que
 * grava com parâmetros ligados via ADODB_Model — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/ProfessoresController.php");
 *   $controller = new ProfessoresController();
 *   $controller->listar();
 *
 * Permissões:
 *  - inserir / atualizar / ver_cada / excluir / listar: exigem login (autentica.inc)
 *  - instituicoes: público (apenas setup.php)
 */
if (!class_exists('ProfessoresController', false)) {

class ProfessoresController {

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
	 * Usado pelas ações públicas (como instituicoes).
	 */
	private function semAutenticacao() {
		global $db;
		include_once(__DIR__ . "/../setup.php");
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
	 * Normaliza uma data de formulário para AAAA-MM-DD.
	 * Aceita AAAA-MM-DD, AAAA-MM-DD HH:MM:SS, dd/mm/aaaa e formato ISO.
	 * Retorna '' (string vazia) para valores vazios.
	 */
	private function normalizaData($valor) {
		$valor = trim((string)$valor);
		if ($valor === '' || strtolower($valor) === '0000-00-00' || strtolower($valor) === 'null') {
			return '';
		}
		// dd/mm/aaaa -> aaaa-mm-dd
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $valor, $m)) {
			return $m[3] . '-' . $m[2] . '-' . $m[1];
		}
		// Já ISO (Ano-mês-dia), com validação básica.
		if (preg_match('#^(\d{4})-(\d{2})-(\d{2})#', $valor, $m)) {
			return $m[1] . '-' . $m[2] . '-' . $m[3];
		}
		return '';
	}

	/**
	 * Lê e valida todos os campos do formulário de professor (POST).
	 */
	private function lerPost() {
		$normalizaData = function($k) {
			$v = $this->normalizaData(isset($_POST[$k]) ? $_POST[$k] : '');
			return ($v === '') ? null : $v;
		};

		$nome = $this->campoTexto('nome');
		if ($nome === '') {
			die("O nome do professor é obrigatório.");
		}

		$email  = strtolower($this->campoTexto('email'));
		$status = $this->campoTexto('status');
		if ($status === '') {
			$status = 'ativo';
		}
		$statusValidos = array('ativo', 'inativo', 'aposentado');
		if (!in_array($status, $statusValidos, true)) {
			$status = 'ativo';
		}

		$codigo_telefone = $this->campoTexto('codigo_telefone');
		if ($codigo_telefone === '') {
			$codigo_telefone = '21';
		}

		$codigo_celular = $this->campoTexto('codigo_celular');
		if ($codigo_celular === '') {
			$codigo_celular = '21';
		}

		return array(
			'nome'              => $nome,
			'cpf'               => $this->campoTexto('cpf'),
			'siape'             => $this->campoTexto('siape'),
			'cress'             => $this->campoTexto('cress'),
			'regiao'            => $this->campoTexto('regiao'),
			'codigo_telefone'   => $codigo_telefone,
			'telefone'          => $this->campoTexto('telefone'),
			'codigo_celular'    => $codigo_celular,
			'celular'           => $this->campoTexto('celular'),
			'email'             => $email,
			'curriculolattes'   => $this->campoTexto('curriculolattes'),
			'atualizacaolattes' => $normalizaData('atualizacaolattes'),
			'dataingresso'      => $normalizaData('dataingresso'),
			'tipocargo'         => $this->campoTexto('tipocargo'),
			'departamento'      => $this->campoTexto('departamento'),
			'dataegresso'       => $normalizaData('dataegresso'),
			'motivoegresso'     => $this->campoTexto('motivoegresso'),
			'status'            => $status,
			'observacoes'       => $this->campoTexto('observacoes'),
		);
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Lista de professores com filtros de busca (nome/email/cpf/siape) e status.
	 * Substitui professores/exibir/listar.php.
	 */
	public function listar() {
		$this->comAutenticacao();

		$busca  = isset($_GET['busca']) ? trim($_GET['busca']) : '';
		$status = isset($_GET['status']) ? trim($_GET['status']) : '';
		$ordem  = isset($_GET['orderby']) ? trim($_GET['orderby']) : 'p.nome';

		// Whitelist de colunas de ordenação.
		$colunas_ordem = array(
			'nome'         => 'p.nome',
			'departamento' => 'p.departamento',
			'email'        => 'p.email',
			'status'       => 'p.status',
			'num_estagios' => 'num_estagios',
		);
		if (!isset($colunas_ordem[$ordem])) {
			$ordem = 'nome';
		}
		$orderby = $colunas_ordem[$ordem];

		// Valida status contra a lista fechada.
		$statuses = Professor::statuses();
		if (!array_key_exists($status, $statuses)) {
			$status = '';
		}

		$professores = Professor::listar($busca, $status, $orderby);

		$smarty = new Smarty_estagio;
		$smarty->assign("busca", $busca);
		$smarty->assign("status", $status);
		$smarty->assign("statuses", $statuses);
		$smarty->assign("professores", $professores);
		$smarty->display("professores-listar.tpl");
		exit;
	}

	/**
	 * Ficha de um professor, com estagiários supervisionados e navegação
	 * entre professores na ordem alfabética.
	 * Substitui professores/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;
		$ordem        = isset($_REQUEST['ordem']) ? trim($_REQUEST['ordem']) : 'e.periodo';

		$dados = Professor::buscar($professor_id);
		if ($dados === null) {
			header("Location: listar.php");
			exit;
		}

		$professor_nome = $dados['nome'];
		$estagiarios    = Professor::estagiarios($professor_id, $ordem);

		// Navegação: professor anterior/próximo na ordem alfabética (com wrap-around).
		$primeiro_id = null;
		$ultimo_id   = null;
		$anterior_id = null;
		$proximo_id  = null;
		$menos_10_id = null;
		$mais_10_id  = null;
		$rs_nav = Professor::query("SELECT id FROM professores ORDER BY nome, id");
		if ($rs_nav) {
			$ids_nav = array();
			foreach ($rs_nav as $item) {
				$ids_nav[] = (int)$item->id;
			}
			$total_nav = count($ids_nav);
			$pos_nav   = array_search($professor_id, $ids_nav, true);
			if ($pos_nav !== false && $total_nav > 1) {
				$primeiro_id = $ids_nav[0];
				$ultimo_id   = $ids_nav[$total_nav - 1];
				$anterior_id = $ids_nav[($pos_nav - 1 + $total_nav) % $total_nav];
				$proximo_id  = $ids_nav[($pos_nav + 1) % $total_nav];
				$menos_10_id = $ids_nav[($pos_nav - 10 + $total_nav) % $total_nav];
				$mais_10_id  = $ids_nav[($pos_nav + 10) % $total_nav];
			}
		}

		// Exibição dos telefones: formata se não vier formatado
		$telefone_exib = (string)$dados['telefone'];
		if ($telefone_exib !== '' && strpos($telefone_exib, '(') === false) {
			$telefone_exib = '(' . (int)$dados['codigo_telefone'] . ') ' . $telefone_exib;
		}
		$celular_exib = (string)$dados['celular'];
		if ($celular_exib !== '' && strpos($celular_exib, '(') === false) {
			$celular_exib = '(' . (int)$dados['codigo_celular'] . ') ' . $celular_exib;
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("professor_id",    $professor_id);
		$smarty->assign("primeiro_id",     $primeiro_id);
		$smarty->assign("ultimo_id",       $ultimo_id);
		$smarty->assign("anterior_id",     $anterior_id);
		$smarty->assign("proximo_id",      $proximo_id);
		$smarty->assign("menos_10_id",     $menos_10_id);
		$smarty->assign("mais_10_id",      $mais_10_id);
		$smarty->assign("professor_nome",  $professor_nome);
		$smarty->assign("cpf",             $dados['cpf']);
		$smarty->assign("siape",           $dados['siape']);
		$smarty->assign("cress",           $dados['cress']);
		$smarty->assign("regiao",          $dados['regiao']);
		$smarty->assign("codigo_telefone", $dados['codigo_telefone']);
		$smarty->assign("telefone",        $telefone_exib);
		$smarty->assign("codigo_celular",  $dados['codigo_celular']);
		$smarty->assign("celular",         $celular_exib);
		$smarty->assign("email",           $dados['email']);
		$smarty->assign("curriculolattes", $dados['curriculolattes']);
		$smarty->assign("atualizacaolattes", $dados['atualizacaolattes']);
		$smarty->assign("dataingresso",    $dados['dataingresso']);
		$smarty->assign("tipocargo",       $dados['tipocargo']);
		$smarty->assign("departamento",    $dados['departamento']);
		$smarty->assign("dataegresso",     $dados['dataegresso']);
		$smarty->assign("motivoegresso",   $dados['motivoegresso']);
		$smarty->assign("status",          $dados['status']);
		$smarty->assign("observacoes",     $dados['observacoes']);
		$smarty->assign("created",         $dados['created']);
		$smarty->assign("modified",        $dados['modified']);
		$smarty->assign("num_estagios",    (int)$dados['num_estagios']);
		$smarty->assign("num_instituicoes", (int)$dados['num_instituicoes']);
		$smarty->assign("estagiarios",     $estagiarios);
		$smarty->assign("ordem",           $ordem);
		$smarty->display("professores-ver_cada.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de inclusão (GET) e, quando há POST, grava o professor.
	 * Substitui professores/inserir/form.php + professores/inserir/inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost();
			$dados['id'] = 0;

			$dup = Professor::duplicado($dados['email'], $dados['cpf'], $dados['siape'], null);
			if ($dup !== null) {
				die("Já existe um professor cadastrado com este e-mail, CPF ou SIAPE (id $dup).");
			}

			$professor = new Professor();
			$professor->preencher($dados);
			$professor->created  = date('Y-m-d H:i:s');
			$professor->modified = date('Y-m-d H:i:s');

			if (!$professor->save()) {
				error_log("Erro ao inserir professor: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela professores.");
			}

			$id = $professor->getKey();
			header("Location: ver_cada.php?professor_id=$id");
			exit;
		}

		// GET: formulário vazio
		$statuses = Professor::statuses();
		array_shift($statuses); // remove "Todos"

		$v = array(
			'id'                => 0,
			'nome'              => '',
			'cpf'               => '',
			'siape'             => '',
			'cress'             => '',
			'regiao'            => '',
			'codigo_telefone'   => '21',
			'telefone'          => '',
			'codigo_celular'    => '21',
			'celular'           => '',
			'email'             => '',
			'curriculolattes'   => '',
			'atualizacaolattes' => '',
			'dataingresso'      => '',
			'tipocargo'         => '',
			'departamento'      => '',
			'dataegresso'       => '',
			'motivoegresso'     => '',
			'status'            => 'ativo',
			'observacoes'       => '',
		);

		$smarty = new Smarty_estagio;
		$smarty->assign("statuses", $statuses);
		$smarty->assign("v", $v);
		$smarty->assign("acao", "inserir.php");
		$smarty->assign("titulo", "Novo professor");
		$smarty->assign("e_edicao", false);
		$smarty->display("professores-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) e, quando há POST, grava a alteração.
	 * Substitui professores/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;
		$prof = Professor::find($professor_id);
		if ($prof === null) {
			die("Professor não encontrado (id $professor_id).");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$dados = $this->lerPost();

			$dup = Professor::duplicado($dados['email'], $dados['cpf'], $dados['siape'], $professor_id);
			if ($dup !== null) {
				die("Já existe outro professor cadastrado com este e-mail, CPF ou SIAPE (id $dup).");
			}

			$prof->preencher($dados);
			$prof->modified = date('Y-m-d H:i:s');

			if (!$prof->save()) {
				error_log("Erro ao atualizar professor: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar o registro na tabela professores.");
			}

			header("Location: ver_cada.php?professor_id=$professor_id");
			exit;
		}

		// GET: formulário pré-preenchido
		$data = $prof->toArray();

		$statuses = Professor::statuses();
		array_shift($statuses); // remove "Todos"

		$v = array(
			'id'                => (int)$data['id'],
			'nome'              => $data['nome'],
			'cpf'               => $data['cpf'],
			'siape'             => $data['siape'],
			'cress'             => $data['cress'],
			'regiao'            => $data['regiao'],
			'codigo_telefone'   => $data['codigo_telefone'],
			'telefone'          => $data['telefone'],
			'codigo_celular'    => $data['codigo_celular'],
			'celular'           => $data['celular'],
			'email'             => strtolower((string)$data['email']),
			'curriculolattes'   => $data['curriculolattes'],
			'atualizacaolattes' => $data['atualizacaolattes'],
			'dataingresso'      => $data['dataingresso'],
			'tipocargo'         => $data['tipocargo'],
			'departamento'      => $data['departamento'],
			'dataegresso'       => $data['dataegresso'],
			'motivoegresso'     => $data['motivoegresso'],
			'status'            => $data['status'],
			'observacoes'       => $data['observacoes'],
		);

		$smarty = new Smarty_estagio;
		$smarty->assign("statuses", $statuses);
		$smarty->assign("v", $v);
		$smarty->assign("acao", "atualizar.php");
		$smarty->assign("titulo", "Editar professor");
		$smarty->assign("e_edicao", true);
		$smarty->display("professores-atualizar.tpl");
		exit;
	}

	/**
	 * Exclui um professor, desde que não tenha estagiários vinculados.
	 * Substitui professores/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$professor_id = isset($_REQUEST['professor_id']) ? (int)$_REQUEST['professor_id'] : 0;

		$prof = Professor::find($professor_id);
		if ($prof === null) {
			die("Professor não encontrado (id $professor_id).");
		}

		// Proteção: não exclui professor com estagiários vinculados
		$info = Professor::buscar($professor_id);
		$num_estagios = $info ? (int)$info['num_estagios'] : 0;

		if ($num_estagios > 0) {
			die("Não é possível excluir este professor: há {$num_estagios} estágio(s) vinculado(s) a ele. "
				. "Remova os vínculos nos estagiários antes de excluir.");
		}

		if (!$prof->delete()) {
			error_log("Erro ao excluir professor: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela professores.");
		}

		header("Location: listar.php");
		exit;
	}

	/**
	 * Lista as instituições vinculadas aos professores através de seus estágios.
	 * Substitui professores/exibir/instituicoes.php.
	 */
	public function instituicoes() {
		$this->comAutenticacao();

		$sql = "SELECT DISTINCT p.nome, i.instituicao "
			 . "FROM professores p "
			 . "INNER JOIN estagiarios e ON e.professor_id = p.id "
			 . "INNER JOIN instituicoes i ON i.id = e.instituicao_id "
			 . "ORDER BY p.nome, i.instituicao";

		$rs = Professor::$db->Execute($sql);
		$instituicoes = array();
		if ($rs) {
			while (!$rs->EOF) {
				$instituicoes[] = array(
					'nome'        => $rs->fields['nome'],
					'instituicao' => $rs->fields['instituicao'],
				);
				$rs->MoveNext();
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->display("professores-instituicoes.tpl");
		exit;
	}

}

}

?>
