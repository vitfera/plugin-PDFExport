// JavaScript para o plugin PDF Export

(function() {
    'use strict';

    // Função para download do PDF
    function downloadPDF(registrationId) {
        console.log('PDFExport: ✅ Função downloadPDF chamada para ID:', registrationId);
        
        const buttonElement = document.querySelector('.pdf-export-button');
        
        // Desabilita botão temporariamente
        if (buttonElement) {
            buttonElement.disabled = true;
            buttonElement.style.opacity = '0.6';
        }
        
        // Usar nossa URL do controller PDFExport
        const url = `/pdfexport/generatePDF/${registrationId}`;
        console.log('PDFExport: 🔗 Abrindo URL:', url);
        
        // Abrir em nova aba para gerar PDF
        window.open(url, '_blank');
        
        // Reabilita botão após delay
        setTimeout(() => {
            if (buttonElement) {
                buttonElement.disabled = false;
                buttonElement.style.opacity = '1';
            }
        }, 1000);
    }

    // Função para detectar se estamos numa página de registration
    function getRegistrationId() {
        // Tentar extrair ID da URL: /inscricao/123456/
        const match = window.location.pathname.match(/\/inscricao\/(\d+)/);
        if (match) {
            return match[1];
        }
        
        // Procurar em elementos da página
        const registrationElements = document.querySelectorAll('[data-registration-id], [data-entity-id]');
        for (const element of registrationElements) {
            const id = element.getAttribute('data-registration-id') || element.getAttribute('data-entity-id');
            if (id) {
                return id;
            }
        }
        
        return null;
    }
    
    // Função para criar e injetar o botão PDF
    function injectPDFButton(registrationId) {
        // Verificar se já existe
        if (document.getElementById('pdf-export-injected')) {
            return;
        }
        
        console.log('PDFExport: ✅ Criando botão para registration ID:', registrationId);
        
        const container = document.createElement('div');
        container.id = 'pdf-export-injected';
        container.style.cssText = 'position: fixed; top: 80px; right: 20px; z-index: 1000; background: rgba(255,255,255,0.9); padding: 5px; border-radius: 8px;';
        
        const button = document.createElement('button');
        button.id = 'pdf-btn-auto-' + registrationId;
        button.className = 'pdf-export-button';
        button.setAttribute('data-registration-id', registrationId);
        button.style.cssText = 'background: #e74c3c; color: white; border: none; padding: 12px 20px; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold; box-shadow: 0 2px 8px rgba(0,0,0,0.15);';
        button.innerHTML = '📄 Baixar PDF';
        button.title = 'Baixar inscrição em PDF';
        
        // Hover effects
        button.addEventListener('mouseover', () => button.style.background = '#c0392b');
        button.addEventListener('mouseout', () => button.style.background = '#e74c3c');
        
        // Click event
        button.addEventListener('click', function(e) {
            e.preventDefault();
            downloadPDF(registrationId);
        });
        
        container.appendChild(button);
        document.body.appendChild(container);
        
        console.log('PDFExport: ✅ Botão injetado com sucesso');
    }

    // Função para inicializar os event listeners
    function initPDFExport() {
        console.log('PDFExport: ✅ Inicializando JavaScript do PDFExport');
        
        // Verificar se estamos em uma página de registration
        const registrationId = getRegistrationId();
        if (registrationId) {
            console.log('PDFExport: ✅ Página de registration detectada, ID:', registrationId);
            injectPDFButton(registrationId);
        } else {
            console.log('PDFExport: ❌ Não é uma página de registration');
        }
        
        // Procurar por botões PDF existentes em qualquer parte da página
        const pdfButtons = document.querySelectorAll('[id^="pdf-btn-"], .pdf-export-button');
        
        pdfButtons.forEach(button => {
            const regId = button.getAttribute('data-registration-id') || 
                         button.id.replace(/pdf-btn-.*?-/, '') ||
                         registrationId;
            
            if (regId && !button.hasAttribute('data-pdf-listener')) {
                console.log('PDFExport: ✅ Anexando listener ao botão existente para ID:', regId);
                
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    downloadPDF(regId);
                });
                
                // Marcar que o listener foi adicionado
                button.setAttribute('data-pdf-listener', 'true');
            }
        });
    }

    // Inicializar quando DOM estiver pronto
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initPDFExport);
    } else {
        initPDFExport();
    }
    
    // Reinicializar periodicamente para elementos dinâmicos do Vue
    setInterval(initPDFExport, 2000);

    // Disponibiliza função globalmente para uso em Vue/Angular
    window.PDFExport = {
        downloadPDF: downloadPDF
    };

})();