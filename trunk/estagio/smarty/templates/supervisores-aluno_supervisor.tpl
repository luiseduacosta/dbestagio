<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <title>Supervisores e alunos</title>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <link href="../../estagio.css" rel="stylesheet" type="text/css">

        {literal}
        <script type="text/javascript">
        function get_periodo() {
            var periodo = document.getElementById('periodo').value;
            window.location = '?periodo=' + periodo + '&indice=0';
        }
        </script>
        {/literal}

    </head>

    <body>

    <div>
    <select name='periodo' id='periodo' size=1 onChange='get_periodo();'>
    {if !$periodo}
	<option value='0'>Seleciona período</option>
    {else}
	<option value='0'>Período: {$periodo}</option>
    {/if}
    {section name=i loop=$periodos}
    <option value='{$periodos[i]}'>{$periodos[i]}</option>
    {/section}
    </select>
    </div>

        <div>
            <table border=1>
                <caption>Supervisores e alunos</caption>
                <thead>
                <tr>

                <th><a href='?periodo={$periodo}&ordem=Supervisor'>Supervisor</a></th>
                <th><a href='?periodo={$periodo}&ordem=Cress'>Cress</a></th>
                <th><a href='?periodo={$periodo}&ordem=Super_Email'>Email</a></th>
                <th><a href='?periodo={$periodo}&ordem=Super_Celular'>Celular</a></th>
                <th><a href='?periodo={$periodo}&ordem=Super_Telefone'>Telefone</a></th>

                <th><a href='?periodo={$periodo}&ordem=Aluno'>Aluno</a></th>
                <th><a href='?periodo={$periodo}&ordem=Registro'>Registro</a></th>
                <th><a href='?periodo={$periodo}&ordem=Celular'>Celular</a></th>
                <th><a href='?periodo={$periodo}&ordem=Telefone'>Telefone</a></th>
                <th><a href='?periodo={$periodo}&ordem=Email'>Email</a></th>
                <th><a href='?periodo={$periodo}&ordem=Estagiario'>Id</a></th>
                <th><a href='?periodo={$periodo}&ordem=Periodo'>Período</a></th>

                </tr>
                <thead>
                <tbody>
                {section name=lista loop=$alunosupervisor}
                    <tr>

                    <td>
                    {if $alunosupervisor[lista].supervisor} 
                        <a href='../exibir/ver_cada.php?id_supervisor={$alunosupervisor[lista].id_supervisor}'>{$alunosupervisor[lista].supervisor}</a>
                    {else}
                        {$alunosupervisor[lista].supervisor}
                    {/if}
                    </td>
                    <td>
                    {$alunosupervisor[lista].cress}
                    </td>
                    <td>
                    {$alunosupervisor[lista].super_email}
                    </td>
                    <td>
                    {$alunosupervisor[lista].super_celular}
                    </td>
                    <td>
                    {$alunosupervisor[lista].super_telefone}
                    </td>

                    <td>
                    <a href=../../alunos/exibir/ver_cada.php?id_aluno={$alunosupervisor[lista].id_aluno}>{$alunosupervisor[lista].aluno}</a>
                    </td>
                    <td>
                    {$alunosupervisor[lista].registro}
                    </td>
                    <td>
                    {$alunosupervisor[lista].celular}
                    </td>
                    <td>
                    {$alunosupervisor[lista].telefone}
                    </td>
                    <td>
                    {$alunosupervisor[lista].email}
                    </td>
                    <td>
                    {$alunosupervisor[lista].id_estagiario}
                    </td>
                    <td>
                    {$alunosupervisor[lista].periodo}
                    </td>

                    </tr>
                {/section}    
                </tbody>
            </table>
        </div>
        
    </body>
</html>
