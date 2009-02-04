<?php

include_once("../../autoriza.inc");
include_once("../../setup.php");

$id_instituicao = isset($_REQUEST['id_instituicao']) ? $_REQUEST['id_instituicao'] : NULL;
$id_supervisor = isset($_REQUEST['id_supervisor']) ? $_REQUEST['id_supervisor'] : NULL;
$modifica = isset($_REQUEST['modifica']) ? $_REQUEST['modifica'] : NULL;
$flag = isset($_REQUEST['flag']) ? $_REQUEST['flag'] : NULL;
$inserir = isset($_REQUEST['inserir']) ? $_REQUEST['inserir'] : NULL;
$curso = isset($_REQUEST['curso']) ? $_REQUEST['curso'] : NULL;

$indice = $_REQUEST['indice'];
$submit = $_REQUEST['submit'];
$botao  = $_REQUEST['botao'];

if ($curso) {
	$tabela_instituicao  = 'curso_inscricao_instituicao';
	$tabela_supervisores = 'curso_inscricao_supervisor';
	$tabela_inst_super   = 'curso_inst_super';
	$tabela_area_estagio = 'areas_estagio';
	$tabela_estagiarios  = 'estagiarios';
	$tabela_professores  = 'professores';
} else {
	$tabela_instituicao  = 'estagio';      // curso_inscricao_instituicao
	$tabela_supervisores = 'supervisores'; // curso_inscricao_supervisor
	$tabela_inst_super   = 'inst_super';   // curso_inst_super
	$tabela_area_estagio = 'areas_estagio';
	$tabela_estagiarios  = 'estagiarios';
	$tabela_professores  = 'professores';
}

// Insere uma instituicao em branco e logo passo para atualizar essa institui��o
if ($inserir) {
	$sql_insert = "insert into $tabela_instituicao (instituicao) values('')";
	$res_insert = $db->Execute($sql_insert);
	$id_instituicao = $db->Insert_ID();
	$modifica = "inserir"; // Para poder atualizar
}

// Atualizacao da instituicao
if ($modifica) {

	// Variavel para alternar entre as duas visoes	
	$flag++;
	// echo $flag . "<br>";
	// echo $indice . "<br>";
	
	// Pego as �reas das institui��es para ser enviadas para o formul�rio
	$sql_areas = "select id, area from $tabela_area_estagio order by area";
	$res_areas = $db->Execute($sql_areas);
	if($res_areas === false) die ("N�o foi poss�vel consultar a tabela areas_estagio");
	$i = 0;
	while(!$res_areas->EOF) {
	    $matriz_areas[$i]["id_area"] = $res_areas->fields['id'];
	    $matriz_areas[$i]["area"]    = $res_areas->fields['area'];
	    $i++;
	    $res_areas->MoveNext();
	}
	
	if ($flag == 1) {
		echo "Modifica dados";
	} elseif ($flag == 2) {
		echo "Atualiza tabela";

		$area_instituicao      = $_POST['area'];
		$natureza_instituicao  = $_POST['natureza'];
		$nome_instituicao      = $_POST['instituicao'];
		$url_instituicao       = $_POST['url'];
		$endereco_instituicao  = $_POST['endereco'];
		$bairro_instituicao    = $_POST['bairro'];
		$municipio_instituicao = $_POST['municipio'];		
		$cep_instituicao       = $_POST['cep'];
		$telefone_instituicao  = $_POST['telefone'];
		$fax_instituicao       = $_POST['fax'];
		$beneficio_instituicao = $_POST['beneficios'];
		$fim_de_semana         = $_POST['fim_de_semana'];
		$convenio              = $_POST['convenio'];
		$observacoes		   = $_POST['observacoes'];
		
		$sql_atualiza  = "update $tabela_instituicao set area='$area_instituicao', natureza='$natureza_instituicao', instituicao='$nome_instituicao', url='$url_instituicao', endereco='$endereco_instituicao', bairro='$bairro_instituicao', municipio='$municipio_instituicao', cep='$cep_instituicao', telefone='$telefone_instituicao', fax='$fax_instituicao', beneficio='$beneficio_instituicao', fim_de_semana='$fim_de_semana', observacoes='$observacoes' ";
		// Campos especificos dos supervisores de estagio
		if (!$curso) $sql_atualiza .= ", convenio='$convenio' ";
		$sql_atualiza .= " where id='$id_instituicao'";
		// echo $sql_atualiza . "<br>";

		$res_atualiza = $db->Execute($sql_atualiza);
		if($res_atualiza === false) die ("N�o foi poss�vel atualizar a tabela estagio");
		
		$flag = NULL;
		unset($modifica);
	}

}

