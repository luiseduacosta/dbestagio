<?php
/**
 * links.php — Menu lateral.
 *
 * Inclui autentica.inc para $isAdmin ser verdadeiro apenas quando o usuário
 * logado tem role === 'admin' na tabela users. Isso garante que os links de
 * Administração (controle_db, configurações, usuários) só sejam renderizados
 * no HTML para admins — mesmo que alguém tente desviar no lado cliente.
 *
 * Para manter compatibilidade com framesets, basta trocar a referência do
 * <frame> de links.html para links.php.
 */
require_once __DIR__ . '/autentica.inc';

/**
 * Retorna o status de admin da sessão.
 *
 * A variável $isAdmin (booleano) já está definida no escopo global por
 * autentica.inc — ela é setada para `true` somente quando
 * users.role === 'admin' para o e-mail presente no cookie `usuario`.
 *
 *   $isAdmin === true  -> user é admin; bloco de Administração é exibido.
 *   $isAdmin === false -> user é aluno/supervisor/professor/inativo ou
 *                         não autenticado (esses últimos são redirecionados
 *                         para login.php por autentica.inc antes de chegar
 *                         aqui).
 */

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="pt-br">

<head>
    <title>Menu lateral</title>
    <link rel="stylesheet" type="text/css" href="lateral.css">
    <link rel="stylesheet" type="text/css" href="ClickShowHideMenu.css">
    <script type="text/javascript" src="lib/mygosumenu/ie5.js"></script>
    <script type="text/javascript" src="lib/mygosumenu/1.3/ClickShowHideMenu.js"></script>
    <style type="text/css">
        @import url("lateral.css");
    </style>

    <script type='text/javascript' src='lib/jquery.js'></script>
    <script type='text/javascript' src='lib/jquery.cookie.js'></script>
    <script type='text/javascript'>
        $(document).ready(function () {
            $('a').css('text-decoration', 'none');
            var autentica = $.cookie("usuario");
            if (autentica) {
                $("#administracao").css("display", "block");
                $("#instituicoes").css("display", "block");
                $("#supervisores").css("display", "block");
                $("#areas").css("display", "block");
                $("#visitas").css("display", "block");
                $("#alunos").css("display", "block");
                $("#mural").css("display", "block");
                $("#historico").css("display", "block");
                $("#curso").css("display", "block");
                $("#alunos_ess").css("display", "block");
            };
        });
    </script>

</head>

