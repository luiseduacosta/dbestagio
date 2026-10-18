<?php

$quantidade     = $_POST['quantidade'];
// Dados do supervisor
$nome           = $_POST['nome'];
$cpf		= $_POST['cpf'];
$endereco       = $_POST['endereco'];
$bairro         = $_POST['bairro'];
$municipio      = $_POST['municipio'];
$cep            = $_POST['cep'];
$codigo_tel 	= $_POST['codigo_tel'];
$telefone       = $_POST['telefone'];
$codigo_cel 	= $_POST['codigo_cel'];
$celular        = $_POST['celular'];
$email          = strtolower($_POST['email']);
$escola         = $_POST['escola'];
$ano_formatura  = $_POST['ano_formatura'];
$cress          = $_POST['cress'];
$regiao	        = $_POST['regiao'];
$outros_estudos = $_POST['outros_estudos'];
$area_curso     = $_POST['area_curso'];
$ano_curso      = $_POST['ano_curso'];
// Dados da instituicao
$id_estagio     = $_POST['num_instituicao'];
$cargo          = $_POST['cargo'];
$instituicao    = $_POST['instituicaoNova'];
$inst_endereco  = $_POST['instituicao_endereco'];
$inst_bairro    = $_POST['instituicao_bairro'];
$inst_municipio = $_POST['instituicao_municipio'];
$inst_cep       = $_POST['instituicao_cep'];
$inst_telefone  = $_POST['instituicao_telefone'];
$inst_fax       = $_POST['instituicao_fax'];
$beneficio      = $_POST['instituicao_beneficio'];
$fim_de_semana  = $_POST['fim_de_semana'];
// echo  "Id instituicao " . $id_instituicao . "<br>";

