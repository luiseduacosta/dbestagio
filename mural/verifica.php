<?php
/**
 * verifica.php — Identificação pública do candidato no mural (pelo DRE).
 *
 * Recebe o registro (DRE) e a oferta escolhida e decide o próximo passo:
 *   - aluno ainda não cadastrado -> cadastro.php (cadastro de aluno novo);
 *   - aluno cadastrado           -> mural-alunos_modifica.php;
 *   - cursou estágio I ou III no último período -> exibe o aviso e a inscrição
 *     só segue com a confirmação do candidato.
 *
 * As consultas passam pelos modelos (libphp/Aluno.php), sem SQL cru. O
 * histórico é buscado pelo registro: há linhas de estagiarios com aluno_id
 * nulo, que a busca por aluno_id perderia.
 */

include_once(__DIR__ . "/../setup.php");
require_once(__DIR__ . "/../libphp/models.php");

$registro        = isset($_REQUEST['registro']) ? trim((string)$_REQUEST['registro']) : '';
$muralestagio_id = isset($_REQUEST['muralestagio_id']) ? (int)$_REQUEST['muralestagio_id'] : 0;

if (!ctype_digit($registro)) {
	die("Digite somente números ou tente com outro navegador: <a href=\"http://br.mozdev.org\">Firefox</a>");
}

// Aluno já cadastrado? Se não, é candidato novo: segue para o cadastro.
$aluno = Aluno::porRegistro($registro);
if ($aluno === null) {
	echo "<meta http-equiv='refresh' content='1; URL=cadastro.php?registro=$registro&muralestagio_id=$muralestagio_id'>";
	die;
}

$aluno_id = (int)$aluno['id'];

// Estágios já cursados (do mais antigo para o mais recente).
$historico = Aluno::historicoEstagiosPorRegistro($registro);
if (count($historico) === 0) {
	die("Error: Aluno estagiario sem estágio? Informar para <a href='mailto:estagio@ess.ufrj.br?Subject=Error: estagiario (DRE: $registro) sem estagio '>estagio@ess.ufrj.br</a>");
}

// Estágio mais recente: decide sobre o aviso do estágio I/III no período anterior.
$ultimo = end($historico);
$nivel_ultimo   = $ultimo['nivel'];
$periodo_ultimo = $ultimo['periodo'];

if (($nivel_ultimo == 1 || $nivel_ultimo == 3) && $periodo_ultimo != PERIODO_ATUAL) {
	echo "<h2>Atenção: aluno que cursou estágio I ou III no período anterior</h2><p style='text-align:justify; background-color:#e7e1ae'>Prezada(o) aluna(o):<br><br>Clicando no botão embaixo sua solicitação será processada, ainda que, pelas normas vigentes, alunos que cursaram estágio I ou III no último período devem requerer autorização especial na Coordenação de Estágio e Extensão para serem habilitados a trocar de estágio antes do cumprimento de dois períodos consecutivos numa mesma instituição.<br><br>Em caso de dúvida entre em contacto através do e-mail <a href='mailto:estagio@ess.ufrj.br'>estagio@ess.ufrj.br</a></p>";
	echo "
	<form action='mural-alunos_modifica.php' method='post'>
	<input type='submit' name='submit' value='Confirmar inscrição'>
	<input type='hidden' name='registro' value='$registro'>
	<input type='hidden' name='aluno_id' value='$aluno_id'>
	<input type='hidden' name='aluno' value='1'>
	<input type='hidden' name='muralestagio_id' value='$muralestagio_id'>
	</form>
		";
	die;
}

echo "<meta HTTP-EQUIV='refresh' CONTENT='1;URL=mural-alunos_modifica.php?aluno_id=$aluno_id&registro=$registro&muralestagio_id=$muralestagio_id&aluno=1'>";
die;

?>
