# QWEN.md — dbestagio

## Project Overview

**dbestagio** is the internship ("estágio") management system of the Coordenação de Estágio e
Extensão of the Escola de Serviço Social da UFRJ (ESS/UFRJ), Brazil. It manages tables of
students (alunos), institutions (instituições), supervisors (supervisores) and professors
(professores) by academic period, including grade entry. It also includes a course-registration
module for supervisors (`curso`, `inscricoes`) and an internship-offers board (`mural`).

The codebase dates from 2005 (originally exported from Google Code) and has been progressively
**refactored to PHP 8.x**, moving dependencies to Composer while keeping the legacy procedural
page scripts as thin entry points. The whole application was also ported to CakePHP under the
separate name `dbestagio-cakephp`; this repository remains the running legacy-modernized version.

### Tech stack

| Concern | Technology |
| --- | --- |
| Language | PHP >= 8.0 (CLI 8.4 in dev) |
| Database | MySQL / MariaDB (`ess_apps`), accessed through **ADOdb** (`mysqli` driver) |
| Templates | **Smarty 4.5** (`smarty/templates/*.tpl`) |
| PDF | **TCPDF 6.7**, **FPDF 1.8** |
| Mail | **PHPMailer 6.9** (legacy Pommo code still referenced in docs) |
| Front end | jQuery 1.x + plugins, DataTables-style client sorting, framesets, HTML 4.01 doctypes |
| Dependency management | Composer (`vendor/` is git-ignored) |

### Architecture

The app is a **classic script-per-page PHP app being migrated to controller/model/MVC-lite**:

```
index.html  ── frameset ──► links.php (left menu)  +  capa.php / capa_logado.php (body)

<module>/listar.php            thin front-controller: require + new Controller + one action
        └──► <module>/<Module>Controller.php    HTTP only: params, sanitization, auth include,
                                                Smarty assigns, redirects
                    └──► libphp/<Model>.php     all SQL and business rules
                              └──► libphp/Model.php (ADODB_Model)  generic CRUD over $db
```

Bootstrap chain: `setup.php` → connects ADOdb (`$db`) → defines `Smarty_estagio` → reads the
`configuracoes` table into constants/globals (`PERIODO_ATUAL`, `PERIODO_ANTERIOR`, `TURMA`,
`ENCERRAMENTO`, `TC_PERIODO_ATUAL`, `TC_PERIODO_ANTERIOR`).

`autentica.inc` requires `setup.php` + `libphp/sessao.php`, populates `$usuario_*` globals and
`$isAdmin` / `$isAluno` / `$isProfessor` / `$isSupervisor`, and redirects to
`http://<server>/estagio/login.php` when there is no authenticated session.

### Module directories (one per table/domain)

`administracao/` `alunos/` `areas/` `configuracoes/` `curso/` `estagiarios/` `imprimir/`
`inscricoes/` `instituicoes/` `mural/` `professores/` `supervisores/` `turnos/` `users/` `visitas/`

Controllers found under each module (`*Controller.php`): Alunos, Areas, Configuracoes,
Estagiarios, Inscricoes, Instituicoes, Professores, Supervisores, Turnos, Users, Visitas,
plus `mural/MuralestagiosController.php` and `mural/HistoricoController.php`.

Models in `libphp/`: `Aluno`, `Area`, `Configuracao`, `Estagiario`, `Inscricao`, `Instituicao`,
`Mural`, `Professor`, `Supervisor`, `Turno`, `User`, `Visita`.

## Building and Running

There is **no build step** — it is plain PHP served by Apache.

```bash
# 1. Install PHP dependencies (vendor/ is git-ignored — required before anything runs)
composer install

# 2. Serve the app. apache-estagio.conf maps Alias /estagio -> /home/luis/html/dbestagio
#    Drop it into Apache's conf dir (or include it), then browse:
#      http://localhost/estagio/index.html
#    The app assumes the /estagio path prefix (autentica.inc redirects there).

# Alternative quick check without Apache (PHP built-in server):
php -S localhost:8080 -t /home/luis/html/dbestagio
```

### Database

No SQL schema dump is committed. Credentials are **hard-coded in two places** and must be
changed together:

- `setup.php`
- `db.inc` (minimal connection only, no `configuracoes` load)

Default dev values: host `localhost`, user `root`, password `root`, database `ess_apps`.
A `mysql`/`mariadb` client is available on the dev machine.