if (empty($nome)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo nome</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($cpf)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo CPF</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($endereco)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo endereco</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($bairro)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o bairro da sua resid&ecirc;ncia</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($municipio)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo o munic&iacute;pio da sua resid&ecirc;ncia</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($cep)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo CEP da sua resid&ecirc;ncia</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($email)) {
	echo "<p>Favor digite um endere&ccedil;o electrónico para nossa comunica&ccedil;&atilde;o</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($escola)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo com o a Escola na que se formou</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($ano_formatura)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o campo com o ano da sua formatura</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($cress)) {
	echo "<p>&Eacute; obrigat&oacute;rio preencher o n&uacute;mero de registro no CRESS</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if (empty($instituicao)) {
	echo "<p>&Eacute; obrigat&oacute;rio informar a institui&ccdil;&atilde;o na qual trabalha</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

// include_once("../db.inc");
include_once("../setup.php");
$turma = (int)TURMA;

// Sem turma configurada nao ha onde gravar a inscricao. Melhor recusar aqui do
// que inserir curso_turma=0 e ainda renumerar num_inscricao de uma turma que
// nao existe -- os dados ficariam gravados sem nenhum aviso visivel.
if ($turma <= 0) {
    error_log("inscricao_curso.php: configuracoes.curso_turma_atual sem valor; inscricao recusada.");
    echo "<h1>Inscri&ccedil;&otilde;es encerradas</h1>";
    echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
    exit;
}

// Para nao repetir uma mesma inscricao - poderia ser tambem com o cpf -
$sql_verifica = "select id from curso_inscricao_supervisor where cpf=? and curso_turma=?";
// echo $sql_verifica . "<br>";
$res_verifica = $db->Execute($sql_verifica, array($cpf, $turma));
$id_supervisor = $res_verifica->fields['id'];
if ($id_supervisor) {
    echo "<h1>Inscri&ccedil;&atilde;o ja foi realizada</h1>";
    echo "<meta HTTP-EQUIV='refresh' CONTENT='2; URL=ver_cada_supervisor.php?id_supervisor=$id_supervisor'>";
    exit;
}

$sql  = "select count(*) as quantidade ";
$sql .= " from curso_inscricao_supervisor ";
$sql .= " where curso_turma=?";
$res_sql = $db->Execute($sql, array($turma));
if ($res_sql === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
$quantidade = $res_sql->fields['quantidade'];

// Renumero o campo num_inscricao da tabela de supervisores por ordem de inscricao
$sql = "select id from curso_inscricao_supervisor where curso_turma = ? order by id";
$res_sql_inscricao = $db->Execute($sql, array($turma));
if ($res_sql_inscricao === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");

$i = 1;
while (!$res_sql_inscricao->EOF) {
    $id = $res_sql_inscricao->fields['id'];
    $sql_atualiza = "update curso_inscricao_supervisor set num_inscricao=? where id=?";
    $res_atualiza = $db->Execute($sql_atualiza, array($i, $id));
    if($res_atualiza === false) die ("Nao foi possivel atualizar a tabela curso_inscricao_supervisor");
    $res_sql_inscricao->MoveNext();
    $i++;
}

// Aumento em uma unidade o numero de inscritos
$quantidade++;

// Verifico se o Cress ja esta cadastrado
$sql_cress = "select id from supervisores where cress=?";
$res_cress = $db->Execute($sql_cress, array($cress));
if ($res_cress == false) die ("Nao foi possivel consultar a tabela supervisores");
$supervisores_cress_id = $res_cress->fields['id'];
// Se esta cadastrado aproveito para atualizar a tabela supervisores
if ($supervisores_cress_id) {
	// echo "Supervisor conhecido: atualizando";
	$sql  = "update supervisores set nome=?, cpf=?, endereco=?, municipio=?, bairro=?, cep=?, ";
	$sql .= " codigo_tel=?, telefone=?, codigo_cel=?, celular=?, email=?, escola=?, ";
	$sql .= " ano_formatura=?, outros_estudos=?, area_curso=?, ano_curso=? where id=?";
	// echo "Atualiza supervisor " . $sql . "<br>";
	$res_atualiza_supervisores = $db->Execute($sql, array(
		$nome, $cpf, $endereco, $municipio, $bairro, $cep,
		$codigo_tel, $telefone, $codigo_cel, $celular, $email, $escola,
		$ano_formatura, $outros_estudos, $area_curso, $ano_curso,
		$supervisores_cress_id
	));
	if ($res_atualiza_supervisores === false) die ("Nao foi possivel atualizar o registro na tabela supervisores");
}

// Insero o supervisor
$sql_supervisor  =  "insert into curso_inscricao_supervisor ";
$sql_supervisor .= "(nome, cpf, endereco, bairro, municipio, cep, codigo_tel, telefone, codigo_cel, celular, email, escola, ano_formatura, cress, regiao, outros_estudos, area_curso, ano_curso, cargo, num_inscricao, curso_turma) ";
$sql_supervisor .= "values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
// echo "Insere supervisor " . $sql_supervisor . "<br>";

$res_supervisor = $db->Execute($sql_supervisor, array(
	$nome, $cpf, $endereco, $bairro, $municipio, $cep, $codigo_tel,
	$telefone, $codigo_cel, $celular, $email, $escola, $ano_formatura,
	$cress, $regiao, $outros_estudos, $area_curso, $ano_curso, $cargo,
	$quantidade, $turma
));
if ($res_supervisor === false) die ("Nao foi possivel inserir o registro na tabela curso_inscricao_supervisor");

// Capturo o id do registro inserido
$id_supervisor = $db->Insert_ID();

// Instituicao ainda nao conhecida como campo de estagio: inserir
if (empty($id_estagio)) {
	$sql_instituicao  = "insert into curso_inscricao_instituicao";
	$sql_instituicao .= "(instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana) ";
	$sql_instituicao .= "values(?,?,?,?,?,?,?,?,?)";
	// echo "Insere instituicao nova no curso " . $sql_instituicao . "<br>";

	$res_institucao = $db->Execute($sql_instituicao, array(
		$instituicao, $inst_endereco, $inst_bairro, $inst_municipio, $inst_cep,
		$inst_telefone, $inst_fax, $beneficio, $fim_de_semana
	));
	if($res_instituicao === false) die ("Nao foi possivel inserir o registro na tabela curso_incricao_instituicao");

	// Capturo o id do registro inserido
	$id_instituicao = $db->Insert_ID();
// Instituicao ja conhecida como campo de estagio: inserir no curso e atualizar estagio
} else {
	$sql_instituicao  = "insert into curso_inscricao_instituicao";
	$sql_instituicao .= "(instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana, id_estagio) ";
	$sql_instituicao .= "values(?,?,?,?,?,?,?,?,?,?)";
	// echo "Insere instituicao  conhecida no curso " . $sql_instituicao . "<br>";
	$res_institucao = $db->Execute($sql_instituicao, array(
		$instituicao, $inst_endereco, $inst_bairro, $inst_municipio, $inst_cep,
		$inst_telefone, $inst_fax, $beneficio, $fim_de_semana, $id_estagio
	));
	if ($res_instituicao === false) die ("Nao foi possivel inserir o registro na tabela curso_incricao_instituicao");
	$id_instituicao = $db->Insert_ID();

	// Atualiza instituicao do curso que eh campo de estagio
	$sql = "select id from curso_inscricao_instituicao where id_estagio=?";
	$res = $db->Execute($sql, array($id_estagio));
	if ($res === false) die ("Nao foi possivel consultar a tabela curso_incricao_instituicao");
	$quantidade = $res->RecordCount();
	if ($quantidade > 0) {
		// N.B.: o original lia '$ints_fax' (typo de $inst_fax), variavel que nunca
		// existiu e portanto interpolava como ''. Mantido o comportamento exato
		// (fax sempre gravado vazio) para que esta correcao de seguranca nao mude
		// o que o script grava. O typo em si esta apenas sinalizado, nao corrigido.
		$fax_atualiza = '';

		$sql_atualiza  = "update curso_inscricao_instituicao set ";
		$sql_atualiza .= " instituicao = ?, ";
		$sql_atualiza .= " endereco = ?, ";
		$sql_atualiza .= " bairro = ?, ";
		$sql_atualiza .= " municipio = ?, ";
		$sql_atualiza .= " cep = ?, ";
		$sql_atualiza .= " telefone = ?, ";
		$sql_atualiza .= " fax = ?, ";
		$sql_atualiza .= " beneficio = ?, ";
		$sql_atualiza .= " fim_de_semana = ? ";
		$sql_atualiza .= " where id_estagio=?";

		// echo "Atualiza instituicao conhecida no curso " . $sql_atualiza . "<br>";
		$res_atualiza = $db->Execute($sql_atualiza, array(
			$instituicao, $inst_endereco, $inst_bairro, $inst_municipio, $inst_cep,
			$inst_telefone, $fax_atualiza, $beneficio, $fim_de_semana, $id_estagio
		));
		if ($res_atualiza === false) die ("Nao foi possivel atualizar o registro na tabela curso_incricao_instituicao");
	}

	// Atualiza a tabela instituicoes
	$sql_estagio  = "update instituicoes set ";
	$sql_estagio .= " instituicao = ?, ";
	$sql_estagio .= " endereco = ?, ";
	$sql_estagio .= " bairro = ?, ";
	$sql_estagio .= " municipio = ?, ";
	$sql_estagio .= " cep = ?, ";
	$sql_estagio .= " telefone = ?, ";
	$sql_estagio .= " beneficios = ?, ";
	$sql_estagio .= " fim_de_semana = ? ";
	$sql_estagio .= " where id=?";
	// echo "Atualiza instituicao campo de estagio " . $sql_estagio . "<br>";
	$res_estagio = $db->Execute($sql_estagio, array(
		$instituicao, $inst_endereco, $inst_bairro, $inst_municipio, $inst_cep,
		$inst_telefone, $beneficio, $fim_de_semana, $id_estagio
	));
	if ($res_estagio === false) die ("Nao foi possivel atualizar o registro na tabela instituicoes");

}

// Calculo o last_id
/*
$res_ultima_instituicao = $db->Execute("select max(id) as ultimo_registro from curso_inscricao_instituicao");
if($res_ultima_instituicao === false) die ("Nao foi possivel consultar a tabela curso_incricao_instituicao");
$id_instituicao = $res_ultima_instituicao->fields['ultimo_registro'];
$id_instituicao = $res_ultima_instituicao->Insert_ID();
*/

$sql_inst_sup = "insert into curso_inst_super (id_supervisor, id_instituicao) values(?,?)";
$res_inst_sup = $db->Execute($sql_inst_sup, array($id_supervisor, $id_instituicao));
if ($res_inst_sup === false) die ("Nao foi possivel inserir o registro na tabela curso_inst_sup");
// echo $sql_inst_sup . "<br>";

// Envio um e-mail para a coordenacao de Estagio
$mensage_supervisor  = "Solicitação de inscrição $num_inscricao para o curso de $nome, \n";
$mensage_supervisor .= "registro no CRESS (7a. região) $cress \n\n";
$mensage_supervisor .= "Nome: $nome \n";
$mensage_supervisor .= "CPF: $cpf \n";
$mensage_supervisor .= "Endereço: $endereco \n";
$mensage_supervisor .= "Bairro $bairro \n";
$mensage_supervisor .= "Município: $municipio \n";
$mensage_supervisor .= "CEP: $cep \n";
$mensage_supervisor .= "Telefone: $telefone \n";
$mensage_supervisor .= "Email: $email \n";
$mensage_supervisor .= "Escola: $escola \n";
$mensage_supervisor .= "Formatura: $ano_formatura \n";
$mensage_supervisor .= "CRESS: $cress \n";
$mensage_supervisor .= "Região: $regiao \n";
$mensage_supervisor .= "Outros estudos: $outros_estudos \n";
$mensage_supervisor .= "Area do curso: $area_curso \n";
$mensage_supervisor .= "Ano do curso: $ano_curso \n";
$mensage_supervisor .= "Cargo: $cargo \n";
$mensage_supervisor .= "Instituição: $instituicao \n";
$mensage_supervisor .= "Endereço da instituição: $inst_endereco \n";
$mensage_supervisor .= "Bairro da instituição: $inst_bairro \n";
$mensage_supervisor .= "Município da instituição: $inst_municipio \n";
$mensage_supervisor .= "Telefone da instituição: $inst_telefone \n";
$mensage_supervisor .= "Fax da instituição: $inst_fax \n\n";

$to = "Coordenação de Estágio <estagio@ess.ufrj.br>";
$assunto  = "Solicitação de inscrição para o curso de atualização de supervisores: $nome";
$headers  = "From: $nome <$email> \r\n";
$headers .= "Replay-To: estagio@ess.ufrj.br \r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
// Envio um e-mail para a cordenacao de estagio
// mail ($to,$assunto,$mensage_supervisor,$headers);

// Envio outro e-mail para o supervisor que fez a inscricao
$mensage_supervisor  = "Sua solicitação de inscrição foi realizada com os seguintes dados \n\n";
$mensage_supervisor .= "Nome: $nome \n";
$mensage_supervisor .= "CPF: $cpf \n";
$mensage_supervisor .= "Endereço: $endereco \n";
$mensage_supervisor .= "Bairro $bairro \n";
$mensage_supervisor .= "Município: $municipio \n";
$mensage_supervisor .= "CEP: $cep \n";
$mensage_supervisor .= "Telefone: $telefone \n";
$mensage_supervisor .= "Email: $email \n";
$mensage_supervisor .= "Escola: $escola \n";
$mensage_supervisor .= "Formatura: $ano_formatura \n";
$mensage_supervisor .= "CRESS: $cress \n";
$mensage_supervisor .= "Região: $regiao \n";
$mensage_supervisor .= "Outros estudos: $outros_estudos \n";
$mensage_supervisor .= "Área do curso: $area_curso \n";
$mensage_supervisor .= "Ano do curso: $ano_curso \n";
$mensage_supervisor .= "Instituição: $instituicao \n";
$mensage_supervisor .= "Endereço da instituição: $inst_endereco \n";
$mensage_supervisor .= "Bairro da instituição: $inst_bairro \n";
$mensage_supervisor .= "Município da instituição: $inst_municipio \n";
$mensage_supervisor .= "Telefone da instituição: $inst_telefone \n";
$mensage_supervisor .= "Fax da instituição: $inst_fax \n\n";
$mensage_supervisor .= "                        Atenciosamente, \n";
$mensage_supervisor .= "                        Coordenação de estágio \n";

$to = $nome . "<" . $email . ">";
// $to = "Luis Acosta <luis@localhost>"; // Para testes
$assunto  = "Inscrição no curso de extensão para supervisores";
$headers  = "From: Coordenação de Estágio <estagio@ess.ufrj.br> \r\n";
$headers .= "Replay-To: $email \r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Envio outro e-mail para o supervisor que fez a inscricao
mail ($to,$assunto,$mensage_supervisor,$headers);

// header("Location:inscricao_lista.php?num_inscricao=$quantidade");
echo  "<meta HTTP-EQUIV='refresh' CONTENT='1, URL=inscricao_lista.php?num_inscricao=$quantidade'>";
// echo "<p>Sua inscricao foi a numero " . $quantidade . "<br>";

?>
