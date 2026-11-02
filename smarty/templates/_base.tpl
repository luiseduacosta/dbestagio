{*
 * Template base para novos templates do sistema de estágio
 * Segue o padrão moderno com estagio-moderno.css e body id="corpo"
 *}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$titulo|default:'Sistema de Estágio'}</title>
    
    {* CSS base do sistema *}
    <link rel="stylesheet" href="../estagio.css" type="text/css">
    <link rel="stylesheet" href="../estagio-moderno.css" type="text/css">
    
    {* DataTables para tabelas *}
    {if $use_datatables|default:false}
    <link rel="stylesheet" href="../libjs/datatables/dataTables.min.css">
    {/if}
    
    {* Scripts específicos da página *}
    {literal}
    <script language="JavaScript" type="text/javascript" src="../lib/jquery.js"></script>
    {if $use_datatables|default:false}
    <script type="text/javascript" src="../libjs/datatables/dataTables.min.js"></script>
    {/if}
    {/literal}
    
    {* Scripts inline podem ser adicionados aqui *}
    {block name=head_scripts}{/block}
</head>

<body id="corpo">

<main class="mural-page">
    <header class="page-head">
        <h1>{$titulo_pagina|default:$titulo|default:'Sistema de Estágio'}</h1>
        {if $subtitulo|default:''}
        <p class="eyebrow">{$subtitulo}</p>
        {/if}
    </header>
    
    {* Conteúdo principal da página *}
    {block name=content}
    <p>Conteúdo da página</p>
    {/block}
    
</main>

{* Scripts no final do body *}
{block name=body_scripts}{/block}

</body>
</html>