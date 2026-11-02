<?php
/**
 * Script para verificar e corrigir templates Smarty
 * Verifica se templates seguem padrão moderno com:
 * 1. body id="corpo"
 * 2. Carrega estagio-moderno.css
 * 3. Estrutura correta com <main class="mural-page">
 */

$templates_dir = __DIR__ . '/smarty/templates';
$correcoes = [];

/**
 * Um template é considerado fragmento (partial) quando não possui
 * <head> nem <body> de abertura. Nesses arquivos os critérios de
 * estagio-moderno.css, body id="corpo" e <main> não se aplicam.
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

function verificar_template($arquivo) {
    global $correcoes;
    
    $conteudo = file_get_contents($arquivo);
    $nome = basename($arquivo);
    $problemas = [];
    
    // Verificar body id="corpo"
    if (!preg_match('/<body[^>]*id=["\']corpo["\'][^>]*>/i', $conteudo)) {
        if (preg_match('/<body[^>]*>/i', $conteudo, $matches)) {
            $problemas[] = "Body sem id='corpo'";
            // Sugerir correção
            $correcoes[$arquivo]['body'] = str_replace($matches[0], '<body id="corpo">', $matches[0]);
        }
    }
    
    // Verificar estagio-moderno.css
    if (!preg_match('/estagio-moderno\.css/i', $conteudo)) {
        $problemas[] = "Não carrega estagio-moderno.css";
        
        // Tentar encontrar onde adicionar
        if (preg_match('/estagio\.css/i', $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1];
            $linha = substr_count(substr($conteudo, 0, $pos), "\n") + 1;
            $correcoes[$arquivo]['css_line'] = $linha;
        }
    }
    
    // Verificar main class="mural-page"
    if (!preg_match('/<main[^>]*class=["\'][^"\'"]*mural-page[^"\'"]*["\'][^>]*>/i', $conteudo)) {
        $problemas[] = "Sem <main class=\"mural-page\">";
        
        // Tentar encontrar body para adicionar main depois
        if (preg_match('/<body[^>]*>/i', $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1] + strlen($matches[0][0]);
            $correcoes[$arquivo]['main_pos'] = $pos;
        }
    }
    
    // Verificar se fecha main
    if (preg_match('/<main[^>]*class=["\'][^"\'"]*mural-page[^"\'"]*["\'][^>]*>/i', $conteudo) && 
        !preg_match('/<\/main>/i', $conteudo)) {
        $problemas[] = "Abre main mas não fecha";
        
        // Encontrar onde adicionar </main>
        if (preg_match('/<\/body>/i', $conteudo, $matches, PREG_OFFSET_CAPTURE)) {
            $pos = $matches[0][1];
            $correcoes[$arquivo]['close_main_pos'] = $pos;
        }
    }
    
    return $problemas;
}

// Processar todos os templates
$templates = glob($templates_dir . '/*.tpl');
$resultados = [];
$fragmentos = [];

foreach ($templates as $template) {
    if (eh_fragmento($template)) {
        $fragmentos[] = basename($template);
        continue;
    }
    $problemas = verificar_template($template);
    if (!empty($problemas)) {
        $resultados[basename($template)] = $problemas;
    }
}

$paginas = count($templates) - count($fragmentos);

// Exibir resultados
echo "=== VERIFICAÇÃO DE TEMPLATES ===\n\n";
echo "Total de templates: " . count($templates) . "\n";
echo "Páginas completas verificadas: " . $paginas . "\n";
echo "Fragmentos ignorados (critérios não se aplicam): " . count($fragmentos);
if (!empty($fragmentos)) {
    echo " -> " . implode(', ', $fragmentos);
}
echo "\n";
echo "Templates com problemas: " . count($resultados) . "\n\n";

foreach ($resultados as $template => $problemas) {
    echo "📄 $template:\n";
    foreach ($problemas as $problema) {
        echo "  ❌ $problema\n";
    }
    echo "\n";
}

// Sugerir comandos para correção
if (!empty($correcoes)) {
    echo "\n=== SUGESTÕES DE CORREÇÃO ===\n\n";
    
    foreach ($correcoes as $arquivo => $sugestoes) {
        $nome = basename($arquivo);
        echo "🔧 $nome:\n";
        
        if (isset($sugestoes['body'])) {
            echo "  • Corrigir body: " . $sugestoes['body'] . " → <body id=\"corpo\">\n";
        }
        
        if (isset($sugestoes['css_line'])) {
            echo "  • Adicionar estagio-moderno.css após estagio.css na linha {$sugestoes['css_line']}\n";
        }
        
        if (isset($sugestoes['main_pos'])) {
            echo "  • Adicionar <main class=\"mural-page\"> após o body\n";
        }
        
        if (isset($sugestoes['close_main_pos'])) {
            echo "  • Adicionar </main> antes do </body>\n";
        }
        
        echo "\n";
    }
}

echo "\n✅ Páginas OK: " . ($paginas - count($resultados)) . " de " . $paginas . "\n";
?>