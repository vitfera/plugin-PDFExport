# PDFExport Plugin para MapasCulturais

Plugin para exportar registrations do MapasCulturais em formato PDF usando a biblioteca mPDF.

## Instalação

1. Clone ou baixe este plugin no diretório de plugins do MapasCulturais:
```bash
cd /caminho/para/mapasculturais/src/plugins/
git clone https://github.com/vitfera/plugin-PDFExport.git PDFExport
```

2. **Opcional**: Instale as dependências via Composer para funcionalidade PDF completa:
```bash
cd PDFExport
composer install
```

3. Ative o plugin no arquivo de configuração do MapasCulturais ou via interface administrativa.

**Nota**: O plugin funciona sem o Composer, gerando HTML otimizado para impressão como PDF pelo navegador. Com o Composer instalado, gera arquivos PDF nativos usando mPDF.

## Configuração Avançada

Você pode personalizar o comportamento do plugin através de parâmetros de configuração:

```php
'plugins' => [
    'PDFExport' => [
        'namespace' => 'PDFExport',
        'config' => [
            'button_text' => 'Baixar PDF',
            'pdf_title' => 'Comprovante de Inscrição',
            'show_registration_fields' => true,
            'show_agent_fields' => true,
            'custom_css' => 'body { font-family: Arial; }'
        ]
    ]
]
```

### Parâmetros Disponíveis

- **button_text**: Texto exibido no botão (padrão: "Exportar PDF")
- **pdf_title**: Título do documento PDF (padrão: "Dados da Inscrição")
- **show_registration_fields**: Exibir campos da inscrição (padrão: true)
- **show_agent_fields**: Exibir dados do agente (padrão: true)  
- **custom_css**: CSS personalizado para o PDF (padrão: '')

## Desenvolvimento

Para trabalhar com os assets do plugin:

```bash
# Instalar dependências
npm install

# Modo desenvolvimento
npm run dev

# Build para produção
npm run build

# Watch mode
npm run watch
```

## Funcionalidades

- ✅ Adiciona botão "Exportar PDF" nas páginas de registration
- ✅ Gera PDF com dados da registration usando template customizável
- ✅ Suporte a CSS personalizado para formatação do PDF
- ✅ Sistema de hooks integrado ao MapasCulturais
- ✅ Controller dedicado para endpoints de PDF
- ✅ Sistema de internacionalização (i18n)
- ✅ Configuração flexível via parâmetros
- ✅ Preview de PDF no navegador
- ✅ Build system com Laravel Mix

## Dependências

- PHP 7.4+
- MapasCulturais v7+
- **Opcional**: mPDF 8.x (instalado via Composer para funcionalidade PDF completa)
- **Fallback**: Funciona sem dependências externas, gerando HTML para impressão

## Funcionalidades

- **Geração de PDF**: Converte fichas de inscrição em documentos PDF usando a biblioteca mPDF
- **Integração com Interface**: Adiciona botão "Baixar PDF" na página de visualização de inscrições
- **Template Customizável**: Template PHP personalizado para layout do PDF
- **Dados Completos**: Inclui todas as informações da inscrição:
  - Dados básicos (status, categoria, faixa)
  - Informações do responsável
  - Agentes relacionados
  - Espaços culturais vinculados
  - Projetos relacionados
  - Campos customizados do formulário
  - Arquivos anexados

## Instalação

1. Copie o diretório `PDFExport` para `src/plugins/`
2. Adicione o plugin ao arquivo `config/plugins.php`:
```php
'PDFExport'
```
3. Instale as dependências via Composer (já incluídas no plugin)

## Estrutura do Plugin

```
PDFExport/
├── Plugin.php              # Classe principal do plugin
├── Services/
│   └── PDFService.php      # Serviço para geração de PDFs
├── views/
│   └── registration-pdf.php # Template do PDF
├── assets/
│   ├── css/
│   │   └── pdf-export.css  # Estilos do botão
│   └── js/
│       └── pdf-export.js   # JavaScript do plugin
└── vendor/                 # Dependências (mPDF)
```

## Como Usar

1. **Para Administradores**: O botão "Baixar PDF" aparece automaticamente na página de visualização de inscrições
2. **Para Usuários**: Clique no botão para fazer download do PDF da inscrição

## Configuração

O plugin não requer configuração adicional. O template do PDF pode ser customizado editando o arquivo `views/registration-pdf.php`.

## Dependências

- **mPDF v8.2**: Biblioteca para geração de PDFs
- **MapasCulturais v6**: Sistema base

## Personalização

### Template do PDF

Para personalizar o layout do PDF, edite o arquivo `views/registration-pdf.php`. O template tem acesso à variável `$registration` com todos os dados da inscrição.

### Estilos CSS

Os estilos do PDF são definidos inline no template. Para modificar a aparência, edite as seções `<style>` no arquivo de template.

## Hooks Utilizados

- `template(single-tab):begin`: Adiciona o botão PDF na interface
- `GET(registration.downloadPdf)`: Rota para download do PDF

## Desenvolvimento

Para desenvolvimento do plugin:

1. Ative o modo debug no MapasCulturais
2. Monitore os logs em `var/logs/app.log`
3. Teste com diferentes tipos de inscrição

## Licença

Este plugin segue a licença do MapasCulturais.

## Configuração

Adicione ao arquivo de configuração do Mapas Culturais:

```php
// config/plugins.php
return [
    'plugins' => [
        'PDFExport' => ['namespace' => 'PDFExport'],
    ]
];
```

## Como Usar

1. Acesse uma ficha de inscrição
2. Clique no botão "Imprimir" 
3. O botão "Baixar PDF" aparecerá ao lado
4. Clique para fazer download do PDF

## Estrutura do PDF

O PDF gerado inclui:

- Informações básicas da inscrição
- Dados do responsável
- Agentes relacionados
- Espaços culturais
- Projetos vinculados
- Campos personalizados
- Arquivos anexados
- Informações de envio

## Requisitos

- PHP 7.4+
- mPDF library (instalada automaticamente)
- Mapas Culturais v6+

## Desenvolvimento

### Estrutura de Arquivos

```
PDFExport/
├── Module.php              # Classe principal
├── composer.json           # Dependências
├── Services/
│   └── PDFService.php      # Serviço de geração PDF
├── views/
│   ├── registration-pdf.php # Template do PDF
│   └── pdf-export-button.php # Botão da interface
└── assets/
    ├── css/pdf-export.css   # Estilos
    └── js/pdf-export.js     # JavaScript
```

### Hooks Utilizados

- `template(registration-print.main):end` - Adiciona botão
- `GET(registration.downloadPdf)` - Rota de download
- `app.init:after` - Carrega assets

## Licença

AGPL-3.0+