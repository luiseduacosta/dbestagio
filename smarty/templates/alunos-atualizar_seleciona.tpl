<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../estagio.css" rel="stylesheet" type="text/css"> 
<title>Seleciona aluno</title>

<style type="text/css">
@import url("../../estagio.css");
</style>

{literal}
<script language="Javascript" type="text/javascript">
function seleciona() {
    var id_valor = document.seleciona_aluno.aluno_id.value;
    document.location.href="atualiza.php?aluno_id=" + id_valor;
    return false;
}
</script>
{/literal}
</head>

<body>

<!--
{section name=elemento loop=$alunos}
<p>{$alunos.id_aluno} {$alunos.nome}</p>
{/section}
//-->

<form name="seleciona_aluno" action="atualiza.php" method="post">

<select name="aluno_id" id="aluno_id" size="1" onChange="return seleciona();">
<option value=0>Selecione aluno</option>
{section name=elemento loop=$alunos}
<option value={$alunos[elemento].aluno_id}>{$alunos[elemento].nome}</option>
{/section}
</select>

<input type="submit" name="submit" value="Confirma">

</form>

</body>

</html>