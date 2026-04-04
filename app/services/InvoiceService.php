<?php
namespace App\Services;

use Dompdf\Dompdf;

class InvoiceService
{
    public static function streamPdf(string $html, string $filename = 'invoice.pdf'): void
    {
        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => false]);
        exit;
    }
}
