<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once("../setup.php");

$id_supervisor = $_REQUEST['id_supervisor_curso'];
$indice = $_REQUEST['indice'];

$sql_curso = "select * from curso_inscricao_supervisor where id = $id_supervisor";
$res_curso = $db->Execute($sql_curso);
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
$sql = "update supervisores set nome=\"$nome\", cpf=\"$cpf\", endereco=\"$endereco\", municipio=\"$municipio\", bairro=\"$bairro\", cep=\"$cep\", codigo_tel=\"$codigo_tel\", telefone=\"$telefone\", codigo_cel=\"$codigo_cel\", celular=\"$celular\", email=\"$email\", escola=\"$escola\", ano_formatura=\"$ano_formatura\", outros_estudos=\"$outros_estudos\", area_curso=\"$area_curso\", ano_curso=\"$ano_curso\" where cress=\"$cress_id\"";
// echo "Atualiza supervisor " . $sql . "<br>";
$res_atualiza_supervisores = $db->Execute($sql);
if ($res_atualiza_supervisores === false) die ("Nao foi possivel atualizar o registro na tabela supervisores");

header("Location: ../assistentes/exibir/ver_cada.php?indice=$indice");

?>
