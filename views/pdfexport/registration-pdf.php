<?php
/**
 * Template para geração de PDF da inscrição
 * Baseado no registration-view do embedtools
 */

$action = 'pdf';

$avaliable_evaluationFields = $entity->opportunity->avaliableEvaluationFields ?? [];
$avaliable_evaluationFields['proponentType'] = true;
$avaliable_evaluationFields['range'] = true;
$avaliable_evaluationFields['category'] = true;

$app->view->jsObject['avaliableEvaluationFields'] = $avaliable_evaluationFields;
$app->view->jsObject['viewUserEvaluation'] = $entity->canUser('viewUserEvaluation');

$app->view->jsObject['bank_data_dict'] = [
    'account_types' => $app->config['module.registrationFieldTypes']['account_types'],
    'bank_types' => $app->config['module.registrationFieldTypes']['bank_types']
];

$_params = [
    'entity' => $entity,
    'action' => $action,
    'opportunity' => $entity->opportunity
];
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Inscrição <?= $entity->number ?> - <?= $entity->opportunity->name ?></title>
    <style>
        /* Estilos para impressão/PDF */
        body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
        .registration-fieldset { margin-bottom: 20px; }
        .registration-step { border: 1px solid #ddd; margin-bottom: 15px; }
        .registration-step__title { background: #f5f5f5; padding: 10px; font-weight: bold; }
        .attachment-list { list-style: none; padding: 0; margin: 0; }
        .attachment-list-item { padding: 10px; border-bottom: 1px solid #eee; }
        .section-title { font-weight: bold; background: #f9f9f9; padding: 15px; }
        .label { font-weight: bold; color: #666; }
        .registration-header { margin-bottom: 30px; padding-bottom: 20px; border-bottom: 2px solid #333; }
        .registration-number { font-size: 24px; font-weight: bold; }
        .opportunity-title { font-size: 18px; margin: 10px 0; }
    </style>
</head>
<body>
    <div class="registration-header">
        <h1>Ficha de Inscrição</h1>
        <div class="opportunity-title"><?= $entity->opportunity->name ?></div>
        <div class="registration-number">Nº <?= $entity->number ?></div>
    </div>

    <?php $this->part('singles/registration-single--fields', $_params) ?>
</body>
</html>