<?php

namespace PDFExport\Services;

use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Entities\Registration;

class PDFService
{
    public function __construct()
    {
        // Verificar se a biblioteca mPDF está disponível
        if (!$this->isMpdfAvailable()) {
            error_log('PDFExport: mPDF não está disponível, usando fallback HTML');
        }
    }

    private function isMpdfAvailable()
    {
        $autoload_path = __DIR__ . '/../vendor/autoload.php';
        if (file_exists($autoload_path)) {
            require_once $autoload_path;
            return class_exists('Mpdf\\Mpdf');
        }
        return false;
    }

    /**
     * Gera PDF da registration usando mPDF se disponível, senão HTML
     */
    public function generateRegistrationPDF(Registration $registration)
    {
        try {
            if ($this->isMpdfAvailable()) {
                return $this->generatePDFWithMpdf($registration);
            } else {
                // Fallback: gerar HTML que pode ser impresso como PDF pelo navegador
                return $this->generateHTMLForPrint($registration);
            }
        } catch (\Exception $e) {
            error_log('PDFExport Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generatePDFWithMpdf(Registration $registration)
    {
        require_once __DIR__ . '/../vendor/autoload.php';
        
        $mpdf = new \Mpdf\Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 20,
            'margin_bottom' => 20
        ]);

        $htmlContent = $this->generateHTMLContent($registration);
        
        $mpdf->SetTitle('Inscrição #' . $registration->number);
        $mpdf->WriteHTML($htmlContent);
        
        return $mpdf->Output('', 'S'); // Retorna string do PDF
    }

    private function generateHTMLForPrint(Registration $registration)
    {
        $htmlContent = $this->generateHTMLContent($registration);
        
        // Adicionar CSS específico para impressão
        $printCSS = '
        <style>
            @media print {
                body { font-family: Arial, sans-serif; }
                .no-print { display: none; }
            }
            @page { margin: 2cm; }
            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
                color: #333;
            }
            .header {
                text-align: center;
                border-bottom: 2px solid #ccc;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .section {
                margin-bottom: 15px;
            }
            .label {
                font-weight: bold;
                display: inline-block;
                width: 150px;
            }
            .value {
                display: inline-block;
            }
        </style>';
        
        return $printCSS . $htmlContent;
    }

    private function generateHTMLContent(Registration $registration)
    {
        $app = App::i();
        
        // Usar o template existente
        $app->view->assign('registration', $registration);
        $app->view->assign('agent', $registration->owner);
        
        ob_start();
        
        // Tentar incluir template customizado, senão usar o padrão
        $customTemplate = __DIR__ . '/../views/registration-pdf.php';
        if (file_exists($customTemplate)) {
            include $customTemplate;
        } else {
            // Template básico inline como fallback
            echo $this->getBasicTemplate($registration);
        }
        
        $content = ob_get_clean();
        
        return $content;
    }

    /**
     * Gera template básico caso não encontre o arquivo customizado
     */
    private function getBasicTemplate(Registration $registration)
    {
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Inscrição #' . $registration->number . '</title>
        </head>
        <body>
            <div class="header">
                <h1>' . i::__('Dados da Inscrição', 'pdfexport') . '</h1>
                <h2>' . i::__('Número', 'pdfexport') . ': ' . $registration->number . '</h2>
            </div>
            
            <div class="section">
                <h3>' . i::__('Informações Básicas', 'pdfexport') . '</h3>
                <p><span class="label">' . i::__('Data da Inscrição', 'pdfexport') . ':</span> <span class="value">' . $registration->createTimestamp->format('d/m/Y H:i:s') . '</span></p>
                <p><span class="label">' . i::__('Status', 'pdfexport') . ':</span> <span class="value">' . $registration->status . '</span></p>
            </div>
            
            <div class="section">
                <h3>' . i::__('Dados do Responsável', 'pdfexport') . '</h3>
                <p><span class="label">' . i::__('Nome', 'pdfexport') . ':</span> <span class="value">' . ($registration->owner->name ?? 'N/A') . '</span></p>
                <p><span class="label">' . i::__('Email', 'pdfexport') . ':</span> <span class="value">' . ($registration->owner->emailPrivado ?? 'N/A') . '</span></p>
            </div>
        </body>
        </html>';
        
        return $html;
    }
}