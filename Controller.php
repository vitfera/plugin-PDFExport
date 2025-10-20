<?php

namespace PDFExport;

use MapasCulturais\App;
use MapasCulturais\i;
use MapasCulturais\Controller;
use MapasCulturais\Entities\Registration;

class PDFController extends Controller
{
    /**
     * Gera PDF de uma registration
     */
    public function GET_generatePDF()
    {
        $app = App::i();
        
        // Verificar se ID da registration foi fornecido
        $registration_id = $this->data['registration_id'] ?? null;
        
        if (!$registration_id) {
            $app->halt(400, i::__('ID da inscrição não fornecido', 'pdfexport'));
        }

        // Buscar a registration
        $registration = $app->repo('Registration')->find($registration_id);
        
        if (!$registration) {
            $app->halt(404, i::__('Inscrição não encontrada', 'pdfexport'));
        }

        // Verificar permissões
        $registration->checkPermission('view');

        try {
            $pdfService = new Services\PDFService();
            $pdfContent = $pdfService->generateRegistrationPDF($registration);
            
            // Configurar headers para download do PDF
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="inscricao_' . $registration->number . '.pdf"');
            header('Content-Length: ' . strlen($pdfContent));
            
            echo $pdfContent;
            $app->stop();
            
        } catch (\Exception $e) {
            error_log("PDFExport Error: " . $e->getMessage());
            $app->halt(500, i::__('Erro ao gerar PDF', 'pdfexport'));
        }
    }
    
    /**
     * Endpoint para preview do PDF (opcional)
     */
    public function GET_previewPDF()
    {
        $app = App::i();
        
        $registration_id = $this->data['registration_id'] ?? null;
        
        if (!$registration_id) {
            $app->halt(400, i::__('ID da inscrição não fornecido', 'pdfexport'));
        }

        $registration = $app->repo('Registration')->find($registration_id);
        
        if (!$registration) {
            $app->halt(404, i::__('Inscrição não encontrada', 'pdfexport'));
        }

        $registration->checkPermission('view');

        try {
            $pdfService = new Services\PDFService();
            $pdfContent = $pdfService->generateRegistrationPDF($registration);
            
            // Exibir PDF inline no navegador
            header('Content-Type: application/pdf');
            header('Content-Disposition: inline; filename="preview_inscricao_' . $registration->number . '.pdf"');
            
            echo $pdfContent;
            $app->stop();
            
        } catch (\Exception $e) {
            error_log("PDFExport Error: " . $e->getMessage());
            $app->halt(500, i::__('Erro ao gerar preview do PDF', 'pdfexport'));
        }
    }
}