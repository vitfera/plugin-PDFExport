<?php

namespace PDFExport\Services;

use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Entities\Registration;
use Mpdf\Mpdf;
use Mpdf\HTMLParserMode;

class PDFService
{
    protected $mpdf;
    
    public function __construct()
    {
        $this->initMpdf();
    }

    /**
     * Inicializa a configuração do mPDF
     */
    protected function initMpdf()
    {
        $this->mpdf = new Mpdf([
            'tempDir' => sys_get_temp_dir(),
            'mode' => 'utf-8',
            'format' => 'A4',
            'pagenumPrefix' => 'Página ',
            'pagenumSuffix' => '  ',
            'nbpgPrefix' => ' de ',
            'nbpgSuffix' => '',
            'margin_top' => 20,
            'margin_bottom' => 20,
            'margin_left' => 15,
            'margin_right' => 15,
            'default_font' => 'DejaVuSans',
        ]);
    }

    /**
     * Gera o PDF da ficha de inscrição
     * 
     * @param Registration $registration
     */
    public function generateRegistrationPDF(Registration $registration)
    {
        $app = App::i();
        
        try {
            // Define o título do documento
            $title = sprintf(
                'Ficha de Inscrição - %s - %s',
                $registration->opportunity->name,
                $registration->number
            );
            
            $this->mpdf->SetTitle($title);
            
            // Adiciona CSS base
            $this->addBaseCSS();
            
            // Gera o conteúdo HTML
            $htmlContent = $this->generateHTMLContent($registration);
            
            // Adiciona cabeçalho e rodapé
            $this->addHeader($registration);
            $this->addFooter();
            
            // Escreve o conteúdo no PDF
            $this->mpdf->WriteHTML($htmlContent);
            
            // Define o nome do arquivo
            $filename = sprintf(
                'ficha-inscricao-%s-%s.pdf',
                $registration->opportunity->id,
                $registration->number
            );
            
            // Força o download
            $this->mpdf->Output($filename, 'D');
            
        } catch (\Exception $e) {
            $app->log->error('Erro ao gerar PDF: ' . $e->getMessage());
            throw new \Exception(i::__('Erro ao gerar PDF da ficha de inscrição'));
        }
    }

    /**
     * Adiciona CSS base para o PDF
     */
    protected function addBaseCSS()
    {
        $css = '
            body { 
                font-family: DejaVuSans, sans-serif; 
                font-size: 12px; 
                line-height: 1.4;
                color: #333;
            }
            .header { 
                text-align: center; 
                border-bottom: 2px solid #333; 
                margin-bottom: 20px; 
                padding-bottom: 10px;
            }
            .section { 
                margin-bottom: 20px; 
                page-break-inside: avoid;
            }
            .section-title { 
                font-size: 14px; 
                font-weight: bold; 
                background-color: #f0f0f0; 
                padding: 8px; 
                margin-bottom: 10px;
                border-left: 4px solid #007cba;
            }
            .field { 
                margin-bottom: 8px; 
            }
            .field-label { 
                font-weight: bold; 
                display: inline-block; 
                min-width: 150px;
            }
            .field-value { 
                display: inline-block; 
            }
            .table { 
                width: 100%; 
                border-collapse: collapse; 
                margin-bottom: 15px;
            }
            .table th, .table td { 
                border: 1px solid #ddd; 
                padding: 8px; 
                text-align: left;
            }
            .table th { 
                background-color: #f9f9f9; 
                font-weight: bold;
            }
            .footer { 
                position: fixed; 
                bottom: 0; 
                text-align: center; 
                font-size: 10px; 
                color: #666;
            }
        ';
        
        $this->mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
    }

    /**
     * Gera o conteúdo HTML da ficha
     * 
     * @param Registration $registration
     * @return string
     */
    protected function generateHTMLContent(Registration $registration)
    {
        $app = App::i();
        
        // Usa o sistema de views do Mapas Culturais
        $app->view->jsObject = [];
        
        ob_start();
        include __DIR__ . '/../views/registration-pdf.php';
        $content = ob_get_clean();
        
        return $content;
    }

    /**
     * Adiciona cabeçalho ao PDF
     * 
     * @param Registration $registration
     */
    protected function addHeader(Registration $registration)
    {
        $header = '
            <div class="header">
                <h1>Ficha de Inscrição</h1>
                <h2>' . htmlspecialchars($registration->opportunity->name) . '</h2>
                <p><strong>Número da Inscrição:</strong> ' . htmlspecialchars($registration->number) . '</p>
                <p><strong>Data de Envio:</strong> ' . $registration->sentTimestamp->format('d/m/Y H:i:s') . '</p>
            </div>
        ';
        
        $this->mpdf->SetHTMLHeader($header);
    }

    /**
     * Adiciona rodapé ao PDF
     */
    protected function addFooter()
    {
        $footer = '
            <div class="footer">
                <p>Documento gerado em ' . date('d/m/Y H:i:s') . ' - Página {PAGENO} de {nbpg}</p>
            </div>
        ';
        
        $this->mpdf->SetHTMLFooter($footer);
    }
}