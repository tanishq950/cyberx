<?php

/**
 * cyberx ~ open-source security framework
 * Copyright (c) Tanishq Mohite (https://www.tirreno.com)
 *
 * Licensed under GNU Affero General Public License version 3 of the or any later version.
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Tanishq Mohite (https://www.tirreno.com)
 * @license       https://opensource.org/licenses/AGPL-3.0 AGPL License
 * @link          https://www.tirreno.com CyberX(tm)
 */

declare(strict_types=1);

namespace CyberX\Controllers\Admin\Domain;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function proceedPostRequest(): array {
        return match (\CyberX\Utils\Conversion::getStringRequestParam('cmd')) {
            'reenrichment' => $this->enrichEntity(),
            default => []
        };
    }

    public function enrichEntity(): array {
        $dataController = new \CyberX\Controllers\Admin\Enrichment\Data();
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();
        $enrichmentKey = \CyberX\Utils\ApiKeys::getCurrentOperatorEnrichmentKeyString();

        $type       = \CyberX\Utils\Conversion::getStringRequestParam('type');
        $search     = \CyberX\Utils\Conversion::getStringRequestParam('search', true);
        $entityId   = \CyberX\Utils\Conversion::getIntRequestParam('entityId', true);

        return $dataController->enrichEntity($type, $search, $entityId, $apiKey, $enrichmentKey);
    }

    public function checkIfOperatorHasAccess(int $domainId, int $apiKey): bool {
        return (new \CyberX\Models\Domain())->checkAccess($domainId, $apiKey);
    }

    public function getDomainDetails(int $domainId, int $apiKey): array {
        $result = (new \CyberX\Models\Domain())->getFullDomainInfoById($domainId, $apiKey);

        $tsColumns = ['lastseen'];
        \CyberX\Utils\Timezones::localizeTimestampsForActiveOperator($tsColumns, $result);

        return $result;
    }

    public function isEnrichable(int $apiKey): bool {
        return (new \CyberX\Models\ApiKeys())->attributeIsEnrichable('domain', $apiKey);
    }
}
