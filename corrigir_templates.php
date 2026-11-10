<?php
/**
 * Script para corrigir templates automaticamente
 * Aplica correções básicas para padrão moderno
 */

$templates_dir = __DIR__ . '/smarty/templates';

/**
 * Um template é considerado fragmento (partial) quando não possui
 * <head> nem <body> de abertura. Nesses arquivos NÃO se injeta
 * estagio-moderno.css, body id="corpo" nem <main>.
 * Ex.: capa.tpl e rodape.tpl.
 */
if (!function_exists('eh_fragmento')) {
    function eh_fragmento($arquivo) {
        $conteudo = file_get_contents($arquivo);
        $tem_body = preg_match('/<body[^>]*>/i', $conteudo);
        $tem_head = preg_match('/<head[^>]*>/i', $conteudo);
        return !$tem_body && !$tem_head;
    }
}

// Templates específicos para corrigir
$templates_para_corrigir = [
    'alunos-atualizar_modificar_estagio.tpl',
    'alunos-cancelar_ver_cancela.tpl', 
    'alunos-exibir_aluno_supervisor.tpl',
    'configuracoes-atualizar.tpl',
    'configuracoes-ver_cada.tpl',
    'estagiarios-atualizar.tpl'
];

$fragmentos_ignorados = [];

foreach ($templates_para_corrigir as $template_nome) {
    $arquivo = $templates_dir . '/' . $template_nome;
    
    if (!file_exists($arquivo)) {
        echo "❌ Arquivo não encontrado: $template_nome\n";
        continue;
    }
    
    // Fragmentos (capa.tpl, rodape.tpl) não devem ser modificados
    if (eh_fragmento($arquivo)) {
        $fragmentos_ignorados[] = $template_nome;
        echo "⏭️  $template_nome ignorado (fragmento sem <head>/<body>)\n";
        continue;
    }
    
    $conteudo = file_get_contents($arquivo);
    $modificacoes = [];
    
    // 1. Corrigir body para id="corpo"
    if (preg_match('/<body[^>]*>/i', $conteudo, $matches)) {
        $body_atual = $matches[0];
        if (!preg_match('/id=["\']corpo["\']/i', $body_atual)) {
            $novo_body = preg_replace('/style=["\'][^"\']*["\']/', '', $body_atual);
            $novo_body = str_replace('<body', '<body id="corpo"', $novo_body);
            $conteudo = str_replace($body_atual, $novo_body, $conteudo);
            $modificacoes[] = 'Body corrigido para id="corpo"';
        }
    }
    
    // 2. Adicionar estagio-moderno.css após estagio.css
    if (!preg_match('/estagio-moderno\.css/i', $conteudo)) {
        if (preg_match('/(<link[^>]*estagio\.css[^>]*>)/i', $conteudo, $matches)) {
            $linha_css = $matches[0];
            $novo_css = $linha_css . "\n" . str_replace('estagio.css', 'estagio-moderno.css', $linha_css);
            $conteudo = str_replace($linha_css, $novo_css, $conteudo);
            $modificacoes[] = 'Adicionado estagio-moderno.css';
        }
    }
    
    // 3. Adicionar <main class="mural-page"> após body
    if (!preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo)) {
        if (preg_match('/(<body[^>]*>)/i', $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1] + strlen($matches[0][0]);
            $conteudo = substr_replace($conteudo, "\n<main class=\"mural-page\">\n", $pos, 0);
            $modificacoes[] = 'Adicionado <main class="mural-page">';
        }
    }
    
    // 4. Adicionar </main> antes do </body>
    if (preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo) && 
        !preg_match('/<\/main>/i', $conteudo)) {
        if (preg_match('/(<\/body>)/i', $conteudo)) {
            $conteudo = str_replace('</body>', "</main>\n</body>", $conteudo);
            $modificacoes[] = 'Adicionado </main>';
        }
    }
    
    // Salvar se houve modificações
    if (!empty($modificacoes)) {
        file_put_contents($arquivo, $conteudo);
        echo "✅ $template_nome corrigido:\n";
        foreach ($modificacoes as $mod) {
            echo "   • $mod\n";
        }
        echo "\n";
    } else {
        echo "➖ $template_nome: Sem correções necessárias\n";
    }
}

echo "\n✅ Correções aplicadas!\n";

if (!empty($fragmentos_ignorados)) {
    echo "⏭️  Fragmentos ignorados (nenhuma alteração feita): "
        . count($fragmentos_ignorados) . " -> " . implode(', ', $fragmentos_ignorados) . "\n";
} else {
    echo "⏭️  Nenhum fragmento ignorado.\n";
}

// Executar verificação novamente
echo "\n=== VERIFICAÇÃO APÓS CORREÇÕES ===\n";
include __DIR__ . '/verificar_templates.php';
?>