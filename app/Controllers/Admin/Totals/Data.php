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

namespace CyberX\Controllers\Admin\Totals;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getTimeFrameTotal(array $ids, string $type, string $startDate, string $endDate, int $apiKey): array {
        $processErrorMessage = ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::TOTALS_INVALID_TYPE];

        if (!in_array($type, ['ip', 'isp', 'domain', 'country', 'resource', 'field', 'userAgent'])) {
            return $processErrorMessage;
        }

        $model = null;

        switch ($type) {
            case 'ip':
                $model = new \CyberX\Models\Ip();
                break;
            case 'isp':
                $model = new \CyberX\Models\Isp();
                break;
            case 'domain':
                $model = new \CyberX\Models\Domain();
                break;
            case 'country':
                $model = new \CyberX\Models\Country();
                break;
            case 'resource':
                $model = new \CyberX\Models\Resource();
                break;
            case 'field':
                $model = new \CyberX\Models\FieldAudit();
                break;
            case 'userAgent':
                $model = new \CyberX\Models\UserAgent();
                break;
        }

        $totals = $model->getTimeFrameTotal($ids, $startDate, $endDate, $apiKey);

        return [
            'SUCCESS_MESSAGE'   => $this->f3->get('AdminTotals_success_message'),
            'totals'            => $totals,
        ];
    }
}
