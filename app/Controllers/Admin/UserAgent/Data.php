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

namespace CyberX\Controllers\Admin\UserAgent;

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

    public function checkIfOperatorHasAccess(int $userAgentId, int $apiKey): bool {
        return (new \CyberX\Models\UserAgent())->checkAccess($userAgentId, $apiKey);
    }

    public function getUserAgentDetails(int $userAgentId, int $apiKey): array {
        return (new \CyberX\Models\UserAgent())->getFullUserAgentInfoById($userAgentId, $apiKey);
    }

    public function isEnrichable(int $apiKey): bool {
        return (new \CyberX\Models\ApiKeys())->attributeIsEnrichable('ua', $apiKey);
    }
}
