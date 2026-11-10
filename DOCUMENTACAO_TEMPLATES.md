# Documentação: Padrão de Templates Smarty

## ✅ Tarefas Concluídas

### 1. **Atualização do `cabecalho.tpl`**
- **Antes**: `<body style="direction: ltr;">` + apenas `estagio.css`
- **Depois**: `<body id="corpo">` + `estagio.css` + `estagio-moderno.css`

### 2. **Correção de Templates Legados**
Corrigidos os seguintes templates principais:
- `estagiarios-inserir.tpl`
- `alunos-atualizar_atualiza_estagio.tpl`
- `alunos-cadastro_termo.tpl`
- `alunos-inserir_acrescentar_estagio.tpl`
- `alunos-atualizar_modificar_estagio.tpl`
- `alunos-cancelar_ver_cancela.tpl`
- `alunos-exibir_aluno_supervisor.tpl`
- `configuracoes-atualizar.tpl`
- `configuracoes-ver_cada.tpl`
- `estagiarios-atualizar.tpl`

### 3. **Criação de Padrão Consistente**

#### **Template Base Criado**: `_base.tpl`
```smarty
{*
 * Template base para novos templates
 *}
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>{$titulo}</title>
    <link rel="stylesheet" href="../estagio.css">
    <link rel="stylesheet" href="../estagio-moderno.css">
</head>
<body id="corpo">
<main class="mural-page">
    {* Conteúdo aqui *}
</main>
</body>
</html>
```

#### **Estrutura Padrão**
1. **HTML5** com `<!DOCTYPE html>`
2. **Meta charset** UTF-8
3. **CSS obrigatório**:
   - `estagio.css` (legado)
   - `estagio-moderno.css` (design system moderno)
4. **Body** com `id="corpo"`
5. **Main** com `class="mural-page"`
6. **Fechamento correto** com `</main>` antes de `</body>`

## 📊 Status Atual

| Métrica | Antes | Depois | Melhoria |
|---------|-------|--------|----------|
| Templates totais | 82 | 82 | - |
| Templates com problemas | 18 | 0 | **100% resolvido** |
| Templates OK | 64 | 82 | **28% melhor** |

> **Verificação de 2026-11-02**: todas as 80 páginas completas carregam `estagio-moderno.css` e usam `<body id="corpo">`. Os 2 arquivos restantes (`capa.tpl` e `rodape.tpl`) são fragmentos sem `<head>`/`<body>` de abertura, portanto os critérios não se aplicam a eles.

## 🔧 Ferramentas Criadas

### 1. **Verificador Automático**: `verificar_templates.php`
- Verifica padrões em todos os templates
- Identifica problemas específicos
- Sugere correções

### 2. **Corretor Automático**: `corrigir_templates.php`
- Aplica correções básicas automaticamente
- Corrige body, CSS e estrutura main

## 📋 Templates Restantes com Problemas (0)

Nenhum template apresenta pendências. Todos os itens anteriormente listados foram corrigidos.

### Fragmentos (critérios não aplicáveis)
Os seguintes arquivos são *partials* e não possuem `<head>` nem `<body>` de abertura, portanto **não precisam** de `estagio-moderno.css` nem de `body id="corpo"`:
- `capa.tpl` — bloco `<section class="capa">` incluído dentro de uma página.
- `rodape.tpl` — apenas fecha as tags `</body></html>`.

O CSS e a abertura do `<body id="corpo">` ficam a cargo do `cabecalho.tpl`, que monta a estrutura HTML completa.

## 📝 Guia para Novos Templates

```smarty
{* Novo template seguindo padrão moderno *}
{extends file="_base.tpl"}

{block name=content}
    {* Conteúdo específico da página *}
    <section class="table-shell">
        <h2>Título da Seção</h2>
        {* Conteúdo aqui *}
    </section>
{/block}
```

## ✅ Checklist para Validação

- [ ] `<!DOCTYPE html>` no início
- [ ] `<body id="corpo">`
- [ ] Carrega `estagio-moderno.css`
- [ ] `<main class="mural-page">` abrindo
- [ ] `</main>` fechando antes de `</body>`
- [ ] Estrutura semântica correta

---

**Data da última verificação**: 2026-11-02  
**Responsável**: Sistema de automação Kiro  
**Status**: 100% dos templates seguem padrão moderno ✅
## 🔧 Configuração MCP para APIs de IA

### **Configuração das APIs**

O sistema está configurado para usar servidores MCP com DeepSeek e Qwen. As chaves de API devem ser configuradas como variáveis de ambiente.

#### **Passos para configurar:**

1. **Criar arquivo `.env`** (baseado em `.env.example`):
   ```bash
   cp .env.example .env
   ```

2. **Editar `.env`** com suas chaves:
   ```env
   DEEPSEEK_API_KEY=sua_chave_aqui
   QWEN_API_KEY=sua_chave_aqui
   ```

3. **Configurar variáveis de ambiente**:
   ```bash
   # Linux/Mac
   export DEEPSEEK_API_KEY="sua_chave"
   export QWEN_API_KEY="sua_chave"
   
   # Ou adicionar ao ~/.bashrc ou ~/.zshrc
   ```

#### **Configuração automática no MCP:**

Edite `.kiro/settings/mcp.json` para incluir as variáveis:

```json
{
  "mcpServers": {
    "deepseek-api": {
      "command": "npx",
      "args": ["-y", "deepseek-mcp-server"],
      "env": {
        "DEEPSEEK_API_KEY": "${DEEPSEEK_API_KEY}"
      },
      "disabled": false,
      "autoApprove": ["chat", "models", "balance"]
    },
    "qwen-api": {
      "command": "openai-mcp-server",
      "args": ["--base-url", "https://token-plan.ap-southeast-1.maas.aliyuncs.com/compatible-mode/v1"],
      "env": {
        "OPENAI_API_KEY": "${QWEN_API_KEY}"
      },
      "disabled": false,
      "autoApprove": []
    }
  }
}
```

### **Solucionando problemas:**

#### **Erro: "DEEPSEEK_API_KEY environment variable is required"**
- Solução: Configure a variável de ambiente `DEEPSEEK_API_KEY`
- Verifique se o .env está carregado corretamente

#### **Erro: Push bloqueado no GitHub**
- Solução: Nunca commit chaves de API no código
- Use arquivos .env e .gitignore
- O arquivo `.env` já está no .gitignore

#### **Servidor não inicia**
- Verifique se os servidores MCP estão instalados:
  ```bash
  npx -y deepseek-mcp-server --help
  pipx list | grep openai-mcp-server
  ```

### **Testando a configuração:**

1. **Reinicie o Kiro** para carregar novas configurações MCP
2. **Verifique os logs** no painel MCP
3. **Teste as ferramentas** através do Kiro

---

**Nota de segurança**: As chaves de API são sensíveis e não devem ser compartilhadas ou commitadas no repositório.