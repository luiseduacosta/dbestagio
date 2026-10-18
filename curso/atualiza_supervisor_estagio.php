<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

// Exige sessao autenticada e perfil admin: este script grava em supervisores a
// partir dos dados do curso. A permissao e conferida aqui, e nao apenas na tela
// que exibe o link -- mesmo criterio de MuralestagiosController::exigirAdmin().
require_once(__DIR__ . "/../autentica.inc");
if (empty($isAdmin)) {
	die("Acesso restrito: apenas administradores podem sincronizar o supervisor.");
}

$id_supervisor = isset($_REQUEST['id_supervisor_curso']) ? (int)$_REQUEST['id_supervisor_curso'] : 0;
$indice        = isset($_REQUEST['indice']) ? (int)$_REQUEST['indice'] : 0;

$sql_curso = "select * from curso_inscricao_supervisor where id = ?";
$res_curso = $db->Execute($sql_curso, array($id_supervisor));
if ($res_curso == false) die ("Nao foi possivel consultar a tabela curso_inscricao_supervisor");
$cress_id = $res_curso->fields['cress'];
$nome = $res_curso->fields['nome'];
$cpf = $res_curso->fields['cpf'];
$endereco = $res_curso->fields['endereco'];
$bairro = $res_curso->fields['bairro'];
$municipio = $res_curso->fields['municipio'];
$cep = $res_curso->fields['cep'];
$codigo_tel = $res_curso->fields['codigo_tel'];
$telefone = $res_curso->fields['telefone'];
$codigo_cel = $res_curso->fields['codigo_cel'];
$celular = $res_curso->fields['celular'];
$email = $res_curso->fields['email'];
$escola = $res_curso->fields['escola'];
$ano_formatura = $res_curso->fields['ano_formatura'];
$outros_estudos = $res_curso->fields['outros_estudos'];
$area_curso = $res_curso->fields['area_curso'];
$ano_curso = $res_curso->fields['ano_curso'];
$num_inscricao = $res_curso->fields['num_inscricao'];
$curso_turma = $res_curso->fields['curso_turma'];

// Atualizacao
$sql  = "update supervisores set nome=?, cpf=?, endereco=?, municipio=?, bairro=?, cep=?, ";
$sql .= " codigo_tel=?, telefone=?, codigo_cel=?, celular=?, email=?, escola=?, ";
$sql .= " ano_formatura=?, outros_estudos=?, area_curso=?, ano_curso=? where cress=?";
// echo "Atualiza supervisor " . $sql . "<br>";
$res_atualiza_supervisores = $db->Execute($sql, array(
	$nome, $cpf, $endereco, $municipio, $bairro, $cep,
	$codigo_tel, $telefone, $codigo_cel, $celular, $email, $escola,
	$ano_formatura, $outros_estudos, $area_curso, $ano_curso,
	$cress_id
));
if ($res_atualiza_supervisores === false) die ("Nao foi possivel atualizar o registro na tabela supervisores");

header("Location: ../supervisores/ver_cada.php?indice=$indice");

?>