/*
echo "curso: " . $curso;
echo " id_instituicao: " . $id_instituicao;
echo " Indice: " . $indice;
echo " Submit: " . $submit;
echo " Botao: " . $botao . "<br>";
*/

// Conto a quantidade de registros
$sql = "select id from $tabela_instituicao";
$resultado = $db->Execute($sql);
if ($resultado === false) die ("N�o foi poss�vel consultar a tabela estagio");
$num_linhas = $resultado->RecordCount();

$ultimo_registro = $num_linhas - 1;

switch($botao)
{
    case "inserir":
	$indice = 0;
	break;
	
    case "primeiro":
	$indice = 0;
	break;

    case "menos_1";
	$indice--;
	if($indice == 0)
	    $indice = $num_linhas - 1;
	break;

    case "menos_10":
	$indice = $indice - 10;
	if($indice < 0)
	    $indice = $ultimo_registro - abs($indice);
	break;

    case "mais_1":
	$indice++;
	if($indice == $num_linhas)
	    $indice = 0;
	break;

    case "mais_10":
	$indice = $indice + 10;
	if($indice > $ultimo_registro)
	    $indice = $indice - $num_linhas;
	break;

    case "ultimo":
	$indice = $ultimo_registro;
	break;

    case "excluir":
	// echo "excluir $id_instituicao";
	echo "<meta http-equiv='refresh' content='0;../cancelar/cancela.php?id_instituicao=$id_instituicao&indice=$indice' />";
	// include_once("../cancelar/cancela.php");
	break;
}

// Rotina para acrescentar um supervisor
if (!empty($id_supervisor)) {
	// echo "Acrescentar supervisor<br>";
	$sql = "insert into $tabela_inst_super (id_supervisor,id_instituicao) values('$id_supervisor','$id_instituicao')";
	// echo $sql . "<br>";
	$resultado = $db->Execute($sql);
	if($resultado === false) die ("N�o foi poss�vel inserir dados na tabela inst_super");	
} else {
	NULL; // echo "Nada: " . $_POST[num_instituicao] . "<br>";
}

// Busco o lugar da instituicao na tabela
if (!empty($id_instituicao)) {
	$sql_instituicao = "select id from $tabela_instituicao order by instituicao";
	// echo $sql_instituicao . "<br>";
	$resultado_instituicao = $db->Execute($sql_instituicao);
	if ($resultado_instituicao === false) die ("N�o foi poss�vel consultar a tabela estagio");
	$lugar = 0;
	while (!$resultado_instituicao->EOF)	{
		$num_instituicao = $resultado_instituicao->fields['id'];
		if ($num_instituicao === $id_instituicao) {
			$indice = $lugar;
		}
		$lugar++;
		$resultado_instituicao->MoveNext();
	}
}

