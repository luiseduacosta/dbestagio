<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css"> 
<title>Seleciona aluno</title>
{literal}
<script language="Javascript" type="text/javascript">
function seleciona() 
{
    var id_valor = document.seleciona_aluno.id_aluno.value;
    var id_valor = getElementById("id_aluno");
    /* alert("id_valor"); */
    document.location.href="verifica.php?id_aluno=" + id_valor;
    return false;
}
</script>
{/literal}
</head>

<body>

<form name="seleciona_aluno" action="verifica.php" method="post">

<select name="id_aluno" id="id_aluno" size="1" onChange="return seleciona();">
<option value=0>Selecione aluno</option>
{section name=elemento loop=$alunos}
<option value={$alunos[elemento].id_aluno}>{$alunos[elemento].nome}</option>
{/section}
</select>

<input type="submit" name="selecionaAluno" value="Confirma">

</form>

</body>

</html>