<body>

    <div align="center">

        <table id="click-menu1" class="click-menu">
            <tbody>
                <tr>
                    <td class="coluna_centralizada">
                        <a href="index0.html" target="_parent">
                            Entrar
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="coluna_centralizada">
                        <a href="http://localhost/wiki/doku.php?id=estagio" target="_corpo">
                            Manual do aluno</a>
                    </td>
                </tr>
                <tr>
                    <?php if ($isAdmin) { ?>
                    <td id="administracao" style="display:block">
                        <a class="box1" href="javascript:void(0)">Administração
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="administracao/configuracao.php" target="_corpo">
                                Configurações</a><br>
                            <a class="box2" href="users/exibir/listar.php" target="_corpo">
                                Usuários</a><br>
                            <a class="box2" href="users/inserir/formulario.php" target="_corpo">
                                Inserir usuário</a><br>
                        </div>
                    </td>
                    <?php } ?>
                </tr>

                <!-- Mural //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">MURAL DE ESTÁGIOS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="mural/mural_inserir.php" target="_corpo">
                                Inserir mural</a><br />
                            <a class="box2" href="mural/ver-mural.php" target="_corpo">
                                Listar mural</a><br />
                            <a class="box2" href="mural/ver_cada.php?opcao=1&indice=0" target="_corpo">
                                Ver cada mural</a><br />

                                <hr />

                            <a class="box2" href="alunos/atualizar/seleciona_termo.php" target="_corpo">
                                Termo de Compromisso</a><br>

                            <div id='historico' style='display:none'>
                                <a class="box2" href="mural/historico.php" target="_corpo">
                                    Histórico</a><br>
                            </div>

                        </div>
                    </td>
                </tr>

                <!-- Inicio de instituicao //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">INSTITUIÇÕES
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="imprimir/listagem.php" target="_corpo">
                                Imprimir catálogo</a>
                            <a class="box2" href="instituicoes/inserir.php" target="_corpo">
                                Inserir instituição</a><br>
                            <a class="box2" href="instituicoes/listar.php" target="_corpo">
                                Listar todas</a><br>
                            <a class="box2" href="areas/listar.php" target="_corpo">
                                Listar áreas</a><br>
                        </div>
                    </td>
                </tr>
                <!-- Fim de instituicao //-->

                <!-- Supervisores //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">SUPERVISORES
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">

                            <div id='supervisores' style='display:none'>
                                <a class="box2" href="supervisores/seleciona.php?opcao=modifica" target="_corpo">
                                    Modificar</a><br>
                                <a class="box2" href="supervisores/seleciona.php?opcao=cancela" target="_corpo">
                                    Excluir</a>
                                <a class="box2" href="supervisores/exibir/aluno_supervisor.php" target="_corpo">
                                    Supervisores alunos</a><br>
                                <a class="box2" href="supervisores/atualizar/seleciona_cadastro.php" target="_corpo">
                                    Cadastro</a><br>
                                <a class="box2" href="supervisores/buscar/busca.php" target="_corpo">
                                    Buscar</a><br>
                                <a class="box2" href="supervisores/exibir/seleciona.php" target="_corpo">
                                    Ver supervisor</a><br>
                                <a class="box2" href="supervisores/exibir/ver_cada.php?indice=0" target="_corpo">
                                    Ver c/supervisor</a><br>

                            </div>
                            <a class="box2" href="supervisores/inserir/form_inserir.php" target="_corpo">
                                Inserir supervisor</a><br>
                            <a class="box2" href="supervisores/exibir/listar.php" target="_corpo">
                                Listar todos</a><br>
                        </div>
                    </td>
                </tr>

                <!-- Visitas //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">VISITAS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">

                            <div id='visitas' style='display:none'>
                                <a class="box2" href="visitas/inserir.php" target="_corpo">
                                    Inserir</a><br>
                                <a class="box2" href="visitas/listar.php" target="_corpo">
                                    Editar</a><br>
                                <a class="box2" href="visitas/listar.php" target="_corpo">
                                    Excluir</a>
                            </div>

                            <a class="box2" href="visitas/listar.php" target="_corpo">
                                Listar</a><br>

                        </div>
                    </td>
                </tr>

                <!-- Turmas //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">TURMAS DE OTP
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">

                            <div id='areas' style='display:none'>
                                <a class="box2" href="areas/inserir.php" target="_corpo">
                                    Inserir</a><br>
                                <a class="box2" href="areas/seleciona.php" target="_corpo">
                                    Modificar</a><br>
                                <a class="box2" href="areas/seleciona.php?opcao=cancela" target="_corpo">
                                    Excluir</a>
                            </div>

                            <a class="box2" href="areas/listar.php" target="_corpo">
                                Listar</a><br>

                        </div>
                    </td>
                </tr>

                <!-- Alunos //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">ALUNOS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">

                            <div id='alunos' style='display:none'>
                                <a class="box2" href="alunos/inserir/seleciona_aluno.php" target="_corpo">
                                    Inserir/atualizar aluno</a><br>
                                <a class="box2" href="alunos/atualizar/seleciona.php" target="_corpo">
                                    Modificar</a><br>
                                <a class="box2" href="alunos/cancelar/seleciona.php" target="_corpo">
                                    Excluir</a><br>

                                <a class="box2" href="alunos/buscar/busca.php" target="_corpo">
                                    Buscar</a><br>
                                <a class="box2" href="alunos/ver_cada.php?indice=0" target="_corpo">
                                    Ver c/aluno</a><br>
                                <a class="box2" href="alunos/exibir/seleciona.php" target="_corpo">
                                    Ver aluno</a><br>
                            </div>

                            <a class="box2" href="alunos/listar.php" target="_corpo">
                                Listar</a><br>

                            <a class="box2" href="alunos/inserir.php" target="_corpo">
                                Inserir aluno</a><br>

                            <a class="box2" href="turnos/exibir/listar.php" target="_corpo">
                                Turnos</a><br>

                        </div>
                    </td>
                </tr>

                <!-- Inscricoes -->

                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">INSCRIÇÕES
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>

                        <div class="section">
                            <a class="box2" href="inscricoes/listar.php" target="_corpo">
                                Listar</a><br>
                        </div>
                    </td>
                </tr>


                <!-- Professores -->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">PROFESSORES
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>

                        <div class="section">
                            <a class="box2" href="professores/listar.php" target="_corpo">
                                Listar</a><br>
                            <a class="box2" href="professores/inserir.php" target="_corpo">
                                Inserir</a><br>
                        </div>
                    </td>
                </tr>

                <!-- Estagiarios -->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">ESTAGIÁRIOS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>

                        <div class="section">
                            <a class="box2" href="estagiarios/listar.php" target="_corpo">
                                Listar</a><br>
                        </div>
                    </td>
                </tr>

                <!-- Graficos //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">GRÁFICOS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="alunos/exibir/graficos.php" target="_corpo">
                                Alunos por período</a><br>
                        </div>
                    </td>
                </tr>


                <!-- Curso //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">CURSO ESTÁGIO
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="curso/capa.html" target="_corpo">
                                Apresentação</a><br />
                            <!--
    <a class="box2" href="curso/supervisores_selecionados_6.html" target="_corpo">
    Inscrição</a><br />
