<?php

function ver_supervisor() {

    include_once("../setup.php");

    $sql  = "select id, nome, endereco, bairro, municipio, cep, telefone ";
    $sql .= " from curso_inscricao_supervisor "; 
    $sql .= " order by nome";
    $resultado = $db->Execute($sql);
    if ($resultado === false) die ("Não foi possível consultar a tabela curso_inscricao_supervisor");

    while (!$resultado->EOF) {
			$id = $resultado->fields['id'];
			$nome = $resultado->fields['nome'];
			$endereco = $resultado->fields['endereco'];
			$bairro = $resultado->fields['bairro'];
			$municipio = $resultado->fields['municipio'];
			$cep = $resultado->fields['cep'];
			$telefone = $resultado->fields['telefone'];

			$items[$nome] = $endereco;	
			
			$resultado->MoveNext();
    }
	return $items;
}

$q = strtolower($_GET["q"]);
if (!$q) return;
$items = ver_supervisor();
foreach ($items as $key=>$value) {
	if (strpos(strtolower($key), $q) !== false) {
        echo "$key|$value\n";
	}
}

?>
