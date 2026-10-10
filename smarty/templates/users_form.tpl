<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
	"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta charset="utf-8">
<link href="../../estagio.css" rel="stylesheet" type="text/css">
<title>{$titulo}</title>
{literal}
<script type="text/javascript">
// Tabela categória (1..4) -> role. Altera um dos dois selects automaticamente
// para manter o par compatível.
var categoriaParaRole = {
    '1': 'admin',
    '2': 'aluno',
    '3': 'professor',
    '4': 'supervisor'
};
var roleParaCategoria = {
    'admin':      '1',
    'aluno':      '2',
    'professor':  '3',
    'supervisor': '4'
};
var categoriaLabel = {
    '1': 'Administrador',
    '2': 'Aluno',
    '3': 'Professor',
    '4': 'Supervisor'
};

function sincronizarRole() {
    var selCategoria = document.forms["form_usuario"]["categoria"];
    var selRole      = document.forms["form_usuario"]["role"];
    var cat = selCategoria.value;
    if (categoriaParaRole[cat]) {
        selRole.value = categoriaParaRole[cat];
    }
    atualizaAviso('');
}
function sincronizarCategoria() {
    var selCategoria = document.forms["form_usuario"]["categoria"];
    var selRole      = document.forms["form_usuario"]["role"];
    var r = selRole.value;
    if (roleParaCategoria[r]) {
        selCategoria.value = roleParaCategoria[r];
    }
    atualizaAviso('');
}
function atualizaAviso(msg) {
    var el = document.getElementById('aviso_categoria_role');
    if (!el) return;
    el.innerHTML = msg;
}
function validarAntesDeEnviar() {
    var cat  = document.forms["form_usuario"]["categoria"].value;
    var r    = document.forms["form_usuario"]["role"].value;
    if (categoriaParaRole[cat] !== r) {
        atualizaAviso(
            'Combinação inválida: Categoria ' + cat + ' (' + categoriaLabel[cat] +
            ') deve ser usada com Role = "' + categoriaParaRole[cat] + '".'
        );
        return false;
    }
    return true;
}
document.addEventListener('DOMContentLoaded', function () {
    var f = document.forms["form_usuario"];
    if (f) {
        f["categoria"].addEventListener('change', sincronizarRole);
        f["role"].addEventListener('change', sincronizarCategoria);
        f.addEventListener('submit', validarAntesDeEnviar);
    }
});
</script>
{/literal}
</head>
<body>

<div align="center">
<h3>{$titulo}</h3>

<div id="aviso_categoria_role" style="color:#b00;font-weight:bold;margin-bottom:8px"></div>

<form name="form_usuario" action="{$acao}" method="post">

{if $v.id}
<input type="hidden" name="id" value="{$v.id}">
{/if}

<table border="1">
<tbody>

<tr>
<td>E-mail *</td>
<td><input type="text" name="email" size="40" maxlength="50" value="{$v.email}"></td>
</tr>

<tr>
<td>Nome *</td>
<td><input type="text" name="nome" size="45" maxlength="128" value="{$v.nome}"></td>
</tr>

<tr>
<td>Role (perfil) *</td>
<td>
<select name="role" size="1">
{foreach key=val item=label from=$opts.roles}
	<option value="{$val}" {if $val == $v.role}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
&nbsp;<span style="font-size:85%;color:#666">(altera automaticamente a Categoria)</span>
</td>
</tr>

<tr>
<td>Categoria (1-4) *</td>
<td>
<select name="categoria" size="1">
{foreach key=val item=label from=$opts.categorias}
	<option value="{$val}" {if $val == $v.categoria}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
&nbsp;<span style="font-size:85%;color:#666">(altera automaticamente o Role)</span>
</td>
</tr>

<tr>
<td>Identificação (DRE, Siape, CRESS)</td>
<td><input type="text" name="identificacao" size="12" maxlength="9" value="{$v.identificacao}"></td>
</tr>

<tr>
<td>Situação</td>
<td>
<select name="ativo" size="1">
{foreach key=val item=label from=$opts.ativo_opcoes}
	<option value="{$val}" {if $val == $v.ativo}selected="selected"{/if}>{$label}</option>
{/foreach}
</select>
</td>
</tr>

<tr>
<td>Senha {if !$e_edicao}*{/if}</td>
<td><input type="password" name="password" size="20" maxlength="72">
{if $e_edicao}<br><span style="font-size:80%;font-style:italic">Deixe em branco para manter a senha atual.</span>{/if}
</td>
</tr>

<tr class="rodape">
<td colspan="2" class="coluna_centralizada">
<input type="submit" value="Salvar">
<input type="button" value="Cancelar" onclick="history.back()">
</td>
</tr>

</tbody>
</table>

</form>

</div>

</body>
</html>