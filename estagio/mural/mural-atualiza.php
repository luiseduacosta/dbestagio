<?php

include_once("../setup.php");

$id_instituicao = $_POST['id_instituicao'];
$instituicao = $_POST['instituicao'];
$convenio = $_POST['convenio'];
$vagas = $_POST['vagas'];
$beneficios = $_POST['beneficios'];
$final_de_semana = $_POST['final_de_semana'];
$cargaHoraria = $_POST['cargaHoraria'];
$requisitos = $_POST['requisitos'];
$id_area = $_POST['id_area'];
$id_professor = $_POST['id_professor'];
$horario = $_POST['horario'];
$dataSelecao = $_POST['dataSelecao'];
$horarioSelecao = $_POST['horarioSelecao'];
$dataInscricao = $_POST['dataInscricao'];
$localSelecao = $_POST['localSelecao'];
$formaSelecao = $_POST['formaSelecao'];
$contato = $_POST['contato'];
$outras = $_POST['outras'];
$periodo = $_POST['periodo'];
$email = $_POST['email'];

// Para salvar tenho que utilizar o formato aaaa/mm/dd/
$data_selecao = date("Y-m-d",strtotime($dataSelecao));
$data_inscricao = date("Y-m-d",strtotime($dataInscricao));

// echo "Instituicao " . $instituicao . "<br>";
// echo "Vagas " . $vagas  . "<br>";
// echo "Beneficios ". $beneficios  . "<br>";
// echo "Final de semana " . $final_de_semana  . "<br>";
// echo "Id area ". $id_area  . "<br>";
// echo "Id professor " . $id_professor  . "<br>";
// echo "Horario " . $horario  . "<br>";
// echo "Data selecao " . $dataSelecao  . "<br>";
// echo "HorArio selecao " . $horarioSelecao  . "<br>";
// echo "Local selecao " . $localSelecao  . "<br>";
// echo "Forma selecao " . $formaSelecao  . "<br>";
// echo "Contato " . $contato  . "<br>";
// echo "Outras " . $outras  . "<br>";

$sql  = "update mural_estagio set ";
$sql .= "instituicao = '$instituicao', ";
$sql .= "convenio = '$convenio', ";
$sql .= "vagas = '$vagas', ";
$sql .= "beneficios='$beneficios', ";
$sql .= "final_de_semana='$final_de_semana', ";
$sql .= "cargaHoraria='$cargaHoraria', ";
$sql .= "requisitos='$requisitos', ";
$sql .= "id_area='$id_area', ";
$sql .= "id_professor='$id_professor', ";
$sql .= "horario='$horario', ";
$sql .= "dataSelecao='$data_selecao', ";
$sql .= "horarioSelecao = '$horarioSelecao', ";
$sql .= "dataInscricao='$data_inscricao', ";
$sql .= "localSelecao='$localSelecao', ";
$sql .= "formaSelecao='$formaSelecao', ";
$sql .= "contato='$contato', ";
$sql .= "outras='$outras', ";
$sql .= "periodo='$periodo', ";
$sql .= "email='$email' ";
$sql .= "where id='$id_instituicao'";

// echo $sql . "<br>";

$resultado = $db->Execute($sql);
if($resultado === false) die ("Não foi possível atualizar a tabela mural_estagio");

header("Location: ver_cada.php?id_instituicao=$id_instituicao");

?>