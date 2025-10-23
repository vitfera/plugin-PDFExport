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

        // Hook mais simples - vamos tentar vários hooks
        $app->hook('template(registration.single):after', function() use($app, $plugin) {
            /** @var \MapasCulturais\Themes\BaseV2\Theme $this */
            
            error_log('PDFExport Plugin: ✅ Hook template(registration.single):after disparado');
            
            $registration = $this->controller->requestedEntity;
            if (!$registration) {
                error_log('PDFExport Plugin: Registration não encontrada');
                return;
            }

            error_log('PDFExport Plugin: ✅ Adicionando botão PDF para registration ID: ' . $registration->id);
            
            // Adiciona apenas o botão HTML limpo
            echo '
            <!-- PDFEXPORT-HOOK: BEGIN -->
            <div id="pdf-export-container" style="position: fixed; top: 80px; right: 20px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 5px; border-radius: 8px;">
                <button id="pdf-btn-' . $registration->id . '" 
                        class="pdf-export-button"
                        data-registration-id="' . $registration->id . '"
                        style="background: #e74c3c; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.15); pointer-events: auto;"
                        title="' . i::__('Baixar inscrição em PDF', 'pdfexport') . '"
                        onmouseover="this.style.background=\'#c0392b\';" 
                        onmouseout="this.style.background=\'#e74c3c\';">
                    📄 ' . i::__($plugin->config['button_text'], 'pdfexport') . '
                </button>
            </div>
            <!-- PDFEXPORT-HOOK: END -->
            ';
        });
        


        // Rotas são tratadas pelo Controller registrado

        // Hook para adicionar assets CSS/JS do plugin
        $app->hook('app.init:after', function() use($app) {
            error_log('PDFExport Plugin: Carregando assets CSS e JS');
            $app->view->enqueueStyle('app-v2', 'pdf-export', 'css/pdf-export.css');
            $app->view->enqueueScript('app-v2', 'pdf-export', 'js/pdf-export.js');
        });
    }

    public function register()
    {
        $app = App::i();
        
        // Plugin já é registrado automaticamente pelo sistema
        // Aqui podemos registrar controladores, file groups etc se necessário
    }
}