<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Demander',
    'description' => 'Configurable, demand-based filtering framework with permalink-support for TYPO3.',
    'category' => 'plugin',
    'author' => 'Pixelant',
    'author_email' => 'info@pixelant.net, hallo@teufels.com',
    'author_company' => 'Pixelant, teufels GmbH',
    'state' => 'beta',
    'version' => '0.3.1',
    'constraints' => [
        'depends' => [
            'typo3' => '12.4.0-12.4.99',
            'php' => '8.1.0-8.3.99'
        ],
        'conflicts' => [],
        'suggests' => []
    ]
];
