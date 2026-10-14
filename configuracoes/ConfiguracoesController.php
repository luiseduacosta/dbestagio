<?php
/**
 * ConfiguracoesController.php — Controlador da tabela configuracoes.
 *
 * Esta tabela possui apenas um único registro global (id = 1), responsável
 * por guardar parâmetros de funcionamento do sistema (período do mural, datas
 * do curso, termo de compromisso, etc.).
 *
 * Por ser uma tabela de registro único, o controlador expõe as ações:
 *   - ver_cada()   -> Visualização dos parâmetros configurados (antigo configuracao.php)
 *   - atualizar()  -> Edição dos parâmetros (antigo configuracao_edita.php)
 */
if (!class_exists('ConfiguracoesController', false)) {

class ConfiguracoesController {

	/**
	 * Inclui autentica.inc e models.php, garantindo login e permissões.
	 */
	private function comAutenticacao() {
		global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
		include_once(__DIR__ . "/../autentica.inc");
		require_once(__DIR__ . "/../libphp/models.php");
	}

	/**
	 * Visualiza as configurações do sistema.
	 * Exibe os rótulos amigáveis e formata datas para exibição (dd-mm-aaaa).
	 * Template: configuracoes-ver_cada.tpl
	 */
	public function ver_cada() {
		$this->comAutenticacao();

		$cfg  = Configuracao::obter();
		$cols = Configuracao::campos();

		// Campos de data recebem o formato dd-mm-aaaa para exibição clara.
		$datas = array(
			'curso_abertura_inscricoes',
			'curso_encerramento_inscricoes',
			'termo_compromisso_inicio',
			'termo_compromisso_final',
		);

		$valores = array();
		foreach ($cols as $col => $rotulo) {
			$valor = $cfg->$col;
			if (in_array($col, $datas, true) && $valor !== '' && $valor !== null) {
				$ts = strtotime($valor);
				$valor = ($ts === false) ? $valor : date('d-m-Y', $ts);
			}
			$valores[$col] = array('rotulo' => $rotulo, 'valor' => $valor);
		}

		$smarty = new Smarty_estagio;
		$smarty->assign("valores", $valores);
		$smarty->display("configuracoes-ver_cada.tpl");
		exit;
	}

	/**
	 * Formulário de edição (GET) e gravação das configurações (POST).
	 * Template: configuracoes-atualizar.tpl
	 */
	public function atualizar() {
		$this->comAutenticacao();

		$cfg            = Configuracao::obter();
		$colunas_editar = array_keys(Configuracao::campos());
		$campos_rotulos = Configuracao::campos();

		$campos_data = array(
			'curso_abertura_inscricoes',
			'curso_encerramento_inscricoes',
			'termo_compromisso_inicio',
			'termo_compromisso_final',
		);

		if ($_SERVER['REQUEST_METHOD'] === 'POST') {
			$erros = array();

			foreach ($colunas_editar as $col) {
				$valor = isset($_POST[$col]) ? trim((string)$_POST[$col]) : '';

				// Validação de formato de datas
				if (in_array($col, $campos_data, true)) {
					if ($valor !== '' && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
						$erros[] = $campos_rotulos[$col] . " deve estar no formato AAAA-MM-DD.";
					}
				} elseif ($col === 'curso_turma_atual') {
					if ($valor !== '' && !ctype_digit($valor)) {
						$erros[] = $campos_rotulos[$col] . " deve ser um número.";
					}
				}

				$cfg->$col = ($valor === '') ? null : $valor;
			}

			if (empty($erros)) {
				if (!$cfg->save()) {
					error_log("Erro ao salvar configuração: " . ADODB_Model::$db->ErrorMsg());
					die("Não foi possível salvar a configuração. Tente novamente.");
				}
				header("Location: ver_cada.php");
				exit;
			}

			// Se houver erros, reexibe o formulário com as mensagens e valores preenchidos
			$smarty = new Smarty_estagio;
			$smarty->assign("v", $cfg->toArray(true));
			$smarty->assign("erros", $erros);
			$smarty->display("configuracoes-atualizar.tpl");
			exit;
		}

		// GET: exibe formulário preenchido
		$dados = $cfg->toArray(true);
		$smarty = new Smarty_estagio;
		$smarty->assign("v", $dados);
		$smarty->assign("erros", array());
		$smarty->display("configuracoes-atualizar.tpl");
		exit;
	}

}

}

?>