if (isset($indice)) {
	$sql_estagio  = "select e.id, e.instituicao, ";
	$sql_estagio .= " e.endereco, e.cep, e.bairro, e.municipio, ";
	$sql_estagio .= " e.telefone, e.fax, e.beneficio, e.fim_de_semana, ";
	$sql_estagio .= " e.observacoes "; 

	// Estes campos somente existem na tabela de instituicoes de estagio
	if (!$curso) $sql_estagio .= ", e.url, e.natureza, e.area as id_area, a.area, e.convenio ";

	$sql_estagio .= " from $tabela_instituicao as e ";
	$sql_estagio .= " left outer join $tabela_area_estagio as a ";
	$sql_estagio .= " on e.area=a.id ";
	$sql_estagio .= " order by instituicao";
	// echo $sql_estagio . '<br';
	$res_estagio = $db->SelectLimit($sql_estagio,1,$indice);
	// echo $indice . "<br>";
	while (!$res_estagio->EOF) {
	    $id            = $res_estagio->fields['id'];
	    $instituicao   = $res_estagio->fields['instituicao'];
	    $url           = $res_estagio->fields['url'];
	    $endereco      = $res_estagio->fields['endereco'];
	    $bairro        = $res_estagio->fields['bairro'];
   	    $municipio     = $res_estagio->fields['municipio'];
	    $cep           = $res_estagio->fields['cep'];
	    $telefone      = $res_estagio->fields['telefone'];
	    $fax           = $res_estagio->fields['fax'];
	    $beneficios    = $res_estagio->fields['beneficio'];
	    $fim_de_semana = $res_estagio->fields['fim_de_semana'];
	    $id_area       = $res_estagio->fields['id_area'];
	    $area          = $res_estagio->fields['area'];
	    $natureza      = $res_estagio->fields['natureza'];	    
	    $convenio      = $res_estagio->fields['convenio'];
	    $observacoes   = $res_estagio->fields['observacoes'];

	    if ($id) {
			// Nao fazer para os supervisores do curso
			if (!$curso) {
		    	// Seleciono a turma das instituicoes
			    $sql_periodo = "select max(periodo) as periodo from $tabela_estagiarios where id_instituicao=$id";
				// echo $sql_periodo . "<br>";	
			    $resultado = $db->Execute($sql_periodo);
			    if ($resultado === false) die ("N�o foi poss�vel consultar a tabela estagiarios");
			    while (!$resultado->EOF) {
					$periodo = $resultado->fields['periodo'];
					$resultado->MoveNext();
			    }
			}

			// Seleciono os supervisores ou assistentes sociais
		    $sql  = "select s.id as supervisor_id, s.cress, s.nome, e.id as instituicao_id from $tabela_supervisores as s, $tabela_instituicao as e, $tabela_inst_super as j ";
		    $sql .= "where s.id=j.id_supervisor and e.id=j.id_instituicao and e.id=$id order by s.nome";
			// echo $sql . "<br>";
		    $resultado = $db->Execute($sql);
		    if ($resultado === false) die ("N�o foi poss�vel consultar a tabela supervisores");
		    $i = 0;
		    while (!$resultado->EOF) {
				$inst_supervisores[$i]["supervisor_id"]  = $resultado->fields["supervisor_id"];
				$inst_supervisores[$i]["cress"]          = $resultado->fields["cress"];
				$inst_supervisores[$i]["nome"]           = $resultado->fields["nome"];
				$inst_supervisores[$i]["instituicao_id"] = $resultado->fields["instituicao_id"];

				/*
				echo "Supervisor: ";
				echo $supervisor_id =  $resultado->fields["supervisor_id"];
				echo " Cress: ";
				echo $cress =  $resultado->fields["cress"];
				echo " Institui��o; ";			
				echo $instituicao_id =  $resultado->fields["instituicao_id"];
				echo "<br>";
				*/

				$supervisor_id =  $resultado->fields["supervisor_id"];
				$cress =  $resultado->fields["cress"];
				$instituicao_id =  $resultado->fields["instituicao_id"];

				// Somente atraves do cress posso conetar as duas tabelas de supervisores
				if ($cress) {
					if ($curso) {
						$sql_estagio = "select id from supervisores where cress=$cress";
						$res_estagio = $db->Execute($sql_estagio);
						$id_super_estagio = $res_estagio->fields['id'];	
						if ($id_super_estagio) {
							// echo "Assistente social supervisor de estagio: " . $id_super_estagio . "<br>";
						}

						// Para pegar as instituicoes tenho que inverter
						$tabela_instituicao  = 'estagio';// 'curso_inscricao_instituicao';
						$tabela_supervisores = 'supervisores'; // 'curso_inscricao_supervisor';
						$tabela_inst_super   = 'inst_super'; // 'curso_inst_super';

					} else {
						$sql_curso = "select id from curso_inscricao_supervisor where cress=$cress";
						// echo $sql_curso . "<br>";
						$res_curso = $db->Execute($sql_curso);
						$id_super_curso = $res_curso->fields['id'];
						if ($id_super_curso) {
							// echo "Supervisor inscrito no curso: " . $id_super_curso . "<br>";
						}

						// Para pegar as instituicoes tenho que inverter
						$tabela_instituicao  = 'curso_inscricao_instituicao';
						$tabela_supervisores = 'curso_inscricao_supervisor';
						$tabela_inst_super   = 'curso_inst_super';

					}

					// Seleciono instituicoes 'cruzadas' entre as tabelas
					$sql_inst_estagio  = "select e.id from $tabela_instituicao as e";
					$sql_inst_estagio .= " join $tabela_inst_super as j on e.id = j.id_instituicao ";
					$sql_inst_estagio .= " join $tabela_supervisores as s on j.id_supervisor = s.id ";
					$sql_inst_estagio .= " where cress=$cress";
					// echo $sql_inst_estagio . "<br>";							
					$res_inst_estagio = $db->Execute($sql_inst_estagio);
					$instituicao_num = $res_inst_estagio->fields['id'];
					// echo $instituicao_num . "<br>";
					$inst_supervisores[$i]["id_curso_inst"]  = $instituicao_num;


					// Assistente social do curso em estagio
					$inst_supervisores[$i]["id_super_estagio"] = $id_super_estagio;
					// Supervisor de estagio no curso
					$inst_supervisores[$i]["id_super_curso"] = $id_super_curso;


				    // Seleciona os professores somente para os supervisores de estagio
				    $sql_professor  = "select professores.id, professores.nome, max(estagiarios.periodo) as periodo ";
				    $sql_professor .= " from $tabela_estagiarios ";
				    $sql_professor .= " inner join $tabela_professores on estagiarios.id_professor = professores.id "; 
				    $sql_professor .= " where estagiarios.id_instituicao = $id";
				    $sql_professor .= " group by professores.nome ";
				    $sql_professor .= " order by nome, periodo ";
				    // echo $sql_professor . "<br>";
					$resultado_professor = $db->Execute($sql_professor);
					$j = 0;
					while (!$resultado_professor->EOF) {
						$inst_professores[$j]['id_professor'] = $resultado_professor->fields['id'];
						$inst_professores[$j]['nome'] = $resultado_professor->fields['nome'];
						$inst_professores[$j]['periodo'] = $resultado_professor->fields['periodo'];
						$j++;
						$resultado_professor->MoveNext();
					}
				
				}

				$i++;
				$resultado->MoveNext();

			}

			// Nao executar com a tabela do curso
/*
			if (!$curso) {
				// Curso de supervisores
				$sql_curso = "select id from curso_inscricao_instituicao where id_estagio='$id'";
				// echo $sql_curso . "<br>";			
				$res_curso = $db->Execute($sql_curso);
				if ($res_curso == false) die("N�o foi possivel consultar a tabela curso_inscricao_instituicao");
				$id_curso_instituicao = $res_curso->fields['id'];
				// echo $id_curso_instituicao . "<br>";
			}
*/			
		}

	    $res_estagio->MoveNext();
	}
}

