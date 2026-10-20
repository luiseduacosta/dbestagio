<?php
/**
 * AlunosController.php — Controlador da tabela alunos.
 *
 * Reúne as ações relacionadas ao cadastro de alunos (tabela alunos). Cada
 * método substitui um script legado:
 *
 *   inserir()    <- inserir/inserir.php + inserir/verifica.php
 *   atualizar()  <- atualizar/atualiza.php (atualização do aluno E, em
 *                  compatibilidade, atualização de um único estagiário via
 *                  estagiario_id no POST — futuro EstagiariosController)
 *   ver_cada()   <- exibir/ver_cada.php
 *   listar()     <- exibir/listar.php
 *   excluir()    <- cancelar/cancela.php
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes
 * de autenticação, assigns do Smarty e redirecionamentos). Toda a lógica
 * de consulta e gravação fica no model Aluno (libphp/Aluno.php) — grava
 * com parâmetros ligados via ADODB_Model, sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/AlunosController.php");
 *   $controller = new AlunosController();
 *   $controller->listar();
 *
 * Permissões (replicando o comportamento dos scripts legados):
 *  - todas as ações exigem login (autentica.inc).
 */
if (!class_exists('AlunosController', false)) {

class AlunosController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php,
	 * incluído por autentica.inc, escreve $db; assim o valor cai em
	 * $GLOBALS e libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id, $usuario_aluno_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Lê um campo de texto do POST (trim). Devolve string vazia quando
	 * ausente, para evitar notices em comparações com ''.
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : '';
	}

	/**
	 * Converte uma data dd/mm/aaaa (ou já em AAAA-MM-DD) para o formato
	 * do banco. Devolve '' se o formato não for reconhecido.
	 */
	private function normalizaData($data_br) {
		$data = trim((string)$data_br);
		if ($data === '') return '';
		if (preg_match('#^(\d{2})/(\d{2})/(\d{4})$#', $data, $m)) {
			return $m[3] . "-" . $m[2] . "-" . $m[1];
		}
		if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $data)) {
			return $data;
		}
		return '';
	}

	/**
	 * Resolve indice -> id (posição na lista ordenada por nome) sem
	 * carregar todo o resultset em memória.
	 */
	private function idNaPosicao($indice) {
		$alunos = Aluno::query(
			"SELECT id FROM alunos ORDER BY nome LIMIT 1 OFFSET ?",
			array((int)$indice)
		);
		if (!empty($alunos)) {
			$primeiroAluno = reset($alunos);
			$id = (int)$primeiroAluno->id;
			return $id > 0 ? $id : 0;
		}
		return 0;
	}

	/**
	 * Varre a lista ordenada e devolve a posição (0-based) do id
	 * informado. -1 quando não encontrado.
	 */
	private function posicaoDoId($id) {
		$id = (int)$id;
		if ($id <= 0) return -1;
		$alunos = Aluno::all("ORDER BY nome");
		if (empty($alunos)) return -1;
		$lugar = 0;
		foreach ($alunos as $aluno) {
			if ((int)$aluno->id === $id) {
				return $lugar;
			}
			$lugar++;
		}
		return -1;
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção e, quando há POST, grava o aluno.
	 * Substitui alunos/inserir/inserir.php + alunos/inserir/verifica.php.
	 *
	 * Suporta três fluxos:
	 *  - GET com `verifica=1` + `registro`/`aluno_id`: tenta localizar o
	 *    aluno; se existir redireciona para a ficha; se não, mostra o
	 *    formulário (pré-preenchido com o registro).
	 *  - GET sem parâmetros: mostra o formulário em branco.
	 *  - POST: grava um novo aluno.
	 */
	public function inserir() {
		global $isAdmin, $isAluno;
		$this->comAutenticacao();

		// Apenas administradores cadastram alunos. O próprio aluno cria o
		// seu registro pelo fluxo de cadastro (users/registrar_entidade.php).
		if (!$isAdmin) {
			die("Acesso restrito: somente administradores podem cadastrar alunos.");
		}

		$origem = isset($_REQUEST['origem']) ? trim($_REQUEST['origem']) : '';
		if (empty($origem)) {
			$origem = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		}

		// ---------------- Fluxo "verifica" (GET) ----------------
		// Tenta localizar o aluno pelo registro (ou id). Se achar, vai
		// direto para a ficha; caso contrário mostra o formulário.
		$verifica    = isset($_REQUEST['verifica']) ? (int)$_REQUEST['verifica'] : 0;
		$registro_in = isset($_REQUEST['registro']) ? trim($_REQUEST['registro']) : '';
		$aluno_id_in = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

		if ($_SERVER['REQUEST_METHOD'] !== 'POST' && ($verifica || $registro_in !== '' || $aluno_id_in > 0)) {
			$dados = null;
			if ($registro_in !== '') {
				$dados = Aluno::firstWhere("registro", (int)$registro_in);
			} elseif ($aluno_id_in > 0) {
				$dados = Aluno::find($aluno_id_in);
			}
			if ($dados === false) {
				die("Não foi possível consultar a tabela alunos");
			}
			if ($dados !== null) {
				header("Location: ver_cada.php?registro=" . (int)$dados->registro);
				exit;
			}
			// Não existe: mostra o formulário pré-preenchido.
			$this->renderFormularioInserir($origem, $registro_in, $aluno_id_in);
			exit;
		}

		// ---------------- POST: grava o novo aluno ----------------
		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$cadastro        = isset($_POST['valorcadastro']) ? (int)$_POST['valorcadastro'] : 0;
			$registro        = $this->campoTexto('registro');
			$nome            = $this->campoTexto('nome');
			$ingresso        = $this->campoTexto('ingresso');
			$codigo_telefone = isset($_POST['codigo_telefone']) ? (int)$_POST['codigo_telefone'] : 21;
			$telefone        = $this->campoTexto('telefone');
			$codigo_celular  = isset($_POST['codigo_celular'])  ? (int)$_POST['codigo_celular']  : 21;
			$celular         = $this->campoTexto('celular');
			$email           = strtolower($this->campoTexto('email'));
			$cpf             = $this->campoTexto('cpf');
			$identidade      = $this->campoTexto('identidade');
			$orgao           = $this->campoTexto('orgao');
			$nascimento      = $this->campoTexto('nascimento');
			$endereco        = $this->campoTexto('endereco');
			$cep             = $this->campoTexto('cep');
			$bairro          = $this->campoTexto('bairro');
			$municipio       = $this->campoTexto('municipio');
			$turno_id        = isset($_POST['turno_id']) ? (int)$_POST['turno_id'] : 0;

			if ($nome === '') {
				die("Informe o nome do aluno.");
			}
			if ($registro === '' || !ctype_digit($registro)) {
				die("Informe um registro (matrícula) válido.");
			}
			if ($ingresso !== '' && !preg_match('/^\d{4}-[12]$/', $ingresso)) {
				die("Informe um ingresso válido no formato AAAA-S (ex.: 2026-1).");
			}
			if (Aluno::registroDuplicado($registro) !== null) {
				die("Erro: Já existe um aluno com este número de registro.");
			}
			if ($turno_id > 0 && Turno::find($turno_id) === null) {
				die("Turno inválido (id $turno_id).");
			}

			$data_nascimento = $this->normalizaData($nascimento);

			$novo = new Aluno();
			$novo->id              = Aluno::proximoId(); // id não é auto_increment.
			$novo->registro        = (int)$registro;
			$novo->nome            = $nome;
			$novo->ingresso        = $ingresso !== '' ? $ingresso : null;
			$novo->codigo_telefone = $codigo_telefone;
			$novo->telefone        = $telefone;
			$novo->codigo_celular  = $codigo_celular;
			$novo->celular         = $celular;
			$novo->email           = $email;
			$novo->cpf             = $cpf;
			$novo->identidade      = $identidade;
			$novo->orgao           = $orgao;
			$novo->nascimento      = $data_nascimento !== '' ? $data_nascimento : null;
			$novo->endereco        = $endereco;
			$novo->cep             = $cep;
			$novo->bairro          = $bairro;
			$novo->municipio       = $municipio;
			$novo->turno_id        = ($turno_id > 0) ? $turno_id : null;
			$novo->estagiarios_count = 0;
			$novo->inscricao_count = 0;

			if (!$novo->save()) {
				error_log("Erro ao inserir aluno: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela alunos.");
			}

			header("Location: ver_cada.php?aluno_id=" . $novo->id);
			exit;
		}

		// GET sem parâmetros: mostra o formulário em branco.
		$this->renderFormularioInserir($origem, '', 0);
		exit;
	}

	/**
	 * Renderiza o formulário de inserir (smarty) com os defaults
	 * informados. Reaproveitado tanto pelo inserir() em branco quanto
	 * pelo fluxo verifica-quando-não-encontrado.
	 */
	private function renderFormularioInserir($origem, $registro, $aluno_id) {
		$smarty = new Smarty_estagio;
		$smarty->assign("origem", $origem);
		$smarty->assign("aluno_id", $aluno_id);
		$smarty->assign("registro", $registro);
		$smarty->assign("nome", '');
		$smarty->assign("ingresso", '');
		$smarty->assign("codigo_telefone", 21);
		$smarty->assign("telefone", '');
		$smarty->assign("codigo_celular", 21);
		$smarty->assign("celular", '');
		$smarty->assign("email", '');
		$smarty->assign("cpf", '');
		$smarty->assign("identidade", '');
		$smarty->assign("orgao", '');
		$smarty->assign("nascimento", '');
		$smarty->assign("endereco", '');
		$smarty->assign("cep", '');
		$smarty->assign("bairro", '');
		$smarty->assign("municipio", '');
		$smarty->assign("cadastro", 0);
		$smarty->assign("turnos", Turno::seleciona());
		$smarty->assign("turno_id", 0);
		$smarty->display("alunos-inserir_verifica.tpl");
	}

	/**
	 * Exibe o formulário de edição (GET) ou grava a edição confirmada
	 * (POST). Substitui alunos/atualizar/atualiza.php.
	 *
	 * Em compatibilidade com o script legado, este método também
	 * atualiza um único registro da tabela `estagiarios` quando o POST
	 * traz `estagiario_id` (double duty). A separação definitiva entre
	 * as duas tabelas será feita em um futuro EstagiariosController.
	 */
	public function atualizar() {
		global $sistema_autentica, $isAdmin, $isAluno, $usuario_aluno_id;
		$this->comAutenticacao();

		// Autorização: não-admin só edita o próprio cadastro
		$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
		if (!$isAdmin) {
			if (!$isAluno || (int)$usuario_aluno_id !== $aluno_id) {
				die("Você só pode editar seu próprio cadastro.");
			}
		}

		$origem = isset($_REQUEST['origem']) ? trim($_REQUEST['origem']) : '';
		if (substr_count($origem, "seleciona.php") == 1) {
			$origem = $_SERVER['PHP_SELF'];
		}
		if ($origem === '') {
			$origem = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
		}

		// ---------------- POST: grava ----------------
		if (($_SERVER['REQUEST_METHOD'] === 'POST') || (isset($_GET['acao']) && $_GET['acao'] == 1)) {
			$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
			$registro = $this->campoTexto('registro');

			// Atualização de um estágio específico (compatibilidade).
			$estagiario_id = isset($_POST['estagiario_id']) ? (int)$_POST['estagiario_id'] : 0;
			if ($estagiario_id > 0) {
				$periodo        = $this->campoTexto('periodo');
				$nivel          = $this->campoTexto('nivel');
				$tc             = $this->campoTexto('tc');
				$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
				$supervisor_id  = isset($_POST['supervisor_id'])  ? (int)$_POST['supervisor_id']  : 0;
				$professor_id   = isset($_POST['professor_id'])   ? (int)$_POST['professor_id']   : 0;
				$nota           = $this->campoTexto('nota');
				$ch             = $this->campoTexto('ch');

				$estagiario = Estagiario::find($estagiario_id);
				if ($estagiario === null) {
					die("Estagiário não encontrado (id $estagiario_id).");
				}
				$estagiario->aluno_id = $aluno_id;
				$estagiario->registro = $registro;
				$estagiario->nivel = $nivel;
				$estagiario->periodo = $periodo;
				$estagiario->tc = $tc;
				$estagiario->supervisor_id = $supervisor_id;
				$estagiario->instituicao_id = $instituicao_id;
				$estagiario->professor_id = $professor_id;
				$estagiario->nota = $nota;
				$estagiario->ch = $ch;
				if (!$estagiario->save()) {
					die("Nao foi possivel atualizar o registro na tabela estagiarios");
				}

				if (substr_count($origem, "listar.php") == 1) {
					header("Location:" . $origem);
				} else {
					header("Location: ver_cada.php?aluno_id=$aluno_id");
				}
				exit;
			}

			// ---------------- Atualização do aluno ----------------
			$nome        = $this->campoTexto('nome');
			$observacoes = $this->campoTexto('observacoes');

			$aluno = Aluno::find($aluno_id);
			if ($aluno === null) {
				die("Aluno não encontrado (id $aluno_id).");
			}

			$aluno->registro    = $registro;
			$aluno->nome        = $nome;
			$aluno->telefone    = $this->campoTexto('telefone');
			$aluno->celular     = $this->campoTexto('celular');
			$aluno->email       = strtolower($this->campoTexto('email'));
			$aluno->cpf         = $this->campoTexto('cpf');
			$aluno->identidade  = $this->campoTexto('identidade');
			$aluno->orgao       = $this->campoTexto('orgao');
			$aluno->endereco    = $this->campoTexto('endereco');
			$aluno->cep         = $this->campoTexto('cep');
			$aluno->bairro      = $this->campoTexto('bairro');
			$aluno->municipio   = $this->campoTexto('municipio');
			$aluno->observacoes = $observacoes;

			if (isset($_REQUEST['codigo_telefone'])) $aluno->codigo_telefone = (int)$_REQUEST['codigo_telefone'];
			if (isset($_REQUEST['codigo_celular']))  $aluno->codigo_celular  = (int)$_REQUEST['codigo_celular'];

			if (!empty($_REQUEST['nascimento'])) {
				$data_nascimento = $this->normalizaData($_REQUEST['nascimento']);
				if ($data_nascimento !== '') $aluno->nascimento = $data_nascimento;
			}

			if (isset($_REQUEST['turno_id'])) {
				$turno_id = (int)$_REQUEST['turno_id'];
				if ($turno_id > 0 && Turno::find($turno_id) === null) {
					die("Turno inválido (id $turno_id).");
				}
				$aluno->turno_id = ($turno_id > 0) ? $turno_id : null;
			}

			// Verifica duplicidade de registro (salvo neste próprio aluno).
			if (Aluno::registroDuplicado($registro, $aluno_id) !== null) {
				die("Já existe outro aluno com o registro $registro.");
			}

			if (!$aluno->save()) {
				die("Nao foi possivel atualizar o registro na tabela alunos");
			}

			// Não-admin: sincroniza o e-mail da entidade com users.email
			// para preservar a relação user ↔ aluno.
			if (!$isAdmin && !empty($aluno->user_id)) {
				$user = User::find((int)$aluno->user_id);
				if ($user && strtolower($user->email) !== strtolower($aluno->email)) {
					$user->email = $aluno->email;
					$user->save();
				}
			}

			// Sincroniza o campo registro na tabela estagiarios.
			$estagiarios = Estagiario::all("WHERE aluno_id = ?", array($aluno_id));
			if (!empty($estagiarios)) {
				foreach ($estagiarios as $estagiario) {
					$estagiario->registro = $registro;
					if (!$estagiario->save()) {
						die("Nao foi possivel atualizar o campo registro na tabela estagiarios");
					}
				}
			}

			if (substr_count($origem, "listar.php") == 1) {
				header("Location:" . $origem);
			} else {
				header("Location: ver_cada.php?aluno_id=$aluno_id");
			}
			exit;
		}

		// ---------------- GET: exibe o formulário ----------------
		$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
		$dados = Aluno::buscar($aluno_id);
		if ($dados === null) {
			die("Aluno não encontrado (id $aluno_id).");
		}

		// Formata a data de nascimento para dd/mm/aaaa.
		$data_sql = '';
		if (!empty($dados['nascimento']) && $dados['nascimento'] !== '0000-00-00') {
			$ts = strtotime($dados['nascimento']);
			$data_sql = ($ts === false) ? $dados['nascimento'] : date('d/m/Y', $ts);
		}

		$registro  = $dados['registro'];
		$nome      = $dados['nome'];

		// Estagiários do aluno (histórico) — uma consulta com JOINs.
		$estagiarios = Aluno::historicoEstagios($aluno_id);
		foreach ($estagiarios as $k => $e) {
			$estagiarios[$k]['id'] = $e['estagiario_id'];
		}

		// Selects do formulário.
		$instituicoes = array();
		$todasInstituicoes = Instituicao::all("ORDER BY instituicao");
		if (!empty($todasInstituicoes)) {
			foreach ($todasInstituicoes as $inst) {
				$instituicoes[] = array('instituicao_id' => (int)$inst->id, 'instituicao' => $inst->instituicao);
			}
		}
		$supervisores = array();
		$todosSupervisores = Supervisor::all("ORDER BY nome");
		if (!empty($todosSupervisores)) {
			foreach ($todosSupervisores as $sup) {
				$supervisores[] = array('supervisor_id' => (int)$sup->id, 'supervisor' => $sup->nome);
			}
		}
		$professores = array();
		$todosProfessores = Professor::all("ORDER BY nome");
		if (!empty($todosProfessores)) {
			foreach ($todosProfessores as $prof) {
				$professores[] = array('professor_id' => (int)$prof->id, 'professor' => $prof->nome);
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("isAdmin", $isAdmin);
		$smarty->assign("origem", $origem);
		$smarty->assign("aluno_id", $aluno_id);
		$smarty->assign("registro", $registro);
		$smarty->assign("aluno_nome", $nome);
		$smarty->assign("codigo_telefone", $dados['codigo_telefone']);
		$smarty->assign("telefone", $dados['telefone']);
		$smarty->assign("codigo_celular", $dados['codigo_celular']);
		$smarty->assign("celular", $dados['celular']);
		$smarty->assign("email", strtolower((string)$dados['email']));
		$smarty->assign("cpf", $dados['cpf']);
		$smarty->assign("identidade", $dados['identidade']);
		$smarty->assign("orgao", $dados['orgao']);
		$smarty->assign("nascimento", $data_sql);
		$smarty->assign("endereco", $dados['endereco']);
		$smarty->assign("cep", $dados['cep']);
		$smarty->assign("bairro", $dados['bairro']);
		$smarty->assign("municipio", $dados['municipio']);
		$smarty->assign("observacoes", $dados['observacoes']);
		$smarty->assign("turno_id", (int)$dados['turno_id']);
		$smarty->assign("turnos", Turno::seleciona());
		$smarty->assign("estagiarios", $estagiarios);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->assign("supervisores", $supervisores);
		$smarty->assign("professores", $professores);
		$smarty->display("alunos-atualizar_atualiza.tpl");
		exit;
	}

	/**
	 * Ficha de um aluno, com navegação registro a registro (barra
	 * superior Primeiro/-10/Retroceder/Avançar/+10/Último). Substitui
	 * alunos/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		global $sistema_autentica, $isAdmin, $isAluno;
		$this->comAutenticacao();

		$aluno_id      = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
		$registro      = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : 0;
		$periodo       = isset($_REQUEST['periodo']) ? trim($_REQUEST['periodo']) : '';
		$origem        = isset($_REQUEST['origem']) ? trim($_REQUEST['origem']) : '';
		$periodo_atual = isset($_REQUEST['periodo_atual']) ? trim($_REQUEST['periodo_atual']) : PERIODO_ATUAL;
		if ($periodo_atual === '') {
			$periodo_atual = PERIODO_ATUAL;
		}

		// Navegação registro a registro (barra superior).
		$indice = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
		$botao  = isset($_REQUEST['botao']) ? trim($_REQUEST['botao']) : '';
		if ($botao !== '') {
			// Ao usar os botões, ignora o aluno da URL: a seleção passa a ser pelo índice.
			$aluno_id = 0;
			$registro = 0;
		}

		// Se veio por registro, converte para aluno_id.
		if ($aluno_id <= 0 && $registro > 0) {
			$aluno = Aluno::firstWhere("registro", $registro);
			if ($aluno !== null) {
				$aluno_id = (int)$aluno->id;
			}
		}

		// Lista ordenada de alunos (apenas os ids) — base da navegação.
		$alunos_ids = array();
		$todosAlunos = Aluno::all("ORDER BY nome");
		if ($todosAlunos === false) {
			die("Não foi possível consultar a tabela alunos.");
		}
		foreach ($todosAlunos as $aluno) {
			$alunos_ids[] = (int)$aluno->id;
		}
		$ultimo = count($alunos_ids);

		// Botões Primeiro / -10 / Retroceder / Avançar / +10 / Último (com wrap-around).
		switch ($botao) {
			case 'primeiro':
				$indice = 0;
				break;
			case 'menos_10':
				$indice -= 10;
				if ($indice < 0) $indice = $ultimo - 1;
				break;
			case 'retroceder':
				$indice--;
				if ($indice < 0) $indice = $ultimo - 1;
				break;
			case 'avancar':
				$indice++;
				if ($indice >= $ultimo) $indice = 0;
				break;
			case 'mais_10':
				$indice += 10;
				if ($indice >= $ultimo) $indice = 0;
				break;
			case 'ultimo':
				$indice = $ultimo - 1;
				break;
		}

		if ($ultimo > 0) {
			if ($indice >= $ultimo) $indice = 0;
			if ($indice < 0) $indice = $ultimo - 1;
		} else {
			$indice = 0;
		}

		// Com aluno_id (link direto) posiciona a barra no aluno; sem ele, seleciona pelo índice.
		if ($aluno_id > 0) {
			$posicao = array_search($aluno_id, $alunos_ids, true);
			if ($posicao !== false) {
				$indice = (int)$posicao;
			}
		} elseif ($ultimo > 0) {
			$aluno_id = $alunos_ids[$indice];
		}

		$dados = ($aluno_id > 0) ? Aluno::buscar($aluno_id) : null;
		if ($dados === null) {
			die($aluno_id > 0
				? "Aluno não encontrado (id $aluno_id)."
				: "Nenhum aluno cadastrado.");
		}
		$aluno_id = (int)$dados['id'];

		// Histórico de estágios do aluno (uma única consulta com JOINs).
		$historico_estagio = Aluno::historicoEstagios($aluno_id);

		// Turno do aluno (alunos.turno_id -> turnos.turno).
		$turno = '';
		if (!empty($dados['turno_id'])) {
			$turno_obj = Turno::find((int)$dados['turno_id']);
			if ($turno_obj !== null) {
				$turno = $turno_obj->turno;
			}
		}

		// Tempo de curso cursado.
		$tempo_cursado = '';
		if (!empty($periodo_atual) && !empty($dados['ingresso'])) {
			$tempo0 = explode("-", $dados['ingresso']);
			if (count($tempo0) >= 2) {
				$tempo_inicial   = (int)$tempo0[0];
				$periodo_inicial = (int)$tempo0[1];
				$tempo1 = explode("-", $periodo_atual);
				if (count($tempo1) >= 2) {
					$tempo_final   = (int)$tempo1[0];
					$periodo_final = (int)$tempo1[1];
					$tempo_cursado = ($tempo_final - $tempo_inicial);
					if ($periodo_inicial < $periodo_final) {
						$tempo_cursado = ($tempo_cursado * 2) + 2;
					} elseif ($periodo_inicial > $periodo_final) {
						$tempo_cursado = ($tempo_cursado * 2);
					} elseif ($periodo_inicial === $periodo_final) {
						$tempo_cursado = ($tempo_cursado * 2) + 1;
					}
				}
			}
		}

		// Períodos de turmas (filtro do template).
		$periodos = Aluno::periodos();

		$smarty = new Smarty_estagio;
		$smarty->assign("isAdmin", $isAdmin);
		$smarty->assign("isAluno", $isAluno);
		$smarty->assign("periodo", $periodo);
		$smarty->assign("origem", $origem);
		$smarty->assign("indice", $indice);
		$smarty->assign("aluno_id", $aluno_id);
		$smarty->assign("instituicao_id", isset($dados['instituicao_id']) ? (int)$dados['instituicao_id'] : 0);
		$smarty->assign("supervisor_id", isset($dados['supervisor_id']) ? (int)$dados['supervisor_id'] : 0);
		$smarty->assign("registro", $dados['registro']);
		$smarty->assign("nome", $dados['nome']);
		$smarty->assign("codigo_telefone", $dados['codigo_telefone']);
		$smarty->assign("telefone", $dados['telefone']);
		$smarty->assign("codigo_celular", $dados['codigo_celular']);
		$smarty->assign("celular", $dados['celular']);
		$smarty->assign("email", strtolower((string)$dados['email']));
		$smarty->assign("cpf", $dados['cpf']);
		$smarty->assign("identidade", $dados['identidade']);
		$smarty->assign("orgao", $dados['orgao']);
		$smarty->assign("nascimento", $dados['nascimento']);
		$smarty->assign("endereco", $dados['endereco']);
		$smarty->assign("cep", $dados['cep']);
		$smarty->assign("bairro", $dados['bairro']);
		$smarty->assign("municipio", $dados['municipio']);
		$smarty->assign("observacoes", $dados['observacoes']);
		$smarty->assign("periodo_intro", $dados['ingresso']);
		$smarty->assign("turno", $turno);
		$smarty->assign("tempo_cursado", $tempo_cursado);
		$smarty->assign("historico_estagio", $historico_estagio);
		$smarty->assign("periodos", $periodos);
		$smarty->display("alunos-exibir_ver_cada.tpl");
		exit;
	}

	/**
	 * Lista alunos com filtros por busca (nome/registro), período e
	 * instituição de estágio. Substitui alunos/exibir/listar.php.
	 */
	public function listar() {
		global $sistema_autentica, $isAdmin;
		$this->comAutenticacao();

		$busca          = isset($_GET['busca']) ? trim($_GET['busca']) : '';
		$periodo        = isset($_GET['periodo']) ? trim($_GET['periodo']) : '';
		$instituicao_id = isset($_GET['instituicao_id']) ? (int)$_GET['instituicao_id'] : 0;

		// Whitelist das colunas de ordenação (evita injeção via ORDER BY).
		$colunas_ordem = array(
			'nome'            => 'a.nome',
			'registro'        => 'a.registro',
			'ingresso'        => 'a.ingresso',
			'periodos_estagio' => 'periodos_estagio',
		);
		$ordemRaw = isset($_GET['ordem']) ? $_GET['ordem'] : 'nome';
		$orderby = isset($colunas_ordem[$ordemRaw]) ? $colunas_ordem[$ordemRaw] : 'a.nome';

		// Períodos e instituições válidos (lista fechada de filtros).
		$periodos      = Aluno::periodos();
		$instituicoes  = Aluno::listarInstituicoes();
		if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
			$periodo = '';
		}
		$ids_instituicoes = array();
		foreach ($instituicoes as $inst) {
			$ids_instituicoes[] = $inst['id'];
		}
		if ($instituicao_id > 0 && !in_array($instituicao_id, $ids_instituicoes, true)) {
			$instituicao_id = 0;
		}

		// A ordenação é feita pelo DataTables no navegador: $orderby é
		// usado apenas como ordenação inicial no banco.
		$alunos = Aluno::listar($busca, $periodo, $instituicao_id, $orderby);

		$smarty = new Smarty_estagio;
		$smarty->assign("busca", $busca);
		$smarty->assign("periodo", $periodo);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("instituicao_id", $instituicao_id);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->assign("alunos", $alunos);
		// Editar/Excluir são exclusivos do admin: alunos, professores e
		// supervisores não editam nem excluem registros nesta listagem.
		$smarty->assign("isAdmin", !empty($isAdmin));
		$smarty->display("alunos-listar.tpl");
		exit;
	}

	/**
	 * Exclui um aluno, desde que não tenha dependências (estágios ou
	 * inscrições). Substitui alunos/cancelar/cancela.php.
	 */
	public function excluir() {
		global $sistema_autentica, $isAdmin;
		$this->comAutenticacao();

		// Apenas administradores podem excluir registros de alunos
		if (!$isAdmin) {
			die("Apenas administradores podem excluir registros de alunos.");
		}

		$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;
		if ($aluno_id <= 0) {
			die("Aluno inválido (id $aluno_id).");
		}

		$aluno = Aluno::find($aluno_id);
		if ($aluno === null) {
			echo "Aluno não encontrado (id $aluno_id).";
			echo "<meta http-equiv='refresh' content='2;listar.php' />";
			exit;
		}

		// Checagem de registros dependentes.
		$erros = array();

		$estagiariosDoAluno = Estagiario::all("WHERE aluno_id = ?", array($aluno_id));
		$countEstagiarios = count($estagiariosDoAluno);
		if ($countEstagiarios > 0) {
			$erros[] = "estágios vinculados ($countEstagiarios)";
		}

		$registro = (int)$aluno->registro;
		$inscricoesDoAluno = Aluno::query(
			"SELECT id FROM inscricoes WHERE aluno_id = ? OR registro = ?",
			array($aluno_id, $registro)
		);
		$countInscricoes = count($inscricoesDoAluno);
		if ($countInscricoes > 0) {
			$erros[] = "inscrições no mural ($countInscricoes)";
		}

		if (!empty($erros)) {
			echo "Operação abortada. É necessário primeiro excluir os seguintes "
			   . "registros relacionados a este aluno:<br>"
			   . "<ul><li>" . implode('</li><li>', $erros) . "</li></ul>";
			echo "<meta http-equiv='refresh' content='3;ver_cada.php?aluno_id=$aluno_id' />";
			exit;
		}

		// Nenhum registro dependente: exclui.
		if (!$aluno->delete()) {
			error_log("Erro ao excluir aluno: " . Aluno::$db->ErrorMsg());
			echo "Não foi possível excluir o registro da tabela alunos.";
			exit;
		}

		echo "<meta http-equiv='refresh' content='0;listar.php' />";
		exit;
	}

}

}

?>