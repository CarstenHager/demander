<?php

declare(strict_types=1);

namespace Pixelant\Demander\Query\Restriction;

use TYPO3\CMS\Core\Context\Context;

class FrontendRestrictionContainer extends \TYPO3\CMS\Core\Database\Query\Restriction\FrontendRestrictionContainer
{
    public function __construct(?Context $context = null)
    {
        $this->defaultRestrictionTypes = (array)($GLOBALS['TYPO3_CONF_VARS']['FE']['defaultRestrictionTypes'] ?? []);

        parent::__construct($context);
    }
}
