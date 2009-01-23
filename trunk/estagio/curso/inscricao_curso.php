<?php

$quantidade     = $_POST['quantidade'];
// Dados do supervisor
$nome           = $_POST['nome'];
$endereco       = $_POST['endereco'];
$bairro         = $_POST['bairro'];
$municipio      = $_POST['municipio'];
$cep            = $_POST['cep'];
$codigo_tel 	= $_POST['codigo_tel'];
$telefone       = $_POST['telefone'];
$codigo_cel 	= $_POST['codigo_cel'];
$celular        = $_POST['celular'];
$email          = $_POST['email'];
$escola         = $_POST['escola'];
$ano_formatura  = $_POST['ano_formatura'];
$cress          = $_POST['cress'];
$regiao		    = $_POST['regiao'];
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

if(empty($nome)) {
	echo "<p>� obrigat�rio prencher o campo com o nome</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if(empty($email)) {
	echo "<p>Favor digite um endere�o electr�nico para nossa comunica��o</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if(empty($cress)) {
	echo "<p>� obrigat�rio prencher o n�mero de registro no CRESS</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

if(empty($instituicao)) {
	echo "<p>� obrigat�rio informar a institui��o em que trabalha</p>";
	echo "<p><a href='javascript:history.back(1)'>Voltar</a></p>";
	exit;
}

include_once("../db.inc");
include_once("../setup.php");
$turma = TURMA;

$sql  = "select count(*) as quantidade ";
$sql .= " from curso_inscricao_supervisor ";
$sql .= " where curso_turma=$turma";
$res_sql = $db->Execute($sql);
if($res_sql === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
$quantidade = $res_sql->fields['quantidade'];

// Renumero o campo num_inscricao da tabela de supervisores por ordem de inscricao
$sql = "select id from curso_inscricao_supervisor where curso_turma = $turma order by id";
$res_sql_inscricao = $db->Execute($sql);
if($res_sql_inscricao === false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");

$i = 1;
while(!$res_sql_inscricao->EOF) {
    $id = $res_sql_inscricao->fields['id'];
    $sql_atualiza = "update curso_inscricao_supervisor set num_inscricao=$i where id=$id";
    $res_atualiza = $db->Execute($sql_atualiza);
    if($res_atualiza === false) die ("Nao foi possivel atualizar a tabela curso_inscricao_supervisor");
    $res_sql_inscricao->MoveNext();
    $i++;
}

// Aumento em uma unidade o numero de inscritos
$quantidade++;

// Verifico se o Cress ja esta cadastrado
$sql_cress = "select id from supervisores where cress=$cress";
$res_cress = $db->Execute($sql_cress);
if($res_cress == false) die ("Nao foi possivel consultar a tabela supervisores");
$supervisores_cress_id = $res_cress->fields['id'];
// Se esta cadastrado aproveito para atualizar a tabela supervisores
if ($supervisores_cress_id) {
	// echo "Supervisor conhecido: atualizando";
	$sql = "update supervisores set nome='$nome', endereco='$endereco', municipio='$municipio', bairro='$bairro', cep='$cep', codigo_tel='$codigo_tel', telefone='$telefone', codigo_cel='$codigo_cel', celular='$celular', email='$email', escola='$escola', ano_formatura='$ano_formatura', outros_estudos='$outros_estudos', area_curso='$area_curso', ano_curso='$ano_curso' where id='$supervisores_cress_id'";
	// echo $sql . "<br>";
	$res_atualiza_supervisores = $db->Execute($sql);
	if($res_atualiza_supervisores === false) die ("Nao foi possivel atualizar o registro na tabela supervisores");	
}

// Insero o supervisor
$sql_supervisor  =  "insert into curso_inscricao_supervisor ";
$sql_supervisor .= "(nome, endereco, bairro, municipio, cep, codigo_tel, telefone, codigo_cel, celular, email, escola, ano_formatura, cress, regiao, outros_estudos, area_curso, ano_curso, cargo, num_inscricao, curso_turma) ";
$sql_supervisor .= "values('$nome','$endereco','$bairro','$municipio','$cep','$codigo_tel','$telefone','$codigo_cel','$celular','$email','$escola','$ano_formatura','$cress','$regiao','$outros_estudos','$area_curso','$ano_curso','$cargo','$quantidade','$turma')";
$res_supervisor = $db->Execute($sql_supervisor);
if($res_supervisor === false) die ("Nao foi possivel inserir o registro na tabela curso_inscricao_supervisor");

$res_ultimo_supervisor = $db->Execute("select max(id) as ultimo_registro from curso_inscricao_supervisor");
$id_supervisor = $res_ultimo_supervisor->fields['ultimo_registro'];

// Instituicao ainda nao conhecida como campo de estagio: inserir
if (empty($id_estagio)) {
	$sql_instituicao  = "insert into curso_inscricao_instituicao";
	$sql_instituicao .= "(instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana) ";
	$sql_instituicao .= "values('$instituicao','$inst_endereco','$inst_bairro','$inst_municipio','$inst_cep','$inst_telefone','$inst_fax','$beneficio','$fim_de_semana')";
	// echo $sql_instituicao . "<br>";

	$res_institucao = $db->Execute($sql_instituicao);
	if($res_instituicao === false) die ("Nao foi possivel inserir o registro na tabela curso_incricao_instituicao");

	// Capturo o id do registro inserido
	$id_instituicao = $db->Insert_ID();
// Instituicao ja conhecida como campo de estagio: inserir no curso e atualizar estagio
} else {
	$sql_instituicao  = "insert into curso_inscricao_instituicao";
	$sql_instituicao .= "(instituicao, endereco, bairro, municipio, cep, telefone, fax, beneficio, fim_de_semana, id_estagio) ";
	$sql_instituicao .= "values('$instituicao','$inst_endereco','$inst_bairro','$inst_municipio','$inst_cep','$inst_telefone','$inst_fax','$beneficio','$fim_de_semana', '$id_estagio')";
	// echo $sql_instituicao . "<br>";
	$res_institucao = $db->Execute($sql_instituicao);
	if($res_instituicao === false) die ("Nao foi possivel inserir o registro na tabela curso_incricao_instituicao");
	$id_instituicao = $db->Insert_ID();

	$sql = "select id from curso_inscricao_instituicao where id_estagio=$id_estagio";
	$res = $db->Execute($sql);
	if($res === false) die ("Nao foi possivel consultar a tabela curso_incricao_instituicao");
	$quantidade = $res->RecordCount();
	if ($quantidade > 0) {
		$sql_atualiza  = "update curso_inscricao_instituicao set ";
		$sql_atualiza .= " instituicao = '$instituicao', ";
		$sql_atualiza .= " endereco = '$inst_endereco', ";
		$sql_atualiza .= " bairro = '$inst_bairro', ";
		$sql_atualiza .= " municipio = '$inst_municipio', ";
		$sql_atualiza .= " cep = '$inst_cep', ";
		$sql_atualiza .= " telefone = '$inst_telefone', ";
		$sql_atualiza .= " fax = '$ints_fax', ";
		$sql_atualiza .= " beneficio = '$beneficio', ";
		$sql_atualiza .= " fim_de_semana = '$fim_de_semana' ";
		$sql_atualiza .= " where id_estagio=$id_estagio";

		// echo $sql_atualiza . "<br>";
		$res_atualiza = $db->Execute($sql_atualiza);
		if($res_atualiza === false) die ("Nao foi possivel atualizar o registro na tabela curso_incricao_instituicao");
	}

	// Atualiza a tabela estagio
	$sql_estagio  = "update estagio set ";
	$sql_estagio .= " instituicao = '$instituicao', ";
	$sql_estagio .= " endereco = '$inst_endereco', ";
	$sql_estagio .= " bairro = '$inst_bairro', ";
	$sql_estagio .= " municipio = '$inst_municipio', ";
	$sql_estagio .= " cep = '$inst_cep', ";
	$sql_estagio .= " telefone = '$inst_telefone', ";
	$sql_estagio .= " fax = '$ints_fax', ";
	$sql_estagio .= " beneficio = '$beneficio', ";
	$sql_estagio .= " fim_de_semana = '$fim_de_semana' ";
	$sql_estagio .= " where id=$id_estagio";
	// echo $sql_estagio . "<br>";
	$res_estagio = $db->Execute($sql_estagio);
	if($res_estagio === false) die ("Nao foi possivel atualizar o registro na tabela estagio");

}

// Calculo o last_id
/*
$res_ultima_instituicao = $db->Execute("select max(id) as ultimo_registro from curso_inscricao_instituicao");
if($res_ultima_instituicao === false) die ("Nao foi possivel consultar a tabela curso_incricao_instituicao");
$id_instituicao = $res_ultima_instituicao->fields['ultimo_registro'];
$id_instituicao = $res_ultima_instituicao->Insert_ID();
*/

$sql_inst_sup = "insert into curso_inst_super (id_supervisor, id_instituicao) values('$id_supervisor','$id_instituicao')";
$res_inst_sup = $db->Execute($sql_inst_sup);
if($res_inst_sup === false) die ("Nao foi possivel inserir o registro na tabela curso_inst_sup");
// echo $sql_inst_sup . "<br>";

// Envio um e-mail para a coordenacao de Estagio
$mensage_supervisor  = "Solicita��o de inscri��o $num_inscricao para o curso de $nome, \n";
$mensage_supervisor .= "registro no CRESS (7a. regi�o) $cress \n\n";
$mensage_supervisor .= "Nome: $nome \n";
$mensage_supervisor .= "Endere�o: $endereco \n";
$mensage_supervisor .= "Bairro $bairro \n";
$mensage_supervisor .= "Munic�pio: $municipio \n";
$mensage_supervisor .= "CEP: $cep \n";
$mensage_supervisor .= "Telefone: $telefone \n";
$mensage_supervisor .= "Email: $email \n";
$mensage_supervisor .= "Escola: $escola \n";
$mensage_supervisor .= "Formatura: $ano_formatura \n";
$mensage_supervisor .= "CRESS: $cress \n";
$mensage_supervisor .= "Regi�o: $regiao \n";
$mensage_supervisor .= "Outros estudos: $outros_estudos \n";
$mensage_supervisor .= "Area do curso: $area_curso \n";
$mensage_supervisor .= "Ano do curso: $ano_curso \n";
$mensage_supervisor .= "Cargo: $cargo \n";
$mensage_supervisor .= "Institui��o: $instituicao \n";
$mensage_supervisor .= "Endere�o da institui��o: $inst_endereco \n";
$mensage_supervisor .= "Bairro da institui��o: $inst_bairro \n";
$mensage_supervisor .= "Munic��pio da institui��o: $inst_municipio \n";
$mensage_supervisor .= "Telefone da institui��o: $inst_telefone \n";
$mensage_supervisor .= "Fax da institui��o: $inst_fax \n\n";

$to = "Coordena��o de Est�gio <estagio@ess.ufrj.br>";
$assunto  = "Solicita��o de inscri��o para o curso de atualiza��o de supervisores: $nome";
$headers  = "From: $nome <$email> \r\n";
$headers .= "Replay-To: estagio@ess.ufrj.br \r\n";
$headers .= "X-Mailer: PHP/" . phpversion();
// Envio um e-mail para a cordenacao de estagio
// mail ($to,$assunto,$mensage_supervisor,$headers);

// Envio outro e-mail para o supervisor que fez a inscricao
$mensage_supervisor  = "Sua solicita��o de inscri��o foi realizada com os seguintes dados \n\n";
$mensage_supervisor .= "Nome: $nome \n";
$mensage_supervisor .= "Endere�o: $endereco \n";
$mensage_supervisor .= "Bairro $bairro \n";
$mensage_supervisor .= "Munic��pio: $municipio \n";
$mensage_supervisor .= "CEP: $cep \n";
$mensage_supervisor .= "Telefone: $telefone \n";
$mensage_supervisor .= "Email: $email \n";
$mensage_supervisor .= "Escola: $escola \n";
$mensage_supervisor .= "Formatura: $ano_formatura \n";
$mensage_supervisor .= "CRESS: $cress \n";
$mensage_supervisor .= "Regi�o: $regiao \n";
$mensage_supervisor .= "Outros estudos: $outros_estudos \n";
$mensage_supervisor .= "�rea do curso: $area_curso \n";
$mensage_supervisor .= "Ano do curso: $ano_curso \n";
$mensage_supervisor .= "Cargo: $cargo \n";
$mensage_supervisor .= "Institui��o: $instituicao \n";
$mensage_supervisor .= "Endere�o da institui��o: $inst_endereco \n";
$mensage_supervisor .= "Bairro da institui��o: $inst_bairro \n";
$mensage_supervisor .= "Munic�pio da institui��o: $inst_municipio \n";
$mensage_supervisor .= "Telefone da institui��o: $inst_telefone \n";
$mensage_supervisor .= "Fax da institui��o: $inst_fax \n\n";
$mensage_supervisor .= "                        Atenciosamente, \n";
$mensage_supervisor .= "                        Coordena��o de est�gio \n";

$to = $nome . "<" . $email . ">";
// $to = "Luis Acosta <luis@localhost>"; // Para testes
$assunto  = "Inscri��o no curso de extens�o para supervisores";
$headers  = "From: Coordena��o de Est�gio <estagio@ess.ufrj.br> \r\n";
$headers .= "Replay-To: $email \r\n";
$headers .= "X-Mailer: PHP/" . phpversion();

// Envio outro e-mail para o supervisor que fez a inscricao
mail ($to,$assunto,$mensage_supervisor,$headers);

header("Location:inscricao_lista.php?num_inscricao=$quantidade");
// echo "<p>Sua inscricao foi a numero " . $quantidade . "<br>";

?>