### Verification / testing

**There is no test framework** (no PHPUnit, no `tests/` directory, no test config). Verification
is limited to:

```bash
# Syntax lint — run on every file you touch (this is the project's only automated check)
php -l <file.php>

# Bulk lint of the application code (excludes vendored libs)
find . -name '*.php' -not -path './vendor/*' -not -path './lib/*' -exec php -l {} \; | grep -v 'No syntax errors'
```

Everything else must be verified manually in the browser against a real `ess_apps` database.
Say so explicitly when a change could not be exercised end-to-end.

## Development Conventions

### Language

**All comments, docblocks, error messages and user-facing strings are in Brazilian Portuguese.**
Identifiers and DB column names are also Portuguese (`aluno_id`, `registro`, `periodo`).
Keep new code consistent — do not introduce English prose into comments or messages.

### Code style

- Tabs for indentation in controllers/models; PHP opening `<?php` and closing `?>` retained.
- Every class file is wrapped in an include guard: `if (!class_exists('Foo', false)) { ... }`.
- Files start with a docblock stating what the file is, what legacy script it replaces, and how
  to use it. Follow that pattern for new files.
- Legacy-compatible `array()` syntax is common; new code may use short arrays but match the
  surrounding file.

### Controllers

Controllers handle **HTTP only**: read/sanitize parameters, include `autentica.inc`, call model
methods, `assign()` to Smarty, and `header("Location: ...")` + `exit`. All SQL and business logic
lives in the model. Documented pattern:

```php
private function comAutenticacao() {
    global $db, $sistema_autentica, $isAdmin, $isAluno, $usuario_id;
    include_once(__DIR__ . "/../autentica.inc");
    require_once(__DIR__ . "/../libphp/models.php");
}
```

`global $db` must be declared **before** the include, because `setup.php` (pulled in by
`autentica.inc`) writes `$db` into `$GLOBALS`, which `libphp/models.php` then binds via
`ADODB_Model::setDb()`.

Front-controller scripts stay thin and keep the legacy public URL:

```php
require_once(__DIR__ . "/AlunosController.php");
$controller = new AlunosController();
$controller->listar();
```

### Models (`libphp/Model.php` — `ADODB_Model`)

Subclasses set `static::$table`, `$pk`, `$columns`, `$hidden` (e.g. `senha`), `$timestamps`.
API: `find()`, `firstWhere()`, `where()`, `all()`, `query()`, `raw()`, `count()`,
`save()` (INSERT/UPDATE), `delete()`, `toArray()`, magic `__get`/`__set`.

`libphp/models.php` must be included **after** `$db` exists; it registers an autoloader for
`libphp/<Class>.php` and `libphp/models/<Class>.php` and eager-requires the common models.

### Security rules (do not regress)

- **Always use bound parameters** — `$db->Execute($sql, array($val))` / `ADODB_Model` methods.
  Never interpolate request data into SQL.
- **Whitelist `ORDER BY` columns** against a fixed map (see `AlunosController::listar()`).
- **Authorization comes only from the PHP session.** `sessao_usuario_atual()` re-reads the
  `users` row on every request and checks `ativo === 1`. The legacy cookies `usuario` and
  `usuario_nome` exist **only** so the JavaScript menus can render admin blocks — they must
  never be used to authorize anything.
- Cast request input at the boundary: `(int)$_REQUEST['aluno_id']`, `trim((string)...`.
- Validate domain formats explicitly (e.g. `periodo`/`ingresso` must match `/^\d{4}-[12]$/`).

### Smarty templates

- All templates live in `smarty/templates/` and are named
  **`<module>-<legacy_subdir>_<legacy_script>.tpl`**, mirroring the script they replaced
  (e.g. `alunos-exibir_ver_cada.tpl` ← `alunos/exibir/ver_cada.php`). Follow this when adding.
- Compiled output goes to `smarty/templates_c/`, cache to `smarty/cache/`; both are git-ignored.
- `Smarty_estagio` sets `caching = true` and `compile_check = true`; **`compile_check` must be
  `false` in production**. The debug console is off and URL-triggered only from localhost.
- Templates are HTML 4.01 Transitional with `{literal}...{/literal}` around raw JavaScript.

### Vendored vs Composer libraries

