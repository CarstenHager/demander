<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Demander',
    'description' => 'Configurable, demand-based filtering framework with permalink-support for TYPO3.',
    'category' => 'plugin',
    'author' => 'Pixelant',
    'author_email' => 'info@pixelant.net, hallo@teufels.com',
    'author_company' => 'Pixelant, teufels GmbH',
    'state' => 'beta',
    'version' => '0.5.0-beta2',
    'constraints' => [
        'depends' => [
            'typo3' => '14.0.0-14.99.99',
            'php' => '8.2.0-8.4.99'
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];