//-->

                            <a class="box2" href="curso/inscricao_supervisores.php" target="_corpo">
                                Inscrição</a><br />

                            <a class="box2" href="curso/inscricao_lista.php" target="_corpo">
                                Lista inscritos</a><br />

                            <a class="box2" href="curso/inscricao_lista.php?selecao=1" target="_corpo">
                                Selecionados</a><br />

                            <a class="box2" href="curso/curso_busca.html" target="_corpo">
                                Busca inscritos</a><br />

                            <a class="box2" href="http://docs.google.com/View?id=dhd8s6wp_479f78fcbg4" target="_corpo">
                                Programação</a><br />
                            <a class="box2" href="http://web.intranet.ess.ufrj.br/cursos/" target="_corpo">
                                Plataforma do curso</a><br />

                            <a class="box2" href="http://groups.google.com/forum/#!forum/supervisores-da-essufrj"
                                target="_top">
                                Lista de discuss&atilde;o</a><br />
                        </div>
                    </td>
                </tr>

                <!-- Eventos //-->
                <tr>
                    <td>
                        <a class="box1" href="javascript:void(0)">EVENTOS
                            <img src="lib/mygosumenu/1.3/images/arrow1.gif" width="8" height="8">
                        </a>
                        <div class="section">
                            <a class="box2" href="http://web.intranet.ess.ufrj.br/ocs/index.php/estagio/estagioI"
                                target="_corpo">
                                I Seminário Regional</a><br />
                        </div>
                    </td>
                </tr>

                <tr>
                    <td class="coluna_centralizada">
                        <a href="index0.html" target="_parent">
                            Sair
                        </a>
                    </td>
                </tr>

            </tbody>
        </table>
    </div>

    <script type="text/javascript">
        var clickMenu1 = new ClickShowHideMenu('click-menu1');
        clickMenu1.init();
    </script>

    <hr>

    <table style="background-color: #fff; padding: 5px;" cellspacing=0>
        <tr>
            <td style="padding-left: 5px"> <a href="http://groups.google.com/forum/#!forum/estagio_ess"
                    target="_top">Alunos em estagio da ESS/UFRJ</a> </td>
        </tr>
        <tr>
            <td style="padding-left: 5px"> <a href="http://groups.google.com/forum/#!forum/supervisores-da-essufrj"
                    target="_top">Supervisores da ESS/UFRJ</a> </td>
        </tr>
    </table>

    <hr>

    <table border="1">
        <tbody>
            <tr>
                <td class="coluna_centralizada">
                    Fale conosco
                </td>
            </tr>
            <tr>
                <td class="coluna_centralizada">
                    <a href="mailto:estagio@ess.ufrj.br?Subject=Mural de Estágio">Coordenação de estágio</a>
                </td>
            </tr>
            <!--
	<tr>
	    <td class="coluna_centralizada">
		<a href="http://codigolivre.org.br"><img src="http://codigolivre.org.br/CLlogo.php?group_id=2434" width="95" height="38" border="0" alt="Logotivo Codigolivre"></a>
	    </td>
	</tr>
//-->
        </tbody>
    </table>

</body>

</html>