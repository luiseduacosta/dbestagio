<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<style type="text/css">
@import url("../../estagio.css");
</style>
<title>Seleciona aluno</title>

{literal}
<script language="Javascript" type="text/javascript">
function seleciona() 
{
    var id_valor = document.seleciona_aluno.id_aluno.value;
    document.location.href="ver_cada.php?id_aluno=" + id_valor + "&origem=seleciona";
    return false;
}
</script>
{/literal}
</head>

<body>

<form name="seleciona_aluno" action="ver_cada.php" method="post">

<select name="id_aluno" id="id_aluno" size="1" onChange="return seleciona();">
<option value=0>Selecione aluno</option>
{section name=elemento loop=$alunos}
<option value={$alunos[elemento].id_aluno}>{$alunos[elemento].nome}</option>
{/section}
</select>
<input type="hidden" name="origem" value="seleciona">
<input type="submit" name="submit" value="Confirma">

</form>

</body>

</html>