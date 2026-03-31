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

namespace CyberX\Controllers\Admin\UserDetails;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getUserDetails(int $userId, int $apiKey): array {
        (new \CyberX\Models\User())->updateTotalsByAccountIds([$userId], $apiKey);

        $model          = new \CyberX\Models\UserDetails\Id();
        $userDetails    = $model->getDetails($userId, $apiKey);

        $model          = new \CyberX\Models\UserDetails\Ip();
        $ipDetails      = $model->getDetails($userId, $apiKey);

        $model          = new \CyberX\Models\UserDetails\Total();
        $totalDetails   = $model->getDetails($userId, $apiKey);

        $model          = new \CyberX\Models\UserDetails\Behaviour();
        $offset         = \CyberX\Utils\Timezones::getCurrentOperatorOffset();

        $dateRange      = \CyberX\Utils\Timezones::getCurDayRange($offset);
        $dayDetails     = $model->getDayDetails($userId, $dateRange, $apiKey);

        $dateRange      = \CyberX\Utils\Timezones::getWeekAgoDayRange($offset);
        $weekDetails    = $model->getDayDetails($userId, $dateRange, $apiKey);

        return [
            'userDetails'   => $userDetails,
            'ipDetails'     => $ipDetails,
            'totalDetails'  => $totalDetails,
            'dayDetails'    => $dayDetails,
            'weekDetails'   => $weekDetails,
        ];
    }

    public function checkIfOperatorHasAccess(int $userId, int $apiKey): bool {
        $model = new \CyberX\Models\UserDetails\Id();

        return $model->checkAccess($userId, $apiKey);
    }
}
