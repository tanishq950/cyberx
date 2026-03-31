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

namespace CyberX\Controllers\Admin\FieldAuditTrail;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $result = [];
        $model = new \CyberX\Models\Grid\FieldAuditTrail\Grid($apiKey);

        $map = [
            'userId'        => 'getDataByUserId',
            'resourceId'    => 'getDataByResourceId',
            'fieldId'       => 'getDataByFieldId',
        ];

        $result = $this->idMapIterate($map, $model);

        $ids = array_column($result['data'], 'field_audit_id');
        if ($ids) {
            $model = new \CyberX\Models\FieldAudit();
            $model->updateTotalsByEntityIds($ids, $apiKey);
            $result['data'] = $model->refreshTotals($result['data'], $apiKey);
        }

        return $result;
    }

    public function getFieldEventDetails(int $id, int $apiKey): array {
        $result = [];
        $model = new \CyberX\Models\FieldAuditTrail();
        $trailResult = $model->getById($id, $apiKey);

        if ($trailResult) {
            $eventId = $trailResult['event_id'];
            $controller = new \CyberX\Controllers\Admin\Events\Data();
            $result = $controller->getEventDetails($eventId, $apiKey);

            if ($result) {
                $result = $controller->extendPayload($result, $apiKey);
            }
        }

        return $result;
    }
}
