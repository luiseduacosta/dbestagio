<?php
/**
 * Mural.php — Modelo para a tabela mural_estagios (oferta de estágios do mural).
 *
 * Concentra a consulta de registros do mural por período, a contagem de
 * inscritos de cada oferta (sem N+1) e a formatação dos campos codificados
 * (final_de_semana, horario, forma_selecao e datas no formato dd-mm-aaaa,
 * mantendo o ISO separado para a ordenação do DataTables).
 */
if (!class_exists('Mural', false)) {

class Mural extends ADODB_Model {
    protected static $table  = 'mural_estagios';
    protected static $pk     = 'id';
    protected static $hidden = array();

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
     * @return array Registros formatados para exibição (lista amigável ao DataTables).
     */
    public static function listarPorPeriodo($periodo = '') {
        $db = static::db();

        $sql = "SELECT m.id AS mural_estagio_id, m.instituicao_id, m.instituicao, m.convenio, m.vagas, "
             . "m.beneficios, m.final_de_semana, m.carga_horaria, m.requisitos, m.horario, "
             . "m.data_selecao, m.horario_selecao, m.data_inscricao, m.local_selecao, "
             . "m.forma_selecao, m.contato, m.email, m.periodo, "
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
        $sql .= " ORDER BY m.data_inscricao DESC";

        $resultado = $db->Execute($sql, $params);
        if ($resultado === false) {
            die("Não foi possível consultar a tabela mural_estagios");
        }

        $out = array();
        while (!$resultado->EOF) {
            $f = $resultado->fields;

            $out[] = array(
                'mural_estagio_id'   => (int)$f['mural_estagio_id'],
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
                'quantidade_alunos'  => (int)$f['quantidade_alunos'],
                'final_de_semana'    => self::finalSemanaTexto($f['final_de_semana']),
                'horario'            => self::horarioTexto($f['horario']),
                'horario_selecao'    => $f['horario_selecao'],
                'forma_selecao'      => self::formaSelecaoTexto($f['forma_selecao']),
                'data_selecao'       => self::dataTexto($f['data_selecao']),
                'data_selecao_iso'   => self::dataIso($f['data_selecao']),
                'data_inscricao'     => self::dataTexto($f['data_inscricao']),
                'data_inscricao_iso' => self::dataIso($f['data_inscricao']),
            );
            $resultado->MoveNext();
        }
        return $out;
    }

    // ------------------------------------------------------------------
    // Formatadores de campos codificados
    // ------------------------------------------------------------------

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
        if (empty($date)) return '';
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