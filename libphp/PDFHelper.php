<?php

/**
 * PDFHelper — facade do FPDF para gerar PDFs a partir de dados UTF-8.
 *
 * O FPDF incluido em lib/fpdf usa fontes core (Helvetica/Arial/Times) com
 * encoding ISO-8859-1 (Latin-1). Quando recebe strings UTF-8 (acentos,
 * cedilha, etc.) via Cell(), MultiCell(), Text(), Write() ou
 * GetStringWidth(), os bytes UTF-8 vao crus para o PDF e o leitor exibe
 * caracteres trocados ("Infancia" -> "InfAncia").
 *
 * Esta classe estende FPDF sobrescrevendo esses metodos para converter
 * UTF-8 -> ISO-8859-1 antes de chamar o pai. Como todos os caracteres
 * usados pelo sistema estao dentro do range Latin-1 (portugues brasileiro),
 * nao ha perda.
 *
 *   require_once __DIR__ . '/libphp/PDFHelper.php';
 *   $pdf = new PDF_UTF8();   // em vez de new FPDF()
 */
class PDF_UTF8 extends FPDF
{
    /**
     * Converte UTF-8 para ISO-8859-1. Se a string ja nao for UTF-8 valida,
     * devolve como esta (idempotente).
     */
    private static function toLatin1($s)
    {
        if ($s === null || $s === '') {
            return '';
        }
        if (!mb_check_encoding($s, 'UTF-8')) {
            return $s; // ja esta em Latin-1 (ou outro encoding); nao mexe
        }
        // mb_convert_encoding() substitui utf8_decode() (deprecado no PHP 8.2+)
        return mb_convert_encoding($s, 'ISO-8859-1', 'UTF-8');
    }

    // Substituicoes para UTF-8 -> Latin-1 -----------------------------------

    public function Cell($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
    {
        parent::Cell($w, $h, self::toLatin1((string)$txt), $border, $ln, $align, $fill, $link);
    }

    public function MultiCell($w, $h, $txt, $border = 0, $align = 'J', $fill = false)
    {
        parent::MultiCell($w, $h, self::toLatin1((string)$txt), $border, $align, $fill);
    }

    public function Text($x, $y, $txt)
    {
        parent::Text($x, $y, self::toLatin1((string)$txt));
    }

    public function Write($h, $txt, $link = '')
    {
        parent::Write($h, self::toLatin1((string)$txt), $link);
    }

    // GetStringWidth precisa ser convertido ANTES da medicao, senao o
    // calculo de largura (usado para centralizar celulas) fica errado.
    public function GetStringWidth($s)
    {
        return parent::GetStringWidth(self::toLatin1((string)$s));
    }
}