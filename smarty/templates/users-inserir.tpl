<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Inserir usuário</title>
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
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
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Administração · Usuários</p>
			<h1>Inserir usuário<span>Crie uma nova conta de acesso ao sistema</span></h1>
		</div>
	</header>

	<div class="action-row" style="margin-top:0;">
		<a class="btn btn-secondary" href="listar.php">Voltar para a listagem</a>
	</div>

	<div id="aviso_categoria_role" class="form-alert" role="alert"></div>

	<form name="form_usuario" action="inserir.php" method="post">

	<table class="ficha card-table">
	<tbody>

	<tr>
	<td><label for="email">E-mail *</label></td>
	<td><input type="text" id="email" name="email" size="40" maxlength="50" value="{$v.email}"></td>
	</tr>

	<tr>
	<td><label for="nome">Nome *</label></td>
	<td><input type="text" id="nome" name="nome" size="45" maxlength="128" value="{$v.nome}"></td>
	</tr>

	<tr>
	<td><label for="role">Role (perfil) *</label></td>
	<td>
	<select name="role" id="role" size="1">
	{foreach key=val item=label from=$opts.roles}
		<option value="{$val}" {if $val == $v.role}selected="selected"{/if}>{$label}</option>
	{/foreach}
	</select>
	&nbsp;<span class="card-note">(altera automaticamente a Categoria)</span>
	</td>
	</tr>

	<tr>
	<td><label for="categoria">Categoria (1-4) *</label></td>
	<td>
	<select name="categoria" id="categoria" size="1">
	{foreach key=val item=label from=$opts.categorias}
		<option value="{$val}" {if $val == $v.categoria}selected="selected"{/if}>{$label}</option>
	{/foreach}
	</select>
	&nbsp;<span class="card-note">(altera automaticamente o Role)</span>
	</td>
	</tr>

	<tr>
	<td><label for="identificacao">Identificação (DRE, Siape, CRESS)</label></td>
	<td><input type="text" id="identificacao" name="identificacao" size="12" maxlength="9" value="{$v.identificacao}"></td>
	</tr>

	<tr>
	<td><label for="ativo">Situação</label></td>
	<td>
	<select name="ativo" id="ativo" size="1">
	{foreach key=val item=label from=$opts.ativo_opcoes}
		<option value="{$val}" {if $val == $v.ativo}selected="selected"{/if}>{$label}</option>
	{/foreach}
	</select>
	</td>
	</tr>

	<tr>
	<td><label for="password">Senha *</label></td>
	<td><input type="password" id="password" name="password" size="20" maxlength="72"></td>
	</tr>

	<tr class="rodape">
	<td colspan="2">
	<button type="submit" class="btn">Salvar</button>
	<input type="button" value="Cancelar" onclick="history.back()">
	</td>
	</tr>

	</tbody>
	</table>

	</form>

</main>

</body>
</html>
