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

namespace CyberX\Crons;

class RiskScoreQueueHandler extends BaseQueue {
    private \CyberX\Controllers\Admin\Rules\Data $rulesController;

    public function __construct() {
        $this->rulesController = new \CyberX\Controllers\Admin\Rules\Data();
        $this->rulesController->buildEvaluationModels();
    }

    public function process(): void {
        $batchSize = \CyberX\Utils\Variables::getAccountOperationQueueBatchSize();
        $queueModel = new \CyberX\Models\Queue();
        $keys = $queueModel->getNextBatchKeys(\CyberX\Utils\Constants::get()->RISK_SCORE_QUEUE_ACTION_TYPE, $batchSize);

        parent::baseProcess(\CyberX\Utils\Constants::get()->RISK_SCORE_QUEUE_ACTION_TYPE);

        $blacklist = new \CyberX\Controllers\Admin\Blacklist\Data();
        $reviewQueue = new \CyberX\Controllers\Admin\ReviewQueue\Data();

        foreach ($keys as $key) {
            $blacklist->setBlacklistUsersCount(false, $key);
            $reviewQueue->setNotReviewedCount(false, $key);
        }
    }

    protected function processItem(array $item): void {
        $this->rulesController->evaluateUser($item['event_account'], $item['key'], true);
    }
}
