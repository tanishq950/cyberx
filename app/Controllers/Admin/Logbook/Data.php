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

namespace CyberX\Controllers\Admin\Logbook;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function getList(int $apiKey): array {
        $model = new \CyberX\Models\Grid\Logbook\Grid($apiKey);

        return $model->getAll();
    }

    public function getLogbookDetails(int $id, int $apiKey): array {
        return (new \CyberX\Models\Logbook())->getLogbookDetails($id, $apiKey);
    }
}
