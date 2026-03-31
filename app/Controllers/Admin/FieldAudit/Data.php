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

namespace CyberX\Controllers\Admin\FieldAudit;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function checkIfOperatorHasAccess(int $fieldId): bool {
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();
        $model = new \CyberX\Models\FieldAudit();

        return $model->checkAccess($fieldId, $apiKey);
    }

    public function getFieldById(int $fieldId): array {
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();

        $model = new \CyberX\Models\FieldAudit();
        $result = $model->getFieldById($fieldId, $apiKey);
        $result['lastseen'] = \CyberX\Utils\ElapsedDate::short($result['lastseen']);
        $result['created'] = \CyberX\Utils\ElapsedDate::short($result['created']);

        return $result;
    }
}
