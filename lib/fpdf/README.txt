FPDF 1.9.0 (from setasign/fpdf, also installed via Composer as
vendor/setasign/fpdf) is vendored here: fpdf.php + font/ directory.

History: the original production server used FPDF ~1.53 (or 1.51) kept
at /usr/local/htdocs/html/fpdf153/ (and fpdf151/); it was never part of
the local html/ folder, so it could not be vendored automatically.
FPDF 1.53 is incompatible with PHP 8 (PHP4-style constructor), so the
API-compatible 1.9.0 is used instead. Note: FPDF 1.7+ removed the
no-op Open() method; scripts must not call $pdf->Open().
