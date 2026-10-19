<?php
/**
 * MuralestagiosController.php — Controlador da tabela mural_estagios.
 *
 * Reúne as ações relacionadas às ofertas de estágio do mural (tabela
 * mural_estagios). Cada ação substitui um script legado:
 *
 *   listar()    <- mural-listar.php      (listagem do mural; o admin escolhe o
 *                      período pelo seletor, os demais perfis veem o atual)
 *   inserir()   <- mural-inserir.php   (formulário + gravação, com login)
 *   inscricao_aluno() <- mural-aluno_inscricao.php (cadastro público do
 *                      candidato novo e inscrição na oferta, sem login)
 *   ver_cada()  <- mural-ver_cada.php        (ficha registro a registro, sem login)
 *   editar()    <- mural-modifica.php  (formulário de edição, com login)
 *   atualizar() <- mural-atualiza.php  (gravação da edição, sem login)
 *   excluir()   <- mural-excluir.php         (exclusão da oferta, com login)
 *   selecionar() <- seleciona.php      (seletor modificar/excluir, com login)
 *   excluir_aluno() <- mural-excluir_aluno.php (tira o aluno da lista de
 *                      alunos que buscam estágio, com login)
 *   inscritos() <- lista-alunos.php, lista-novos_alunos.php,
 *                      alunosConhecidos.php e listaInscritos.php (tabela única
 *                      dos alunos inscritos, com filtros de período, oferta e
 *                      grupo — os quatro scripts foram removidos)
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Mural (libphp/Mural.php), que grava com
 * parâmetros ligados — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/MuralestagiosController.php");
 *   $controller = new MuralestagiosController();
 *   $controller->listar();
 *
 * ATENÇÃO: atualizar() NÃO exige login — mantido por paridade com o script
 * antigo (mural-atualiza.php também não exigia). Vale proteger esse endpoint
 * no futuro.
 */
