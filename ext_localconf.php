<?php

(function ()
{
    $GLOBALS['TYPO3_CONF_VARS']['FE']['defaultRestrictionTypes'] = [
        \TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction::class,
        \TYPO3\CMS\Core\Database\Query\Restriction\WorkspaceRestriction::class,
        \TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction::class,
        \TYPO3\CMS\Core\Database\Query\Restriction\StartTimeRestriction::class,
        \TYPO3\CMS\Core\Database\Query\Restriction\EndTimeRestriction::class,
        \TYPO3\CMS\Core\Database\Query\Restriction\FrontendGroupRestriction::class,
    ];

    $GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects'][\TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer::class] = [
        'className' => \Pixelant\Demander\Query\Restriction\FrontendRestrictionContainer::class
    ];
})();
