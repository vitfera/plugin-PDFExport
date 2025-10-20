<?php

namespace PDFExport;

use MapasCulturais\App;
use MapasCulturais\i;

class Plugin extends \MapasCulturais\Plugin
{
    function __construct($config = [])
    {
        $config += [];
        parent::__construct($config);
    }

    public function _init()
    {
        $app = App::i();
        $plugin = $this;

        // Log de debug para confirmar que o plugin está sendo inicializado
        error_log('PDFExport Plugin: _init() executado - Plugin inicializado com sucesso');

        // Registra o autoload do composer do plugin
        $composer_autoload = __DIR__ . '/vendor/autoload.php';
        if (file_exists($composer_autoload)) {
            require_once $composer_autoload;
            error_log('PDFExport Plugin: Composer autoload carregado');
        } else {
            error_log('PDFExport Plugin: Composer autoload não encontrado em: ' . $composer_autoload);
        }

        // Hook para adicionar botão PDF - usar hook genérico mas com controle
        $app->hook('template(<<*>>):after', function() use($app, $plugin) {
            /** @var \MapasCulturais\Themes\BaseV2\Theme $this */
            
            // Só executa se for registration/single e ainda não executou
            if ($this->controller->id !== 'registration' || 
                ($this->template ?? '') !== 'registration/single' ||
                defined('PDFEXPORT_BUTTON_ADDED')) {
                return;
            }
            
            // Marca que já foi executado para evitar duplicação
            define('PDFEXPORT_BUTTON_ADDED', true);
            
            $registration = $this->controller->requestedEntity;
            if (!$registration) {
                return;
            }

            error_log('PDFExport Plugin: Adicionando botão PDF para registration ID: ' . $registration->id);
            
            // Adiciona o botão diretamente no HTML
            echo '
            <!-- PDFEXPORT-HOOK: BEGIN -->
            <div class="pdf-export-section" style="display: flex; justify-content: flex-end; margin: 1rem 0; gap: 10px; background: #f0f0f0; padding: 10px; border-radius: 5px;">
                <button id="pdf-download-btn" class="button button--danger button--icon button--sm" onclick="downloadRegistrationPDF(' . $registration->id . ')" title="Baixar inscrição em PDF">
                    📄 Baixar PDF
                </button>
            </div>
            <!-- PDFEXPORT-HOOK: END -->
            
            <script>
                console.log("PDFExport Plugin: Script carregado para registration ID: ' . $registration->id . '");
                
                function downloadRegistrationPDF(registrationId) {
                    console.log("downloadRegistrationPDF chamado para ID:", registrationId);
                    
                    if (typeof Utils !== "undefined") {
                        const url = Utils.createUrl("registration", "downloadPdf", [registrationId]);
                        console.log("Abrindo URL via Utils:", url);
                        window.open(url, "_blank");
                    } else {
                        // Fallback se Utils não estiver disponível
                        const fallbackUrl = "/registration/downloadPdf/" + registrationId;
                        console.log("Abrindo URL fallback:", fallbackUrl);
                        window.open(fallbackUrl, "_blank");
                    }
                }
            </script>
            ';
        });
        

        
        // Hook para adicionar rota de download PDF
        $app->hook('GET(registration.downloadPdf)', function() use($app, $plugin) {
            /** @var \MapasCulturais\Controllers\Registration $this */
            $this->requireAuthentication();

            $registration = $this->requestedEntity;
            if (!$registration) {
                $app->pass();
            }

            // Verifica permissão para visualizar a inscrição
            $registration->checkPermission('view');

            $pdfService = new Services\PDFService();
            $pdfService->generateRegistrationPDF($registration);
        });

        // Hook para adicionar assets CSS/JS do plugin
        $app->hook('app.init:after', function() use($app) {
            $app->view->enqueueStyle('app-v2', 'pdf-export', 'css/pdf-export.css');
        });
    }

    public function register()
    {
        $app = App::i();
        
        // Plugin já é registrado automaticamente pelo sistema
        // Aqui podemos registrar controladores, file groups etc se necessário
    }
}