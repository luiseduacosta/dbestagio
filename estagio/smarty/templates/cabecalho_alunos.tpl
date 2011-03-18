<!--
<div class="cabecalho" id="cabecalho" align="center">
Turno
//-->
<div class="coluna1" id="coluna1">
    <form method="post" action="{$pagina}" name="form_turno">
        <select name="seleciona_turno" size="1" id="turno" onChange="return get_valor();">
        {if $seleciona_turno eq 'D'}
            <option value="0">Turno</option>
            <option value="D" selected>Diurno</option>
            <option value="N">Noturno</option>
        {elseif $seleciona_turno eq 'N'}
            <option value="0">Turno</option>
            <option value="D">Diurno</option>
            <option value="N" selected>Noturno</option>
        {else}
            <option value="0" selected>Turno</option>
            <option value="D">Diurno</option>
            <option value="N">Noturno</option>
        {/if}
        </select>
        <input type="hidden" name="seleciona" value="turno">
        <input type="hidden" name="ordem" value="{$ordem}">
        <input type="hidden" name="seleciona_nivel" value="{$seleciona_nivel}">
        <input type="hidden" name="seleciona_instituicao" value="{$seleciona_instituicao}">
        <input type="hidden" name="periodo" value="{$periodo}">
    </form>
</div>

<!-- Nivel //-->
<div class="coluna2" id="coluna2">
    <form method="post" action="{$pagina}" name="form_nivel">
        <select name="seleciona_nivel" size="1" id="nivel" onChange="return get_valor();">
        {if $seleciona_nivel eq "1"}
            <option value="0">Níveis</option>
            <option value="1" selected>1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "2"}
            <option value="0">Níveis</option>
            <option value="1">1</option>
            <option value="2" selected>2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "3"}
            <option value="0">Níveis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3" selected>3</option>
            <option value="4">4</option>
        {elseif $seleciona_nivel eq "4"}
            <option value="0">Níveis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4" selected>4</option>
        {else}
            <option value="0" selected>Níveis</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
            <option value="4">4</option>
        {/if}
        </select>
        <input type="hidden" name="seleciona" value="nivel">
        <input type="hidden" name="ordem" value="{$ordem}">
        <input type="hidden" name="seleciona_turno" value="{$seleciona_turno}">
        <input type="hidden" name="seleciona_instituicao" value="{$seleciona_instituicao}">
        <input type="hidden" name="periodo" value="{$periodo}">
    </form>
<!--
</div>
//-->

<!-- Instituicao //-->
<div class="coluna3" id="coluna3">
    <form method="post" action="{$pagina}" name="form_instituicao">
        <select name="seleciona_instituicao" size="1" id="instituicao" onChange="return get_valor()";>
        {if $seleciona_instituicao eq "0"}
            <option value='0' selected>Institui��es</option>
            <option value={$num_instituicao}>{$nome_instituicao|truncate:30}</option>
            {section name=elemento loop=$instituicoes}
            <option value="{$instituicoes[elemento].id_instituicao}">{$instituicoes[elemento].instituicao|truncate:40}</option>
            {/section}
        {else}
            <option value='0'>Instituições</option>
            <option value={$num_instituicao} selected>{$nome_instituicao|truncate:25}</option>
            {section name=elemento loop=$instituicoes}
            <option value="{$instituicoes[elemento].id_instituicao}">{$instituicoes[elemento].instituicao|truncate:40}</option>
            {/section}
        {/if}
        </select>
        <input type="hidden" name="seleciona" value="instituicao">
        <input type="hidden" name="ordem" value="{$ordem}">
        <input type="hidden" name="seleciona_turno" value="{$seleciona_turno}">
        <input type="hidden" name="seleciona_nivel" value="{$seleciona_nivel}">
        <input type="hidden" name="periodo" value="{$periodo}">
    </form>
</div>

Período
<div class="coluna4" id="coluna4">
    <form method="post" action="{$pagina}" name="form_periodo">
        <select name="periodo" size="1" id="periodo" onChange="return get_valor();">
        <option value=0>Período</option>
        <option value={$periodo} selected>{$periodo}</option>
        {section name=elemento loop=$matriz_periodo}
            <option value={$matriz_periodo[elemento].turma}>{$matriz_periodo[elemento].turma}</option>
        {/section}
        </select>
        <input type="hidden" name="ordem" id="ordem" value="{$ordem}">
    </form>
</div>
<!--
</div>
//-->