// Pego a listagem de todos os supervisores
$sql_supervisores = "select id, nome from $tabela_supervisores order by nome";
$res_supervisores = $db->Execute($sql_supervisores);
if ($res_supervisores === false) die ("N�o foi poss�vel consultar a tabela supervisores");
$i = 0;
while(!$res_supervisores->EOF) {
    $supervisores[$i]['id']   = $res_supervisores->fields['id'];
    $supervisores[$i]['nome'] = $res_supervisores->fields['nome'];
	$i++;
    $res_supervisores->MoveNext();
}

$smarty = new Smarty_estagio;

$smarty->assign("curso",$curso);
$smarty->assign("modifica",$modifica);
$smarty->assign("sistema_autentica",$sistema_autentica);
$smarty->assign("indice",$indice);
$smarty->assign("id_instituicao",$id_instituicao);
$smarty->assign("id",$id);
$smarty->assign("instituicao",$instituicao);
$smarty->assign("url",$url);
$smarty->assign("id_curso_instituicao",$id_curso_instituicao);
$smarty->assign("endereco",$endereco);
$smarty->assign("cep",$cep);
$smarty->assign("bairro",$bairro);
$smarty->assign("municipio",$municipio);
$smarty->assign("telefone",$telefone);
$smarty->assign("fax",$fax);
$smarty->assign("beneficios",$beneficios);
$smarty->assign("fim_de_semana",$fim_de_semana);
$smarty->assign("id_area",$id_area);
$smarty->assign("area",$area);
$smarty->assign("natureza",$natureza);
$smarty->assign("convenio",$convenio);
$smarty->assign("observacoes",$observacoes);
$smarty->assign("turma",$periodo);
$smarty->assign("inst_supervisores",$inst_supervisores);
$smarty->assign("inst_professores",$inst_professores);
$smarty->assign("supervisores",$supervisores);
$smarty->assign("matriz_areas",$matriz_areas);
$smarty->assign("flag",$flag);
$smarty->display("instituicao_ver_cada.tpl");

?>