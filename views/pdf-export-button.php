<?php
/**
 * Partial para o botão de download PDF
 * @var \MapasCulturais\Entities\Registration $registration
 */

use MapasCulturais\i;
?>

<div class="pdf-export-button">
    <button class="button button--danger button--icon button--sm" 
            data-registration-id="<?= $registration->id ?>"
            @click="downloadPDF(<?= $registration->id ?>)">
        <mc-icon name="file-pdf"></mc-icon> 
        <?= i::__('Baixar PDF') ?>
    </button>
</div>

<div class="pdf-export-loading">
    <mc-loading :condition="pdfLoading"></mc-loading>
</div>

<script>
// Adiciona método ao Vue component se existir
if (typeof app !== 'undefined' && app.component) {
    // Verifica se o componente registration-print existe
    const registrationPrintComponent = app._context.components['registration-print'];
    
    if (registrationPrintComponent) {
        // Adiciona dados e métodos ao componente
        const originalData = registrationPrintComponent.data || function() { return {}; };
        registrationPrintComponent.data = function() {
            const data = originalData.call(this);
            data.pdfLoading = false;
            return data;
        };
        
        const originalMethods = registrationPrintComponent.methods || {};
        registrationPrintComponent.methods = {
            ...originalMethods,
            downloadPDF(registrationId) {
                this.pdfLoading = true;
                
                const url = Utils.createUrl('registration', 'downloadPdf', [registrationId]);
                
                // Criar iframe temporário para download
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                iframe.src = url;
                document.body.appendChild(iframe);
                
                // Remove loading após delay
                setTimeout(() => {
                    this.pdfLoading = false;
                    document.body.removeChild(iframe);
                }, 3000);
            }
        };
    }
}
</script>