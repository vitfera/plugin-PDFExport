// JavaScript para o plugin PDF Export

(function() {
    'use strict';

    // Função para download do PDF
    function downloadPDF(registrationId) {
        const loadingElement = document.querySelector('.pdf-export-loading');
        const buttonElement = document.querySelector('.pdf-export-button .button');
        
        // Mostra loading
        if (loadingElement) {
            loadingElement.classList.add('active');
        }
        
        // Desabilita botão
        if (buttonElement) {
            buttonElement.disabled = true;
        }
        
        // Cria URL para download
        const url = Utils.createUrl('registration', 'downloadPdf', [registrationId]);
        
        // Cria elemento temporário para download
        const downloadLink = document.createElement('a');
        downloadLink.href = url;
        downloadLink.download = `ficha-inscricao-${registrationId}.pdf`;
        downloadLink.style.display = 'none';
        
        document.body.appendChild(downloadLink);
        downloadLink.click();
        document.body.removeChild(downloadLink);
        
        // Remove loading após delay
        setTimeout(() => {
            if (loadingElement) {
                loadingElement.classList.remove('active');
            }
            
            if (buttonElement) {
                buttonElement.disabled = false;
            }
        }, 2000);
    }

    // Adiciona event listener quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        const pdfButton = document.querySelector('.pdf-export-button .button');
        
        if (pdfButton) {
            pdfButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                const registrationId = this.getAttribute('data-registration-id');
                if (registrationId) {
                    downloadPDF(registrationId);
                }
            });
        }
    });

    // Disponibiliza função globalmente para uso em Vue/Angular
    window.PDFExport = {
        downloadPDF: downloadPDF
    };

})();