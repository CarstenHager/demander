<?php

declare(strict_types=1);

namespace Pixelant\Demander\DemandProvider;

use BadMethodCallException;

class PageDemandProvider implements DemandProviderInterface
{
    /**
     * @return array
     * @throws Exception\NotImplementedException
     */
    public function getDemand(): array
    {
        throw new BadMethodCallException(__METHOD__, 1614083012);
    }
}
