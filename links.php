<?php
/**
 * links.php — Sidebar menu partial.
 * Included by index.php. Expects $isAdmin, $usuario_logado and $usuario_nome to be set.
 *
 * Authorization rules:
 *   - Unauthenticated: only "Mural → Listar" and "Eventos" (external links).
 *   - Aluno:           "Mural → Listar / Ver cada / Termo de Compromisso",
 *                      "Eventos" and "Manual do aluno" only.
 *   - Professor:       "Mural → Listar / Ver cada", "Professores" (Listar +
 *                      o próprio cadastro), "Estagiários", "Eventos" and
 *                      "Manual do aluno" only.
 *   - Supervisor:      read-only menu — "Mural → Listar / Ver cada",
 *                      "Instituições" (listar), "Supervisores" (listar /
 *                      ver), "Alunos" (listar / ver / buscar), "Inscrições",
 *                      "Professores" (listar), "Estagiários", "Gráficos",
 *                      "Curso Estágio", "Eventos" and "Manual do aluno".
 *                      Não cadastra, edita nem exclui (links de escrita
 *                      ocultos; os endpoints também barram).
 *   - Admin:           full menu, incluindo "Visitas".
 *
 * Observação: a seção "Turmas de OTP" foi removida do menu.
 */
if (!isset($isAdmin))        { $isAdmin = false; }
if (!isset($isAluno))        { $isAluno = false; }
if (!isset($isProfessor))    { $isProfessor = false; }
if (!isset($isSupervisor))   { $isSupervisor = false; }
if (!isset($usuario_logado)) { $usuario_logado = false; }
if (!isset($usuario_professor_id)) { $usuario_professor_id = 0; }
?>
<nav class="sidebar-nav">

<?php if ($isAdmin) { ?>
<details>
  <summary>Administração</summary>
  <a href="configuracoes/ver_cada.php" target="_corpo">Configurações</a>
  <a href="users/listar.php" target="_corpo">Usuários</a>
  <a href="users/inserir.php" target="_corpo">Inserir usuário</a>
</details>
<?php } ?>

<?php if (isset($imprimindo) && $imprimindo) { ?>
<details open>
  <summary>Impersonação ativa</summary>
  <a href="users/desassumir.php" target="_top">Voltar como admin</a>
</details>
<?php } ?>

<details>
  <summary>Mural de Estágios</summary>
  <a href="mural/mural-listar.php" target="_corpo">Listar mural</a>
<?php if ($usuario_logado) { ?>
<?php if ($isAdmin) { ?>
  <a href="mural/mural-inserir.php" target="_corpo">Inserir mural</a>
<?php } ?>
  <a href="mural/mural-ver_cada.php?opcao=1&amp;indice=0" target="_corpo">Ver cada mural</a>
<?php if (!$isProfessor) { ?>
  <hr>
  <a href="alunos/atualizar/seleciona_termo.php" target="_corpo">Termo de Compromisso</a>
<?php } ?>
<?php if (!$isAluno && !$isProfessor) { ?>
  <a href="mural/historico.php" target="_corpo">Histórico</a>
<?php } ?>
<?php } ?>
</details>

