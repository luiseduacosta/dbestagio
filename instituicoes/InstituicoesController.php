<?php
/**
 * InstituicoesController.php — Controlador da tabela instituicoes.
 *
 * Reúne as ações relacionadas ao cadastro de instituições conveniadas (tabela
 * instituicoes). Cada método substitui um script legado:
 *
 *   inserir()    <- inserir/inserir.php + inserir/formulario.php
 *   atualizar()  <- atualizar/atualiza.php + atualizar/modifica.php
 *   ver_cada()   <- exibir/ver_cada.php
 *   listar()     <- exibir/listar.php
 *   excluir()    <- cancelar/cancela.php
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Instituicao (libphp/Instituicao.php),
 * que grava com parâmetros ligados via ADODB_Model — sem interpolação de
 * strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/InstituicoesController.php");
 *   $controller = new InstituicoesController();
 *   $controller->listar();
 *
 * Permissões (replicando o comportamento dos scripts legados):
 *  - inserir / atualizar / ver_cada / excluir: exigem login (autentica.inc)
 *  - listar: público (apenas setup.php); igual ao listar.php antigo
 */
if (!class_exists('InstituicoesController', false)) {

class InstituicoesController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php, incluído
	 * por autentica.inc, escreve $db; assim o valor cai em $GLOBALS e
	 * libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Inclui apenas setup.php (sem forçar login), como fazia o listar.php
	 * antigo, e carrega os models.
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
	 * Resolve indice -> id (posição na lista ordenada por nome) sem
	 * carregar todo o resultset em memória.
	 */
	private function idNaPosicao($indice) {
		$id = (int)ADODB_Model::$db->GetOne(
			"SELECT id FROM instituicoes ORDER BY instituicao LIMIT 1 OFFSET ?",
			array((int)$indice)
		);
		return $id > 0 ? (int)$id : 0;
	}

	/**
	 * Varre a lista ordenada e devolve a posição (0-based) do id
	 * informado. -1 quando não encontrado.
	 */
	private function posicaoDoId($id) {
		$id = (int)$id;
		if ($id <= 0) return -1;
		$rs = ADODB_Model::$db->Execute("SELECT id FROM instituicoes ORDER BY instituicao");
		if (!$rs) return -1;
		$lugar = 0;
		while (!$rs->EOF) {
			if ((int)$rs->fields['id'] === $id) {
				return $lugar;
			}
			$lugar++;
			$rs->MoveNext();
		}
		return -1;
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção e, quando há POST, grava a instituição.
	 * Substitui instituicoes/inserir/inserir.php + instituicoes/inserir/formulario.php.
	 */
	public function inserir() {
		global $db, $sistema_autentica, $usuario_id;
		$this->comAutenticacao();

		$confirma = isset($_POST['inserir']) ? $_POST['inserir'] : null;

		if ($confirma !== null && $_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->gravaInsercao(); // grava e redireciona; não retorna
		}

		// GET (ou POST sem confirmação): renderiza o formulário.
		$smarty = new Smarty_estagio;
		$smarty->assign("matriz_areas", Instituicao::areasLista());
		$smarty->assign("supervisores", Instituicao::supervisoresTodos());
		$smarty->display("instituicao_form.tpl");
		exit;
	}

	/**
	 * Grava a instituição confirmada no formulário de inserção, vincula os
	 * supervisores marcados e (opcionalmente) cadastra+vincula um supervisor
	 * novo. Redireciona para a ficha da nova instituição.
	 */
	private function gravaInsercao() {
		global $usuario_id;

		$instituicao = $this->campoTexto('instituicao');
		if ($instituicao === '') {
			die("<p>O campo Instituição é obrigatório.</p>");
		}

		$area_id = isset($_POST['area_id']) && $_POST['area_id'] !== ''
			? (int)$_POST['area_id']
			: (isset($_POST['area']) && $_POST['area'] !== '' ? (int)$_POST['area'] : null);

		$nova = new Instituicao();
		$nova->instituicao      = $instituicao;
		$nova->natureza         = $this->campoTexto('natureza') ?: null;
		$nova->area_id          = $area_id;
		$nova->cnpj             = $this->campoTexto('cnpj') ?: null;
		$nova->email            = $this->campoTexto('email') ?: null;
		$nova->url              = $this->campoTexto('url') ?: null;
		$nova->endereco         = $this->campoTexto('endereco') ?: null;
		$nova->bairro           = $this->campoTexto('bairro') ?: null;
		$nova->municipio        = $this->campoTexto('municipio') ?: null;
		$nova->cep              = $this->campoTexto('cep') ?: null;
		$nova->telefone         = $this->campoTexto('telefone') ?: null;
		$nova->beneficios       = $this->campoTexto('beneficios') ?: null;
		$nova->fim_de_semana    = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : 0;
		$nova->convenio         = $this->campoTexto('convenio') ?: null;
		$nova->expira           = isset($_POST['expira']) && $_POST['expira'] !== '' ? $_POST['expira'] : null;
		$nova->seguro           = isset($_POST['seguro']) ? $_POST['seguro'] : 0;
		$nova->observacoes      = $this->campoTexto('observacoes') ?: null;
		$nova->user_id          = (int)(isset($usuario_id) ? $usuario_id : 0);
		$nova->estagiarios_count = 0;

		if (!$nova->save()) {
			error_log("Erro ao inserir instituicao: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível inserir a instituição. Tente novamente.");
		}

		$novo_id = $nova->getKey();

		// Vincula os supervisores existentes selecionados.
		$supervisores = isset($_POST['supervisores']) && is_array($_POST['supervisores'])
			? $_POST['supervisores']
			: array();
		foreach ($supervisores as $supervisor_id) {
			$supervisor_id = (int)$supervisor_id;
			if ($supervisor_id > 0) {
				$nova->vincularSupervisor($supervisor_id);
			}
		}

		// Opcional: cadastra um supervisor novo e já vincula à instituição.
		$novo_supervisor_nome = $this->campoTexto('novo_supervisor_nome');
		if ($novo_supervisor_nome !== '') {
			$novo_supervisor_cress = $this->campoTexto('novo_supervisor_cress');
			$novo_supervisor_email = $this->campoTexto('novo_supervisor_email');
			$ok_supervisor = Instituicao::$db->Execute(
				"INSERT INTO supervisores (nome, cress, email) VALUES (?, ?, ?)",
				array($novo_supervisor_nome, $novo_supervisor_cress, $novo_supervisor_email)
			);
			if ($ok_supervisor !== false) {
				$nova->vincularSupervisor((int)Instituicao::$db->Insert_ID());
			} else {
				error_log("Erro ao inserir supervisor: " . Instituicao::$db->ErrorMsg());
			}
		}

		header("Location: ver_cada.php?instituicao_id=$novo_id");
		exit;
	}

	/**
	 * Exibe o formulário simples de edição (GET) e, quando há POST, grava
	 * as alterações. Substitui instituicoes/atualizar/atualiza.php +
	 * instituicoes/atualizar/modifica.php.
	 */
	public function atualizar() {
		global $sistema_autentica;
		$this->comAutenticacao();

		$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
		$inst = Instituicao::find($instituicao_id);
		if ($inst === null) {
			die("Instituição não encontrada (id $instituicao_id).");
		}

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$this->gravaAtualizacao($inst); // grava e redireciona; não retorna
		}

		// Última turma com estagiários (readonly no formulário).
		$turma = '';
		if ($inst->countEstagiarios() > 0) {
			$turma = ADODB_Model::$db->GetOne(
				"SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
				array($instituicao_id)
			);
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("instituicao_id", $instituicao_id);
		$smarty->assign("nome_instituicao", $inst->instituicao);
		$smarty->assign("endereco_instituicao", $inst->endereco);
		$smarty->assign("cep_instituicao", $inst->cep);
		$smarty->assign("telefone_instituicao", $inst->telefone);
		$smarty->assign("id_area_instituicao", $inst->areaId());
		$smarty->assign("area_instituicao", $inst->areaNome());
		$smarty->assign("beneficio_instituicao", $inst->beneficios);
		$smarty->assign("fim_de_semana", $inst->fim_de_semana);
		$smarty->assign("convenio", $inst->convenio);
		$smarty->assign("turma", $turma);
		$smarty->assign("matriz_areas", Instituicao::areasLista());
		$smarty->assign("inst_supervisores", $inst->supervisores());
		$smarty->assign("supervisores", Instituicao::supervisoresTodos());
		$smarty->display("instituicao_modifica.tpl");
		exit;
	}

	/**
	 * Grava a edição confirmada no formulário simples de atualização.
	 * Redireciona para a ficha da instituição.
	 */
	private function gravaAtualizacao($inst) {
		$instituicao_id = (int)$inst->getKey();
		$dados = array();

		$area_val = isset($_POST['area_instituicao']) && $_POST['area_instituicao'] !== ''
			? (int)$_POST['area_instituicao']
			: 0;
		$dados['area_id'] = $area_val > 0 ? $area_val : null;

		$dados['instituicao']   = $this->campoTexto('nome_instituicao');
		$dados['endereco']      = $this->campoTexto('endereco_instituicao') ?: null;
		$dados['cep']           = $this->campoTexto('cep_instituicao') ?: null;
		$dados['telefone']      = $this->campoTexto('telefone_instituicao') ?: null;
		$dados['beneficios']    = $this->campoTexto('beneficio_instituicao') ?: null;
		$dados['fim_de_semana'] = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : null;
		$dados['convenio']      = $this->campoTexto('convenio') ?: null;
		$dados['seguro']        = isset($_POST['seguro']) ? $_POST['seguro'] : null;

		// Validação de tamanho (mantida do script antigo).
		$limites = array(
			'instituicao' => 75,
			'endereco'    => 104,
			'cep'         => 9,
		);
		foreach ($limites as $campo => $max) {
			if (isset($dados[$campo]) && mb_strlen((string)$dados[$campo], 'UTF-8') > $max) {
				echo "Campo '" . htmlspecialchars($campo, ENT_QUOTES, 'UTF-8') . "' excede $max caracteres (tamanho atual: "
				   . mb_strlen((string)$dados[$campo], 'UTF-8') . ").<br>";
				exit;
			}
		}

		if ($dados['instituicao'] === '') {
			die("<p>O nome da instituição é obrigatório.</p>");
		}

		foreach ($dados as $campo => $valor) {
			$inst->$campo = $valor;
		}

		if (!$inst->save()) {
			error_log("Erro ao atualizar instituicao: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível atualizar a tabela instituicoes. Tente novamente.");
		}

		header("Location: ver_cada.php?instituicao_id=$instituicao_id");
		exit;
	}

	/**
	 * Ficha de uma instituição, com navegação registro a registro e
	 * edição inline em 2 passos (mesma lógica do script antigo).
	 * Substitui instituicoes/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		global $db, $sistema_autentica, $usuario_id;
		$this->comAutenticacao();

		$instituicao_id = isset($_REQUEST['instituicao_id'])
			? (int)$_REQUEST['instituicao_id']
			: (isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0);
		$supervisor_id  = isset($_REQUEST['supervisor_id']) ? (int)$_REQUEST['supervisor_id'] : 0;
		$modifica       = isset($_REQUEST['modifica']) ? $_REQUEST['modifica'] : false;
		$flag           = isset($_REQUEST['flag']) ? (int)$_REQUEST['flag'] : 0;
		$inserir        = isset($_REQUEST['inserir']) ? $_REQUEST['inserir'] : null;
		$indice         = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;
		$botao          = isset($_REQUEST['botao']) ? $_REQUEST['botao'] : null;

		$curso = false; // este módulo trabalha somente com a tabela instituicoes

		// ---------- Inserir instituição em branco ----------
		if ($inserir) {
			$nova = new Instituicao();
			$nova->instituicao = '';
			$nova->cnpj = '';
			$nova->user_id = (int)(isset($usuario_id) ? $usuario_id : 0);
			if ($nova->save()) {
				$instituicao_id = $nova->getKey();
				$flash = "Registro criado. Preencher o formulário com os dados e logo clicar em 'Modificar instituição'.";
			} else {
				error_log("Erro ao inserir instituicao: " . Instituicao::$db->ErrorMsg());
				die("Não foi possível inserir a instituição. Tente novamente.");
			}
		}

		// ---------- Edição inline (2 passos: flag 1 = form, flag 2 = save) ----------
		if ($modifica) {
			$flag++;
			if ($flag == 2) {
				$area_instituicao  = $this->campoTexto('area');
				$nome_instituicao  = $this->campoTexto('instituicao');
				$url               = $this->campoTexto('url');
				$endereco          = $this->campoTexto('endereco');
				$bairro            = $this->campoTexto('bairro');
				$municipio         = $this->campoTexto('municipio');
				$cep               = $this->campoTexto('cep');
				$telefone          = $this->campoTexto('telefone');
				$beneficio         = $this->campoTexto('beneficios');
				$fim_de_semana     = isset($_POST['fim_de_semana']) ? $_POST['fim_de_semana'] : null;
				$convenio          = $this->campoTexto('convenio');
				$seguro            = isset($_POST['seguro']) ? $_POST['seguro'] : null;
				$observacoes       = $this->campoTexto('observacoes');
				$natureza          = $this->campoTexto('natureza');

				if ($nome_instituicao === '') {
					die("<p>Faltou inserir o nome da instituição. Registro será excluído."
					   . " <meta http-equiv='refresh' content='2;url=excluir.php?instituicao_id=$instituicao_id'></p>");
				}

				$inst_inline = Instituicao::find($instituicao_id);
				if ($inst_inline === null) {
					die("Instituição não encontrada (id $instituicao_id).");
				}
				$area_id_val = ($area_instituicao !== '' && $area_instituicao !== null) ? (int)$area_instituicao : 0;
				$inst_inline->area_id       = $area_id_val > 0 ? $area_id_val : null;
				$inst_inline->natureza      = $natureza ?: null;
				$inst_inline->instituicao   = $nome_instituicao;
				$inst_inline->url           = $url ?: null;
				$inst_inline->endereco      = $endereco ?: null;
				$inst_inline->bairro        = $bairro ?: null;
				$inst_inline->municipio     = $municipio ?: null;
				$inst_inline->cep           = $cep ?: null;
				$inst_inline->telefone      = $telefone ?: null;
				$inst_inline->beneficios    = $beneficio ?: null;
				$inst_inline->fim_de_semana = $fim_de_semana;
				$inst_inline->convenio      = $convenio ?: null;
				$inst_inline->seguro        = $seguro;
				$inst_inline->observacoes   = $observacoes ?: null;
				if (!$inst_inline->save()) {
					error_log("Erro ao atualizar instituicao: " . Instituicao::$db->ErrorMsg());
					die("Não foi possível atualizar a tabela instituicoes. Tente novamente.");
				}
				$flag = 0;
				$modifica = false;
			}
		}

		// ---------- Quantidade total de registros (para a navegação) ----------
		$num_linhas = Instituicao::count();
		$ultimo_registro = $num_linhas - 1;

		// ---------- Navegação (botao) ----------
		switch ($botao) {
			case "inserir":
			case "primeiro":
				$indice = 0;
				break;
			case "menos_1":
				$indice = ($indice == 0) ? $ultimo_registro : $indice - 1;
				break;
			case "menos_10":
				$indice = $indice - 10;
				if ($indice < 0) $indice = $ultimo_registro - abs($indice);
				break;
			case "mais_1":
				$indice++;
				if ($indice >= $num_linhas) $indice = 0;
				break;
			case "mais_10":
				$indice = $indice + 10;
				if ($indice > $ultimo_registro) $indice = $indice - $num_linhas;
				break;
			case "ultimo":
				$indice = $ultimo_registro;
				break;
			case "excluir":
				// Exclusão via excluir.php; nada a fazer aqui.
				break;
		}

		// ---------- Vínculo de supervisor ----------
		if ($supervisor_id > 0 && $instituicao_id > 0) {
			$instSup = Instituicao::find($instituicao_id);
			if ($instSup) {
				$instSup->vincularSupervisor($supervisor_id);
			}
		}

		// ---------- Resolve indice <-> id ----------
		if ($instituicao_id <= 0) {
			$instituicao_id = $this->idNaPosicao($indice);
		} else {
			$pos = $this->posicaoDoId($instituicao_id);
			if ($pos >= 0) $indice = $pos;
		}

		// ---------- Carrega a instituição atual ----------
		$inst = $instituicao_id > 0 ? Instituicao::find($instituicao_id) : null;

		$dados = array(
			'id'              => $inst ? $inst->getKey() : null,
			'instituicao'     => $inst ? $inst->instituicao : '',
			'url'             => $inst ? $inst->url : '',
			'endereco'        => $inst ? $inst->endereco : '',
			'bairro'          => $inst ? $inst->bairro : '',
			'municipio'       => $inst ? $inst->municipio : '',
			'cep'             => $inst ? $inst->cep : '',
			'telefone'        => $inst ? $inst->telefone : '',
			'beneficios'      => $inst ? $inst->beneficios : '',
			'fim_de_semana'   => $inst ? $inst->fim_de_semana : 0,
			'id_area'         => $inst ? $inst->areaId() : '',
			'area'            => $inst ? $inst->areaNome() : '',
			'natureza'        => $inst ? $inst->natureza : '',
			'convenio'        => $inst ? $inst->convenio : 0,
			'seguro'          => $inst ? $inst->seguro : 0,
			'observacoes'     => $inst ? $inst->observacoes : '',
			'turma'           => '',
			'inst_supervisores' => array(),
			'inst_professores'  => array(),
		);

		if ($inst) {
			// Última turma de estagiários da instituição.
			if ($inst->countEstagiarios() > 0) {
				$dados['turma'] = ADODB_Model::$db->GetOne(
					"SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
					array($inst->getKey())
				);
			}

			// Supervisores vinculados.
			$dados['inst_supervisores'] = $inst->supervisores();

			// Professores que orientam estagiários nesta instituição (última turma).
			$rs_prof = ADODB_Model::$db->Execute(
				"SELECT p.id AS id_professor, p.nome, MAX(e.periodo) AS periodo
				 FROM estagiarios AS e
				 INNER JOIN professores AS p ON e.professor_id = p.id
				 WHERE e.instituicao_id = ?
				 GROUP BY p.nome
				 ORDER BY p.nome",
				array($inst->getKey())
			);
			if ($rs_prof) {
				$i = 0;
				while (!$rs_prof->EOF) {
					$dados['inst_professores'][$i]['id_professor'] = $rs_prof->fields['id_professor'];
					$dados['inst_professores'][$i]['nome']         = $rs_prof->fields['nome'];
					$dados['inst_professores'][$i]['periodo']      = $rs_prof->fields['periodo'];
					$i++;
					$rs_prof->MoveNext();
				}
			}
		}

		// ---------- Listas auxiliares ----------
		$matriz_areas = Instituicao::areasLista();
		$supervisores = Instituicao::supervisoresTodos();

		$smarty = new Smarty_estagio;
		$smarty->assign("titulo", "Ver cada instituição");
		$smarty->assign("curso", $curso);
		$smarty->assign("modifica", $modifica);
		$smarty->assign("sistema_autentica", $sistema_autentica);
		$smarty->assign("indice", $indice);
		$smarty->assign("instituicao_id", $instituicao_id);
		$smarty->assign("id", $dados['id']);
		$smarty->assign("instituicao", $dados['instituicao']);
		$smarty->assign("url", $dados['url']);
		$smarty->assign("id_curso_instituicao", null);
		$smarty->assign("endereco", $dados['endereco']);
		$smarty->assign("cep", $dados['cep']);
		$smarty->assign("bairro", $dados['bairro']);
		$smarty->assign("municipio", $dados['municipio']);
		$smarty->assign("telefone", $dados['telefone']);
		$smarty->assign("beneficios", $dados['beneficios']);
		$smarty->assign("fim_de_semana", $dados['fim_de_semana']);
		$smarty->assign("id_area", $dados['id_area']);
		$smarty->assign("area", $dados['area']);
		$smarty->assign("natureza", $dados['natureza']);
		$smarty->assign("convenio", $dados['convenio']);
		$smarty->assign("seguro", $dados['seguro']);
		$smarty->assign("observacoes", $dados['observacoes']);
		$smarty->assign("turma", $dados['turma']);
		$smarty->assign("inst_supervisores", $dados['inst_supervisores']);
		$smarty->assign("inst_professores", $dados['inst_professores']);
		$smarty->assign("supervisores", $supervisores);
		$smarty->assign("matriz_areas", $matriz_areas);
		$smarty->assign("flag", $flag);
		$smarty->assign("flash", isset($flash) ? $flash : '');
		$smarty->display("instituicao_ver_cada.tpl");
		exit;
	}

	/**
	 * Lista instituições com filtros por período, nome e natureza.
	 * Substitui instituicoes/exibir/listar.php.
	 * PÚBLICO: não exige login.
	 */
	public function listar() {
		$this->semAutenticacao();

		$turma        = isset($_GET['turma']) ? $_GET['turma'] : Instituicao::$db->GetOne("SELECT MAX(periodo) FROM estagiarios");
		$sem_periodo  = ($turma === '' || $turma === null || (string)$turma === '0');
		$instituicao  = isset($_REQUEST['instituicao']) ? trim((string)$_REQUEST['instituicao']) : '';
		$naturezaFilt = isset($_GET['natureza']) ? $_GET['natureza'] : '';
		$todos_periodos = 0;
		$total_supervi  = 0;

		// ---------- Totais gerais ----------
		$total_alunos      = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT registro) FROM estagiarios");
		$total_instituicoes = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT instituicao_id) FROM estagiarios WHERE instituicao_id IS NOT NULL");
		$total_professores = (int)Instituicao::$db->GetOne("SELECT COUNT(DISTINCT professor_id) FROM estagiarios WHERE professor_id IS NOT NULL");

		// ---------- Lista de instituições (filtrada por turma e nome/natureza) ----------
		$wheres = array();
		$params = array();
		if (!$sem_periodo) {
			$wheres[] = "t.periodo = ?";
			$params[] = $turma;
		}
		if ($instituicao !== '') {
			$wheres[] = "e.instituicao LIKE ?";
			$params[] = "%$instituicao%";
		}
		if ($naturezaFilt !== '' && $naturezaFilt !== '0') {
			$wheres[] = "e.natureza = ?";
			$params[] = $naturezaFilt;
		}
		$where_sql = $wheres ? ('WHERE ' . implode(' AND ', $wheres)) : '';

		$sql = "SELECT e.id, e.instituicao, e.seguro, e.convenio, e.natureza, e.area_id AS id_area,
		               e.beneficios AS beneficio, a.area
		        FROM instituicoes AS e
		        LEFT JOIN areas AS a ON a.id = e.area_id
		        LEFT JOIN estagiarios AS t ON e.id = t.instituicao_id
		        $where_sql
		        GROUP BY e.instituicao, e.area_id, e.beneficios, e.id";
		$resultado = Instituicao::$db->Execute($sql, $params);
		if ($resultado === false) {
			error_log("Erro ao consultar instituicoes: " . Instituicao::$db->ErrorMsg());
			die("Não foi possível consultar a tabela instituicoes.");
		}

		$matriz = array();
		while (!$resultado->EOF) {
			$id          = (int)$resultado->fields['id'];
			$inst_nome   = $resultado->fields['instituicao'];
			$convenio    = $resultado->fields['convenio'];
			$seguro      = $resultado->fields['seguro'];
			$beneficio   = $resultado->fields['beneficio'];
			$id_area     = $resultado->fields['id_area'];
			$area        = $resultado->fields['area'];
			$natureza    = $resultado->fields['natureza'];

			// Supervisores distintos no período (e no total quando turma=vazio).
			$superSql   = "SELECT COUNT(DISTINCT supervisor_id) FROM estagiarios WHERE instituicao_id = ?";
			$superArgs  = array($id);
			if (!$sem_periodo) {
				$superSql .= " AND periodo = ?";
				$superArgs[] = $turma;
			}
			$q_supervi  = (int)Instituicao::$db->GetOne($superSql, $superArgs);
			$total_supervi += $q_supervi;

			// Última turma com estagiários na instituição.
			$turma_inst = Instituicao::$db->GetOne(
				"SELECT MAX(periodo) FROM estagiarios WHERE instituicao_id = ?",
				array($id)
			);
			$turma_inst = $turma_inst ?: '';

			// Alunos (distintos por registro) no período selecionado.
			$aluSql   = "SELECT COUNT(DISTINCT registro) FROM estagiarios WHERE instituicao_id = ?";
			$aluArgs  = array($id);
			if (!$sem_periodo) {
				$aluSql .= " AND periodo = ?";
				$aluArgs[] = $turma;
			}
			$q_alunos = (int)Instituicao::$db->GetOne($aluSql, $aluArgs);

			// Total de períodos (valores distintos de periodo) para a instituição.
			$n_periodos = Instituicao::$db->GetOne(
				"SELECT COUNT(DISTINCT periodo) FROM estagiarios WHERE instituicao_id = ?",
				array($id)
			);
			$todos_periodos += (int)$n_periodos;

			$row = array(
				'instituicao_id' => $id,
				'instituicao'    => $inst_nome,
				'convenio'       => $convenio,
				'seguro'         => $seguro,
				'beneficio'      => $beneficio,
				'area'           => $area,
				'natureza'       => $natureza,
				'supervisores'   => $q_supervi,
				'turma'          => $turma_inst,
				'alunos'         => $q_alunos,
				'periodos'       => (int)$n_periodos,
			);
			$matriz[] = $row;

			$resultado->MoveNext();
		}

		// ---------- Períodos e naturezas para os filtros ----------
		$periodos = array();
		$rs_turma = Instituicao::$db->Execute("SELECT DISTINCT periodo FROM estagiarios WHERE periodo IS NOT NULL ORDER BY periodo DESC");
		if ($rs_turma) {
			while (!$rs_turma->EOF) {
				$periodos[] = $rs_turma->fields['periodo'];
				$rs_turma->MoveNext();
			}
		}

		$naturezas = array();
		$rs_nat = Instituicao::$db->Execute("SELECT DISTINCT natureza FROM instituicoes WHERE natureza IS NOT NULL ORDER BY natureza");
		if ($rs_nat) {
			while (!$rs_nat->EOF) {
				$naturezas[] = $rs_nat->fields['natureza'];
				$rs_nat->MoveNext();
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("turma", $turma);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("natureza", $naturezaFilt);
		$smarty->assign("naturezas", $naturezas);
		$smarty->assign("instituicao", $instituicao);
		$smarty->assign("instituicoes", $matriz);
		$smarty->assign("total_professores", $total_professores);
		$smarty->assign("total_instituicoes", $total_instituicoes);
		$smarty->assign("total_supervisores", $total_supervi);
		$smarty->assign("total_alunos", $total_alunos);
		$smarty->assign("total_periodos", $todos_periodos);
		$smarty->display("instituicoes.tpl");
		exit;
	}

	/**
	 * Exclui uma instituição, desde que não tenha dependências
	 * (supervisores vinculados, estagiários, visitas, murais). Substitui
	 * instituicoes/cancelar/cancela.php.
	 */
	public function excluir() {
		global $sistema_autentica;
		$this->comAutenticacao();

		$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
		$indice         = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

		if ($instituicao_id <= 0) {
			echo "Instituição inválida (id $instituicao_id).";
			exit;
		}

		$inst = Instituicao::find($instituicao_id);
		if ($inst === null) {
			echo "Instituição não encontrada (id $instituicao_id).";
			echo "<meta http-equiv='refresh' content='2;ver_cada.php?indice=$indice' />";
			exit;
		}

		// ---------- Checagem de registros dependentes ----------
		$erros = array();

		$countInstSuper = (int)Instituicao::$db->GetOne(
			"SELECT COUNT(*) FROM inst_super WHERE instituicao_id = ?",
			array($instituicao_id)
		);
		if ($countInstSuper > 0) {
			$erros[] = "supervisores vinculados ($countInstSuper)";
		}

		$countEstagiarios = $inst->countEstagiarios();
		if ($countEstagiarios > 0) {
			$erros[] = "estagiários ($countEstagiarios)";
		}

		$countVisitas = $inst->countVisitas();
		if ($countVisitas > 0) {
			$erros[] = "visitas ($countVisitas)";
		}

		$countMurais = $inst->countMurais();
		if ($countMurais > 0) {
			$erros[] = "registros de mural de estágios ($countMurais)";
		}

		if (!empty($erros)) {
			echo "Operação abortada. É necessário primeiro excluir os seguintes registros "
			   . "relacionados a esta instituição:<br>"
			   . "<ul><li>" . implode('</li><li>', $erros) . "</li></ul>";
			echo "<meta http-equiv='refresh' content='3;ver_cada.php?instituicao_id=$instituicao_id' />";
			exit;
		}

		// ---------- Nenhum registro dependente: exclui ----------
		if (!$inst->delete()) {
			error_log("Erro ao excluir instituicao: " . Instituicao::$db->ErrorMsg());
			echo "Não foi possível excluir o registro da tabela instituicoes.";
			exit;
		}

		if (!$indice) $indice = 0;
		echo "<meta http-equiv='refresh' content='0;ver_cada.php?indice=$indice' />";
		exit;
	}

}

}

?>
