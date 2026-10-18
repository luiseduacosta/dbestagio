<?php
/**
 * Inscricao.php — Modelo da tabela `inscricoes` (alunos inscritos nas ofertas do mural).
 *
 * O módulo lista/insere/edita/exclui o registro de um aluno em uma oferta de
 * estágio (mural_estagios). A listagem faz JOIN com `alunos` e `mural_estagios`
 * na própria consulta (sem N+1).
 */
if (!class_exists('Inscricao', false)) {

class Inscricao extends ADODB_Model {
    protected static $table  = 'inscricoes';
    protected static $pk     = 'id';
    protected static $hidden = array();

    /**
     * Períodos distintos com inscrições registradas (do mais recente ao mais antigo).
     */
    public static function periodos() {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT DISTINCT periodo FROM inscricoes WHERE periodo IS NOT NULL AND periodo <> '' ORDER BY periodo DESC"
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = $rs->fields['periodo'];
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Ofertas do mural de um período, para popular o seletor do formulário.
     *
     * @param string $periodo Vazio = todas as ofertas.
     * @return array[]['id'|'instituicao']
     */
    public static function ofertasPorPeriodo($periodo = '') {
        $db = static::db();
        $params = array();
        $sql = "SELECT id, instituicao, periodo FROM mural_estagios";
        if ($periodo !== '') {
            $sql .= " WHERE periodo = ?";
            $params[] = $periodo;
        }
        $sql .= " ORDER BY instituicao";
        $rs = $db->Execute($sql, $params);
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'          => (int)$rs->fields['id'],
                    'instituicao' => $rs->fields['instituicao'],
                    'periodo'     => $rs->fields['periodo'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Busca um aluno na tabela `alunos` pelo registro (matrícula).
     *
     * @param int $registro
     * @return array|null
     */
    public static function alunoPorRegistro($registro) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT id, registro, nome, email FROM alunos WHERE registro = ? LIMIT 1",
            array((int)$registro)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        return $rs->fields;
    }

    /**
     * Já existe uma inscrição do mesmo aluno (registro) na mesma oferta/período?
     */
    public static function duplicada($registro, $muralestagio_id, $periodo, $ignorarId = null) {
        $db = static::db();
        $sql = "SELECT id FROM inscricoes WHERE registro = ? AND muralestagio_id = ? AND periodo = ?";
        $params = array((int)$registro, (int)$muralestagio_id, $periodo);
        if ($ignorarId !== null) {
            $sql .= " AND id <> ?";
            $params[] = (int)$ignorarId;
        }
        $sql .= " LIMIT 1";
        $rs = $db->Execute($sql, $params);
        return ($rs !== false && $rs->RecordCount() > 0) ? (int)$rs->fields['id'] : null;
    }

    /**
     * Lista inscrições (com aluno e oferta), opcionalmente filtradas por oferta.
     *
     * @param string $periodo         Período ('' = todos).
     * @param int    $muralestagio_id Filtra uma oferta específica (0 = todas).
     * @return array Registros formatados (data em dd-mm-aaaa e timestamp legível).
     */
    public static function listar($periodo = '', $muralestagio_id = 0, $aluno_id = 0, $aluno_registro = '') {
        $db = static::db();

        $sql = "SELECT i.id, i.registro, i.muralestagio_id, i.data, i.periodo, i.timestamp, "
             . "i.aluno_id, m.instituicao AS oferta_instituicao, "
             . "al.nome AS aluno_nome, al.email AS aluno_email, "
             . "al.telefone AS aluno_telefone, al.celular AS aluno_celular "
             . "FROM inscricoes AS i "
             . "LEFT JOIN mural_estagios AS m ON m.id = i.muralestagio_id "
             . "LEFT JOIN alunos AS al        ON al.registro = i.registro";

        $where = array();
        $params = array();
        if ($periodo !== '') {
            $where[] = "i.periodo = ?";
            $params[] = $periodo;
        }
        if ($muralestagio_id > 0) {
            $where[] = "i.muralestagio_id = ?";
            $params[] = (int)$muralestagio_id;
        }
        if ($aluno_id > 0 || $aluno_registro !== '') {
            $where[] = "(i.aluno_id = ? OR i.registro = ?)";
            $params[] = (int)$aluno_id;
            $params[] = (int)$aluno_registro;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY i.data DESC, i.id DESC";

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela inscricoes");
        }

        $out = array();
        while (!$rs->EOF) {
            $out[] = array(
                'id'                 => (int)$rs->fields['id'],
                'registro'           => (int)$rs->fields['registro'],
                'muralestagio_id'    => (int)$rs->fields['muralestagio_id'],
                'data'               => self::dataTexto($rs->fields['data']),
                'data_iso'           => self::dataIso($rs->fields['data']),
                'periodo'            => $rs->fields['periodo'],
                'timestamp'          => $rs->fields['timestamp'],
                'aluno_id'           => (int)$rs->fields['aluno_id'],
                'oferta_instituicao' => $rs->fields['oferta_instituicao'],
                'aluno_nome'         => $rs->fields['aluno_nome'],
                'aluno_email'        => $rs->fields['aluno_email'],
                'aluno_telefone'     => $rs->fields['aluno_telefone'],
                'aluno_celular'      => $rs->fields['aluno_celular'],
            );
            $rs->MoveNext();
        }
        return $out;
    }

    /**
     * Lista os ALUNOS com inscrição em um período e/ou oferta, agregando as
     * inscrições por aluno — tabela única do mural (mural/inscritos.php).
     *
     * Substitui as quatro listagens legadas do mural:
     *   mural/lista-alunos.php       -> grupo 'todos'      (quem busca estágio)
     *   mural/lista-novos_alunos.php -> grupo 'novos'      (nunca estagiou antes)
     *   mural/alunosConhecidos.php   -> grupo 'conhecidos' (já estagiou; muda de estágio)
     *   mural/listaInscritos.php     -> muralestagio_id > 0 (inscritos de uma oferta)
     *
     * Uma linha por aluno: as inscrições do escopo (período/oferta) viram
     * 'quantidade', 'ofertas' e as datas de início/última. Ingresso, estágios e
     * nomes de instituições são buscados em bloco com IN (...) — sem N+1.
     *
     * @param string $periodo         Período exibido ('' = todos os períodos).
     * @param int    $muralestagio_id Filtra uma oferta específica (0 = todas).
     * @param string $grupo           'todos', 'novos' ou 'conhecidos'.
     * @return array Linhas prontas para o template (uma por aluno).
     */
    public static function listarAlunosPorPeriodo($periodo = '', $muralestagio_id = 0, $grupo = 'todos') {
        $db = static::db();

        // 1) Inscrições do escopo, com aluno e oferta resolvidos no JOIN.
        $sql = "SELECT i.id, i.registro, i.aluno_id, i.muralestagio_id, i.periodo, i.data, "
             . "m.instituicao AS oferta_instituicao, "
             . "al.nome, al.email, al.telefone, al.celular "
             . "FROM inscricoes AS i "
             . "LEFT JOIN mural_estagios AS m ON m.id = i.muralestagio_id "
             . "LEFT JOIN alunos AS al        ON al.registro = i.registro";

        $where  = array();
        $params = array();
        if ($periodo !== '') {
            $where[]  = "i.periodo = ?";
            $params[] = $periodo;
        }
        if ($muralestagio_id > 0) {
            $where[]  = "i.muralestagio_id = ?";
            $params[] = (int)$muralestagio_id;
        }
        if ($where) {
            $sql .= " WHERE " . implode(" AND ", $where);
        }
        $sql .= " ORDER BY al.nome, i.data, i.id";

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            die("Não foi possível consultar a tabela inscricoes");
        }

        $alunos = array(); // registro => linha agregada do aluno
        while (!$rs->EOF) {
            $f   = $rs->fields;
            $reg = (int)$f['registro'];
            if (!isset($alunos[$reg])) {
                $alunos[$reg] = array(
                    'registro'     => $reg,
                    'aluno_id'     => (int)$f['aluno_id'],
                    'nome'         => (string)$f['nome'],
                    'email'        => (string)$f['email'],
                    'telefone'     => (string)$f['telefone'],
                    'celular'      => (string)$f['celular'],
                    'quantidade'   => 0,
                    'data_inicio'  => '', // ISO, para a ordenação do DataTables
                    'data_ultima'  => '', // ISO
                    'periodos'     => array(),
                    'ofertas'      => array(),
                    'inscricao_id' => 0,  // preenchido só no filtro por oferta
                );
            }

            $alunos[$reg]['quantidade']++;

            $data = self::dataIso($f['data']);
            if ($data !== '') {
                if ($alunos[$reg]['data_inicio'] === '' || $data < $alunos[$reg]['data_inicio']) {
                    $alunos[$reg]['data_inicio'] = $data;
                }
                if ($alunos[$reg]['data_ultima'] === '' || $data > $alunos[$reg]['data_ultima']) {
                    $alunos[$reg]['data_ultima'] = $data;
                }
            }

            if (!in_array($f['periodo'], $alunos[$reg]['periodos'], true)) {
                $alunos[$reg]['periodos'][] = $f['periodo'];
            }

            $oferta = (string)$f['oferta_instituicao'];
            if ($oferta === '') {
                $oferta = "Oferta " . (int)$f['muralestagio_id'];
            }
            if (!in_array($oferta, $alunos[$reg]['ofertas'], true)) {
                $alunos[$reg]['ofertas'][] = $oferta;
            }

            // Com o filtro por oferta o aluno tem no máximo uma inscrição nela
            // (Inscricao::duplicada impede repetição), então o id é único.
            if ($muralestagio_id > 0) {
                $alunos[$reg]['inscricao_id'] = (int)$f['id'];
            }

            $rs->MoveNext();
        }

        if (empty($alunos)) {
            return array();
        }

        $registros = array_keys($alunos);
        $placeh    = implode(', ', array_fill(0, count($registros), '?'));

        // 2) Período/turno de ingresso na universidade (uma consulta em bloco;
        //    quando há mais de uma linha, vale a primeira — como no legado).
        $rs = $db->Execute(
            "SELECT registro, periodo, turno FROM alunos_ingresso WHERE registro IN ($placeh)",
            $registros
        );
        while ($rs && !$rs->EOF) {
            $reg = (int)$rs->fields['registro'];
            if (isset($alunos[$reg]) && !isset($alunos[$reg]['data_ingresso'])) {
                $alunos[$reg]['data_ingresso'] = (string)$rs->fields['periodo'];
                $alunos[$reg]['turno']         = (string)$rs->fields['turno'];
            }
            $rs->MoveNext();
        }

        // 3) Estágios dos alunos (poucos por aluno): período, nível, termo de
        //    compromisso e instituição de cada estágio.
        $estagios         = array(); // registro => estágios, ordenados por período
        $instituicoes_ids = array();
        $rs = $db->Execute(
            "SELECT registro, periodo, nivel, tc, instituicao_id FROM estagiarios "
          . "WHERE registro IN ($placeh) ORDER BY registro, periodo, id",
            $registros
        );
        while ($rs && !$rs->EOF) {
            $f = $rs->fields;
            $estagios[(int)$f['registro']][] = $f;
            $iid = (int)$f['instituicao_id'];
            if ($iid > 0) {
                $instituicoes_ids[$iid] = true;
            }
            $rs->MoveNext();
        }

        // 4) Nome das instituições usadas pelos estágios (evita N+1).
        $instituicoes = array();
        if ($instituicoes_ids) {
            $ids = array_keys($instituicoes_ids);
            $ph2 = implode(', ', array_fill(0, count($ids), '?'));
            $rs  = $db->Execute("SELECT id, instituicao FROM instituicoes WHERE id IN ($ph2)", $ids);
            while ($rs && !$rs->EOF) {
                $instituicoes[(int)$rs->fields['id']] = (string)$rs->fields['instituicao'];
                $rs->MoveNext();
            }
        }

        // 5) Consolida estágio, nível, situação e textos de cada aluno.
        $out = array();
        foreach ($alunos as $reg => $a) {
            $est = isset($estagios[$reg]) ? $estagios[$reg] : array();

            $a['data_ingresso']       = isset($a['data_ingresso']) ? $a['data_ingresso'] : '';
            $a['turno']               = isset($a['turno']) ? $a['turno'] : '';
            $a['nivel']               = '';
            $a['tc']                  = '';
            $a['estagio_periodo']     = '';
            $a['estagio_instituicao'] = '';

            $nivel_max = 0;
            $tem_outro = false; // estágio registrado fora do período exibido
            foreach ($est as $e) {
                $nivel = (int)$e['nivel'];
                if ($nivel > $nivel_max) {
                    $nivel_max = $nivel;
                }
                if ($periodo === '' || (string)$e['periodo'] !== $periodo) {
                    $tem_outro = true;
                }
                if ($periodo !== '' && (string)$e['periodo'] === $periodo) {
                    // Nível e TC do período exibido (como nas listas legadas).
                    $a['nivel'] = $nivel;
                    $a['tc']    = (string)$e['tc'];
                }
            }

            // Último estágio = maior período (a lista já vem ordenada).
            if ($est) {
                $ultimo = $est[count($est) - 1];
                $a['estagio_periodo']     = (string)$ultimo['periodo'];
                $iid                      = (int)$ultimo['instituicao_id'];
                $a['estagio_instituicao'] = isset($instituicoes[$iid]) ? $instituicoes[$iid] : '';
                if ($a['nivel'] === '') {
                    $a['nivel'] = $nivel_max;
                }
            }

            // "Novo" = ainda não estagiou fora do período exibido (critério de
            // lista-novos_alunos.php); "estagiário" = já estagiou ou está em
            // nível > 1 no período exibido (critério de lista-alunos.php).
            $a['situacao']       = ($tem_outro || (int)$a['nivel'] > 1) ? 'estagiario' : 'novo';
            $a['situacao_texto'] = ($a['situacao'] === 'estagiario') ? 'Já estagiou' : 'Novo';

            // Filtro por grupo: reproduz as três listas de alunos do mural.
            if ($grupo === 'novos' && $tem_outro) {
                continue;
            }
            if ($grupo === 'conhecidos' && empty($est)) {
                continue;
            }

            $a['periodos_texto']  = implode(', ', $a['periodos']);
            $a['ofertas_texto']   = implode('; ', $a['ofertas']);
            $a['data_inicio_txt'] = self::dataTexto($a['data_inicio']);
            $a['data_ultima_txt'] = self::dataTexto($a['data_ultima']);
            unset($a['periodos'], $a['ofertas']);
            $out[] = $a;
        }

        return $out;
    }

    /**
     * Retorna UMA inscrição com dados do aluno e da oferta, ou null.
     */
    public static function buscar($id) {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT i.id, i.registro, i.muralestagio_id, i.data, i.periodo, i.timestamp, "
            . "i.aluno_id, m.instituicao AS oferta_instituicao, "
            . "al.nome AS aluno_nome, al.email AS aluno_email, "
            . "al.telefone AS aluno_telefone, al.celular AS aluno_celular "
            . "FROM inscricoes AS i "
            . "LEFT JOIN mural_estagios AS m ON m.id = i.muralestagio_id "
            . "LEFT JOIN alunos AS al        ON al.registro = i.registro "
            . "WHERE i.id = ? LIMIT 1",
            array((int)$id)
        );
        if ($rs === false || $rs->RecordCount() == 0) {
            return null;
        }
        $f = $rs->fields;
        return array(
            'id'                 => (int)$f['id'],
            'registro'           => (int)$f['registro'],
            'muralestagio_id'    => (int)$f['muralestagio_id'],
            'data'               => self::dataTexto($f['data']),
            'data_iso'           => self::dataIso($f['data']),
            'periodo'            => $f['periodo'],
            'timestamp'          => $f['timestamp'],
            'aluno_id'           => (int)$f['aluno_id'],
            'oferta_instituicao' => $f['oferta_instituicao'],
            'aluno_nome'         => $f['aluno_nome'],
            'aluno_email'        => $f['aluno_email'],
            'aluno_telefone'     => $f['aluno_telefone'],
            'aluno_celular'      => $f['aluno_celular'],
        );
    }

    /**
     * Remove todas as inscrições de um aluno no período informado.
     * Usada pela lista de alunos que buscam estágio do mural ("Excluir"):
     * tira o aluno do processo do período sem tocar no registro da tabela
     * alunos — a exclusão do aluno em si continua no módulo alunos
     * (alunos/excluir.php), que checa dependências antes de apagar.
     *
     * @param int    $registro Matrícula (DRE) do aluno.
     * @param string $periodo  Período das inscrições (ex.: "2025-2").
     * @param int    $aluno_id (Opcional) PK do aluno na tabela alunos.
     * @return int|false Número de inscrições excluídas ou false em erro.
     */
    public static function excluirDoPeriodo($registro, $periodo, $aluno_id = 0) {
        $db = static::db();
        $conds = array();
        $params = array();

        if ((int)$registro > 0) {
            $conds[] = "registro = ?";
            $params[] = (int)$registro;
        }
        if ((int)$aluno_id > 0) {
            $conds[] = "aluno_id = ?";
            $params[] = (int)$aluno_id;
        }

        if (empty($conds)) {
            return 0;
        }

        $sql = "DELETE FROM inscricoes WHERE (" . implode(" OR ", $conds) . ") AND periodo = ?";
        $params[] = $periodo;

        $rs = $db->Execute($sql, $params);
        if ($rs === false) {
            return false;
        }
        return (int)$db->Affected_Rows();
    }

    /**
     * Lista de alunos (registro + nome) para o seletor do formulário.
     */
    public static function alunosLista() {
        $db = static::db();
        $rs = $db->Execute("SELECT id, registro, nome FROM alunos ORDER BY nome");
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = array(
                    'id'       => (int)$rs->fields['id'],
                    'registro' => (string)$rs->fields['registro'],
                    'nome'     => $rs->fields['nome'],
                );
                $rs->MoveNext();
            }
        }
        return $out;
    }

    private static function dataIso($date) {
        if (empty($date) || $date === '0000-00-00') return '';
        $ts = strtotime($date);
        return ($ts === false) ? '' : date('Y-m-d', $ts);
    }

    private static function dataTexto($date) {
        $iso = self::dataIso($date);
        if ($iso === '') return '';
        $ts = strtotime($iso);
        return ($ts === false) ? '' : date('d-m-Y', $ts);
    }
}

}

?>