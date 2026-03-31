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

namespace CyberX\Controllers\Admin\Blacklist;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $model = new \CyberX\Models\Grid\Blacklist\Grid($apiKey);

        return $model->getAll();
    }

    public function removeItemFromBlacklist(int $itemId, string $type, int $apiKey): void {
        $model = null;

        switch ($type) {
            case 'ip':
                $model = new \CyberX\Models\Ip();
                break;
            case 'email':
                $model = new \CyberX\Models\Email();
                break;
            case 'phone':
                $model = new \CyberX\Models\Phone();
                break;
        }

        if ($model) {
            $model->updateFraudFlag([$itemId], false, $apiKey);
        }
    }

    public function setBlacklistUsersCount(bool $cache, int $apiKey): array {
        $operator = \CyberX\Utils\Routes::getCurrentRequestOperator();

        if (!$operator) {
            $key = \CyberX\Entities\ApiKey::getById($apiKey);
            $operator = \CyberX\Entities\Operator::getById($key->creator);
        }

        $takeFromCache = $this->canTakeNumberOfBlacklistUsersFromCache($operator);

        $total = $operator->blacklistUsersCnt;
        if (!$cache || !$takeFromCache) {
            $total = (new \CyberX\Models\Dashboard())->getTotalBlockedUsers(null, $apiKey);

            $model = new \CyberX\Models\Operator();
            $model->updateBlacklistUsersCnt($total, $operator->id);
        }

        return ['total' => $total];
    }

    private function canTakeNumberOfBlacklistUsersFromCache(\CyberX\Entities\Operator $operator): bool {
        $interval = \Base::instance()->get('REVIEWED_QUEUE_CNT_CACHE_TIME');

        return !!\CyberX\Utils\DateRange::inIntervalTillNow($operator->reviewQueueUpdatedAt, $interval);
    }
}
