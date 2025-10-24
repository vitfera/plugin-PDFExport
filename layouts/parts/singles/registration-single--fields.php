<?php
/**
 * Template para PDF - Estrutura inspirada no embedtools
 * Organizado por cards sem tabelas
 */

$registration = $entity;
$opportunity = $registration->opportunity;
$agent = $registration->owner;

// Buscar campos do formulário
$fieldConfigurations = [];
if ($opportunity && method_exists($opportunity, 'getRegistrationFieldConfigurations')) {
    try {
        $fieldConfigurations = $opportunity->getRegistrationFieldConfigurations();
    } catch (Exception $e) {
        // Ignora erro
    }
}

// Buscar dados de formulário
$formData = [];
foreach ($fieldConfigurations as $config) {
    $fieldName = $config->fieldName;
    if (isset($registration->$fieldName) && !empty($registration->$fieldName)) {
        $formData[$config->title ?: $fieldName] = $registration->$fieldName;
    }
}

// Buscar avaliações
$evaluations = [];
$evaluationInfo = [];
try {
    if (method_exists($registration, 'getEvaluations')) {
        $evaluations = $registration->getEvaluations();
    }
    
    if ($registration->opportunity) {
        $opp = $registration->opportunity;
        if ($opp->parent) {
            $evaluationInfo['parent'] = $opp->parent->name;
        }
        $evaluationInfo['current'] = $opp->name;
        if ($opp->evaluationMethodConfiguration) {
            $evaluationInfo['method'] = $opp->evaluationMethodConfiguration->name;
        }
    }
} catch (Exception $e) {
    // Ignora erros
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ficha de Inscrição - <?= htmlspecialchars($registration->number) ?></title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #007cba;
        }
        
        .header h1 {
            color: #007cba;
            margin: 0 0 10px 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #666;
            margin: 0;
            font-size: 18px;
            font-weight: normal;
        }
        
        .card {
            background: #f8f9fa;
            border-left: 4px solid #007cba;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 0 8px 8px 0;
        }
        
        .card h3 {
            color: #007cba;
            margin: 0 0 15px 0;
            font-size: 18px;
            font-weight: 600;
        }
        
        .info-item {
            margin-bottom: 12px;
        }
        
        .info-label {
            font-weight: 600;
            color: #555;
            display: block;
            margin-bottom: 4px;
        }
        
        .info-value {
            color: #333;
            white-space: pre-line;
            word-wrap: break-word;
        }
        
        .info-value em {
            color: #999;
            font-style: italic;
        }
        
        .status {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .status-draft { background: #ffc107; color: #856404; }
        .status-enabled { background: #28a745; color: #fff; }
        .status-selected { background: #007cba; color: #fff; }
        .status-rejected { background: #dc3545; color: #fff; }
        
        .evaluation-card {
            background: #e8f4f8;
            border-left: 4px solid #17a2b8;
            padding: 15px;
            margin: 10px 0;
            border-radius: 0 6px 6px 0;
        }
        
        .field-section {
            margin-bottom: 15px;
        }
        
        .field-section h4 {
            color: #007cba;
            font-size: 16px;
            margin: 0 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        
        @media print {
            body { margin: 0; padding: 15px; }
            .card { break-inside: avoid; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Ficha de Inscrição</h1>
        <h2><?= htmlspecialchars($registration->number) ?></h2>
    </div>

    <!-- Informações Básicas -->
    <div class="card">
        <h3>Informações Básicas</h3>
        
        <div class="info-item">
            <span class="info-label">ID da Inscrição:</span>
            <span class="info-value"><?= htmlspecialchars($registration->id) ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Número:</span>
            <span class="info-value"><?= htmlspecialchars($registration->number) ?></span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Status:</span>
            <span class="info-value">
                <span class="status <?= $registration->status == 1 ? 'status-enabled' : ($registration->status == 10 ? 'status-selected' : ($registration->status == 3 ? 'status-rejected' : 'status-draft')) ?>">
                    <?php
                    $statusLabels = [
                        0 => 'Rascunho',
                        1 => 'Enviada',
                        2 => 'Aprovada', 
                        3 => 'Rejeitada',
                        8 => 'Suplente',
                        10 => 'Selecionada'
                    ];
                    echo $statusLabels[$registration->status] ?? "Status {$registration->status}";
                    ?>
                </span>
            </span>
        </div>
        
        <?php if ($registration->createTimestamp): ?>
        <div class="info-item">
            <span class="info-label">Data de Criação:</span>
            <span class="info-value"><?= $registration->createTimestamp->format('d/m/Y H:i:s') ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->sentTimestamp): ?>
        <div class="info-item">
            <span class="info-label">Data de Envio:</span>
            <span class="info-value"><?= $registration->sentTimestamp->format('d/m/Y H:i:s') ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($registration->category)): ?>
        <div class="info-item">
            <span class="info-label">Categoria:</span>
            <span class="info-value"><?= htmlspecialchars($registration->category) ?></span>
        </div>
        <?php else: ?>
        <div class="info-item">
            <span class="info-label">Categoria:</span>
            <span class="info-value"><em>Não informado</em></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($registration->range)): ?>
        <div class="info-item">
            <span class="info-label">Faixa:</span>
            <span class="info-value"><?= htmlspecialchars($registration->range) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($registration->proponentType)): ?>
        <div class="info-item">
            <span class="info-label">Tipo de Proponente:</span>
            <span class="info-value"><?= htmlspecialchars($registration->proponentType) ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Dados do Proponente -->
    <?php if ($registration->owner): ?>
    <div class="card">
        <h3>Dados do Proponente</h3>
        
        <div class="info-item">
            <span class="info-label">Nome:</span>
            <span class="info-value">
                <?php if (!empty($registration->owner->name)): ?>
                    <?= htmlspecialchars($registration->owner->name) ?>
                <?php else: ?>
                    <em>Não informado</em>
                <?php endif; ?>
            </span>
        </div>
        
        <div class="info-item">
            <span class="info-label">Tipo:</span>
            <span class="info-value"><?= $registration->owner->type == 1 ? 'Pessoa Física' : 'Pessoa Jurídica' ?></span>
        </div>
        
        <?php if (!empty($registration->owner->shortDescription)): ?>
        <div class="info-item">
            <span class="info-label">Descrição:</span>
            <span class="info-value"><?= htmlspecialchars($registration->owner->shortDescription) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Dados da Oportunidade -->
    <div class="card">
        <h3>Dados da Oportunidade</h3>
        
        <div class="info-item">
            <span class="info-label">Nome:</span>
            <span class="info-value"><?= htmlspecialchars($opportunity->name) ?></span>
        </div>
        
        <?php if ($opportunity->registrationFrom && $opportunity->registrationTo): ?>
        <div class="info-item">
            <span class="info-label">Período de Inscrições:</span>
            <span class="info-value">
                <?= $opportunity->registrationFrom->format('d/m/Y') ?> a 
                <?= $opportunity->registrationTo->format('d/m/Y') ?>
            </span>
        </div>
        <?php endif; ?>
        
        <?php if ($opportunity->evaluationMethodConfiguration): ?>
        <div class="info-item">
            <span class="info-label">Método de Avaliação:</span>
            <span class="info-value"><?= htmlspecialchars($opportunity->evaluationMethodConfiguration->name) ?></span>
        </div>
        
        <?php if ($opportunity->evaluationMethodConfiguration->evaluationFrom && $opportunity->evaluationMethodConfiguration->evaluationTo): ?>
        <div class="info-item">
            <span class="info-label">Período de Avaliação:</span>
            <span class="info-value">
                <?= $opportunity->evaluationMethodConfiguration->evaluationFrom->format('d/m/Y') ?> a 
                <?= $opportunity->evaluationMethodConfiguration->evaluationTo->format('d/m/Y') ?>
            </span>
        </div>
        <?php endif; ?>
        <?php endif; ?>
    </div>

    <!-- Dados do Formulário de Inscrição -->
    <?php if (!empty($formData)): ?>
    <div class="card">
        <h3>Dados do Formulário de Inscrição</h3>
        
        <?php foreach ($formData as $label => $value): ?>
        <div class="info-item">
            <span class="info-label"><?= htmlspecialchars($label) ?>:</span>
            <span class="info-value">
                <?php
                if (is_array($value)) {
                    // Converter todos os elementos do array para string
                    $stringValues = array_map(function($item) {
                        if (is_object($item)) {
                            return json_encode($item);
                        } elseif (is_array($item)) {
                            return json_encode($item);
                        } else {
                            return strval($item);
                        }
                    }, $value);
                    echo htmlspecialchars(implode(', ', $stringValues));
                } elseif (is_object($value)) {
                    echo htmlspecialchars(json_encode($value));
                } else {
                    echo htmlspecialchars(strval($value));
                }
                ?>
            </span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Resultado da Avaliação -->
    <div class="card">
        <h3>Resultado da Avaliação</h3>
        
        <div class="info-item">
            <span class="info-label">Status da Inscrição:</span>
            <span class="info-value">
                <span class="status <?= $registration->status == 1 ? 'status-enabled' : ($registration->status == 10 ? 'status-selected' : ($registration->status == 3 ? 'status-rejected' : 'status-draft')) ?>">
                    <?php
                    $statusLabels = [
                        0 => 'Rascunho',
                        1 => 'Ativa/Enviada',
                        2 => 'Aprovada',
                        3 => 'Rejeitada',
                        8 => 'Suplente',
                        10 => 'Selecionada'
                    ];
                    echo $statusLabels[$registration->status] ?? "Status {$registration->status}";
                    ?>
                </span>
            </span>
        </div>
        
        <?php if (!empty($registration->consolidatedResult)): ?>
        <div class="info-item">
            <span class="info-label">Resultado Consolidado:</span>
            <span class="info-value"><strong><?= htmlspecialchars($registration->consolidatedResult) ?></strong></span>
        </div>
        <?php endif; ?>
        
        <?php if (isset($registration->score) && $registration->score !== null): ?>
        <div class="info-item">
            <span class="info-label">Pontuação:</span>
            <span class="info-value"><?= number_format($registration->score, 2, ',', '.') ?></span>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($evaluationInfo)): ?>
        <div class="field-section">
            <h4>Estrutura da Avaliação</h4>
            
            <?php if (isset($evaluationInfo['parent'])): ?>
            <div class="info-item">
                <span class="info-label">Edital Principal:</span>
                <span class="info-value"><?= htmlspecialchars($evaluationInfo['parent']) ?></span>
            </div>
            <?php endif; ?>
            
            <div class="info-item">
                <span class="info-label">Fase Atual:</span>
                <span class="info-value"><?= htmlspecialchars($evaluationInfo['current']) ?></span>
            </div>
            
            <?php if (isset($evaluationInfo['method'])): ?>
            <div class="info-item">
                <span class="info-label">Método de Avaliação:</span>
                <span class="info-value"><?= htmlspecialchars($evaluationInfo['method']) ?></span>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($evaluations)): ?>
        <div class="field-section">
            <h4>Detalhes das Avaliações</h4>
            
            <?php foreach ($evaluations as $eval): ?>
            <div class="evaluation-card">
                <?php if ($eval->user && $eval->user->profile): ?>
                <div class="info-item">
                    <span class="info-label">Avaliador:</span>
                    <span class="info-value"><?= htmlspecialchars($eval->user->profile->name) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($eval->committee)): ?>
                <div class="info-item">
                    <span class="info-label">Comissão:</span>
                    <span class="info-value"><?= htmlspecialchars($eval->committee) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($eval->result): ?>
                <div class="info-item">
                    <span class="info-label">Resultado:</span>
                    <span class="info-value"><?= htmlspecialchars($eval->result) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (isset($eval->evaluationData)): ?>
                    <?php 
                    $evalData = $eval->evaluationData;
                    if (is_string($evalData)) {
                        $evalData = json_decode($evalData, true);
                    }
                    if (is_object($evalData)) {
                        $evalData = (array) $evalData;
                    }
                    if (is_array($evalData)): ?>
                        <?php foreach ($evalData as $key => $value): ?>
                            <?php if ($key !== 'uid' && !empty($value)): ?>
                            <div class="info-item">
                                <span class="info-label"><?= ucfirst(str_replace('_', ' ', $key)) ?>:</span>
                                <span class="info-value"><?= htmlspecialchars(is_array($value) ? implode(', ', $value) : strval($value)) ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if ($eval->sentTimestamp): ?>
                <div class="info-item">
                    <span class="info-label">Data de Envio:</span>
                    <span class="info-value"><small><?= $eval->sentTimestamp->format('d/m/Y H:i:s') ?></small></span>
                </div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php elseif ($registration->status == 0): ?>
        <div class="info-item">
            <span class="info-label">Status de Avaliação:</span>
            <span class="info-value"><em>Esta inscrição está em rascunho e ainda não foi submetida para avaliação.</em></span>
        </div>
        <?php else: ?>
        <div class="info-item">
            <span class="info-label">Status de Avaliação:</span>
            <span class="info-value"><em>Nenhuma avaliação individual encontrada, mas há resultado consolidado disponível.</em></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>Documento gerado automaticamente pelo sistema em <?= date('d/m/Y H:i:s') ?></p>
        <p>Mapas Culturais - PDFExport Plugin</p>
    </div>
</body>
</html>