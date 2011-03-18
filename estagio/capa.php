<?php

// Elimino os cookies
setcookie("usuario_nome");
setcookie("usuario_senha");
setcookie("mural_usuario","",0,"/estagio/mural/administraco");
setcookie("mural_senha","",0,"/estagio/mural/administraco");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
 "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<title>Capa principal</title>
<style type="text/css" title="estagio" media="all">
@import url("estagio.css");
</style>
<script>
// setTimeout("trocaImagem();",3000);
function trocaImagem() {
    figura = document.getElementById("capa_imagem");
    figura.src="imagens/fachada_redonda.jpg";
    setTimeout("escolaImagem();",1000);
}
function escolaImagem() {
    figura = document.getElementById("capa_imagem");
    figura.src="imagens/seminario_estagio.jpg";
    setTimeout("trocaImagem();",6000);
}

</script>
</head>

<body>

<div align="center">
<strong>
<font size="+2">Escola de Serviço Social</font>
<br />
<font size="+2">Coordenação de Estágio e Extens&atilde;o</font>
</strong>

<br />
<span style='text-align: center'>
Telefone: 3873 5394 <br />
Email: estagio@ess.ufrj.br
</span>
 
<p class="coluna_centralizada">
<a href="http://web.intranet.ess.ufrj.br/ocs/index.php/estagio/">
<img src="imagens/fachada_redonda.jpg" name="capa_imagem" id="capa_imagem" alt="Fachada da Escola de Serviço Social/UFRJ" width="588" height="400">
</a>
</p>

</div>

</body>
</html>