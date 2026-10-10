<?php

require_once __DIR__ . '/Model.php';

/**
 * Mural.php — Modelo para a tabela mural_estagios (oferta de estágios do mural).
 *
 * Concentra todo o acesso à tabela:
 *  - listagem por período (index) com contagem de inscritos sem N+1 e
 *    ordenação por coluna em lista fechada (proteção contra SQL injection);
 *  - navegação registro a registro por posição (ver_cada);
 *  - inclusão e alteração (preencher + save herdado de ADODB_Model, que
 *    grava com parâmetros ligados — sem interpolação de strings);
 *  - formatação dos campos codificados (final_de_semana, horario,
 *    forma_selecao e datas em dd-mm-aaaa, mantendo o ISO separado para a
 *    ordenação do DataTables).
 *
 * Colunas graváveis (ver $colunas_gravaveis): a tabela NÃO possui as colunas
 * de área/professor que existiam no cadastro antigo — os seletores desses
 * campos nos formulários são mantidos apenas visuais e não são gravados.
 */
if (!class_exists('Mural', false)) {

class Mural extends ADODB_Model {
    protected static $table  = 'mural_estagios';
    protected static $pk     = 'id';
    protected static $hidden = array();

    /** Colunas que preencher() aceita (defesa contra campos extras no POST). */
    private static $colunas_gravaveis = array(
        'instituicao_id', 'instituicao', 'convenio', 'vagas', 'beneficios',
        'final_de_semana', 'carga_horaria', 'requisitos', 'horario',
        'data_selecao', 'data_inscricao', 'horario_selecao', 'local_selecao',
        'forma_selecao', 'contato', 'email', 'periodo', 'outras',
    );

    /** Ordenações permitidas na listagem (chave da URL => expressão SQL). */
    private static $ordens_listagem = array(
        'instituicao'    => 'm.instituicao',
        'vagas'          => 'm.vagas',
        'beneficios'     => 'm.beneficios',
        'data_inscricao' => 'm.data_inscricao',
        'data_selecao'   => 'm.data_selecao',
    );

    /**
     * Preenche as colunas graváveis a partir de um array associativo
     * (tipicamente $_POST já higienizado pelo controlador).
     */
    public function preencher(array $dados) {
        foreach (self::$colunas_gravaveis as $col) {
            if (array_key_exists($col, $dados)) {
                $this->$col = $dados[$col];
            }
        }
        return $this;
    }

    // ------------------------------------------------------------------
    // Listagem (index)
    // ------------------------------------------------------------------

    /**
     * Períodos distintos com registros no mural, do mais recente para o mais
     * antigo. Usados no seletor de período da listagem.
     */
    public static function periodos() {
        $db = static::db();
        $rs = $db->Execute(
            "SELECT DISTINCT periodo FROM mural_estagios WHERE periodo IS NOT NULL AND periodo <> '' ORDER BY periodo DESC"
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
     * Lista as ofertas do mural de um período, com a quantidade de inscritos
     * calculada na própria consulta (elimina N+1).
     *
     * @param string $periodo Período (ex.: "2025-2"). Vazio = todos.
     * @param string $ordem   Chave de ordenação (whitelist interna);
     *                        vazio/inválido = data_inscricao DESC.
     * @return array Registros formatados para exibição (lista amigável ao DataTables).
     */
    public static function listarMuralPorPeriodo($periodo = '', $ordem = '') {
        $db = static::db();

        $orderby = 'm.data_inscricao DESC';
        if ($ordem !== '' && isset(self::$ordens_listagem[$ordem])) {
            $orderby = self::$ordens_listagem[$ordem];
        }

        $sql = "SELECT m.id AS mural_estagio_id, m.instituicao_id, m.instituicao, m.convenio, m.vagas, "
             . "m.beneficios, m.final_de_semana, m.carga_horaria, m.requisitos, m.horario, "
             . "m.data_selecao, m.horario_selecao, m.data_inscricao, m.local_selecao, "
             . "m.forma_selecao, m.contato, m.email, m.periodo, m.outras, "
             . "COALESCE((SELECT COUNT(i.registro) FROM inscricoes AS i "
             . "          WHERE i.muralestagio_id = m.id"
             . ($periodo !== '' ? "          AND i.periodo = ?" : '')
             . "), 0) AS quantidade_alunos "
             . "FROM mural_estagios AS m";
        $params = array();
        if ($periodo !== '') {
            // Subselect de inscritos filtra pelo período, e o WHERE também.
            $params[] = $periodo;
            $params[] = $periodo;
            $sql .= " WHERE m.periodo = ?";
        }
        $sql .= " ORDER BY $orderby";

        $resultado = $db->Execute($sql, $params);
        if ($resultado === false) {
            die("Não foi possível consultar a tabela mural_estagios");
        }

        $out = array();
        while (!$resultado->EOF) {
            $out[] = self::formatar($resultado->fields);
            $resultado->MoveNext();
        }
        return $out;
    }

    /**
     * Estatísticas de alunos inscritos no período (cabeçalho do mural).
     * total      = inscritos distintos;
     * conhecidos = que já constam em estagiarios;
     * estagio_um = conhecidos que já fizeram estágio de nível 1 no período.
     */
    public static function estatisticasInscritos($periodo) {
        $db = static::db();

        $total = (int)$db->GetOne(
            "SELECT COUNT(DISTINCT registro) FROM inscricoes WHERE periodo = ?",
            array($periodo)
        );

        $conhecidos = (int)$db->GetOne(
            "SELECT COUNT(DISTINCT i.registro) FROM inscricoes AS i "
          . "WHERE i.periodo = ? "
          . "AND EXISTS (SELECT 1 FROM estagiarios AS e WHERE e.registro = i.registro)",
            array($periodo)
        );

        $estagio_um = (int)$db->GetOne(
            "SELECT COUNT(DISTINCT i.registro) FROM inscricoes AS i "
          . "WHERE i.periodo = ? "
          . "AND EXISTS (SELECT 1 FROM estagiarios AS e WHERE e.registro = i.registro "
          . "            AND e.periodo = ? AND e.nivel = 1)",
            array($periodo, $periodo)
        );

        return array(
            'total'      => $total,
            'conhecidos' => $conhecidos,
            'estagio_um' => $estagio_um,
        );
    }

    // ------------------------------------------------------------------
    // Visualização (ver_cada)
    // ------------------------------------------------------------------

    /**
     * Quantidade de ofertas de um período.
     */
    public static function contarPorPeriodo($periodo) {
        return (int)static::db()->GetOne(
            "SELECT COUNT(*) FROM mural_estagios WHERE periodo = ?",
            array($periodo)
        );
    }

    /**
     * Soma da coluna 'vagas' de todas as ofertas do mural no período.
     *
     * @param string $periodo Período (ex.: "2025-2").
     * @return int Soma das vagas; 0 quando não há registros.
     */
    public static function totalVagasPorPeriodo($periodo) {
        return (int)static::db()->GetOne(
            "SELECT COALESCE(SUM(vagas), 0) FROM mural_estagios WHERE periodo = ?",
            array($periodo)
        );
    }

    /**
     * Ids das ofertas do período na ordenação usada pela navegação
     * (instituicao, id — desempate para instituições repetidas).
     */
    public static function idsPorPeriodo($periodo) {
        $rs = static::db()->Execute(
            "SELECT id FROM mural_estagios WHERE periodo = ? ORDER BY instituicao, id",
            array($periodo)
        );
        $out = array();
        if ($rs) {
            while (!$rs->EOF) {
                $out[] = (int)$rs->fields['id'];
                $rs->MoveNext();
            }
        }
        return $out;
    }

    /**
     * Posição (0-based) de um registro na ordenação da navegação,
     * ou null se o id não pertence ao período.
     */
    public static function posicaoDoRegistro($id, $periodo) {
        $pos = array_search((int)$id, self::idsPorPeriodo($periodo), true);
        return ($pos === false) ? null : $pos;
    }

    /**
     * Registro formatado da posição $indice (com wrap-around) para o ver_cada;
     * null quando o período não tem registros.
     */
    public static function registroDaPosicao($periodo, $indice) {
        $ids = self::idsPorPeriodo($periodo);
        $n = count($ids);
        if ($n === 0) {
            return null;
        }
        $indice = (int)$indice % $n;
        if ($indice < 0) {
            $indice += $n;
        }
        $mural = self::find($ids[$indice]);
        if (!$mural) {
            return null;
        }
        return self::formatar($mural->toArray(true));
    }

    // ------------------------------------------------------------------
    // Datas (entrada de formulário)
    // ------------------------------------------------------------------

    /**
     * Converte data vinda de formulário (dd-mm-aaaa, aaaa-mm-dd ou aaaammdd)
     * em 'aaaa-mm-dd' gravável. Null quando vazia/inválida.
     * Usa createFromFormat com '!' (campos resetados), evitando a ambiguidade
     * dd/mm vs mm/dd do strtotime.
     */
    public static function dataParaGravar($valor) {
        $valor = trim((string)$valor);
        if ($valor === '' || $valor === '00-00-0000') {
            return null;
        }
        foreach (array('d-m-Y', 'Y-m-d', 'Ymd') as $fmt) {
            $dt = DateTime::createFromFormat('!' . $fmt, $valor);
            if ($dt !== false) {
                return $dt->format('Y-m-d');
            }
        }
        $ts = strtotime($valor);
        return ($ts === false) ? null : date('Y-m-d', $ts);
    }

    // ------------------------------------------------------------------
    // Formatação de registros
    // ------------------------------------------------------------------

    /**
     * Transforma uma linha bruta de mural_estagios no formato usado pelos
     * templates (campos codificados como texto, datas em dd-mm-aaaa com ISO
     * separado para ordenação do DataTables).
     */
    public static function formatar($f) {
        $tem_inscritos = isset($f['quantidade_alunos']);
        return array(
            'mural_estagio_id'   => (int)$f['id'],
            'muralestagio_id'    => (int)$f['id'], // alias usado por ver_cada.tpl
            'instituicao_id'     => (int)$f['instituicao_id'],
            'instituicao'        => $f['instituicao'],
            'convenio'           => $f['convenio'],
            'vagas'              => (int)$f['vagas'],
            'beneficios'         => $f['beneficios'],
            'carga_horaria'      => $f['carga_horaria'],
            'requisitos'         => $f['requisitos'],
            'contato'            => $f['contato'],
            'email'              => $f['email'],
            'periodo'            => $f['periodo'],
            'outras'             => $f['outras'],
            'local_selecao'      => $f['local_selecao'],
            'quantidade_alunos'  => $tem_inscritos ? (int)$f['quantidade_alunos'] : 0,
            'final_de_semana'    => self::finalSemanaTexto($f['final_de_semana']),
            'horario'            => self::horarioTexto($f['horario']),
            'horario_selecao'    => $f['horario_selecao'],
            'forma_selecao'      => self::formaSelecaoTexto($f['forma_selecao']),
            'data_selecao'       => self::dataTexto($f['data_selecao']),
            'data_selecao_iso'   => self::dataIso($f['data_selecao']),
            'data_inscricao'     => self::dataTexto($f['data_inscricao']),
            'data_inscricao_iso' => self::dataIso($f['data_inscricao']),
            'tem_selecao'        => !empty($f['data_selecao']) && $f['data_selecao'] !== '0000-00-00',
            // Campos legados exibidos por ver_cada.tpl, sem origem no cadastro atual:
            'area'      => '',
            'professor' => '',
            'datafax'   => 0,
        );
    }

    private static function finalSemanaTexto($val) {
        switch ($val) {
            case '1': return 'Sim';
            case '2': return 'Parcialmente';
            default:  return 'Não';
        }
    }

    private static function horarioTexto($val) {
        switch ($val) {
            case 'D': return 'Diurno';
            case 'N': return 'Noturno';
            case 'A': return 'Ambos';
            default:  return '';
        }
    }

    private static function formaSelecaoTexto($val) {
        switch ($val) {
            case '0': return 'Entrevista';
            case '1': return 'CR';
            case '2': return 'Prova';
            case '3': return 'Outras';
            default:  return '';
        }
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
