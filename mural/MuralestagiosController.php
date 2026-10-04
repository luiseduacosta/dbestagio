<?php
/**
 * MuralestagiosController.php — Controlador da tabela mural_estagios.
 *
 * Reúne as funções relacionadas à tabela mural_estagios (oferta de estágios do
 * mural). O método inserir() contém a lógica que antes ficava no arquivo
 * mural_inserir.php: monta as listas de áreas/professores/instituições e exibe
 * o formulário de inserção; quando o formulário é confirmado, grava o registro
 * na tabela mural_estagio.
 *
 * Uso:
 *   require_once("MuralestagiosController.php");
 *   $controller = new MuralestagiosController();
 *   $controller->inserir();
 */
if (!class_exists('MuralestagiosController', false)) {

class MuralestagiosController {

	/**
	 * Exibe o formulário de inserção no mural e, quando confirmado, grava o
	 * registro na tabela mural_estagio.
	 *
	 * Conteúdo migrado de mural_inserir.php.
	 */
	public function inserir() {
		global $db;

		include_once(__DIR__ . "/../autentica.inc");

		$confirma   = isset($_POST['inserir']) ? $_POST['inserir'] : NULL;
		$aviso      = isset($_GET['aviso']) ? $_GET['aviso'] : NULL;

		$convenio        = isset($_POST['convenio']) ? $_POST['convenio'] : NULL;
		$instituicao_id  = isset($_POST['instituicao_id']) ? $_POST['instituicao_id'] : NULL;
		$vagas           = isset($_POST['vagas']) ? $_POST['vagas'] : NULL;
		$beneficios      = isset($_POST['beneficios']) ? $_POST['beneficios'] : NULL;
		$final_de_semana = isset($_POST['final_de_semana']) ? $_POST['final_de_semana'] : NULL;
		$carga_horaria   = isset($_POST['carga_horaria']) ? $_POST['carga_horaria'] : NULL;
		$requisitos      = isset($_POST['requisitos']) ? $_POST['requisitos'] : NULL;
		$area_id         = isset($_POST['area_id']) ? $_POST['area_id'] : NULL;
		$professor_id    = isset($_POST['professor_id']) ? $_POST['professor_id'] : NULL;
		$horario         = isset($_POST['horario']) ? $_POST['horario'] : NULL;
		$dia_inscricao   = isset($_POST['dia_inscricao']) ? $_POST['dia_inscricao'] : NULL;
		$mes_inscricao   = isset($_POST['mes_inscricao']) ? $_POST['mes_inscricao'] : NULL;
		$ano_inscricao   = isset($_POST['ano_inscricao']) ? $_POST['ano_inscricao'] : NULL;
		$dia_selecao     = isset($_POST['dia_selecao']) ? $_POST['dia_selecao'] : NULL;
		$mes_selecao     = isset($_POST['mes_selecao']) ? $_POST['mes_selecao'] : NULL;
		$ano_selecao     = isset($_POST['ano_selecao']) ? $_POST['ano_selecao'] : NULL;
		$horario_selecao = isset($_POST['horario_selecao']) ? $_POST['horario_selecao'] : NULL;
		$local_selecao   = isset($_POST['local_selecao']) ? $_POST['local_selecao'] : NULL;
		$forma_selecao   = isset($_POST['forma_selecao']) ? $_POST['forma_selecao'] : NULL;
		$contato         = isset($_POST['contato']) ? $_POST['contato'] : NULL;
		$outras          = isset($_POST['outras']) ? $_POST['outras'] : NULL;
		$periodo         = isset($_POST['periodo']) ? $_POST['periodo'] : NULL;
		$email           = isset($_POST['email']) ? $_POST['email'] : NULL;

		// Para salvar tenho que utilizar o formato aaaa/mm/dd/
		$data_selecao = $ano_selecao . "-" . $mes_selecao . "-" . $dia_selecao;
		$data_inscricao = $ano_inscricao . "-" . $mes_inscricao . "-" . $dia_inscricao;

		if ($confirma == "Confirma") {
			if ($convenio === "1") {
			    $sql = "select instituicao from instituicoes where id=$instituicao_id";
			    $resultado = $db->Execute($sql);
			    $instituicao = $resultado->fields['instituicao'];

			    $sql = "insert into mural_estagios(instituicao_id," .
				"instituicao, " .
				"convenio, ".
				"vagas," .
				"beneficios," .
				"final_de_semana," .
				"carga_horaria, " .
				"requisitos, " .
				"area_id," .
				"professor_id," .
				"horario," .
				"dataInscricao," .
				"data_selecao," .
				"horario_selecao, ".
				"local_selecao," .
				"forma_selecao," .
				"contato," .
				"outras," .
				"periodo, " .
				"email) " .
				"values('$instituicao_id', ".
				    "'$instituicao', ".
				    "'$convenio', ".
				    "'$vagas', ".
				    "'$beneficios', ".
				    "'$final_de_semana', ".
				    "'$carga_horaria', ".
				    "'$requisitos', ".
				    "'$area_id', ".
				    "'$professor_id', ".
				    "'$horario', ".
				    "'$data_inscricao', ".
				    "'$data_selecao', ".
				    "'$horario_selecao', ".
				    "'$local_selecao', ".
				    "'$forma_selecao', ".
				    "'$contato', ".
				    "'$outras', ".
				    "'$periodo', ".
				    "'$email')";

			        // echo $sql . "<br>";
			        $resultado = $db->Execute($sql);
			        if ($resultado === false) die ("Não foi possível inserir o registro na tabela mural_estagios");
			        $confirma = "";
			        $mural_id = $db->Insert_ID();
			        // echo $mural_id . "<br>";
			        // die("Ver cada");
				echo "<meta HTTP-EQUIV='refresh' content='0,URL=ver_cada.php?mural_id=$mural_id'>";
				// header("Location:ver_cada.php?id=$mural_id");
				exit;
			} elseif ($convenio === "0") {
			echo "<meta HTTP-EQUIV='refresh' content='0,URL=mural_inserir.php?aviso=" . rawurlencode("Instituição NÃO conveniada!") . "'>";
			exit;
			}
		}

		$sql = "select id, area from areas order by area";
		$resultado = $db->Execute($sql);
		if ($resultado === false) die ("Não foi possível consultar a tabela areas");

		$i = 0;
		$area_id[$i] = 0;
		$areas[$i] = "Seleciona área";
		$i++;
		while (!$resultado->EOF) {
			  $area_id[$i] = $resultado->fields["id"];
			  $areas[$i]    = $resultado->fields["area"];
			  $i++;
			  $resultado->MoveNext();
		}

		$sqlProfessores = "select id, nome from professores order by nome";
		$resultadoProfessores = $db->Execute($sqlProfessores);
		if ($resultadoProfessores === false) die ("Não foi possível consultar a tabela professores");
		$i = 0;
		$professor_id[$i] = 0;
		$professores[$i]    = "Seleciona professor";
		$i++;
		while (!$resultadoProfessores->EOF) {
			  $professor_id[$i] = $resultadoProfessores->fields["id"];
			  $professores[$i]    = $resultadoProfessores->fields["nome"];
			  $i++;
			  $resultadoProfessores->MoveNext();
		}

		$sql_instituicoes = "select id, instituicao from instituicoes order by instituicao";
		// echo $sql_instituicoes . "<br>";
		$resultado_instituicoes = $db->Execute($sql_instituicoes);
		if ($resultado_instituicoes === false) die ("Não foi possível consultar a tabela instituicoes");
		$i = 1;
		while (!$resultado_instituicoes->EOF) {
		    $instituicoes[$i]['id'] = $resultado_instituicoes->fields['id'];
		    $instituicoes[$i]['instituicao'] = $resultado_instituicoes->fields['instituicao'];
		    $i++;
		    $resultado_instituicoes->MoveNext();
		}

		$periodo_atual = PERIODO_ATUAL;
		// echo "Periodo " . $periodo_atual;

		$smarty = new Smarty_estagio;

		$smarty->assign("aviso",$aviso);
		$smarty->assign("periodo",$periodo_atual);
		$smarty->assign("periodo_atual",$periodo_atual);
		$smarty->assign("professor_id",$professor_id);
		$smarty->assign("professores",$professores);
		$smarty->assign("area_id",$area_id);
		$smarty->assign("areas",$areas);
		$smarty->assign("instituicoes",$instituicoes);
		$smarty->display("file:". RAIZ . "/mural/mural_inserir.tpl");

		exit;
	}
}

}

?>
