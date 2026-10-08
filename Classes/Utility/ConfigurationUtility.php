<?php

declare(strict_types=1);

namespace Pixelant\Demander\Utility;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface;

/**
 * Utility for demander typoscript configuration.
 */
class ConfigurationUtility
{
    /**
     * @return array
     */
    public static function getExtensionConfiguration(): array
    {
        $configurationManager = GeneralUtility::makeInstance(ConfigurationManagerInterface::class);
        $fullTypoScript = (array)$configurationManager->getConfiguration(
            ConfigurationManagerInterface::CONFIGURATION_TYPE_FULL_TYPOSCRIPT
        );
        $config = $fullTypoScript['config.']['tx_demander.'] ?? [];

        return DemandArrayUtility::removeDotsFromKeys(is_array($config) ? $config : []);
    }
}
