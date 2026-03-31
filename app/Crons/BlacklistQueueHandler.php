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

class BlacklistQueueHandler extends BaseQueue {
    public function process(): void {
        parent::baseProcess(\CyberX\Utils\Constants::get()->BLACKLIST_QUEUE_ACTION_TYPE);
    }

    protected function processItem(array $item): void {
        $f3 = \Base::instance();
        $fraud = true;

        $dataController = new \CyberX\Controllers\Admin\User\Data();
        $items = $dataController->setFraudFlag(
            $item['event_account'],
            $fraud,
            $item['key'],
        );

        $model = new \CyberX\Models\User();
        $username = $model->getUserById($item['event_account'], $item['key'])['userid'] ?? '';

        $msg = \CyberX\Utils\SystemMessages::syslogLine(10, 5, 'BlacklistQueue', 'blacklisted userid=' . $username);
        $f3->write($f3->get('LOGS') . 'blacklist.log', $msg . PHP_EOL, true);

        $key = \CyberX\Entities\ApiKey::getById($item['key']);

        if (!$key->skipBlacklistSync && $key->token) {
            $user = new \CyberX\Models\User();
            $userEmail = $user->getUserById($item['event_account'], $item['key'])['email'] ?? null;

            if ($userEmail !== null) {
                $hashes = \CyberX\Utils\Cron::getHashes($items, $userEmail);
                $errorMessage = \CyberX\Utils\Cron::sendBlacklistReportPostRequest($hashes, $key->token);
                if (strlen($errorMessage) > 0) {
                    // TODO: log error into database?
                    $this->addLog('Enrichment API cURL ' . $errorMessage);
                    $this->addLog('Enrichment API cURL logged to database.');
                }
            }
        }
    }
}
