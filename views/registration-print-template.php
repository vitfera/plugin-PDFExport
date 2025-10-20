<?php
/**
 * Template modificado do componente registration-print para incluir botão PDF
 * @var MapasCulturais\App $app
 * @var MapasCulturais\Themes\BaseV2\Theme $this
 */

use MapasCulturais\i;
?>

<div class="registration-print">
    <div style="display: flex; gap: 10px; align-items: center;">
        <button class="registration-print__button bold" @click="print()">
            <div class="button button--primary button--icon button--sm">
                <mc-icon name="print"></mc-icon> <?= i::__('Imprimir') ?>
            </div>
        </button>
        
        <button class="pdf-export-button bold" @click="downloadPDF()">
            <div class="button button--danger button--icon button--sm">
                <mc-icon name="file-pdf"></mc-icon> <?= i::__('Baixar PDF') ?>
            </div>
        </button>
    </div>

    <iframe ref="printIframe" class="registration-print__printOnly"></iframe>
    <mc-loading class="registration-print__loading" :condition="loading"></mc-loading>
</div>