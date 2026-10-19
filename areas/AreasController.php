<?php
/**
 * AreasController.php — Controlador da tabela areas.
 *
 * Reúne as ações relacionadas ao cadastro de áreas de estágio (tabela areas).
 * Cada método substitui um script legado:
 *
 *   inserir()     <- inserir/inserir.php + inserir/form_inserir.php
 *   atualizar()   <- atualizar/atualiza.php + atualizar/modifica.php
 *   ver_cada()    <- exibir/ver_cada.php
 *   listar()      <- exibir/listar.php
 *   excluir()     <- cancelar/cancela.php
 *   seleciona()   <- seleciona.php
 *   instituicoes()<- exibir/instituicoes.php
 *
 * O controlador cuida apenas de HTTP (parâmetros, higienização, includes de
 * autenticação, assigns do Smarty e redirecionamentos). Toda a lógica de
 * consulta e gravação fica no model Area (libphp/Area.php), que grava com
 * parâmetros ligados via ADODB_Model — sem interpolação de strings.
 *
 * Uso (front-controllers finos):
 *   require_once(__DIR__ . "/AreasController.php");
 *   $controller = new AreasController();
 *   $controller->listar();
 *
 * Permissões (replicando o comportamento dos scripts legados):
 *  - inserir / atualizar / ver_cada / excluir / seleciona: exigem login
 *    (autentica.inc)
 *  - listar / instituicoes: público (apenas setup.php)
 */
