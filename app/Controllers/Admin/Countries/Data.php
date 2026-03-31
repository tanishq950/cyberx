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

namespace CyberX\Controllers\Admin\Countries;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $result = [];

        $model = new \CyberX\Models\Grid\Countries\Grid($apiKey);

        $result = $model->getAll();

        $ids = array_column($result['data'], 'id');
        if ($ids) {
            $model = new \CyberX\Models\Country();
            $model->updateTotalsByEntityIds($ids, $apiKey);
            $result['data'] = $model->refreshTotals($result['data'], $apiKey);
        }

        return $result;
    }

    public function getMap(int $apiKey): array {
        $result = [];

        $model = new \CyberX\Models\Map();

        $map = [
            'userId'        => 'getCountriesByUserId',
            'ispId'         => 'getCountriesByIspId',
            'userAgentId'   => 'getCountriesByUserAgentId',
            'domainId'      => 'getCountriesByDomainId',
            'resourceId'    => 'getCountriesByResourceId',
        ];

        $result = $this->idMapIterate($map, $model, 'getAllCountriesByDateRange', $apiKey);

        return $result;
    }
}
