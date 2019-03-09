<html>
    <link href='../../css/tcc.css' rel='stylesheet' type='text/css'>
    {literal}
    <script language='JavaScript' type='text/javascript' src='http://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js'></script>
    <script language='JavaScript' type='text/javascript'>
    $(function() {
        $('#periodo').change(function() {
            var periodo = $(this).val();
            /* alert(periodo); */
            window.location.href='?periodo=' + periodo;
              
            });
        })
    </script>
    {/literal}
<body>

<h1>Estudantes sem professor</h1>
{if $funcao == 'sem_professor'}
    <p>Estudantes sem professor período: {$periodo}</p>
    <table>
        {section name=i loop=$aluno_sem_professor}
            <tr>
                <td>{$aluno_sem_professor[i].registro}</td>
                <td><a href="../alunos/exibir/ver_cada.php?id_aluno={$aluno_sem_professor[i].id}">{$aluno_sem_professor[i].nome}</a></td>
                <td>{$aluno_sem_professor[i].celular}</td>
                <td>{$aluno_sem_professor[i].email}</td>
            </tr>
        {/section}
    </table>
<a href="?">Retorna</a>
{/if}
    
<table>
    <thead>
        <tr>
            <th>Período</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        {section name=i loop=$sem_professor}
        <tr>
            <td>{$sem_professor[i].periodo}</td>
            <td><a href="?funcao=sem_professor&periodo={$sem_professor[i].periodo}">{$sem_professor[i].quantidade}</a></td>
        </tr>
        {/section}
    </tbody>
</table>
   
<h1>Estudantes sem supervisor</h1>
{if $funcao == 'sem_supervisor'}
<p>Estudantes sem supervisor período: {$periodo}</p>
    <table>
        {section name=i loop=$aluno_sem_supervisor}
            <tr>
                <td>{$aluno_sem_supervisor[i].registro}</td>
                <td><a href="../alunos/exibir/ver_cada.php?id_aluno={$aluno_sem_supervisor[i].id}">{$aluno_sem_supervisor[i].nome}</a></td>
                <td>{$aluno_sem_supervisor[i].celular}</td>
                <td>{$aluno_sem_supervisor[i].email}</td>
            </tr>
        {/section}
    </table>
<a href="?">Retorna</a>
{/if}

<table>
    <thead>
        <tr>
            <th>Período</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        {section name=i loop=$sem_supervisor}
        <tr>
            <td>{$sem_supervisor[i].periodo}</td>
            <td><a href="?funcao=sem_supervisor&periodo={$sem_supervisor[i].periodo}">{$sem_supervisor[i].quantidade}</a></td>
        </tr>
        {/section}
    </tbody>
</table>

<h1>Estudantes sem termo de compromisso</h1>
<p>Estudantes que cursaram estágio no período anterior e que estão sem termo de compromisso no período {$periodo}</p>
<form name='sem_tc' id='sem_tc' action='#'>
    <select name='periodo' id='periodo'>
        {if $periodo}
        <option value='{$periodo}'>{$periodo}</option>
        {/if}
        {section name=i loop=$periodos}
        <option value={$periodos[i].periodo}>{$periodos[i].periodo}</option>
        {/section}
    </select>
</form>

<table>
    <thead>
        <tr>
            <th>Id</th>
            <th>Registro</th>
            <th>Nome</th> 
            <th>Celular</th>
            <th>Email</th>
        </tr>
    </thead>
    <tbody>
        {section name=i loop=$sem_tc}
        <tr>
            <td>{$sem_tc[i].id}</td>
            <td>{$sem_tc[i].registro}</td>
            <td><a href="../alunos/exibir/ver_cada.php?id_aluno={$sem_tc[i].id}">{$sem_tc[i].nome}</a></td>            
            <td>{$sem_tc[i].celular}</td>
            <td>{$sem_tc[i].email}</td>
        </tr>
        {/section}
    </tbody>
</table>   
<a href="?">Retorna</a>

<h1>Estudantes sem devolução do Termo de Compromisso</h1>
{if $funcao == "sem_devolucao"}
<p>Estudantes que não entregaram o Termo de Compromisso no período: {$periodo}</p>
    <table>
        {section name=i loop=$aluno_sem_devolucao}
            <tr>
                <td>{$aluno_sem_devolucao[i].registro}</td>
                <td><a href="../alunos/exibir/ver_cada.php?id_aluno={$aluno_sem_devolucao[i].id}">{$aluno_sem_devolucao[i].nome}</a></td>
                <td>{$aluno_sem_devolucao[i].celular}</td>
                <td>{$aluno_sem_devolucao[i].email}</td>
            </tr>
        {/section}
    </table>
<a href="?">Retorna</a>
{/if}    

<table>
    <thead>
        <tr>
            <th>Período</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        {section name=i loop=$sem_devolucao}
        <tr>
            <td>{$sem_devolucao[i].periodo}</td>
            <td><a href="?funcao=sem_devolucao&periodo={$sem_devolucao[i].periodo}">{$sem_devolucao[i].quantidade}</a></td>
        </tr>
        {/section}
    </tbody>
</table>

<h1>Estudantes sem estágio</h1>
{if $funcao == "sem_estagio"}
    <p>Estudantes que buscaram estágio no mural no período: {$periodo} e não estão cursando estágio no momento</p>
    <table>
        {section name=i loop=$aluno_sem_estagio}
            <tr>
                <td>{$aluno_sem_estagio[i].registro}</td>
                <td><a href="../mural/ver-aluno.php?id_aluno={$aluno_sem_estagio[i].registro}">{$aluno_sem_estagio[i].nome}</a></td>
                <td>{$aluno_sem_estagio[i].celular}</td>
                <td>{$aluno_sem_estagio[i].email}</td>
            </tr>
        {/section}
    </table>
<a href="?">Retorna</a>        
{/if}
 
<table>
    <thead>
        <tr>
            <th>Período</th>
            <th>Quantidade</th>
        </tr>
    </thead>
    <tbody>
        {section name=i loop=$sem_estagio}
        <tr>
            <td>{$sem_estagio[i].periodo}</td>
            <td><a href="?funcao=sem_estagio&periodo={$sem_estagio[i].periodo}">{$sem_estagio[i].quantidade}</a></td>
        </tr>
        {/section}
    </tbody>
</table>
    
</body>
</html>