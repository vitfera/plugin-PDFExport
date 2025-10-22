<?php

namespace PDFExport;

use MapasCulturais\App;
use MapasCulturais\i;

// Incluir dependências diretamente
require_once __DIR__ . '/Services/PDFService.php';
require_once __DIR__ . '/Controller.php';

class Plugin extends \MapasCulturais\Plugin
{
    function __construct($config = [])
    {
        $config += [
            'button_text' => 'Exportar PDF',
            'pdf_title' => 'Dados da Inscrição',
            'show_registration_fields' => true,
            'show_agent_fields' => true,
            'custom_css' => ''
        ];
        parent::__construct($config);
    }

    public function _init()
    {
        $app = App::i();
        $plugin = $this;

        // register translation text domain
        i::load_textdomain('pdfexport', __DIR__ . "/translations");

        // Load CSS
        $app->hook('GET(<<*>>):before', function() use ($app) {
            $app->view->enqueueStyle('app-v2', 'pdfexport-v2', 'css/pdf-export.css');
        });

        // Registrar Controller
        $app->registerController('pdfexport', Controller::class);

        // Log de debug para confirmar que o plugin está sendo inicializado
        error_log('PDFExport Plugin: _init() executado - Plugin inicializado com sucesso');

        // Hook para adicionar botão PDF - usar hook genérico mas com controle
        $app->hook('template(<<*>>):after', function() use($app, $plugin) {
            /** @var \MapasCulturais\Themes\BaseV2\Theme $this */
            
            // Só executa se for registration/single
            if ($this->controller->id !== 'registration' || 
                $this->controller->action !== 'single' ||
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
                <button id="pdf-download-btn" class="button button--danger button--icon button--sm" onclick="downloadRegistrationPDF(' . $registration->id . ')" title="' . i::__('Baixar inscrição em PDF', 'pdfexport') . '">
                    📄 ' . i::__($plugin->config['button_text'], 'pdfexport') . '
                </button>
            </div>
            <!-- PDFEXPORT-HOOK: END -->
            
            <script>
                window.downloadRegistrationPDF = function(registrationId) {
                    console.log("PDFExport: downloadRegistrationPDF chamado para ID:", registrationId);
                    
                    // Usar a rota do nosso controller PDFExport
                    const pdfUrl = "/pdfexport/generatePDF/" + registrationId;
                    console.log("PDFExport: Abrindo URL:", pdfUrl);
                    
                    // Abrir em nova aba
                    window.open(pdfUrl, "_blank");
                };
                
                console.log("PDFExport Plugin: Script carregado para registration ID: ' . $registration->id . '");
            </script>
            ';
        });

        // Rotas são tratadas pelo Controller registrado

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