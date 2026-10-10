<?php
/**
 * MuralestagiosController.php — Controlador da tabela mural_estagios.
 *
 * Reúne as ações relacionadas às ofertas de estágio do mural (tabela
 * mural_estagios). Cada ação substitui um script legado:
 *
 *   index()     <- ver-mural.php      (listagem do período atual, com login)
 *   inserir()   <- mural_inserir.php   (formulário + gravação, com login)
 *   ver_cada()  <- ver_cada.php        (ficha registro a registro, sem login)
 *   editar()    <- mural-modifica.php  (formulário de edição, sem login)
 *   atualizar() <- mural-atualiza.php  (gravação da edição, sem login)
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Mural (libphp/Mural.php), que grava com
 * parâmetros ligados — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/MuralestagiosController.php");
 *   $controller = new MuralestagiosController();
 *   $controller->index();
 *
 * ATENÇÃO: editar()/atualizar() NÃO exigem login — mantido por paridade com
 * os scripts antigos (mural-modifica.php/mural-atualiza.php também não
 * exigiam). Vale proteger esses endpoints no futuro.
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
		global $db, $sistema_autentica;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Inclui apenas setup.php (sem forçar login), como faziam os scripts
	 * antigos de ficha/edição, e carrega os models.
	 */
	private function semAutenticacao() {
		global $db;
		include_once(__DIR__ . "/../setup.php");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Lista as ofertas do período atual (index do mural).
	 * Substitui ver-mural.php.
	 */
	public function index() {
		global $db, $sistema_autentica;
		$this->semAutenticacao();

		// Nome do aluno recém-inscrito em seleção de estágio (vem por GET).
		$insere = isset($_GET['insere']) ? $_GET['insere'] : '';

		// Ordenação: chave validada por whitelist dentro do model.
		$ordem = isset($_GET['ordem']) ? $_GET['ordem'] : '';
		$instituicao = Mural::listarMuralPorPeriodo(PERIODO_ATUAL, $ordem);
		$totalVagas = Mural::totalVagasPorPeriodo(PERIODO_ATUAL);

		$stats = Mural::estatisticasInscritos(PERIODO_ATUAL);
		$novos = $stats['total'] - $stats['conhecidos'];
		$novo_novo = $novos + $stats['estagio_um'];
		$conhecidos_conhecidos = $stats['conhecidos'] - $stats['estagio_um'];

		$smarty = new Smarty_estagio;
		$smarty->assign("periodo_atual", PERIODO_ATUAL);
		$smarty->assign("sistema_autentica", $sistema_autentica);
		$smarty->assign("insere", $insere);
		$smarty->assign("instituicao", $instituicao);
		$smarty->assign("totalVagas", $totalVagas);
		$smarty->assign("totalAlunos", $stats['total']);
		$smarty->assign("alunos_novos", $novo_novo);
		$smarty->assign("alunosVelhos", $conhecidos_conhecidos);
		$smarty->display("file:" . RAIZ . "/mural/ver-mural.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de inserção e, quando confirmado, grava a oferta.
	 * Substitui mural_inserir.php.
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
				// Antes: redirect para mural_inserir.php?aviso=...; agora exibe
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
		$smarty->display("file:" . RAIZ . "/mural/mural_inserir.tpl");
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

		header("Location: ver_cada.php?mural_id=" . $mural->getKey());
		exit;
	}

	/**
	 * Ficha de uma oferta, com navegação registro a registro.
	 * Substitui ver_cada.php. Não força login: apenas lê o cookie para
	 * habilitar funções administrativas.
	 */
	public function ver_cada() {
		global $db;
		$this->semAutenticacao();

		$mural_id       = isset($_REQUEST['mural_id']) ? (int)$_REQUEST['mural_id'] : 0;
		$instituicao_id = isset($_REQUEST['instituicao_id']) ? (int)$_REQUEST['instituicao_id'] : 0;
		$indice         = isset($_REQUEST['indice']) ? $_REQUEST['indice'] : '';
		$botao          = isset($_REQUEST['botao']) ? $_REQUEST['botao'] : '';

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

		// Quando vem um id (novo mural_id ou o antigo id_instituicao, que
		// apontava para o id do mural), converto para a posição na listagem.
		if ($mural_id > 0) {
			$pos = Mural::posicaoDoRegistro($mural_id, PERIODO_ATUAL);
			if ($pos !== null) $indice = $pos;
		} elseif ($instituicao_id > 0) {
			$pos = Mural::posicaoDoRegistro($instituicao_id, PERIODO_ATUAL);
			if ($pos !== null) $indice = $pos;
		}

		$registro = ($num_linhas > 0) ? Mural::registroDaPosicao(PERIODO_ATUAL, $indice) : null;
		$instituicao = $registro ? array($registro) : array();
		$muralestagio_id = $registro ? $registro['muralestagio_id'] : 0;

		// Perfil do usuário logado (sem forçar login): define a visibilidade
		// do botão Inscrição.
		$sistema_autentica = 0;
		$usuario_role = '';
		if (!empty($_COOKIE['usuario'])) {
			$res_role = $db->Execute(
				"select role from users where email = ? and ativo = 1",
				array($_COOKIE['usuario'])
			);
			if ($res_role && $res_role->RecordCount() == 1) {
				$usuario_role = (string)$res_role->fields['role'];
			}
		}
		if (!empty($_COOKIE['usuario_nome'])) $sistema_autentica = 1;

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
		$smarty->display("file:" . RAIZ . "/mural/ver_cada.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição de uma oferta.
	 * Substitui mural-modifica.php. Precisa de autenticação .
	 */
	public function editar() {
		global $db;
		$this->comAutenticacao();

		$id = isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0;
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
		$smarty->assign("id_instituicao", $id);
		$smarty->assign("convenio", $mural->convenio);
		$smarty->assign("instituicao", $mural->instituicao);
		$smarty->assign("vagas", $mural->vagas);
		$smarty->assign("beneficios", $mural->beneficios);
		$smarty->assign("final_de_semana", $mural->final_de_semana);
		$smarty->assign("cargaHoraria", $mural->carga_horaria);
		$smarty->assign("requisitos", $mural->requisitos);
		$smarty->assign("id_area", 0);
		$smarty->assign("area", '');
		$smarty->assign("id_professor", 0);
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
		$smarty->display("file:" . RAIZ . "/mural/mural-modifica.tpl");
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
			$id = isset($_REQUEST['id_instituicao']) ? (int)$_REQUEST['id_instituicao'] : 0;
			header("Location: mural-modifica.php?id_instituicao=" . $id);
			exit;
		}

		$id = isset($_POST['mural_id']) ? (int)$_POST['mural_id'] : 0;
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

		header("Location: ver_cada.php?mural_id=" . $mural->getKey());
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
