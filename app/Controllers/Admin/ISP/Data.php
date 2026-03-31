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

namespace CyberX\Controllers\Admin\ISP;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function checkIfOperatorHasAccess(int $ispId, int $apiKey): bool {
        return (new \CyberX\Models\Isp())->checkAccess($ispId, $apiKey);
    }

    public function getFullIspInfoById(int $ispId, int $apiKey): array {
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();
        $model = new \CyberX\Models\Isp();
        $result = $model->getFullIspInfoById($ispId, $apiKey);
        $result['lastseen'] = \CyberX\Utils\ElapsedDate::short($result['lastseen']);

        return $result;
    }

    private function getNumberOfIpsByIspId(int $ispId, int $apiKey): int {
        return (new \CyberX\Models\Isp())->getIpCountById($ispId, $apiKey);
    }

    public function getIspDetails(int $ispId, int $apiKey): array {
        $result = [];
        $data = $this->getFullIspInfoById($ispId, $apiKey);

        if (array_key_exists('asn', $data)) {
            $result = [
                'asn'           => $data['asn'],
                'total_fraud'   => $data['total_fraud'],
                'total_visit'   => $data['total_visit'],
                'total_account' => $data['total_account'],
                'total_ip'      => $this->getNumberOfIpsByIspId($ispId, $apiKey),
            ];
        }

        return $result;
    }
}
