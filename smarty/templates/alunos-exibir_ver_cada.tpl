<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">

<head>
    <meta content="text/html; charset=UTF-8" http-equiv="content-type">
    <title>Ver cada aluno</title>
    <meta content="author" name="Luis Acosta">
    <style type="text/css">
        @import url("../../estagio.css");
    </style>

    {literal}
    <script type="text/javascript" src="../../lib/jquery.js"></script>
    <script type="text/javascript" src="../../lib/jquery.maskedinput-1.2.1.pack.js"></script>
    <script type="text/javascript">
        $(function () {
            $("#telefone").mask("(99)9999.9999");
            $("#celular").mask("(99)99999.9999");
            $("#cep").mask("99999-999");
            $("#cpf").mask("999999999-99");
        });
    </script>

    <script type="text/javascript">

        function get_periodo() {
            var periodo = document.getElementById('periodo').value;
            var id_periodo = document.getElementById('id_periodo').value;
            window.location = 'ver_cada.php?periodo=' + periodo + '&indice=0';
        }

    </script>

    {/literal}

</head>

<body style="direction: ltr;">

    {include file='cabecalho.tpl'}

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

    <!-- ###CORPO### -->

    <div align="center">

        <table id="navegacao">
            <caption>Alunos</caption>
            <tbody>
                <tr>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="primeiro">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value="Primeiro">
                        </form>
                    </td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="menos_10">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value=" - 10 ">
                        </form>
                    </td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="retroceder">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value="Retroceder">
                        </form>
                    </td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="avancar">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value="Avançar">
                        </form>
                    </td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="mais_10">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value=" + 10 ">
                        </form>
                    </td>

                    <td>
                        <form action="#" method="post">
                            <input type="hidden" name="indice" value="{$indice}">
                            <input type="hidden" name="botao" value="ultimo">
                            <input type="hidden" name="periodo" id="id_periodo" value='{$periodo}'>
                            <input type="hidden" name="aluno_id" value="">
                            <input type="submit" name="submit" value="Último">
                        </form>
                    </td>

                </tr>
            </tbody>
        </table>
    </div>

    <div align="center">
        <table border="1" width="98%">
            <tbody>

                <tr>
                    <th width='80%'>Aluno {* $aluno_id *}</th>
                    {if $isAdmin}
                    <th width='20%'><a href='../cancelar/ver_cancela.php?aluno_id={$aluno_id}'>Excluir registro</a></th>
                    {/if}
                </tr>
            </tbody>
        </table>

        <div align="center">
            <table border="1" width="98%">
                <tbody>

                    {if $smarty.cookies.usuario_senha}
                    <tr>
                        <td width='20%'>Registro:</td>
                        <td width='80%'>{$registro}</td>
                    </tr>
                    {/if}

                    <tr>
                        <td width='20%'>Nome:</td>
                        <td>{$nome}</td>
                    </tr>

                    <tr>
                        <td>Ingresso:</td>
                        {if !$periodo_intro}
                        <td>s/d</td>
                        {else}
                        <td>{$periodo_intro}&nbsp; - Período atual: {$tempo_cursado}o.</td>
                        {/if}
                    </tr>

                    {if $smarty.cookies.usuario_senha}
                    <tr>
                        <td>E-mail</td>
                        <td>{$email}</td>
                    </tr>
                    {/if}

                    {if $smarty.cookies.usuario_senha}
                    <tr>
                        <td>Telefone</td>
                        <td>({$codigo_telefone}){$telefone}</td>
                    </tr>

                    <tr>
                        <td>Celular</td>
                        <td>({$codigo_celular}){$celular}</td>
                    </tr>

                    <tr>
                        <td>Observa&ccedil;&otilde;es</td>
                        <td>{$observacoes}</td>
                    </tr>
                    {/if}

                </tbody>
            </table>
        </div>

        <div align="center">
            <table border="1" width="98%">
                <tbody>

                    <tr>
                        {if $isAdmin}
                        <th>Editar</th>
                        {/if}
                        <th>Período</th>
                        <th>TC</th>
                        <th>Nível</th>
                        <th>Turno</th>
                        <th>Instituição</th>
                        <th>Supervisor</th>
                        <th>Professor</th>
                        {if $isAdmin}
                        <th>Nota</th>
                        <th>ch</th>
                        {/if}
                    </tr>

                    {section name=estagio loop=$historico_estagio}

                    <tr>
                        {if $isAdmin}
                        <td><a
                                href=../atualizar/atualiza_estagio.php?estagiario_id={$historico_estagio[estagio].estagiario_id}&aluno_id={$aluno_id}>Editar</a>
                        </td>
                        {/if}
                        <td style="text-align:center">{$historico_estagio[estagio].periodo}</td>
                        <td style="text-align:center">{$historico_estagio[estagio].tc}</td>
                        <td style="text-align:center">{$historico_estagio[estagio].nivel}</td>
                        <td style="text-align:center">{$historico_estagio[estagio].turno}</td>
                        <td>
                            {if $isAdmin}
                            <a
                                href="../../instituicoes/exibir/ver_cada.php?instituicao_id={$historico_estagio[estagio].instituicao_id}">{$historico_estagio[estagio].instituicao}</a>
                            {else}
                            {$historico_estagio[estagio].instituicao}
                            {/if}
                        </td>

                        {if $historico_estagio[estagio].supervisor_id eq 0}
                        <td>-</td>
                        {else}
                        <td>
                            {if $isAdmin}
                            <a
                                href="../../assistentes/exibir/ver_cada.php?supervisor_id={$historico_estagio[estagio].supervisor_id}">{$historico_estagio[estagio].supervisor}</a>
                            {else}
                            {$historico_estagio[estagio].supervisor}
                            {/if}
                        </td>
                        {/if}

                        <td>{$historico_estagio[estagio].professor}</td>
                        {if $isAdmin}
                        <td style="text-align:center">{$historico_estagio[estagio].nota}</td>
                        <td style="text-align:center">{$historico_estagio[estagio].ch}</td>
                        {/if}
                    </tr>

                    {/section}

                    {if $isAdmin}
                    <tr>
                        <td colspan="10" style="text-align: center">
                            <form action="../atualizar/atualiza.php" method="post">
                                <input type="hidden" name="aluno_id" value="{$aluno_id}">
                                <input type="hidden" name="origem" value="{$origem}">
                                <input type="submit" name="submit"
                                    value="Clique aqui para modificar dados do aluno ou atualizar/inserir estágios">
                            </form>
                        </td>
                    </tr>
                    {/if}

                </tbody>
            </table>
        </div>

        <!-- ###CORPO### -->

        {include file='rodape.tpl'}

</body>

</html>