`lib/` still holds legacy copies of adodb5, fpdf, tcpdf, phpmailer, jquery + plugins,
tablesorter, mygosumenu, xajax, jpgraph and libchart; `libphp/` holds `phpMyEdit.class.php`
(with `libphp/lang/` translations) and helpers (`password_helper.php`, `PDFHelper.php`).
**Composer (`vendor/`) is now the canonical source** for adodb, smarty, tcpdf, fpdf and
phpmailer — prefer `vendor/` and constants defined in `setup.php` (`ADODB`, `SMARTY_DIR`,
`FPDF`, `MAILER`, `TMP`, `RAIZ`) over the `lib/` copies. Don't edit files under `vendor/`,
`lib/` or `libphp/phpMyEdit.class.php`.

### Domain conventions

- Academic period format is **`AAAA-S`** where `S` is `1` or `2` (semester). `PERIODO_ANTERIOR`
  is derived by arithmetic in `setup.php`.
- `alunos.id` is **not** `AUTO_INCREMENT` — new rows call `Aluno::proximoId()`.
- Dates are shown to users as `dd/mm/aaaa` and stored as `AAAA-MM-DD`; use the controller
  helper `normalizaData()` pattern. Beware the legacy `'0000-00-00'` sentinel in date columns.
- **Never call `strtotime()` directly on a `configuracoes` value.** Use `estagio_formata_data()`
  from `setup.php`, which returns `''` for NULL/empty/`'0000-00-00'` instead of letting
  `strtotime(null)` yield `false` and `date()` silently render `01/01/1970`. The nullable
  `curso_abertura_inscricoes` / `curso_encerramento_inscricoes` columns make this a live risk, so
  consumers must handle `''` explicitly — see the deadline gate in `curso/inscricao_supervisores.php`
  (no configured deadline = fail **closed**, and log the cause).
- **`TURMA` is always an `int`; `0` means "turma não configurada".**
  `configuracoes.curso_turma_atual` is nullable, but the legacy `curso/` scripts interpolate it
  *unquoted* into SQL (`where curso_turma=$turma`), so a NULL yielded `where curso_turma=` and a
  MariaDB syntax error. `setup.php` now casts it — which fixes every consumer at once — and the
  write path (`curso/inscricao_curso.php`) refuses the registration instead of storing an orphan
  row. Read-only pages degrade to "no rows". Keep the cast if you add a consumer.
- Current period, current class (`TURMA`) and registration deadlines come from the
  `configuracoes` table — change behavior through that table/admin UI, not by editing constants.

### Git

Current branch: `new-master`. Its upstream is **not** stable — it currently tracks
`origin/trae/agent-oLpLFE`, while `origin/master` also exists. Check `git status` or
`git rev-parse --abbrev-ref @{upstream}` before assuming, and never push without being asked.
Commit messages are inconsistent (several are tool-generated noise); use a clear conventional
prefix with a real description, e.g. `fix(mural): ...`, `feat(alunos): ...`, focused on *why*.

Never commit `vendor/`, `smarty/cache/`, `smarty/templates_c/` or `tmp/` (see `.gitignore`).
`docs/wiki/` is preserved project documentation (see `preserve-wiki-docs.patch`) — keep it intact.

## Key Files

| File | Purpose |
| --- | --- |
| `setup.php` | Main bootstrap: ADOdb connection, `Smarty_estagio`, `configuracoes` constants |
| `db.inc` | Standalone minimal DB connection for scripts that don't need `setup.php` |
| `autentica.inc` | Login gate; sets `$isAdmin`/`$isAluno`/`$isProfessor`/`$isSupervisor` |
| `libphp/sessao.php` | Session-based identity and authorization (`sessao_*` functions) |
| `libphp/Model.php` | `ADODB_Model` — generic table abstraction over ADOdb |
| `libphp/models.php` | Autoloader + binds `$GLOBALS['db']` to the models |
| `links.php` | Left-hand frameset menu; renders admin block only for `role === 'admin'` |
| `login.php` / `verifica_login.php` / `logout.php` | Authentication entry points |
| `capa.php` / `capa_logado.php` | Frameset body (anonymous / logged in) |
| `index.html` | Frameset entry point (`links.php` + `capa.php`) |
| `apache-estagio.conf` | Apache `Alias /estagio` for this directory |
| `composer.json` | PHP 8.x dependency set |
| `README.md`, `docs/wiki/ProjectHome.md`, `docs/wiki/Apresentacao.md` | Project documentation |
