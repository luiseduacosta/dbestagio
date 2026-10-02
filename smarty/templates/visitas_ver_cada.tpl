<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver visita</title>
</head>
<body>

<div align="center">
<h3>Dados da visita</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>Data</th><td>{$v.data}</td></tr>
<tr><th>Instituição</th><td>{$nome_instituicao}</td></tr>
<tr><th>Professor</th><td>{$nome_professor}</td></tr>
<tr><th>Motivo</th><td>{$v.motivo}</td></tr>
<tr><th>Responsável</th><td>{$v.responsavel}</td></tr>
<tr><th>Avaliação</th><td>{$v.avaliacao}</td></tr>
<tr><th>Descrição</th><td>{$v.descricao}</td></tr>
</tbody>
</table>

<div align="center">
<p>
<a href="../atualizar/modifica.php?id={$v.id}">Editar</a> |
<a href="javascript:history.back()">Voltar</a> |
<a href="../cancelar/cancela.php?id={$v.id}" onclick="return confirm('Excluir esta visita?');">Excluir</a>
</p>
</div>

</body>
</html>