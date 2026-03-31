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

namespace CyberX\Controllers\Admin\Resource;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function checkIfOperatorHasAccess(int $resourceId): bool {
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();
        $model = new \CyberX\Models\Resource();

        return $model->checkAccess($resourceId, $apiKey);
    }

    public function getResourceById(int $resourceId): array {
        $model = new \CyberX\Models\Resource();
        $result = $model->getResourceById($resourceId);
        $result['lastseen'] = \CyberX\Utils\ElapsedDate::short($result['lastseen']);

        return $result;
    }
}
