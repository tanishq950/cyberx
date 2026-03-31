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

class LogbookRotation extends Base {
    public function process(): void {
        $this->addLog('Start logbook rotation.');

        $model = new \CyberX\Models\ApiKeys();
        $keys = $model->getAllApiKeyIds();
        // rotate events for unauthorized requests
        $keys[] = ['id' => null];

        $model = new \CyberX\Models\Logbook();
        $cnt = 0;
        foreach ($keys as $key) {
            $cnt += $model->rotateRequests($key['id']);
        }

        $this->addLog(sprintf('Deleted %s events for %s keys in logbook.', $cnt, count($keys)));
    }
}
