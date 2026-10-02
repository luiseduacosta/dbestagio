<?php
/**
 * Configuracao.php — Modelo da tabela `configuracoes` (parâmetros globais do app).
 *
 * Esta tabela guarda APENAS UM registro (id = 1) com os parâmetros que o app
 * usa para funcionar (período atual do mural, turma atual do curso, datas do
 * termo de compromisso, etc.). Por isso, em vez de um CRUD completo, o módulo
 * expõe ver + editar do único registro.
 */
if (!class_exists('Configuracao', false)) {

class Configuracao extends ADODB_Model {
    protected static $table  = 'configuracoes';
    protected static $pk     = 'id';
    protected static $hidden = array();

    /**
     * Obtém o registro único de configuração (id = 1).
     * Se ainda não existir, cria um registro em branco (padrões).
     *
     * @return Configuracao
     */
    public static function obter() {
        $db = static::db();

        $rs = $db->Execute("SELECT * FROM " . static::$table . " WHERE id = 1");
        if ($rs === false || $rs->RecordCount() == 0) {
            $cfg = new static();
            $cfg->id = 1;
            return $cfg;
        }
        $cfg = new static();
        $cfg->_data  = $rs->fields;
        $cfg->_exists = true;
        return $cfg;
    }

    /**
     * Rótulo amigável de cada parâmetro (usado na listagem e no formulário).
     */
    public static function campos() {
        return array(
            'instituicao'                  => 'Instituição',
            'instituicao_curso'            => 'Instituição do curso',
            'mural_periodo_atual'          => 'Período atual do mural',
            'curso_turma_atual'            => 'Turma atual do curso',
            'curso_abertura_inscricoes'    => 'Abertura das inscrições do curso',
            'curso_encerramento_inscricoes'=> 'Encerramento das inscrições do curso',
            'termo_compromisso_periodo'    => 'Período do termo de compromisso',
            'termo_compromisso_inicio'     => 'Início do termo de compromisso',
            'termo_compromisso_final'      => 'Finalização do termo de compromisso',
            'periodo_calendario_academico' => 'Período do calendário acadêmico',
        );
    }
}

}

?>