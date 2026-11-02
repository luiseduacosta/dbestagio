<?php
/**
 * Script para corrigir TODOS os templates restantes
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

// Lista de todos os templates problemáticos restantes
$templates_problematicos = [
    'alunos-atualizar_atualiza_termo.tpl',
    'alunos-atualizar_modificar_estagio.tpl',
    'alunos-atualizar_seleciona_termo.tpl',
    'alunos-cancelar_ver_cancela.tpl',
    'alunos-exibir_aluno_supervisor.tpl',
    'cabecalho.tpl',
    'capa.tpl',
    'curso-supervisores_lista.tpl',
    'curso-supervisores_seleciona.tpl',
    'rodape.tpl',
    'supervisores-atualizar_seleciona_cadastro.tpl',
    'supervisores-enviar_email.tpl'
];

function aplicar_correcoes($arquivo, $conteudo) {
    $modificacoes = [];
    
    // 1. Adicionar charset UTF-8 se não existir
    if (!preg_match('/<meta[^>]*charset[^>]*>/i', $conteudo)) {
        if (preg_match('/<head>/i', $conteudo)) {
            $conteudo = preg_replace('/<head>/i', "<head>\n<meta charset=\"utf-8\">", $conteudo, 1);
            $modificacoes[] = 'Adicionado meta charset UTF-8';
        }
    }
    
    // 2. Adicionar lang="pt-br" se não existir
    if (!preg_match('/<html[^>]*lang=["\']pt-br["\'][^>]*>/i', $conteudo)) {
        if (preg_match('/<html[^>]*>/i', $conteudo, $matches)) {
            $html_atual = $matches[0];
            $novo_html = str_replace('<html', '<html lang="pt-br"', $html_atual);
            $conteudo = str_replace($html_atual, $novo_html, $conteudo);
            $modificacoes[] = 'Adicionado lang="pt-br"';
        }
    }
    
    // 3. Corrigir body para id="corpo"
    if (preg_match('/<body[^>]*>/i', $conteudo, $matches)) {
        $body_atual = $matches[0];
        if (!preg_match('/id=["\']corpo["\']/i', $body_atual)) {
            // Remover atributos de estilo do body
            $novo_body = preg_replace('/\s+style=["\'][^"\']*["\']/', '', $body_atual);
            $novo_body = preg_replace('/\s+onLoad=["\'][^"\']*["\']/', '', $novo_body);
            $novo_body = str_replace('<body', '<body id="corpo"', $novo_body);
            
            // Restaurar onLoad se existia
            if (preg_match('/onLoad=["\'][^"\']*["\']/', $body_atual, $onload_match)) {
                $novo_body = str_replace('id="corpo"', 'id="corpo" ' . $onload_match[0], $novo_body);
            }
            
            $conteudo = str_replace($body_atual, $novo_body, $conteudo);
            $modificacoes[] = 'Body corrigido para id="corpo"';
        }
    }
    
    // 4. Adicionar estagio-moderno.css se não existir
    if (!preg_match('/estagio-moderno\.css/i', $conteudo)) {
        // Tentar adicionar após estagio.css
        if (preg_match('/(<link[^>]*href=["\'][^"\']*estagio\.css["\'][^>]*>)/i', $conteudo, $matches)) {
            $estagio_css = $matches[0];
            $estagio_moderno_css = str_replace('estagio.css', 'estagio-moderno.css', $estagio_css);
            $conteudo = str_replace($estagio_css, $estagio_css . "\n" . $estagio_moderno_css, $conteudo);
            $modificacoes[] = 'Adicionado estagio-moderno.css';
        }
        // Se não encontrar estagio.css, tentar adicionar no head
        elseif (preg_match('/<\/head>/i', $conteudo)) {
            $css_links = '<link rel="stylesheet" href="../estagio.css" type="text/css">' . "\n" .
                        '<link rel="stylesheet" href="../estagio-moderno.css" type="text/css">';
            $conteudo = str_replace('</head>', $css_links . "\n</head>", $conteudo);
            $modificacoes[] = 'Adicionado ambos CSS no head';
        }
    }
    
    // 5. Adicionar main class="mural-page" se não existir
    if (!preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo)) {
        if (preg_match('/(<body[^>]*>)/i', $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1] + strlen($matches[0][0]);
            $conteudo = substr_replace($conteudo, "\n<main class=\"mural-page\">\n", $pos, 0);
            $modificacoes[] = 'Adicionado <main class="mural-page">';
        }
    }
    
    // 6. Adicionar </main> antes do </body> se necessário
    if (preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo) && 
        !preg_match('/<\/main>/i', $conteudo)) {
        if (preg_match('/(<\/body>)/i', $conteudo)) {
            $conteudo = str_replace('</body>', "</main>\n</body>", $conteudo);
            $modificacoes[] = 'Adicionado </main>';
        }
    }
    
    return [$conteudo, $modificacoes];
}

echo "=== CORREÇÃO DE TODOS OS TEMPLATES RESTANTES ===\n\n";

$fragmentos_ignorados = [];

foreach ($templates_problematicos as $template_nome) {
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
    list($novo_conteudo, $modificacoes) = aplicar_correcoes($arquivo, $conteudo);
    
    if (!empty($modificacoes)) {
        file_put_contents($arquivo, $novo_conteudo);
        echo "✅ $template_nome corrigido:\n";
        foreach ($modificacoes as $mod) {
            echo "   • $mod\n";
        }
        echo "\n";
    } else {
        echo "➖ $template_nome: Sem correções necessárias\n";
    }
}

echo "✅ Todas as correções aplicadas!\n\n";

if (!empty($fragmentos_ignorados)) {
    echo "⏭️  Fragmentos ignorados (nenhuma alteração feita): "
        . count($fragmentos_ignorados) . " -> " . implode(', ', $fragmentos_ignorados) . "\n\n";
} else {
    echo "⏭️  Nenhum fragmento ignorado.\n\n";
}

// Executar verificação final
echo "=== VERIFICAÇÃO FINAL ===\n";
include __DIR__ . '/verificar_templates.php';
?>