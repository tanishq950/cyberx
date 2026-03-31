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

namespace CyberX\Controllers\Admin\Devices;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $result = [];
        $model = new \CyberX\Models\Grid\Devices\Grid($apiKey);

        $map = [
            'ipId'          => 'getDevicesByIpId',
            'userId'        => 'getDevicesByUserId',
            'resourceId'    => 'getDevicesByResourceId',
        ];

        $result = $this->idMapIterate($map, $model);

        return $result;
    }

    public function getDeviceDetails(int $id, int $apiKey): array {
        $details = (new \CyberX\Models\Device())->getFullDeviceInfoById($id, $apiKey);
        $details['enrichable'] = $this->isEnrichable($apiKey);

        $tsColumns = ['created'];
        \CyberX\Utils\Timezones::localizeTimestampsForActiveOperator($tsColumns, $details);

        return $details;
    }

    private function isEnrichable(int $apiKey): bool {
        return (new \CyberX\Models\ApiKeys())->attributeIsEnrichable('ua', $apiKey);
    }
}
