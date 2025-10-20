<?php
/**
 * Template para geração de PDF da ficha de inscrição
 * @var Registration $registration
 */

use MapasCulturais\i;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= i::__('Ficha de Inscrição') ?> - <?= htmlspecialchars($registration->number) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #0066cc;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        
        .header h1 {
            color: #0066cc;
            font-size: 18px;
            margin: 0 0 5px 0;
        }
        
        .header h2 {
            color: #666;
            font-size: 14px;
            margin: 0;
            font-weight: normal;
        }
        
        .section {
            margin-bottom: 20px;
            page-break-inside: avoid;
        }
        
        .section-title {
            background-color: #f0f6ff;
            color: #0066cc;
            font-weight: bold;
            font-size: 14px;
            padding: 8px 12px;
            border-left: 4px solid #0066cc;
            margin-bottom: 10px;
        }
        
        .field {
            margin-bottom: 8px;
            display: block;
        }
        
        .field-label {
            font-weight: bold;
            color: #555;
            display: inline-block;
            min-width: 150px;
            vertical-align: top;
        }
        
        .field-value {
            color: #333;
            word-wrap: break-word;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #555;
        }
        
        .table td {
            word-wrap: break-word;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        h4 {
            color: #0066cc;
            font-size: 14px;
            margin: 15px 0 10px 0;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        
        em {
            color: #999;
            font-style: italic;
        }
        
        @page {
            margin: 2cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1><?= i::__('Ficha de Inscrição') ?></h1>
        <h2><?= htmlspecialchars($registration->opportunity->name) ?></h2>
        <h2><?= i::__('Nº') ?> <?= htmlspecialchars($registration->number) ?></h2>
    </div>

<div class="registration-pdf">
    <!-- Informações básicas da inscrição -->
    <div class="section">
        <div class="section-title"><?= i::__('Informações da Inscrição') ?></div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Número da Inscrição:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->number) ?></span>
        </div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Status:') ?></span>
            <span class="field-value"><?= \MapasCulturais\Entities\Registration::getStatusesNames()[$registration->status] ?? 'Não definido' ?></span>
        </div>
        
        <?php if ($registration->opportunity->name): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Oportunidade:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->opportunity->name) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->category): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Categoria:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->category) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->range): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Faixa:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->range) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->proponentType): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Tipo de Proponente:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->proponentType) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->projectName): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Nome do Projeto:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->projectName) ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Informações do responsável (Dados do proponente) -->
    <div class="section">
        <div class="section-title"><?= i::__('Dados do Proponente') ?></div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Nome:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->name) ?></span>
        </div>
        
        <?php if ($registration->owner->shortDescription): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Descrição curta:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->shortDescription) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->documento || $registration->owner->cnpj): ?>
        <div class="field">
            <span class="field-label"><?= i::__('CPF/CNPJ:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->documento ?? $registration->owner->cnpj) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->dataDeNascimento): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Data de nascimento/fundação:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->dataDeNascimento) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->emailPublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('E-mail:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->emailPublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->telefonePublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Telefone:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->telefonePublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->raca): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Raça:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->raca) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->genero): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Gênero:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->genero) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->orientacaoSexual): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Orientação Sexual:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->orientacaoSexual) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->pessoaDeficiente): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Pessoa com Deficiência:') ?></span>
            <span class="field-value"><?= $registration->owner->pessoaDeficiente === 'Sim' ? 'Sim' : 'Não' ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->escolaridade): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Escolaridade:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->escolaridade) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->endereco): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Endereço:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->endereco) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->En_CEP): ?>
        <div class="field">
            <span class="field-label"><?= i::__('CEP:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->En_CEP) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->En_Municipio): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Município:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->En_Municipio) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->owner->En_Estado): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Estado:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->owner->En_Estado) ?></span>
        </div>
        <?php endif; ?>
        
        <!-- Links e redes sociais -->
        <?php 
        $links = $registration->owner->getMetaLists()['links'] ?? [];
        if ($links): 
        ?>
        <div class="field">
            <span class="field-label"><?= i::__('Links/Redes Sociais:') ?></span>
            <span class="field-value">
                <?php foreach ($links as $link): ?>
                    <div><?= htmlspecialchars($link) ?></div>
                <?php endforeach; ?>
            </span>
        </div>
        <?php endif; ?>
        
        <!-- Vídeos -->
        <?php 
        $videos = $registration->owner->getMetaLists()['videos'] ?? [];
        if ($videos): 
        ?>
        <div class="field">
            <span class="field-label"><?= i::__('Vídeos:') ?></span>
            <span class="field-value">
                <?php foreach ($videos as $video): ?>
                    <div><?= htmlspecialchars($video) ?></div>
                <?php endforeach; ?>
            </span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Coletivo -->
    <?php 
    $coletivo = $registration->getRelatedAgents('coletivo')[0] ?? null;
    if ($coletivo && $registration->opportunity->useAgentRelationColetivo !== 'dontUse'): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Coletivo') ?></div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Nome:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->name) ?></span>
        </div>
        
        <?php if ($coletivo->shortDescription): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Descrição:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->shortDescription) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($coletivo->documento): ?>
        <div class="field">
            <span class="field-label"><?= i::__('CPF/CNPJ:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->documento) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($coletivo->emailPublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('E-mail:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->emailPublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($coletivo->telefonePublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Telefone:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->telefonePublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($coletivo->endereco): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Endereço:') ?></span>
            <span class="field-value"><?= htmlspecialchars($coletivo->endereco) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Instituição responsável -->
    <?php 
    $instituicao = $registration->getRelatedAgents('instituicao')[0] ?? null;
    if ($instituicao && $registration->opportunity->useAgentRelationInstituicao !== 'dontUse'): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Instituição Responsável') ?></div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Nome:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->name) ?></span>
        </div>
        
        <?php if ($instituicao->shortDescription): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Descrição:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->shortDescription) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($instituicao->documento): ?>
        <div class="field">
            <span class="field-label"><?= i::__('CPF/CNPJ:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->documento) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($instituicao->emailPublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('E-mail:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->emailPublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($instituicao->telefonePublico): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Telefone:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->telefonePublico) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($instituicao->endereco): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Endereço:') ?></span>
            <span class="field-value"><?= htmlspecialchars($instituicao->endereco) ?></span>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Outros agentes relacionados -->
    <?php 
    $agents = $registration->getRelatedAgents();
    // Remove coletivo e instituição já mostrados nas seções específicas
    unset($agents['coletivo']);
    unset($agents['instituicao']);
    
    if ($agents): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Outros Agentes Relacionados') ?></div>
        
        <table class="table">
            <thead>
                <tr>
                    <th><?= i::__('Grupo') ?></th>
                    <th><?= i::__('Nome') ?></th>
                    <th><?= i::__('Tipo') ?></th>
                    <th><?= i::__('CPF/CNPJ') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($agents as $groupName => $groupAgents): ?>
                    <?php foreach ($groupAgents as $agentRelation): ?>
                    <tr>
                        <td><?= htmlspecialchars(ucfirst($groupName)) ?></td>
                        <td><?= htmlspecialchars($agentRelation->agent->name) ?></td>
                        <td><?= htmlspecialchars($agentRelation->agent->type->name ?? '-') ?></td>
                        <td><?= htmlspecialchars($agentRelation->agent->documento ?? '-') ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Espaços relacionados -->
    <?php 
    $spaces = $registration->relatedSpaces ?? [];
    if ($spaces && $registration->opportunity->useSpaceRelationIntituicao !== 'dontUse'): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Espaços Culturais Vinculados') ?></div>
        
        <table class="table">
            <thead>
                <tr>
                    <th><?= i::__('Nome') ?></th>
                    <th><?= i::__('Tipo') ?></th>
                    <th><?= i::__('Endereço') ?></th>
                    <th><?= i::__('Descrição') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($spaces as $space): ?>
                <tr>
                    <td><?= htmlspecialchars($space->name) ?></td>
                    <td><?= htmlspecialchars($space->type->name ?? '-') ?></td>
                    <td><?= htmlspecialchars($space->endereco ?? '-') ?></td>
                    <td><?= htmlspecialchars(substr($space->shortDescription ?? '', 0, 150)) ?><?= strlen($space->shortDescription ?? '') > 150 ? '...' : '' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Projetos relacionados -->
    <?php 
    $projects = $registration->relatedProjects ?? [];
    if ($projects): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Projetos') ?></div>
        
        <table class="table">
            <thead>
                <tr>
                    <th><?= i::__('Nome') ?></th>
                    <th><?= i::__('Tipo') ?></th>
                    <th><?= i::__('Descrição') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= htmlspecialchars($project->name) ?></td>
                    <td><?= htmlspecialchars($project->type->name ?? '-') ?></td>
                    <td><?= htmlspecialchars(substr($project->shortDescription ?? '', 0, 200)) ?><?= strlen($project->shortDescription ?? '') > 200 ? '...' : '' ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Campos personalizados da inscrição -->
    <?php 
    $registrationFields = $registration->opportunity->registrationFieldConfigurations;
    if ($registrationFields): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Informações Adicionais') ?></div>
        
        <?php foreach ($registrationFields as $field): ?>
            <?php 
            $fieldName = $field->fieldName;
            $fieldValue = $registration->$fieldName ?? '';
            
            // Exibe campos de seção como títulos
            if ($field->fieldType === 'section') {
                echo '<h4 style="margin: 15px 0 10px 0; color: #0066cc; font-size: 14px;">' . htmlspecialchars($field->title) . '</h4>';
                continue;
            }
            
            // Pula campos vazios exceto checkbox que pode ser false
            if (empty($fieldValue) && $field->fieldType !== 'checkbox') {
                continue;
            }
            ?>
            <div class="field">
                <span class="field-label"><?= $field->required ? '*' : '' ?> <?= htmlspecialchars($field->title) ?>:</span>
                <span class="field-value">
                    <?php if ($field->fieldType === 'bankFields'): ?>
                        <?php if (is_string($fieldValue)) {
                            $bankData = json_decode($fieldValue, true);
                        } else {
                            $bankData = (array) $fieldValue;
                        } ?>
                        <br>
                        <table class="table" style="margin-top: 5px;">
                            <tr><th>Tipo de conta</th><td><?= htmlspecialchars($bankData['account_type'] ?? '') ?></td></tr>
                            <tr><th>Banco</th><td><?= htmlspecialchars($bankData['number'] ?? '') ?></td></tr>
                            <tr><th>Agência</th><td><?= htmlspecialchars($bankData['branch'] ?? '') ?>-<?= htmlspecialchars($bankData['dv_branch'] ?? '') ?></td></tr>
                            <tr><th>Conta</th><td><?= htmlspecialchars($bankData['account_number'] ?? '') ?>-<?= htmlspecialchars($bankData['dv_account_number'] ?? '') ?></td></tr>
                        </table>
                    <?php elseif ($field->fieldType === 'persons'): ?>
                        <?php 
                        $personsData = is_string($fieldValue) ? json_decode($fieldValue, true) : (array) $fieldValue;
                        if (is_array($personsData) && !empty($personsData)):
                        ?>
                        <table class="table" style="margin-top: 5px;">
                            <thead>
                                <tr>
                                    <th>Nome</th>
                                    <th>CPF</th>
                                    <th>Relação</th>
                                    <th>Função</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($personsData as $key => $person): ?>
                                    <?php if ($key !== 'location' && $key !== 'publicLocation' && is_array($person) && !empty($person)): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($person['name'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($person['cpf'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($person['relationship'] ?? '-') ?></td>
                                        <td><?= htmlspecialchars($person['function'] ?? '-') ?></td>
                                    </tr>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    <?php elseif ($field->config['entityField'] ?? '' === '@location'): ?>
                        <?php 
                        $locationData = is_string($fieldValue) ? json_decode($fieldValue, true) : (array) $fieldValue;
                        if (is_array($locationData)):
                        ?>
                        <div>
                            <?php foreach ($locationData as $key => $value): ?>
                                <?php if ($key !== 'location' && $key !== 'publicLocation' && !empty($value) && !str_starts_with($key, 'field')): ?>
                                <div><strong><?= htmlspecialchars(ucfirst(str_replace('_', ' ', explode('_', $key)[count(explode('_', $key))-1]))) ?>:</strong> <?= htmlspecialchars($value) ?></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            
                            <?php if (isset($locationData['publicLocation'])): ?>
                            <div><strong>Este endereço pode ficar público na plataforma?:</strong> <?= ($locationData['publicLocation'] === true || $locationData['publicLocation'] === 'true') ? 'Sim' : 'Não' ?></div>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                    <?php elseif ($field->config['entityField'] ?? '' === '@links' || $field->fieldType === 'links'): ?>
                        <?php 
                        $linksData = is_string($fieldValue) ? json_decode($fieldValue, true) : (array) $fieldValue;
                        if (is_array($linksData)):
                        ?>
                        <div>
                            <?php foreach ($linksData as $key => $linkItem): ?>
                                <?php if (is_array($linkItem) && isset($linkItem['title']) && isset($linkItem['value'])): ?>
                                <div><strong><?= htmlspecialchars($linkItem['title']) ?>:</strong> <a href="<?= htmlspecialchars($linkItem['value']) ?>" target="_blank"><?= htmlspecialchars($linkItem['value']) ?></a></div>
                                <?php elseif ($key !== 'location' && $key !== 'publicLocation' && !empty($linkItem)): ?>
                                <div><a href="<?= htmlspecialchars($linkItem) ?>" target="_blank"><?= htmlspecialchars($linkItem) ?></a></div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    <?php elseif ($field->fieldType === 'agent-owner-field' && ($field->config['entityField'] ?? '') === 'pessoaDeficiente'): ?>
                        <?= $fieldValue === 'Sim' ? 'Sim' : ($fieldValue === 'Não' ? 'Não' : (empty($fieldValue) ? 'Não informado' : htmlspecialchars($fieldValue))) ?>
                    <?php elseif ($field->fieldType === 'checkbox'): ?>
                        <?= $fieldValue ? 'Sim' : 'Não' ?>
                    <?php elseif ($field->fieldType === 'checkboxes'): ?>
                        <?php 
                        $checkboxValues = is_string($fieldValue) ? json_decode($fieldValue, true) : $fieldValue;
                        if (is_array($checkboxValues)) {
                            echo htmlspecialchars(implode(', ', $checkboxValues));
                        } else {
                            echo htmlspecialchars($fieldValue);
                        }
                        ?>
                    <?php elseif ($field->fieldType === 'currency'): ?>
                        R$ <?= number_format((float) $fieldValue, 2, ',', '.') ?>
                    <?php elseif ($field->fieldType === 'date'): ?>
                        <?php 
                        try {
                            if ($fieldValue instanceof DateTime) {
                                echo $fieldValue->format('d/m/Y');
                            } else {
                                $date = new DateTime($fieldValue);
                                echo $date->format('d/m/Y');
                            }
                        } catch (Exception $e) {
                            echo htmlspecialchars($fieldValue);
                        }
                        ?>
                    <?php elseif ($field->fieldType === 'textarea'): ?>
                        <div style="white-space: pre-line;"><?= htmlspecialchars($fieldValue) ?></div>
                    <?php elseif ($field->fieldType === 'url'): ?>
                        <a href="<?= htmlspecialchars($fieldValue) ?>" target="_blank"><?= htmlspecialchars($fieldValue) ?></a>
                    <?php elseif ($field->fieldType === 'email'): ?>
                        <a href="mailto:<?= htmlspecialchars($fieldValue) ?>"><?= htmlspecialchars($fieldValue) ?></a>
                    <?php elseif (is_array($fieldValue)): ?>
                        <?php 
                        $values = array_map(function($item) {
                            if (is_object($item)) {
                                return $item->name ?? $item->label ?? $item->value ?? $item->id ?? (string)$item;
                            }
                            return (string)$item;
                        }, $fieldValue);
                        ?>
                        <?= htmlspecialchars(implode(', ', array_filter($values))) ?>
                    <?php elseif (is_object($fieldValue)): ?>
                        <?= htmlspecialchars($fieldValue->name ?? $fieldValue->label ?? $fieldValue->value ?? $fieldValue->id ?? (string)$fieldValue) ?>
                    <?php else: ?>
                        <?= !empty($fieldValue) ? htmlspecialchars($fieldValue) : '<em>Campo não informado</em>' ?>
                    <?php endif; ?>
                </span>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Arquivos anexados -->
    <?php 
    $files = $registration->files ?? [];
    if ($files): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Arquivos Anexados') ?></div>
        
        <table class="table">
            <thead>
                <tr>
                    <th><?= i::__('Nome do Arquivo') ?></th>
                    <th><?= i::__('Tipo') ?></th>
                    <th><?= i::__('Tamanho') ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $group => $groupFiles): ?>
                    <?php foreach ($groupFiles as $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($file->name) ?></td>
                        <td><?= htmlspecialchars($file->mimeType ?? '-') ?></td>
                        <td><?= $file->size ? number_format($file->size / 1024, 2) . ' KB' : '-' ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php endif; ?>

    <!-- Avaliações -->
    <?php 
    $evaluations = [];
    $currentPhase = $registration;
    
    // Percorre todas as fases para buscar avaliações
    while ($currentPhase) {
        if ($currentPhase->opportunity && $currentPhase->opportunity->evaluationMethodConfiguration) {
            try {
                $phaseEvaluations = $currentPhase->getEvaluations();
                if ($phaseEvaluations) {
                    foreach ($phaseEvaluations as $evaluation) {
                        $evaluations[] = [
                            'phase' => $currentPhase->opportunity->name,
                            'evaluation' => $evaluation
                        ];
                    }
                }
            } catch (Exception $e) {
                // Continue se não conseguir buscar avaliações
            }
        }
        $currentPhase = $currentPhase->nextPhase ?? null;
    }
    
    if ($evaluations): 
    ?>
    <div class="section">
        <div class="section-title"><?= i::__('Avaliações') ?></div>
        
        <?php foreach ($evaluations as $evalData): ?>
            <?php $evaluation = $evalData['evaluation']; ?>
            <div style="margin-bottom: 15px; border-left: 3px solid #ddd; padding-left: 10px;">
                <div class="field">
                    <span class="field-label"><?= i::__('Fase:') ?></span>
                    <span class="field-value"><?= htmlspecialchars($evalData['phase']) ?></span>
                </div>
                
                <?php if ($evaluation->user): ?>
                <div class="field">
                    <span class="field-label"><?= i::__('Avaliador:') ?></span>
                    <span class="field-value"><?= htmlspecialchars($evaluation->user->profile->name ?? $evaluation->user->profile->publicName ?? 'Nome não disponível') ?></span>
                </div>
                <?php endif; ?>
                
                <div class="field">
                    <span class="field-label"><?= i::__('Status:') ?></span>
                    <span class="field-value">
                        <?php 
                        $statusNames = [
                            0 => 'Rascunho',
                            1 => 'Enviada',
                            2 => 'Enviada'
                        ];
                        echo $statusNames[$evaluation->status] ?? 'Indefinido';
                        ?>
                    </span>
                </div>
                
                <?php if ($evaluation->result): ?>
                <div class="field">
                    <span class="field-label"><?= i::__('Resultado:') ?></span>
                    <span class="field-value"><?= htmlspecialchars($evaluation->result) ?></span>
                </div>
                <?php endif; ?>
                
                <?php if ($evaluation->evaluationData): ?>
                    <?php 
                    $evalData = is_string($evaluation->evaluationData) ? 
                        json_decode($evaluation->evaluationData, true) : 
                        (array) $evaluation->evaluationData;
                    ?>
                    
                    <?php if (isset($evalData['obs']) && !empty($evalData['obs'])): ?>
                    <div class="field">
                        <span class="field-label"><?= i::__('Observações:') ?></span>
                        <span class="field-value" style="white-space: pre-line;"><?= htmlspecialchars($evalData['obs']) ?></span>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (is_array($evalData)): ?>
                        <?php foreach ($evalData as $key => $value): ?>
                            <?php if ($key === 'obs') continue; // Já mostrou acima ?>
                            <?php if (!empty($value) && is_scalar($value)): ?>
                            <div class="field">
                                <span class="field-label"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $key))) ?>:</span>
                                <span class="field-value"><?= htmlspecialchars($value) ?></span>
                            </div>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if ($evaluation->createTimestamp): ?>
                <div class="field">
                    <span class="field-label"><?= i::__('Data da Avaliação:') ?></span>
                    <span class="field-value"><?= $evaluation->createTimestamp->format('d/m/Y H:i:s') ?></span>
                </div>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- Informações de envio -->
    <div class="section">
        <div class="section-title"><?= i::__('Informações de Envio') ?></div>
        
        <div class="field">
            <span class="field-label"><?= i::__('Data de Criação:') ?></span>
            <span class="field-value"><?= $registration->createTimestamp->format('d/m/Y H:i:s') ?></span>
        </div>
        
        <?php if ($registration->sentTimestamp): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Data de Envio:') ?></span>
            <span class="field-value"><?= $registration->sentTimestamp->format('d/m/Y H:i:s') ?></span>
        </div>
        <?php else: ?>
        <div class="field">
            <span class="field-label"><?= i::__('Status de envio:') ?></span>
            <span class="field-value"><?= i::__('Inscrição não enviada') ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->updateTimestamp): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Última Atualização:') ?></span>
            <span class="field-value"><?= $registration->updateTimestamp->format('d/m/Y H:i:s') ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Informações da oportunidade -->
    <div class="section">
        <div class="section-title"><?= i::__('Informações da Oportunidade') ?></div>
        
        <?php if ($registration->opportunity->shortDescription): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Descrição:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->opportunity->shortDescription) ?></span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->opportunity->registrationFrom): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Período de inscrição:') ?></span>
            <span class="field-value">
                <?= $registration->opportunity->registrationFrom->format('d/m/Y') ?> 
                <?= i::__('até') ?> 
                <?= $registration->opportunity->registrationTo->format('d/m/Y H:i:s') ?>
            </span>
        </div>
        <?php endif; ?>
        
        <?php if ($registration->opportunity->parent && $registration->opportunity->parent->name): ?>
        <div class="field">
            <span class="field-label"><?= i::__('Oportunidade pai:') ?></span>
            <span class="field-value"><?= htmlspecialchars($registration->opportunity->parent->name) ?></span>
        </div>
        <?php endif; ?>
    </div>
    
    <div class="footer">
        <p><?= i::__('Documento gerado automaticamente em') ?> <?= date('d/m/Y H:i:s') ?></p>
        <p><?= i::__('Sistema MapasCulturais') ?></p>
    </div>
</div>
</body>
</html>