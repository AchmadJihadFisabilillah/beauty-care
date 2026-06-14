<?php
namespace App\Services;

use App\Models\Invoice;
use Dompdf\Dompdf;
use PDO;

class InvoiceService
{
    public static function generateNumber(array $order): string
    {
        return 'INV/' . date('Ymd') . '/' . str_pad((string) $order['id'], 6, '0', STR_PAD_LEFT);
    }

    public static function ensureExists(PDO $pdo, array $order): array
    {
        $invoiceModel = new Invoice($pdo);
        $invoice = $invoiceModel->findByOrder((int) $order['id']);
        if ($invoice) {
            return $invoice;
        }

        $invoiceNumber = self::generateNumber($order);
        $invoiceId = $invoiceModel->create((int) $order['id'], $invoiceNumber, (float) $order['total']);
        return $invoiceModel->findByOrder((int) $order['id']) ?: [
            'id' => $invoiceId,
            'invoice_number' => $invoiceNumber,
            'invoice_date' => date('Y-m-d H:i:s'),
            'total_amount' => (float) $order['total'],
        ];
    }

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
