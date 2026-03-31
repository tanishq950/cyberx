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

class DeletionQueueHandler extends BaseQueue {
    public function process(): void {
        parent::baseProcess(\CyberX\Utils\Constants::get()->DELETE_USER_QUEUE_ACTION_TYPE);
    }

    protected function processItem(array $item): void {
        $user = new \CyberX\Models\User();
        $user->deleteAllUserData($item['event_account'], $item['key']);
    }
}