<?php if ($usuario_logado && !$isAluno && !$isProfessor) { ?>

<details>
  <summary>Instituições</summary>
  <a href="imprimir/listagem.php" target="_corpo">Imprimir catálogo</a>
  <a href="instituicoes/listar.php" target="_corpo">Listar todas</a>
<?php if ($isAdmin) { ?>
  <a href="instituicoes/inserir.php" target="_corpo">Inserir instituição</a>
<?php } ?>
  <a href="areas/listar.php" target="_corpo">Listar áreas</a>
</details>

<details>
  <summary>Supervisores</summary>
  <a href="supervisores/listar.php" target="_corpo">Listar todos(as)</a>
  <a href="supervisores/ver_cada.php?indice=0" target="_corpo">Ver c/supervisor</a>
<?php if ($isAdmin) { ?>
  <a href="supervisores/inserir.php" target="_corpo">Inserir supervisor</a>
<?php } ?>
</details>

<?php if ($isAdmin) { ?>
<details>
  <summary>Visitas</summary>
  <a href="visitas/listar.php" target="_corpo">Listar</a>
  <a href="visitas/inserir.php" target="_corpo">Inserir</a>
  <a href="visitas/listar.php" target="_corpo">Editar</a>
  <a href="visitas/listar.php" target="_corpo">Excluir</a>
</details>
<?php } ?>

<details>
  <summary>Alunos</summary>
  <a href="alunos/listar.php" target="_corpo">Listar</a>
<?php if ($isAdmin) { ?>
  <a href="alunos/inserir/seleciona_aluno.php" target="_corpo">Inserir/atualizar aluno</a>
  <a href="alunos/atualizar/seleciona.php" target="_corpo">Modificar</a>
  <a href="alunos/cancelar/seleciona.php" target="_corpo">Excluir</a>
<?php } ?>
  <a href="alunos/buscar/busca.php" target="_corpo">Buscar</a>
  <a href="alunos/ver_cada.php?indice=0" target="_corpo">Ver c/aluno</a>
  <a href="alunos/exibir/seleciona.php" target="_corpo">Ver aluno</a>
<?php if ($isAdmin) { ?>
  <a href="alunos/inserir.php" target="_corpo">Inserir aluno</a>
<?php } ?>
  <a href="turnos/listar.php" target="_corpo">Turnos</a>
</details>

<details>
  <summary>Inscrições</summary>
  <a href="inscricoes/listar.php" target="_corpo">Listar</a>
</details>

<details>
  <summary>Professores</summary>
  <a href="professores/listar.php" target="_corpo">Listar</a>
<?php if ($isAdmin) { ?>
  <a href="professores/inserir.php" target="_corpo">Inserir</a>
<?php } ?>
</details>

<details>
  <summary>Estagiários</summary>
  <a href="estagiarios/listar.php" target="_corpo">Listar</a>
</details>

<details>
  <summary>Gráficos</summary>
  <a href="alunos/exibir/graficos.php" target="_corpo">Alunos por período</a>
</details>

<details>
  <summary>Curso Estágio</summary>
  <a href="curso/capa.html" target="_corpo">Apresentação</a>
  <a href="curso/inscricao_supervisores.php" target="_corpo">Inscrição</a>
  <a href="curso/inscricao_lista.php" target="_corpo">Lista inscritos</a>
  <a href="curso/inscricao_lista.php?selecao=1" target="_corpo">Selecionados</a>
  <a href="curso/curso_busca.html" target="_corpo">Busca inscritos</a>
  <a href="http://docs.google.com/View?id=dhd8s6wp_479f78fcbg4" target="_corpo">Programação</a>
</details>

<?php } // end $usuario_logado && !$isAluno && !$isProfessor ?>

<?php if ($usuario_logado && $isProfessor) { ?>

<details>
  <summary>Professores</summary>
  <a href="professores/listar.php" target="_corpo">Listar</a>
<?php if ($usuario_professor_id) { ?>
  <a href="professores/ver_cada.php?professor_id=<?php echo (int)$usuario_professor_id; ?>" target="_corpo">Meu cadastro</a>
<?php } ?>
</details>

<details>
  <summary>Estagiários</summary>
  <a href="estagiarios/listar.php" target="_corpo">Listar</a>
</details>

<?php } // end $isProfessor ?>

<details>
  <summary>Eventos</summary>
  <a href="http://web.intranet.ess.ufrj.br/ocs/index.php/estagio/estagioI" target="_corpo">I Seminário Regional</a>
</details>

<?php if ($usuario_logado) { ?>
<details>
  <summary>Manual do aluno</summary>
  <a href="http://localhost/wiki/doku.php?id=estagio" target="_corpo">Abrir manual</a>
</details>
<?php } ?>

</nav>

<div class="sidebar-footer">
  <p><a href="mailto:estagio@ess.ufrj.br?Subject=Mural de Estágio">Fale conosco</a></p>
</div>