if (!class_exists('MuralestagiosController', false)) {

class MuralestagiosController {

	/**
	 * Inclui autentica.inc (exige login ativo) e carrega os models.
	 * As variáveis precisam ser globais ANTES do include: setup.php, incluído
	 * por autentica.inc, escreve $db; assim o valor cai em $GLOBALS e
	 * libphp/models.php consegue ligar a conexão aos modelos.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $isProfessor, $isSupervisor;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
		require_once(__DIR__ . "/../libphp/sessao.php");
	}

	/**
	 * As exclusões do mural (oferta do mural e remoção de aluno da lista) são
	 * restritas ao role admin. A interface esconde os botões dos demais
	 * perfis, mas a permissão é conferida de novo aqui — nunca só na tela.
	 */
	private function exigirAdmin() {
		global $isAdmin;
		if (empty($isAdmin)) {
			die("Acesso restrito: apenas administradores podem excluir registros do mural.");
		}
	}

	/**
	 * Inclui apenas setup.php (sem forçar login), como faziam os scripts
	 * antigos de ficha/edição, e carrega os models.
	 */
	private function semAutenticacao() {
		global $db;
		include_once(__DIR__ . "/../setup.php");
		require_once(__DIR__ . "/../libphp/models.php");
		require_once(__DIR__ . "/../libphp/sessao.php");
	}

	/**
	 * Lista as ofertas do mural (front-controller mural-listar.php).
	 *
	 * O seletor de período é exclusivo do admin: os demais perfis sempre veem
	 * o período atual e o parâmetro ?periodo da URL é ignorado. O papel vem da
	 * SESSÃO autenticada (cookie de identificação não autoriza).
	 */
	public function listar() {
		global $db, $sistema_autentica;
		$this->semAutenticacao();

		$is_admin = false;
		$sessao_usuario = sessao_usuario_atual();
		if ($sessao_usuario !== null) {
			$is_admin = ((string)$sessao_usuario['role'] === 'admin');
		}

		// Nome do aluno recém-inscrito em seleção de estágio (vem por GET).
		$insere = isset($_GET['insere']) ? $_GET['insere'] : '';

		// Períodos existentes no mural: preenchem o seletor do admin e servem
		// de lista fechada de valores aceitos em ?periodo.
		$periodos = Mural::periodos();

		// Período exibido. Para o admin, ?periodo escolhe o período (vazio =
		// todos); valor fora da lista cai de volta no período atual.
		$periodo = PERIODO_ATUAL;
		if ($is_admin && isset($_GET['periodo'])) {
			$periodo = trim((string)$_GET['periodo']);
			if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
				$periodo = PERIODO_ATUAL;
			}
		}

		// Ordenação: chave validada por whitelist dentro do model.
		$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : '';
		$instituicao = Mural::listarMuralPorPeriodo($periodo, $ordem);
		$totalVagas = Mural::totalVagasPorPeriodo($periodo);

		$stats = Mural::estatisticasInscritos($periodo);
		$novos = $stats['total'] - $stats['conhecidos'];
		$novo_novo = $novos + $stats['estagio_um'];
		$conhecidos_conhecidos = $stats['conhecidos'] - $stats['estagio_um'];

		$smarty = new Smarty_estagio;
		$smarty->assign("is_admin", $is_admin);
		$smarty->assign("periodo", $periodo);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("sistema_autentica", $sistema_autentica);
		$smarty->assign("insere", $insere);
		$smarty->assign("instituicao", $instituicao);
		$smarty->assign("totalVagas", $totalVagas);
		$smarty->assign("totalAlunos", $stats['total']);
		$smarty->assign("alunos_novos", $novo_novo);
		$smarty->assign("alunosVelhos", $conhecidos_conhecidos);
		$smarty->display("muralestagios-listar.tpl");
		exit;
	}

	/**
	 * Tabela única dos alunos inscritos no mural. Substitui as quatro listagens
	 * legadas: lista-alunos.php (grupo todos), lista-novos_alunos.php (grupo
	 * novos), alunosConhecidos.php (grupo conhecidos) e listaInscritos.php
	 * (filtro por oferta).
	 *
	 * Filtros: ?periodo (vazio = todos os períodos; SÓ o admin escolhe),
	 * ?muralestagio_id (0 = todas as ofertas) e ?grupo
	 * ('todos' | 'novos' | 'conhecidos'). A ordenação é do DataTables, no
	 * cliente. Exige login e é restrita à coordenação (admin, professor ou
	 * supervisor); alunos usam inscricoes/listar.php, que mostra só as
	 * próprias inscrições.
	 */
	public function inscritos() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $isProfessor, $isSupervisor;
		$this->comAutenticacao();

		// A lista reúne dados pessoais de todos os inscritos: é exclusiva da
		// coordenação. Alunos usam inscricoes/listar.php, que mostra apenas as
		// próprias inscrições.
		if (empty($isAdmin) && empty($isProfessor) && empty($isSupervisor)) {
			die("Acesso restrito: a lista de inscritos no mural é exclusiva da coordenação.");
		}

		$is_admin = !empty($isAdmin);

		// Períodos com ofertas no mural: preenchem o seletor do admin e servem
		// de lista fechada de valores aceitos em ?periodo.
		$periodos = Mural::periodos();

		// Período exibido: o admin escolhe (vazio = todos); os demais perfis
		// sempre veem o período atual e o parâmetro da URL é ignorado.
		$periodo = PERIODO_ATUAL;
		if ($is_admin && isset($_GET['periodo'])) {
			$periodo = trim((string)$_GET['periodo']);
			if ($periodo !== '' && !in_array($periodo, $periodos, true)) {
				$periodo = PERIODO_ATUAL;
			}
		}

		$muralestagio_id = isset($_GET['muralestagio_id']) ? (int)$_GET['muralestagio_id'] : 0;

		$grupo = isset($_GET['grupo']) ? (string)$_GET['grupo'] : 'todos';
		if (!in_array($grupo, array('todos', 'novos', 'conhecidos'), true)) {
			$grupo = 'todos';
		}

		$alunos = Inscricao::listarAlunosPorPeriodo($periodo, $muralestagio_id, $grupo);

		// Ofertas do período para o seletor e a instituição em foco (título e
		// link de impressão).
		$ofertas     = Inscricao::ofertasPorPeriodo($periodo);
		$instituicao = '';
		if ($muralestagio_id > 0) {
			$oferta = Mural::find($muralestagio_id);
			$instituicao = ($oferta !== null) ? $oferta->instituicao : '';
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("is_admin", $is_admin);
		$smarty->assign("sistema_autentica", $sistema_autentica);
		$smarty->assign("periodo", $periodo);
		$smarty->assign("periodo_atual", PERIODO_ATUAL);
		$smarty->assign("periodos", $periodos);
		$smarty->assign("muralestagio_id", $muralestagio_id);
		$smarty->assign("instituicao", $instituicao);
		$smarty->assign("grupo", $grupo);
		$smarty->assign("ofertas", $ofertas);
		$smarty->assign("totalAlunos", count($alunos));
		$smarty->assign("alunos", $alunos);
		$smarty->display("muralestagios-inscritos.tpl");
		exit;
	}

	/**
	 * Cadastro público do candidato novo e inscrição dele na oferta escolhida.
	 * Substitui mural-aluno_inscricao.php (antigo cadastro.php).
	 *
	 * Exibe o formulário (muralestagios-alunos_insere.tpl) e, no POST, grava o
	 * aluno e a inscrição no período atual, redirecionando para a lista de
	 * inscritos da oferta. Não exige login: é a rota pública alcançada por
	 * verifica.php.
	 */
	public function inscricao_aluno() {
		global $db;
		$this->semAutenticacao();

		$registro        = isset($_REQUEST['registro']) ? trim((string)$_REQUEST['registro']) : '';
		$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

		// Instituição da oferta em que o candidato se inscreve.
		$oferta = ($muralestagio_id > 0) ? Mural::find($muralestagio_id) : null;
		if ($oferta === null) {
			die("Oferta de estágio não encontrada no mural (id $muralestagio_id).");
		}

		if (!empty($_POST['submit'])) {
			$this->gravaInscricaoAluno($registro, $muralestagio_id);
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("registro", $registro);
		$smarty->assign("instituicao", $oferta->instituicao);
		$smarty->assign("muralestagio_id", $muralestagio_id);
		$smarty->display("muralestagios-alunos_insere.tpl");
		exit;
	}

	/**
	 * Grava o aluno novo e a inscrição dele na oferta; redireciona ao final.
	 *
	 * alunos.id NÃO é auto_increment: o id vem de Aluno::proximoId().
	 */
	private function gravaInscricaoAluno($registro, $muralestagio_id) {
		if (!ctype_digit($registro)) {
			die("Registro (DRE) inválido: informe somente números.");
		}

		// Aluno já cadastrado: segue para a atualização dos dados.
		if (Aluno::registroDuplicado($registro) !== null) {
			header("Location: mural-alunos_modifica.php?registro=$registro");
			exit;
		}

		$nascimento = isset($_POST['nascimento']) ? trim($_POST['nascimento']) : '';

		// Grava o aluno novo.
		$aluno = new Aluno();
		$aluno->id                = Aluno::proximoId();
		$aluno->registro          = (int)$registro;
		$aluno->nome              = isset($_POST['nome']) ? $_POST['nome'] : '';
		$aluno->codigo_telefone   = (isset($_POST['codigo_telefone']) && $_POST['codigo_telefone'] !== '') ? (int)$_POST['codigo_telefone'] : 21;
		$aluno->telefone          = isset($_POST['telefone']) ? $_POST['telefone'] : '';
		$aluno->codigo_celular    = (isset($_POST['codigo_celular']) && $_POST['codigo_celular'] !== '') ? (int)$_POST['codigo_celular'] : 21;
		$aluno->celular           = isset($_POST['celular']) ? $_POST['celular'] : '';
		$aluno->email             = strtolower(isset($_POST['email']) ? $_POST['email'] : '');
		$aluno->cpf               = isset($_POST['cpf']) ? $_POST['cpf'] : '';
		$aluno->identidade        = isset($_POST['identidade']) ? $_POST['identidade'] : '';
		$aluno->orgao             = isset($_POST['orgao']) ? $_POST['orgao'] : '';
		$aluno->nascimento        = ($nascimento === '') ? null : date("Y-m-d", strtotime($nascimento));
		$aluno->endereco          = isset($_POST['endereco']) ? $_POST['endereco'] : '';
		$aluno->cep               = isset($_POST['cep']) ? $_POST['cep'] : '';
		$aluno->municipio         = isset($_POST['municipio']) ? $_POST['municipio'] : '';
		$aluno->bairro            = isset($_POST['bairro']) ? $_POST['bairro'] : '';
		$aluno->estagiarios_count = 0;
		$aluno->inscricao_count   = 0;

		if (!$aluno->save()) {
			error_log("Erro ao inserir aluno: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível inserir o registro na tabela alunos");
		}

		// Grava a inscrição do aluno novo na oferta escolhida.
		if (Inscricao::inscrever($registro, $muralestagio_id, $aluno->id, PERIODO_ATUAL, date('Y-m-d')) === null) {
			die("Não foi possível inserir o registro na tabela inscricoes");
		}

		header("Location: inscritos.php?muralestagio_id=$muralestagio_id");
		exit;
	}

	/**
	 * Exibe o formulário de inserção e, quando confirmado, grava a oferta.
	 * Substitui mural-inserir.php.
	 */
	public function inserir() {
		global $db, $sistema_autentica;
		$this->comAutenticacao();

		$confirma = isset($_POST['inserir']) ? $_POST['inserir'] : null;
		$aviso    = isset($_GET['aviso']) ? $_GET['aviso'] : null;

		if ($confirma === "Confirma") {
			$convenio = isset($_POST['convenio']) ? (string)$_POST['convenio'] : '';
			if ($convenio === "1") {
				$this->gravaInsercao(); // grava e redireciona; não retorna
			} elseif ($convenio === "0") {
				// Antes: redirect para mural-inserir.php?aviso=...; agora exibe
				// o aviso diretamente junto com o formulário.
				$aviso = "Instituição NÃO conveniada!";
			}
		}

		// Listas dos seletores (área e professor são apenas visuais: a tabela
		// mural_estagios não tem colunas para eles).
		$area_id = array(0);
		$areas   = array("Seleciona área");
		foreach (Area::listarAllAreas() as $a) {
			$area_id[] = $a['id'];
			$areas[]   = $a['area'];
		}

		$professor_id = array(0);
		$professores  = array("Seleciona professor");
		foreach (Professor::listaSimples() as $p) {
			$professor_id[] = $p['id'];
			$professores[]  = $p['nome'];
		}

		$instituicoes = Instituicao::listar_todas();

		$periodo_atual = PERIODO_ATUAL;

		$smarty = new Smarty_estagio;
		$smarty->assign("aviso", $aviso);
		$smarty->assign("periodo", $periodo_atual);
		$smarty->assign("periodo_atual", $periodo_atual);
		$smarty->assign("professor_id", $professor_id);
		$smarty->assign("professores", $professores);
		$smarty->assign("area_id", $area_id);
		$smarty->assign("areas", $areas);
		$smarty->assign("instituicoes", $instituicoes);
		$smarty->display("muralestagios-inserir.tpl");
		exit;
	}

	/**
	 * Grava a oferta confirmada no formulário de inserção.
	 */
	private function gravaInsercao() {
		$instituicao_id = isset($_POST['instituicao_id']) ? (int)$_POST['instituicao_id'] : 0;
		$instituicao = Instituicao::find($instituicao_id);
		if (!$instituicao) die("Instituição não encontrada");

		$periodo = isset($_POST['periodo']) ? trim((string)$_POST['periodo']) : '';
		if (!preg_match('/^\d{4}-\d{1,2}$/', $periodo)) {
			$periodo = PERIODO_ATUAL;
		}

		$mural = new Mural();
		$mural->preencher(array(
			'instituicao_id'  => $instituicao_id,
			'instituicao'     => $instituicao->instituicao,
			'convenio'        => '1',
			'vagas'           => isset($_POST['vagas']) ? (int)$_POST['vagas'] : 0,
			'beneficios'      => $this->campoTexto('beneficios'),
			'final_de_semana' => $this->campoEmLista('final_de_semana', array('0', '1', '2'), '0'),
			'carga_horaria'   => isset($_POST['carga_horaria']) ? (int)$_POST['carga_horaria'] : 0,
			'requisitos'      => $this->campoTexto('requisitos'),
			'horario'         => $this->campoEmLista('horario', array('D', 'N', 'A'), null),
			'data_selecao'    => $this->dataDoTriplo('selecao'),
			'data_inscricao'  => $this->dataDoTriplo('inscricao'),
			'horario_selecao' => $this->campoTexto('horario_selecao'),
			'local_selecao'   => $this->campoTexto('local_selecao'),
			'forma_selecao'   => $this->campoEmLista('forma_selecao', array('0', '1', '2', '3'), null),
			'contato'         => $this->campoTexto('contato'),
			'outras'          => $this->campoTexto('outras'),
			'periodo'         => $periodo,
			'email'           => $this->campoTexto('email'),
		));

		if (!$mural->save()) die("Não foi possível inserir o registro na tabela mural_estagios");

		header("Location: mural-ver_cada.php?muralestagio_id=" . $mural->getKey());
		exit;
	}

	/**
	 * Ficha de uma oferta, com navegação registro a registro.
	 * Usada por mural-ver_cada.php. Não força login: apenas lê o cookie para
	 * habilitar funções administrativas.
	 */
	public function ver_cada() {
		global $db;
		$this->semAutenticacao();

		// Id da oferta recebido na URL/formulário (muralestagio_id é o nome
		// único para a PK de mural_estagios). A variável é reatribuída mais
		// abaixo com o id da oferta EM EXIBIÇÃO, usado pelos links do template.
		$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;
		$indice          = isset($_REQUEST['indice']) ? $_REQUEST['indice'] : '';
		$botao           = isset($_REQUEST['botao']) ? $_REQUEST['botao'] : '';

		$num_linhas = Mural::contarPorPeriodo(PERIODO_ATUAL);
		$ultimo_registro = $num_linhas - 1;

		// Navegação entre registros — mesma lógica (e mesmas peculiaridades)
		// do script antigo; o model apenas protege índices fora da faixa.
		switch ($botao) {
			case "primeiro":
				$indice = 0;
				break;

			case "menos_1":
				$indice--;
				if ($indice < 0) $indice = $num_linhas - 1;
				break;

			case "menos_10":
				$indice = $indice - 10;
				if ($indice < 0) $indice = $ultimo_registro - abs($indice);
				break;

			case "mais_1":
				$indice++;
				if ($indice == $num_linhas) $indice = 0;
				break;

			case "mais_10":
				$indice = $indice + 10;
				if ($indice > $ultimo_registro) $indice = $indice - $num_linhas;
				break;

			case "ultimo":
				$indice = $ultimo_registro;
				break;
		}
		$indice = (int)$indice;

		// Posição na listagem. Pelos botões de navegação (indice + botao) ou,
		// quando o acesso vem por link, pelo muralestagio_id. O formulário de
		// navegação também envia muralestagio_id (a oferta em exibição), então
		// ele só vale quando não há botão no request — senão cada clique de
		// navegação voltaria para a oferta atual em vez de avançar.
		if ($botao === '' && $muralestagio_id > 0) {
			$pos = Mural::posicaoDoRegistro($muralestagio_id, PERIODO_ATUAL);
			if ($pos !== null) $indice = $pos;
		}

		$registro = ($num_linhas > 0) ? Mural::registroDaPosicao(PERIODO_ATUAL, $indice) : null;
		$instituicao = $registro ? array($registro) : array();
		$muralestagio_id = $registro ? $registro['muralestagio_id'] : 0;

		// Perfil do usuário logado (sem forçar login): define a visibilidade
		// do botão Inscrição. A identidade vem da SESSÃO e é relida da tabela
		// users a cada requisição — usuário desativado perde o acesso na hora.
		$sistema_autentica = 0;
		$usuario_role = '';
		$sessao_usuario = sessao_usuario_atual();
		if ($sessao_usuario !== null) {
			$usuario_role = (string)$sessao_usuario['role'];
			$sistema_autentica = 1;
		}

		// Botão Inscrição: admin sempre; aluno só com inscrição ainda aberta;
		// professor/supervisor nunca.
		$data_inscricao_iso = $registro ? $registro['data_inscricao_iso'] : '';
		$pode_inscrever = false;
		if ($usuario_role === "admin") {
			$pode_inscrever = true;
		} elseif ($usuario_role === "aluno" && $data_inscricao_iso !== '' && $data_inscricao_iso < date("Y-m-d")) {
			$pode_inscrever = true;
		}
		if ($registro) {
			$registro['pode_inscrever'] = $pode_inscrever;
			$instituicao = array($registro);
		}

		$data_hoje = date("Ymd");

		// Escalares comparados no template como inteiro Ymd; 0 = inscrição
		// direta na instituição (sem data de encerramento na Coordenação).
		$data_inscricao = ($data_inscricao_iso !== '') ? (int)str_replace('-', '', $data_inscricao_iso) : 0;
		$data_selecao_iso = $registro ? $registro['data_selecao_iso'] : '';
		$data_selecao = ($data_selecao_iso !== '') ? (int)str_replace('-', '', $data_selecao_iso) : 0;

		$smarty = new Smarty_estagio;
		$smarty->assign("isAdmin", $usuario_role === "admin");
		$smarty->assign("sistema_autentica", $sistema_autentica);
		$smarty->assign("muralestagio_id", $muralestagio_id);
		$smarty->assign("pode_inscrever", $pode_inscrever);
		$smarty->assign("instituicao", $instituicao);
		$smarty->assign("data_hoje", $data_hoje);
		$smarty->assign("data_inscricao", $data_inscricao);
		$smarty->assign("data_selecao", $data_selecao);
		$smarty->assign("data_encerramento", ''); // campo legado, nunca preenchido
		$smarty->assign("data_fax", 0);
		$smarty->assign("indice", $indice);
		$smarty->display("muralestagios-ver_cada.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição de uma oferta.
	 * Substitui mural-modifica.php. Precisa de autenticação .
	 */
	public function editar() {
		global $db;
		$this->comAutenticacao();

		// Id da oferta (PK de mural_estagios).
		$id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;
		$mural = Mural::find($id);
		if (!$mural) die("Registro não encontrado na tabela mural_estagios");

		// Seletores (área e professor são apenas visuais: sem colunas destino).
		$areas = array();
		foreach (Area::listarAllAreas() as $a) {
			$areas[] = array('id_areas' => $a['id'], 'areas' => $a['area']);
		}
		$professores = array();
		foreach (Professor::listaSimples() as $p) {
			$professores[] = array('id_professores' => $p['id'], 'professores' => $p['nome']);
		}

		$f = Mural::formatar($mural->toArray(true));

		$smarty = new Smarty_estagio;
		$smarty->assign("muralestagio_id", $id);
		$smarty->assign("convenio", $mural->convenio);
		$smarty->assign("instituicao", $mural->instituicao);
		$smarty->assign("vagas", $mural->vagas);
		$smarty->assign("beneficios", $mural->beneficios);
		$smarty->assign("final_de_semana", $mural->final_de_semana);
		$smarty->assign("cargaHoraria", $mural->carga_horaria);
		$smarty->assign("requisitos", $mural->requisitos);
		$smarty->assign("area_id", 0);
		$smarty->assign("area", '');
		$smarty->assign("professor_id", 0);
		$smarty->assign("professor", '');
		$smarty->assign("horario", $mural->horario);
		$smarty->assign("dataInscricao", $f['data_inscricao']); // dd-mm-aaaa ou ''
		$smarty->assign("dataSelecao", $f['data_selecao']);
		$smarty->assign("horarioSelecao", $mural->horario_selecao);
		$smarty->assign("localSelecao", $mural->local_selecao);
		$smarty->assign("formaSelecao", $mural->forma_selecao);
		$smarty->assign("contato", $mural->contato);
		$smarty->assign("email", $mural->email);
		$smarty->assign("outras", $mural->outras);
		$smarty->assign("periodo", $mural->periodo);
		$smarty->assign("areas", $areas);
		$smarty->assign("professores", $professores);
		$smarty->display("muralestagios-atualizar.tpl");
		exit;
	}

	/**
	 * Grava a edição confirmada no formulário de edição.
	 * Substitui mural-atualiza.php. Mantém a ausência de login do original.
	 */
	public function atualizar() {
		global $db;
		$this->semAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			// Acesso direto (GET) ao endpoint de gravação: manda para o
			// formulário de edição.
			$id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;
			header("Location: mural-modifica.php?muralestagio_id=" . $id);
			exit;
		}

		// Id da oferta vindo do formulário de edição (campo muralestagio_id).
		$id = isset($_POST['muralestagio_id']) ? (int)$_POST['muralestagio_id'] : 0;
		$mural = Mural::find($id);
		if (!$mural) die("Registro não encontrado na tabela mural_estagios");

		$periodo = isset($_POST['periodo']) ? trim((string)$_POST['periodo']) : '';
		if (!preg_match('/^\d{4}-\d{1,2}$/', $periodo)) {
			$periodo = $mural->periodo;
		}

		$mural->preencher(array(
			'instituicao'     => isset($_POST['instituicao']) ? trim((string)$_POST['instituicao']) : $mural->instituicao,
			'convenio'        => $this->campoEmLista('convenio', array('0', '1'), $mural->convenio),
			'vagas'           => isset($_POST['vagas']) ? (int)$_POST['vagas'] : 0,
			'beneficios'      => $this->campoTexto('beneficios'),
			'final_de_semana' => $this->campoEmLista('final_de_semana', array('0', '1', '2'), $mural->final_de_semana),
			'carga_horaria'   => isset($_POST['carga_horaria']) ? (int)$_POST['carga_horaria'] : 0,
			'requisitos'      => $this->campoTexto('requisitos'),
			'horario'         => $this->campoEmLista('horario', array('D', 'N', 'A'), $mural->horario),
			'data_selecao'    => Mural::dataParaGravar(isset($_POST['data_selecao']) ? $_POST['data_selecao'] : ''),
			'data_inscricao'  => Mural::dataParaGravar(isset($_POST['data_inscricao']) ? $_POST['data_inscricao'] : ''),
			'horario_selecao' => $this->campoTexto('horario_selecao'),
			'local_selecao'   => $this->campoTexto('local_selecao'),
			'forma_selecao'   => $this->campoEmLista('forma_selecao', array('0', '1', '2', '3'), $mural->forma_selecao),
			'contato'         => $this->campoTexto('contato'),
			'email'           => $this->campoTexto('email'),
			'outras'          => $this->campoTexto('outras'),
			'periodo'         => $periodo,
		));

		if (!$mural->save()) die("Não foi possível atualizar o registro na tabela mural_estagios");

		header("Location: mural-ver_cada.php?muralestagio_id=" . $mural->getKey());
		exit;
	}

	/**
	 * Exclui uma oferta do mural (DELETE). Substitui mural-excluir.php.
	 * Restrito ao admin e só exclui quando não há alunos inscritos: as
	 * inscrições apontam para a oferta (inscricoes.muralestagio_id) e a tabela
	 * não tem chave estrangeira, então a checagem é feita aqui, antes do delete.
	 */
	public function excluir() {
		global $db;
		$this->comAutenticacao();
		$this->exigirAdmin();

		$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;
		if ($muralestagio_id <= 0) {
			die("Oferta inválida (id $muralestagio_id).");
		}

		$mural = Mural::find($muralestagio_id);
		if (!$mural) die("Registro não encontrado na tabela mural_estagios");

		$quantidade = Mural::contarInscritos($muralestagio_id);
		if ($quantidade > 0) {
			echo "Primeiro tem que excluir os alunos inscritos ($quantidade) para logo poder excluir a oferta do mural.";
			echo "<meta http-equiv='refresh' content='3;mural-listar.php' />";
			exit;
		}

		if (!$mural->delete()) {
			error_log("Erro ao excluir mural_estagios: " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir o registro da tabela mural_estagios");
		}

		header("Location: mural-listar.php");
		exit;
	}

	/**
	 * Seletor de ofertas do período atual para modificar ou excluir.
	 * Substitui seleciona.php — que consultava a tabela inexistente
	 * mural_estagio (singular) e morria antes mesmo de exibir o formulário.
	 * O template decide o destino do formulário pela opção: "cancela" aponta
	 * para mural-excluir.php; qualquer outro valor (ou ausente) aponta para
	 * mural-modifica.php.
	 */
	public function selecionar() {
		global $db;
		$this->comAutenticacao();

		$opcao = isset($_GET['opcao']) ? (string)$_GET['opcao'] : '';
		if ($opcao !== 'cancela') {
			$opcao = 'modificar';
		}

		$ofertas = Mural::listarSimplesPorPeriodo(PERIODO_ATUAL);

		$smarty = new Smarty_estagio;
		$smarty->assign("opcao", $opcao);
		$smarty->assign("ofertas", $ofertas);
		$smarty->display("muralestagios-seleciona.tpl");
		exit;
	}

	/**
	 * Tira o aluno da lista "alunos que buscam estágio" do período atual,
	 * removendo todas as suas inscrições do período. Substitui
	 * mural-excluir_aluno.php. Restrito ao admin.
	 *
	 * O script antigo (sem login e com SQL montado por concatenação) excluía
	 * também o registro da tabela alunos, deixando órfãos os vínculos que
	 * apontam para ele (estagiarios.aluno_id, users.aluno_id) e apagando as
	 * inscrições de todos os períodos. Aqui só as inscrições do período atual
	 * saem; a exclusão do aluno em si continua no módulo alunos
	 * (alunos/excluir.php), que checa dependências antes de apagar.
	 */
	public function excluir_aluno() {
		global $db;
		$this->comAutenticacao();
		$this->exigirAdmin();

		$registro = isset($_REQUEST['registro']) ? (int)$_REQUEST['registro'] : 0;
		$aluno_id = isset($_REQUEST['aluno_id']) ? (int)$_REQUEST['aluno_id'] : 0;

		// Se veio apenas aluno_id ou apenas registro, resolve o outro a partir de Aluno
		if ($registro <= 0 && $aluno_id > 0) {
			$aluno = Aluno::find($aluno_id);
			if ($aluno !== null) {
				$registro = (int)$aluno->registro;
			}
		} elseif ($aluno_id <= 0 && $registro > 0) {
			$aluno = Aluno::firstWhere('registro', $registro);
			if ($aluno !== null) {
				$aluno_id = (int)$aluno->id;
			}
		}

		if ($registro <= 0 && $aluno_id <= 0) {
			die("Aluno inválido (registro $registro, id $aluno_id).");
		}

		$excluidas = Inscricao::excluirDoPeriodo($registro, PERIODO_ATUAL, $aluno_id);
		if ($excluidas === false) {
			error_log("Erro ao excluir inscricoes do aluno (registro $registro, aluno_id $aluno_id, periodo "
				. PERIODO_ATUAL . "): " . ADODB_Model::$db->ErrorMsg());
			die("Não foi possível excluir os registros da tabela inscricoes");
		}

		header("Location: inscritos.php");
		exit;
	}

	// ------------------------------------------------------------------
	// Auxiliares de higienização de campos do formulário
	// ------------------------------------------------------------------

	/**
	 * Campo de texto simples: trim; null quando ausente do POST.
	 */
	private function campoTexto($nome) {
		return isset($_POST[$nome]) ? trim((string)$_POST[$nome]) : null;
	}

	/**
	 * Campo cujo valor deve pertencer a uma lista fechada (ex.: convenio,
	 * final_de_semana, horario, forma_selecao). Fora da lista, usa $padrao.
	 */
	private function campoEmLista($nome, array $lista, $padrao) {
		$valor = isset($_POST[$nome]) ? (string)$_POST[$nome] : '';
		return in_array($valor, $lista, true) ? $valor : $padrao;
	}

	/**
	 * Monta 'aaaa-mm-dd' a partir dos três selects dia/mês/ano do formulário
	 * de inserção; null quando inválido ou ausente.
	 */
	private function dataDoTriplo($prefixo) {
		$d = isset($_POST["dia_$prefixo"]) ? (int)$_POST["dia_$prefixo"] : 0;
		$m = isset($_POST["mes_$prefixo"]) ? (int)$_POST["mes_$prefixo"] : 0;
		$a = isset($_POST["ano_$prefixo"]) ? (int)$_POST["ano_$prefixo"] : 0;
		if ($d < 1 || $m < 1 || $a < 1 || !checkdate($m, $d, $a)) return null;
		return sprintf("%04d-%02d-%02d", $a, $m, $d);
	}

}

}

?>
