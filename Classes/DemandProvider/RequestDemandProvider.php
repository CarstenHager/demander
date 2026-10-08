<?php

declare(strict_types=1);

namespace Pixelant\Demander\DemandProvider;

use Pixelant\Demander\Service\RequestSingleton;
use Psr\Http\Message\ServerRequestInterface;

class RequestDemandProvider implements DemandProviderInterface
{
    public function getDemand(): array
    {
        $request = RequestSingleton::getInstance()->getRequest() ?? ($GLOBALS['TYPO3_REQUEST'] ?? null);
        if (!$request instanceof ServerRequestInterface) {
            return [];
        }
        $query = $request->getQueryParams()['d'] ?? [];
        $body = $request->getParsedBody();
        $posted = is_array($body) ? ($body['d'] ?? []) : [];
        $demands = is_array($query) ? $query : [];
        if (is_array($posted)) {
            \TYPO3\CMS\Core\Utility\ArrayUtility::mergeRecursiveWithOverrule($demands, $posted);
        }
        return $demands;
    }
}
