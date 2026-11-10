<?php
/**
 * Script final para verificar templates Smarty
 * Considera templates parciais como válidos
 */

$templates_dir = __DIR__ . '/smarty/templates';

// Templates que são parciais (não precisam de estrutura completa)
$templates_parciais = [
    'cabecalho.tpl',  // Template parcial de cabeçalho
    'capa.tpl',       // Template parcial da capa  
    'rodape.tpl'      // Template parcial de rodapé
];

function verificar_template_final($arquivo, $nome) {
    global $templates_parciais;
    
    $conteudo = file_get_contents($arquivo);
    $problemas = [];
    
    // Templates parciais têm regras diferentes
    $e_parcial = in_array($nome, $templates_parciais);
    
    if (!$e_parcial) {
        // Para templates completos, verificar estrutura
        
        // 1. Verificar body id="corpo"
        if (!preg_match('/<body[^>]*id=["\']corpo["\'][^>]*>/i', $conteudo)) {
            if (preg_match('/<body[^>]*>/i', $conteudo)) {
                $problemas[] = "Body sem id='corpo'";
            }
        }
        
        // 2. Verificar estagio-moderno.css
        if (!preg_match('/estagio-moderno\.css/i', $conteudo)) {
            $problemas[] = "Não carrega estagio-moderno.css";
        }
        
        // 3. Verificar main class="mural-page"
        if (!preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo)) {
            $problemas[] = "Sem <main class=\"mural-page\">";
        }
        
        // 4. Verificar se fecha main
        if (preg_match('/<main[^>]*class=["\'][^"\']*mural-page[^"\']*["\'][^>]*>/i', $conteudo) && 
            !preg_match('/<\/main>/i', $conteudo)) {
            $problemas[] = "Abre main mas não fecha";
        }
    } else {
        // Para templates parciais, apenas verificações básicas
        if ($nome == 'capa.tpl') {
            // Verificar se a imagem tem alt correto
            if (preg_match('/alt=["\'][^"\']*Servi�o[^"\']*["\']/i', $conteudo)) {
                $problemas[] = "Alt da imagem com encoding incorreto";
            }
        }
        
        if ($nome == 'rodape.tpl') {
            // Verificar se tem as tags de fechamento
            if (!preg_match('/<\/body>/i', $conteudo) || !preg_match('/<\/html>/i', $conteudo)) {
                $problemas[] = "Template de rodapé incompleto";
            }
        }
    }
    
    return $problemas;
}

// Processar todos os templates
$templates = glob($templates_dir . '/*.tpl');
$resultados = [];
$templates_ok = 0;

foreach ($templates as $template) {
    $nome = basename($template);
    $problemas = verificar_template_final($template, $nome);
    
    if (!empty($problemas)) {
        $resultados[$nome] = $problemas;
    } else {
        $templates_ok++;
    }
}

// Exibir resultados
echo "=== VERIFICAÇÃO FINAL DE TEMPLATES ===\n\n";
echo "Total de templates: " . count($templates) . "\n";
echo "Templates parciais: " . count($templates_parciais) . "\n";
echo "Templates completos: " . (count($templates) - count($templates_parciais)) . "\n";
echo "Templates com problemas: " . count($resultados) . "\n";
echo "Templates OK: " . $templates_ok . "\n\n";

if (!empty($resultados)) {
    echo "📋 TEMPLATES COM PROBLEMAS:\n\n";
    
    foreach ($resultados as $template => $problemas) {
        $e_parcial = in_array($template, $templates_parciais) ? " (parcial)" : "";
        echo "📄 $template$e_parcial:\n";
        foreach ($problemas as $problema) {
            echo "  ❌ $problema\n";
        }
        echo "\n";
    }
}

// Estatísticas por categoria
$completos_com_problemas = 0;
$parciais_com_problemas = 0;

foreach ($resultados as $template => $problemas) {
    if (in_array($template, $templates_parciais)) {
        $parciais_com_problemas++;
    } else {
        $completos_com_problemas++;
    }
}

echo "📊 ESTATÍSTICAS:\n";
echo "• Templates completos OK: " . ((count($templates) - count($templates_parciais)) - $completos_com_problemas) . "/" . (count($templates) - count($templates_parciais)) . "\n";
echo "• Templates parciais OK: " . (count($templates_parciais) - $parciais_com_problemas) . "/" . count($templates_parciais) . "\n";
echo "• Taxa de sucesso total: " . round(($templates_ok / count($templates)) * 100, 1) . "%\n";

// Verificar templates críticos (completos que ainda têm problemas)
$templates_criticos = [];
foreach ($resultados as $template => $problemas) {
    if (!in_array($template, $templates_parciais)) {
        $templates_criticos[] = $template;
    }
}

if (!empty($templates_criticos)) {
    echo "\n🚨 TEMPLATES CRÍTICOS (completos com problemas):\n";
    foreach ($templates_criticos as $template) {
        echo "  • $template\n";
    }
}
?>