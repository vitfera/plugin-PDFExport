<?php

namespace PDFExport;

use MapasCulturais\App;
use MapasCulturais\Controllers\EntityController;
use MapasCulturais\Entities\Registration;
use MapasCulturais\i;

class Controller extends EntityController
{
    /**
     * Endpoint para gerar e baixar PDF da inscrição
     * URL: /pdfexport/generatePDF/{registrationId}
     */
    public function GET_generatePDF()
    {
        $app = App::i();
        
        try {
            $registrationId = $this->getData['id'] ?? null;
            
            if (!$registrationId) {
                $app->halt(400, i::__('ID da inscrição é obrigatório', 'pdfexport'));
                return;
            }

            $registration = $app->repo('Registration')->find($registrationId);
            
            if (!$registration) {
                $app->halt(404, i::__('Inscrição não encontrada', 'pdfexport'));
                return;
            }

            if (!$registration->canUser('view')) {
                $app->halt(403, i::__('Acesso negado', 'pdfexport'));
                return;
            }

            $pdfService = new Services\PDFService();
            $pdfContent = $pdfService->generateRegistrationPDF($registration);

            if (strpos($pdfContent, '<!DOCTYPE html>') === 0 || strpos($pdfContent, '<html') === 0) {
                header('Content-Type: text/html; charset=utf-8');
                echo $pdfContent;
            } else {
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment; filename="inscricao_' . $registration->number . '.pdf"');
                header('Content-Length: ' . strlen($pdfContent));
                echo $pdfContent;
            }
            
            $app->stop();
            
        } catch (\Exception $e) {
            error_log("PDFExport Error: " . $e->getMessage());
            $app->halt(500, i::__('Erro ao gerar PDF', 'pdfexport') . ': ' . $e->getMessage());
        }
    }

    /**
     * Endpoint para preview do PDF
     * URL: /pdfexport/previewPDF/{registrationId}
     */
    public function GET_previewPDF()
    {
        $app = App::i();
        
        try {
            $registrationId = $this->getData['id'] ?? null;
            
            if (!$registrationId) {
                $app->halt(400, i::__('ID da inscrição é obrigatório', 'pdfexport'));
                return;
            }

            $registration = $app->repo('Registration')->find($registrationId);
            
            if (!$registration) {
                $app->halt(404, i::__('Inscrição não encontrada', 'pdfexport'));
                return;
            }

            if (!$registration->canUser('view')) {
                $app->halt(403, i::__('Acesso negado', 'pdfexport'));
                return;
            }

            $pdfService = new Services\PDFService();
            $pdfContent = $pdfService->generateRegistrationPDF($registration);
            
            header('Content-Type: text/html; charset=utf-8');
            echo $pdfContent;
            
            $app->stop();
            
        } catch (\Exception $e) {
            error_log("PDFExport Error: " . $e->getMessage());
            $app->halt(500, i::__('Erro ao gerar preview do PDF', 'pdfexport'));
        }
    }
}