if (!class_exists('AreasController', false)) {

class AreasController {

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
	 * Usado pelas ações públicas (listar e instituicoes), como nos scripts
	 * legados.
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
	 * Valida o nome de uma área (obrigatório, no máximo 90 caracteres).
	 * Devolve a string tratada; morre com mensagem quando inválida.
	 */
	private function validaNomeArea($area) {
		if ($area === '') {
			die("O campo Área é obrigatório.");
		}
		if (mb_strlen($area, 'UTF-8') > 90) {
			die("O nome da área excede 90 caracteres.");
		}
		return $area;
	}

	// ------------------------------------------------------------------
	// Ações CRUD
	// ------------------------------------------------------------------

	/**
	 * Exibe o formulário de inserção (GET) e, quando há POST, grava a área.
	 * Substitui areas/inserir/inserir.php + areas/inserir/form_inserir.php.
	 */
	public function inserir() {
		$this->comAutenticacao();

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$area = $this->validaNomeArea($this->campoTexto('area'));

			// Evita duplicar a mesma área (case-insensitive).
			$existe = Area::$db->GetOne(
				"SELECT COUNT(*) FROM areas WHERE LOWER(area) = LOWER(?)",
				array($area)
			);
			if ($existe > 0) {
				die("Essa área já está cadastrada.");
			}

			$nova = new Area();
			$nova->area = $area;
			if (!$nova->save()) {
				error_log("Erro ao inserir area: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível inserir o registro na tabela areas.");
			}

			echo "<p>Registro inserido</p>";
			echo "<p><a href='listar.php'>Voltar para a listagem de áreas</a></p>";
			exit;
		}

		// GET: renderiza o formulário de inclusão.
		$smarty = new Smarty_estagio;
		$smarty->display("areas-inserir.tpl");
		exit;
	}

	/**
	 * Exibe o formulário de edição (GET) e, quando há POST, grava as
	 * alterações. Substitui areas/atualizar/modifica.php +
	 * areas/atualizar/atualiza.php.
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$area_id = isset($_REQUEST['area_id']) ? (int)$_REQUEST['area_id'] : 0;

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$area = $this->validaNomeArea($this->campoTexto('area'));

			$area_obj = Area::find($area_id);
			if ($area_obj === null) {
				die("Área não encontrada (id $area_id).");
			}

			$area_obj->area = $area;
			if (!$area_obj->save()) {
				error_log("Erro ao atualizar area: " . ADODB_Model::$db->ErrorMsg());
				die("Não foi possível atualizar a tabela areas. Tente novamente.");
			}

			header("Location: listar.php");
			exit;
		}

		// GET: renderiza o formulário de edição (pré-preenchido).
		$area_obj = Area::find($area_id);
		$area = $area_obj ? $area_obj->area : '';

		$smarty = new Smarty_estagio;
		$smarty->assign("area_id", $area_id);
		$smarty->assign("area", $area);
		$smarty->display("areas-atualizar.tpl");
		exit;
	}

	/**
	 * Ficha de uma área, com navegação registro a registro (primeiro,
	 * anterior, próximo, último, -10, +10) na ordem alfabética.
	 * Substitui areas/exibir/ver_cada.php.
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$area_id = isset($_REQUEST['area_id']) ? (int)$_REQUEST['area_id'] : 0;

		if ($area_id <= 0) {
			die("Área não informada.");
		}

		$area = Area::find($area_id);
		if ($area === null) {
			die("Área não encontrada (id $area_id).");
		}

		// Navegação entre áreas na ordem alfabética.
		$primeiro_id = null;
		$ultimo_id   = null;
		$anterior_id = null;
		$proximo_id  = null;
		$menos_10_id = null;
		$mais_10_id  = null;
		$areas_nav = Area::all("ORDER BY area, id");
		if (!empty($areas_nav)) {
			$ids_nav = array();
			foreach ($areas_nav as $area) {
				$ids_nav[] = (int)$area->id;
			}
			$total_nav = count($ids_nav);
			$pos_nav   = array_search($area_id, $ids_nav, true);
			if ($pos_nav !== false && $total_nav > 1) {
				$primeiro_id = $ids_nav[0];
				$ultimo_id   = $ids_nav[$total_nav - 1];
				$anterior_id = $ids_nav[($pos_nav - 1 + $total_nav) % $total_nav];
				$proximo_id  = $ids_nav[($pos_nav + 1) % $total_nav];
				$menos_10_id = $ids_nav[($pos_nav - 10 + $total_nav) % $total_nav];
				$mais_10_id  = $ids_nav[($pos_nav + 10) % $total_nav];
			}
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("area_id",          $area_id);
		$smarty->assign("primeiro_id",      $primeiro_id);
		$smarty->assign("ultimo_id",        $ultimo_id);
		$smarty->assign("anterior_id",      $anterior_id);
		$smarty->assign("proximo_id",       $proximo_id);
		$smarty->assign("menos_10_id",      $menos_10_id);
		$smarty->assign("mais_10_id",       $mais_10_id);
		$smarty->assign("area",             $area->toArray());
		$smarty->assign("num_instituicoes", $area->countInstituicoes());
		$smarty->assign("instituicoes",     $area->instituicoes());
		$smarty->assign("pagina",           $_SERVER['PHP_SELF']);
		$smarty->display("areas-ver_cada.tpl");

		exit;
	}

	/**
	 * Lista as áreas (id + nome) para o índice do módulo.
	 * Substitui areas/exibir/listar.php.
	 * PÚBLICO: não exige login.
	 */
	public function listar() {
		$this->comAutenticacao();

		$ordem = isset($_REQUEST['ordem']) ? $_REQUEST['ordem'] : 'areas.area';

		// Whitelist segura das colunas de ordenação (usadas pelos links da template).
		$ordens_validas = array(
			'areas.area'               => 'areas.area',
			'professores.nome'         => 'professores.nome',
			'min(estagiarios.periodo)' => 'min(estagiarios.periodo)',
			'max(estagiarios.periodo)' => 'max(estagiarios.periodo)',
		);
		if (!isset($ordens_validas[$ordem])) {
			$ordem = 'areas.area';
		}

		$sql = "SELECT areas.id AS area_id, areas.area, professores.nome,
		               professores.id AS id_professor,
		               MIN(estagiarios.periodo) AS min_periodo,
		               MAX(estagiarios.periodo) AS max_periodo
		        FROM estagiarios
		        JOIN instituicoes ON estagiarios.instituicao_id = instituicoes.id
		        LEFT JOIN areas ON instituicoes.area_id = areas.id
		        JOIN professores ON estagiarios.professor_id = professores.id
		        GROUP BY instituicoes.area_id, estagiarios.professor_id, areas.area, professores.nome, professores.id
		        ORDER BY " . $ordens_validas[$ordem];

		$res_professores = Area::query($sql);
		if (empty($res_professores)) {
			error_log("Erro ao consultar areas: " . Area::$db->ErrorMsg());
			die("Não foi possível consultar as tabelas.");
		}

		$matriz = array();
		$i = 0;
		foreach ($res_professores as $row) {
			$matriz[$i]['area_id']       = $row->area_id;
			$matriz[$i]['area']          = $row->area;
			$matriz[$i]['id_professor']  = $row->id_professor;
			$matriz[$i]['nome']          = $row->nome;
			$matriz[$i]['min_periodo']   = $row->min_periodo;
			$matriz[$i]['max_periodo']   = $row->max_periodo;
			$i++;
		}

		$areas = Area::listarAllAreas();

		$smarty = new Smarty_estagio;
		$smarty->assign("ordem", $ordem);
		$smarty->assign("areas", $areas);
		$smarty->assign("matriz", $matriz);
		$smarty->display("areas-listar.tpl");

		exit;
	}

	/**
	 * Exclui uma área, desde que não existam instituições vinculadas.
	 * Substitui areas/cancelar/cancela.php.
	 */
	public function excluir() {
		$this->comAutenticacao();

		$area_id = isset($_REQUEST['area_id']) ? (int)$_REQUEST['area_id'] : 0;

		$area_obj = Area::find($area_id);
		if ($area_obj === null) {
			echo "Área não encontrada (id $area_id).";
			exit;
		}

		// Bloqueia a exclusão caso existam instituições vinculadas.
		$quantidade = $area_obj->countInstituicoes();
		if ($quantidade > 0) {
			echo "<p>Operação abortada porque $quantidade instituições dependem desta área.</p>";
			echo "<p><a href='instituicoes.php?area_id=$area_id'>Ver as instituições desta área</a></p>";
			exit;
		}

		if (!$area_obj->delete()) {
			error_log("Erro ao excluir area: " . ADODB_Model::$db->ErrorMsg());
			echo "Não foi possível excluir o registro da tabela areas.";
			exit;
		}

		echo "Registro excluído<br>";
		echo "<p><a href='listar.php'>Voltar para a listagem de áreas</a></p>";

		exit;
	}

	/**
	 * Seletor de áreas para os formulários de modificar/excluir.
	 * Substitui areas/seleciona.php.
	 *
	 * Observação: o script legado chamava Area::seleciona(), método que não
	 * existia no model; aqui usa-se Area::listarAllAreas(), que devolve a
	 * mesma estrutura (id + area) em ordem alfabética.
	 */
	public function seleciona() {
		$this->comAutenticacao();

		$opcao = isset($_GET['opcao']) ? $_GET['opcao'] : '';

		// Lista completa de áreas (ordem alfabética).
		$areas_objs = Area::listarAllAreas();

		$id_areas = array();
		$areas    = array();
		foreach ($areas_objs as $a) {
			$id_areas[] = $a['id'];
			$areas[]    = $a['area'];
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("opcao", $opcao);
		$smarty->assign("id_areas", $id_areas);
		$smarty->assign("areas", $areas);
		$smarty->display("areas-seleciona.tpl");

		exit;
	}

	/**
	 * Lista as instituições de uma área, com ordenação segura em memória.
	 * Substitui areas/exibir/instituicoes.php.
	 * PÚBLICO: não exige login.
	 */
	public function instituicoes() {
		$this->comAutenticacao();

		$area_id = isset($_GET['area_id']) ? (int)$_GET['area_id'] : 0;
		$ordem   = isset($_GET['ordem']) ? $_GET['ordem'] : 'instituicao';

		// Whitelist de colunas de ordenação seguras.
		$ordens_validas = array(
			'instituicao' => 'instituicao',
			'turma'       => 'turma',
			'endereco'    => 'endereco',
			'telefone'    => 'telefone',
		);
		if (!isset($ordens_validas[$ordem])) {
			$ordem = 'instituicao';
		}

		$area_obj  = Area::find($area_id);
		$nome_area = $area_obj ? $area_obj->area : '';
		$matriz    = $area_obj ? $area_obj->instituicoes() : array();

		// Aplica a ordenação selecionada (em memória, segura).
		if ($matriz) {
			usort($matriz, function ($a, $b) use ($ordem) {
				$va = isset($a[$ordem]) ? (string)$a[$ordem] : '';
				$vb = isset($b[$ordem]) ? (string)$b[$ordem] : '';
				return strnatcasecmp($va, $vb);
			});
			$matriz = array_values($matriz);
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("area_id", $area_id);
		$smarty->assign("nome_area", $nome_area);
		$smarty->assign("instituicoes", $matriz);
		$smarty->display("areas-instituicoes.tpl");

		exit;
	}

}

}

?>