<<<<<<< HEAD
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
    <meta charset="utf-8">
    <link href="../../libjs/datatables/dataTables.min.css" rel="stylesheet" type="text/css">
    <link href="../../estagio.css" rel="stylesheet" type="text/css">
    <title>Listar Áreas</title>
</head>

<body>

    <a href="javascript:history.back();">Voltar</a><br>

    <div align="center">
        <p><a href="../../areas/inserir.php">Inserir nova área</a></p>
    </div>

    <div align="center">
        <h3>Lista de Áreas</h3>
    </div>

    <table id="areas" class="display">
        <thead>
            <tr>
                <th>Id</th>
                <th>Área</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>

            {section name=i loop=$areas}
            <tr>
                <td class="coluna_direita">{$areas[i].id}</td>
                <td><a href="../../areas/ver_cada.php?area_id={$areas[i].id}">{$areas[i].area}</a></td>
                <td>
                    <a href="../../areas/instituicoes.php?area_id={$areas[i].id}">Ver Instituições</a> |
                    <a href="../../areas/atualizar.php?area_id={$areas[i].id}">Editar</a>
                </td>
            </tr>
            {/section}

        </tbody>
    </table>

    {literal}
    <script src="../../libjs/datatables/jquery-3.7.1.min.js"></script>
    <script src="../../libjs/datatables/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#areas').DataTable({
                language: { url: '../../libjs/datatables/pt-BR.json' },
                pageLength: 25,
                lengthMenu: [10, 25, 50, 100],
                order: []
            });
        });
    </script>
    {/literal}

</body>

</html>
=======
<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Listar Áreas</title>
<link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
<link rel="stylesheet" href="../estagio.css">
<link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">

<main class="mural-page">

	<header class="page-head">
		<div class="page-heading">
			<p class="eyebrow">Coordenação de Estágio · ESS/UFRJ</p>
			<h1>Áreas<span>Áreas de estágio cadastradas</span></h1>
		</div>
	</header>

	<div class="action-row">
		<a class="action-btn" href="inserir.php">Inserir nova área</a>
	</div>

	<section class="table-shell" aria-label="Áreas">

		<table id="areas" class="display dt-table">
		<thead>
			<tr>
				<th>Id</th>
				<th>Área</th>
				<th data-orderable="false">Ações</th>
			</tr>
		</thead>
		<tbody>

			{section name=i loop=$areas}
			<tr>
				<td class="coluna_direita">{$areas[i].id}</td>
				<td class="inst-name"><a href="ver_cada.php?area_id={$areas[i].id}">{$areas[i].area}</a></td>
				<td>
					<span class="action-links">
					<a href="instituicoes.php?area_id={$areas[i].id}">Ver Instituições</a>
					<a href="atualizar.php?area_id={$areas[i].id}">Editar</a>
					</span>
				</td>
			</tr>
			{/section}

		</tbody>
		</table>

	</section>

</main>

{literal}
<script src="../libjs/datatables/jquery-3.7.1.min.js"></script>
<script src="../libjs/datatables/dataTables.min.js"></script>
<script>
	$(document).ready(function () {
		$('#areas').DataTable({
			language: { url: '../libjs/datatables/pt-BR.json' },
			pageLength: 25,
			lengthMenu: [10, 25, 50, 100],
			order: []
		});
	});
</script>
{/literal}

</body>

</html>
>>>>>>> 73b82345dbc2a4ee8d41cc4ec84448ffcf7e91fa
