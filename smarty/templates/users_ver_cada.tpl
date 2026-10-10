<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>Ver usuário</title>
</head>
<body>

<div align="center">
<h3>Dados do usuário</h3>
</div>

<table class="ficha">
<tbody>
<tr><th>E-mail</th><td>{$u.email}</td></tr>
<tr><th>Nome</th><td>{$u.nome}</td></tr>
<tr><th>Role</th><td>{$u.role_texto}</td></tr>
<tr><th>Categoria</th><td>{$u.categoria_texto}</td></tr>
<tr><th>Identificação</th><td>{$u.identificacao}</td></tr>
<tr><th>Situação</th><td>{if $u.ativo == 1}Ativo{else}Inativo{/if}</td></tr>
{if $u.aluno}
<tr><th colspan="2" style="background:#e8f0fe">Dados do Aluno associado</th></tr>
<tr><th>ID Aluno</th><td>{$u.aluno.id}</td></tr>
{if $u.aluno.nome}        <tr><th>Nome</th><td>{$u.aluno.nome}</td></tr>{/if}
{if $u.aluno.registro}    <tr><th>Registro</th><td>{$u.aluno.registro}</td></tr>{/if}
{if $u.aluno.cpf}         <tr><th>CPF</th><td>{$u.aluno.cpf}</td></tr>{/if}
{if $u.aluno.email}       <tr><th>E-mail</th><td>{$u.aluno.email}</td></tr>{/if}
{if $u.aluno.turno_nome}  <tr><th>Turno</th><td>{$u.aluno.turno_nome}</td></tr>{/if}
{if $u.aluno.telefone}    <tr><th>Telefone</th><td>{$u.aluno.telefone}</td></tr>{/if}
{if $u.aluno.endereco}    <tr><th>Endereço</th><td>{$u.aluno.endereco}</td></tr>{/if}
{/if}
{if $u.supervisor}
<tr><th colspan="2" style="background:#e8f5e9">Dados do Supervisor associado</th></tr>
<tr><th>ID Supervisor</th><td>{$u.supervisor.id}</td></tr>
{if $u.supervisor.nome}     <tr><th>Nome</th><td>{$u.supervisor.nome}</td></tr>{/if}
{if $u.supervisor.cpf}      <tr><th>CPF</th><td>{$u.supervisor.cpf}</td></tr>{/if}
{if $u.supervisor.cress}    <tr><th>CRESS</th><td>{$u.supervisor.cress} / {$u.supervisor.regiao}</td></tr>{/if}
{if $u.supervisor.email}    <tr><th>E-mail</th><td>{$u.supervisor.email}</td></tr>{/if}
{if $u.supervisor.cargo}    <tr><th>Cargo</th><td>{$u.supervisor.cargo}</td></tr>{/if}
{if $u.supervisor.escola}   <tr><th>Escola de formação</th><td>{$u.supervisor.escola}</td></tr>{/if}
{if $u.supervisor.telefone || $u.supervisor.celular}
<tr><th>Contato</th><td>
{if $u.supervisor.codigo_telefone}({$u.supervisor.codigo_telefone}) {/if}{$u.supervisor.telefone}
{if $u.supervisor.celular} / {if $u.supervisor.codigo_celular}({$u.supervisor.codigo_celular}) {/if}{$u.supervisor.celular}{/if}
</td></tr>
{/if}
{if $u.supervisor.municipio}<tr><th>Município</th><td>{$u.supervisor.endereco}{if $u.supervisor.bairro} - {$u.supervisor.bairro}{/if}{if $u.supervisor.municipio} - {$u.supervisor.municipio}{/if}</td></tr>{/if}
{/if}
{if $u.professor}
<tr><th colspan="2" style="background:#fff3e0">Dados do Professor associado</th></tr>
<tr><th>ID Professor</th><td>{$u.professor.id}</td></tr>
{if $u.professor.nome}     <tr><th>Nome</th><td>{$u.professor.nome}</td></tr>{/if}
{if $u.professor.cpf}      <tr><th>CPF</th><td>{$u.professor.cpf}</td></tr>{/if}
{if $u.professor.siape}    <tr><th>SIAPE</th><td>{$u.professor.siape}</td></tr>{/if}
{if $u.professor.cress}    <tr><th>CRESS</th><td>{$u.professor.cress} / {$u.professor.regiao}</td></tr>{/if}
{if $u.professor.email}    <tr><th>E-mail</th><td>{$u.professor.email}</td></tr>{/if}
{if $u.professor.departamento}<tr><th>Departamento</th><td>{$u.professor.departamento}</td></tr>{/if}
{if $u.professor.tipocargo} <tr><th>Tipo de cargo</th><td>{$u.professor.tipocargo}</td></tr>{/if}
{if $u.professor.dataingresso}<tr><th>Data de ingresso</th><td>{$u.professor.dataingresso}</td></tr>{/if}
{if $u.professor.curriculolattes}<tr><th>Curriculo Lattes</th><td><a href="{$u.professor.curriculolattes}" target="_blank">{$u.professor.curriculolattes}</a></td></tr>{/if}
{/if}
</tbody>
</table>

<div align="center">
<p>
<a href="../atualizar/modifica.php?id={$u.id}">Editar</a> |
<a href="javascript:history.back()">Voltar</a> |
<a href="../cancelar/cancela.php?id={$u.id}" onclick="return confirm('Excluir este usuário?');">Excluir</a>
</p>
</div>

</body>
</html>