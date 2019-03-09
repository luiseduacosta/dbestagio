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

<iframe src="http://www.google.com/talk/service/badge/Show?tk=z01q6amlqp78ptnr0nagvm9j3d0hn2octg360qk9mo74siivjba96gckl5d06fj3blih5hfhspl96rq57lvo4d5pfjg3p4gssibebq3qqi5mlceijcs9rksrlt7e0suh4i4e56avl9ma64asbscfk09l4gckvqofcmqsod6blcqlkll0hnosd4g8hhm456bpdoo&amp;w=200&amp;h=60" frameborder="0" allowtransparency="true" width="300" height="60"></iframe>

<br />

<span style='text-align: center'>
Telefone: 3873 5394 <br />
Email: estagio@ess.ufrj.br
</span>

<!--
<p style='text-align: center'>
    <span style='text-align: center'>
    <a rel="author" href="https://profiles.google.com/u/0/115567722862878215603">
    <img src="http://www.google.com/images/icons/ui/gprofile_button-32.png" width="32" height="32">  
    </a>
    </span>
</p>
//-->

<p class="coluna_centralizada">
<a href="http://200.20.112.2/estagio/">
<img src="imagens/fachada_redonda.jpg" name="capa_imagem" id="capa_imagem" alt="Fachada da Escola de Serviço Social/UFRJ" width="588" height="400">
</a>
</p>

</div>

</body>
</html>