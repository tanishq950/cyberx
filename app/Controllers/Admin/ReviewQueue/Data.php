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

namespace CyberX\Controllers\Admin\ReviewQueue;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $model = new \CyberX\Models\Grid\ReviewQueue\Grid($apiKey);

        return $model->getAll();
    }

    public function setNotReviewedCount(bool $cache, int $apiKey): array {
        $operator = \CyberX\Utils\Routes::getCurrentRequestOperator();

        if (!$operator) {
            $key = \CyberX\Entities\ApiKey::getById($apiKey);
            $operator = \CyberX\Entities\Operator::getById($key->creator);
        }

        $takeFromCache = $this->canTakeNumberOfNotReviewedUsersFromCache($operator);

        $total = $operator->reviewQueueCnt;
        if (!$cache || !$takeFromCache) {
            $total = (new \CyberX\Models\ReviewQueue())->getCount($apiKey);

            $model = new \CyberX\Models\Operator();
            $model->updateReviewedQueueCnt($total, $operator->id);
        }

        return ['total' => $total];
    }

    private function canTakeNumberOfNotReviewedUsersFromCache(\CyberX\Entities\Operator $operator): bool {
        $interval = \Base::instance()->get('REVIEWED_QUEUE_CNT_CACHE_TIME');

        return !!\CyberX\Utils\DateRange::inIntervalTillNow($operator->reviewQueueUpdatedAt, $interval);
    }
}
