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
        <p><a href="../inserir/form_inserir.php">Inserir nova área</a></p>
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
                <td><a href="../exibir/ver_cada.php?area_id={$areas[i].id}">{$areas[i].area}</a></td>
                <td>
                    <a href="instituicoes.php?area_id={$areas[i].id}">Ver Instituições</a> |
                    <a href="../atualizar/modifica.php?area_id={$areas[i].id}">Editar</a>
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