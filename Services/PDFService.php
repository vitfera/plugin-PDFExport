<?php

namespace PDFExport\Services;

use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Entities\Registration;

class PDFService
{
    /**
     * Gera HTML otimizado para conversão em PDF
     */
    public function generateRegistrationPDF(Registration $registration)
    {
        try {
            error_log('PDFExport: Gerando HTML para registration ID: ' . $registration->id);
            return $this->generateHTMLForPrint($registration);
        } catch (\Exception $e) {
            error_log('PDFExport Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function generateHTMLForPrint(Registration $registration)
    {
        $htmlContent = $this->generateHTMLContent($registration);
        
        // CSS otimizado para conversão em PDF
        $printCSS = '
        <style>
            @media print {
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0;
                    padding: 20px;
                }
                .no-print { display: none; }
                .page-break { page-break-before: always; }
            }
            
            @page { 
                margin: 2cm; 
                size: A4;
            }
            
            body {
                font-family: Arial, sans-serif;
                font-size: 12pt;
                line-height: 1.4;
                color: #333;
                max-width: 800px;
                margin: 0 auto;
                padding: 20px;
            }
            
            .header {
                text-align: center;
                border-bottom: 2px solid #0073aa;
                padding-bottom: 20px;
                margin-bottom: 30px;
            }
            
            .header h1 {
                color: #0073aa;
                margin: 0;
                font-size: 24pt;
            }
            
            .header h2 {
                color: #666;
                margin: 10px 0 0 0;
                font-size: 16pt;
            }
            
            .section {
                margin-bottom: 25px;
                border: 1px solid #ddd;
                padding: 15px;
                border-radius: 5px;
            }
            
            .section h3 {
                color: #0073aa;
                margin-top: 0;
                border-bottom: 1px solid #eee;
                padding-bottom: 10px;
            }
            
            .field-row {
                margin-bottom: 10px;
                display: table;
                width: 100%;
            }
            
            .label {
                font-weight: bold;
                display: table-cell;
                width: 200px;
                vertical-align: top;
                padding-right: 10px;
            }
            
            .value {
                display: table-cell;
                word-wrap: break-word;
            }
            
            .print-instructions {
                background: #f0f8ff;
                border: 1px solid #0073aa;
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 5px;
            }
            
            .print-instructions strong {
                color: #0073aa;
            }
            
            @media print {
                .print-instructions { display: none; }
            }
        </style>';
        
        return $printCSS . $htmlContent;
    }

    private function generateHTMLContent(Registration $registration)
    {
        ob_start();
        
        // Template básico otimizado
        echo $this->getOptimizedTemplate($registration);
        
        $content = ob_get_clean();
        return $content;
    }

    /**
     * Template otimizado para impressão/PDF
     */
    private function getOptimizedTemplate(Registration $registration)
    {
        $app = App::i();
        
        $html = '
        <!DOCTYPE html>
        <html lang="pt-BR">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>' . i::__('Inscrição', 'pdfexport') . ' #' . $registration->number . '</title>
        </head>
        <body>
            <div class="print-instructions">
                <strong>' . i::__('Instruções para gerar PDF', 'pdfexport') . ':</strong><br>
                ' . i::__('Use Ctrl+P (Windows) ou Cmd+P (Mac), selecione "Salvar como PDF" e ajuste as margens se necessário.', 'pdfexport') . '
            </div>
            
            <div class="header">
                <h1>' . i::__('Dados da Inscrição', 'pdfexport') . '</h1>
                <h2>' . i::__('Número', 'pdfexport') . ': ' . $registration->number . '</h2>
            </div>
            
            <div class="section">
                <h3>' . i::__('Informações Básicas', 'pdfexport') . '</h3>
                <div class="field-row">
                    <span class="label">' . i::__('Data da Inscrição', 'pdfexport') . ':</span>
                    <span class="value">' . $registration->createTimestamp->format('d/m/Y H:i:s') . '</span>
                </div>
                <div class="field-row">
                    <span class="label">' . i::__('Status', 'pdfexport') . ':</span>
                    <span class="value">' . $registration->status . '</span>
                </div>
                <div class="field-row">
                    <span class="label">' . i::__('Oportunidade', 'pdfexport') . ':</span>
                    <span class="value">' . ($registration->opportunity->name ?? 'N/A') . '</span>
                </div>
            </div>
            
            <div class="section">
                <h3>' . i::__('Dados do Responsável', 'pdfexport') . '</h3>
                <div class="field-row">
                    <span class="label">' . i::__('Nome', 'pdfexport') . ':</span>
                    <span class="value">' . ($registration->owner->name ?? 'N/A') . '</span>
                </div>
                <div class="field-row">
                    <span class="label">' . i::__('Email', 'pdfexport') . ':</span>
                    <span class="value">' . ($registration->owner->emailPrivado ?? $registration->owner->emailPublico ?? 'N/A') . '</span>
                </div>';
        
        // Adicionar campos personalizados se existirem
        if ($registration->metadata && count($registration->metadata) > 0) {
            $html .= '
            </div>
            
            <div class="section">
                <h3>' . i::__('Campos da Inscrição', 'pdfexport') . '</h3>';
            
            foreach ($registration->metadata as $key => $value) {
                if (!empty($value) && is_string($value)) {
                    $label = $this->formatFieldLabel($key);
                    $html .= '
                <div class="field-row">
                    <span class="label">' . htmlspecialchars($label) . ':</span>
                    <span class="value">' . htmlspecialchars($value) . '</span>
                </div>';
                }
            }
        }
        
        $html .= '
            </div>
            
            <div class="section">
                <h3>' . i::__('Informações do Sistema', 'pdfexport') . '</h3>
                <div class="field-row">
                    <span class="label">' . i::__('Gerado em', 'pdfexport') . ':</span>
                    <span class="value">' . date('d/m/Y H:i:s') . '</span>
                </div>
                <div class="field-row">
                    <span class="label">' . i::__('Sistema', 'pdfexport') . ':</span>
                    <span class="value">MapasCulturais - Plugin PDFExport</span>
                </div>
            </div>
        </body>
        </html>';
        
        return $html;
    }

    /**
     * Formata o nome do campo para exibição
     */
    private function formatFieldLabel($fieldName)
    {
        // Remove prefixos comuns
        $fieldName = preg_replace('/^(field_|meta_)/', '', $fieldName);
        
        // Converte underscore/camelCase para espaços
        $fieldName = preg_replace('/([a-z])([A-Z])/', '$1 $2', $fieldName);
        $fieldName = str_replace('_', ' ', $fieldName);
        
        // Capitaliza primeira letra de cada palavra
        return ucwords(strtolower($fieldName));
    }
}