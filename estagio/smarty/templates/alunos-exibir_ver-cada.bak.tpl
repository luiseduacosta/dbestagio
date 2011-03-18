<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="pt-br">
<head>
  <meta content="text/html; charset=UTF-8" http-equiv="content-type">
  <title>Ver cada aluno</title>
  <meta content="author" name="Luis Acosta">
  <style type="text/css">
@import url(../../estagio.css);
  </style>
</head>


<body style="direction: ltr;">

{literal}
<script type="text/javascript" src="../lib/jquery.js"></script>
<script type="text/javascript" src="../lib/jquery.maskedinput-1.2.1.pack.js"></script>
<script type="text/javascript">
$(function() {
	$("#telefone").mask("9999.9999");
 	$("#celular").mask("9999.9999");
	$("#cep").mask("99999-999");
	$("#cpf").mask("999999999-99");	
});
</script>
{/literal}

{include file='cabecalho.tpl'}

<!-- ###CORPO### -->
<div align="center">
<table id="navegacao">
<caption>Alunos</caption>
  <tbody>
    <tr>
      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="primeiro" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value="Primeiro" type="submit">
      </form>
      </td>

      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="menos_10" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value=" - 10 " type="submit">
      </form>
      </td>

      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="retroceder" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value="Retroceder" type="submit">
      </form>
      </td>

      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="avancar" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value="Avan&ccedil;ar" type="submit">
      </form>
      </td>

      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="mais_10" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value=" + 10 " type="submit">
      </form>
      </td>

      <td>
      <form action="#" method="post">
        <input name="indice" value="{$indice}" type="hidden">
        <input name="botao" value="ultimo" type="hidden">
        <input name="id_aluno" value="" type="hidden">
        <input name="submit" value="&Uacute;ltimo" type="submit">
      </form>
      </td>

    </tr>
  
  </tbody>
</table>
</div>

<div align="center">
{if $logado == 1}
	{/if}
<table border="1" width="98%">
  <tbody>
    <tr>
      <th width="80%">Aluno estagi&aacute;rio {* $num_aluno *}</th>
      <th width="20%"><a href="../cancelar/ver_cancela.php?id_aluno=%7B$num_aluno%7D">Excluir registro</a></th>
    </tr>
  </tbody>
</table>

<div align="center">
{if $logado == 1}
    {/if}

<table border="1" width="98%">
  <tbody>
    <tr>
      <td width="20%">Registro:</td>
      <td width="80%">{$registro}</td>
    </tr>
    <tr>
      <td>Nome:</td>
      <td>{$nome}</td>
    </tr>
    <tr>
      <td>E-mail:</td>
      <td>{$email}</td>
    </tr>
    <tr>
    <td>Telefone:</td>
    <td>({$codigo_telefone}){$telefone}</td>
    </tr>
    <tr>
    <td>Celular:</td>
    <td>({$codigo_celular}){$celular}</td>
    </tr>
    <tr>
    <td>Observa&ccedil;&otilde;es:</td>
    <td>{$observacoes}</td>
    </tr>
  </tbody>
</table>


</div>



<div align="center">
{if $logado == 1}
    {/if}
{section name=estagio loop=$historico_estagio}

{if $historico_estagio[estagio].id_supervisor eq 0}
	{else}
	{/if}
{if $logado == 1}
    {/if}
{/section}


{if $logado == 1}
    {/if}

<table border="1" width="98%">


  <tbody>



    <tr>


      <th>Per&iacute;odo</th>


      <th>TC</th>


      <th>Nivel</th>


      <th>Turno</th>


      <th>Institui&ccedil;&atilde;o</th>


      <th>Supervisor</th>


      <th>Professor</th>


      <th>Nota</th>


    <th>ch</th>


    </tr>



    <tr>


      <td style="text-align: center;">{$historico_estagio[estagio].periodo}</td>


      <td style="text-align: center;">{$historico_estagio[estagio].tc}</td>


      <td style="text-align: center;">{$historico_estagio[estagio].nivel}</td>


      <td style="text-align: center;">{$historico_estagio[estagio].turno}</td>


      <td><a href="../../instituicoes/exibir/ver_cada.php?id_instituicao=%7B$historico_estagio%5Bestagio%5D.id_instituicao%7D">{$historico_estagio[estagio].instituicao}</a></td>


      <td>&nbsp;</td>


      <td><a href="../../assistentes/exibir/ver_cada.php?id_supervisor=%7B$historico_estagio%5Bestagio%5D.id_supervisor%7D">{$historico_estagio[estagio].supervisor}</a></td>


      <td>{$historico_estagio[estagio].professor}</td>


      <td style="text-align: center;">{$historico_estagio[estagio].nota}</td>


    <td style="text-align: center;">{$historico_estagio[estagio].ch}</td>


    </tr>



    <tr>


    
      
      <form action="../atualizar/atualiza.php" method="post"></form>


    <td colspan="8" style="text-align: center;">
    <input name="id_aluno" value="{$num_aluno}" type="hidden">
    <input name="origem" value="{$origem}" type="hidden">
    <input name="submit" value="Clique aqui para modificar dados do aluno ou atualizar/inserir est&aacute;gios" type="submit">
    </td>


    
    </tr>


  
  </tbody>
</table>


</div>



{* Monografia de fin do curso *}
{if $tcc}
    
<table border="1">


    <caption>Monografia de fim de curso</caption>
    <tbody>



    <tr>


    <th>Peri&oacute;do</th>


    <th>T&iacute;tulo</th>


    <th>Cat&aacute;logo</th>


    <th>Professor</th>


    </tr>



    <tr>



    <td style="text-align: center;">{$tcc.periodo}</td>


	<td><a href="../../../tcc/monografia/visualizar/ver_monografia.php?codigo={$tcc.id}">{$tcc.titulo}</a></td>


    <td style="text-align: center;">{$tcc.catalogo}</td>


    <td>{$tcc.professor}</td>



    </tr>



    
  
  </tbody>
    
</table>


{/if}
<!-- ###CORPO### -->

{include file='rodape.tpl'}

</div>

</body>
</html>
