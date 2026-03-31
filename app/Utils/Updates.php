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

namespace CyberX\Utils;

class Updates {
    private const UPDATES_LIST = [
        \CyberX\Updates\Update001::class,
        \CyberX\Updates\Update002::class,
        \CyberX\Updates\Update003::class,
        \CyberX\Updates\Update004::class,
        \CyberX\Updates\Update005::class,
        \CyberX\Updates\Update006::class,
        \CyberX\Updates\Update007::class,
        \CyberX\Updates\Update008::class,
    ];

    public static function syncUpdates(): void {
        $f3 = \Base::instance();
        $updates = new \CyberX\Models\Updates($f3);
        $applied = $updates->checkDb('core', self::UPDATES_LIST);

        if ($applied) {
            $controller = new \CyberX\Controllers\Admin\Rules\Data();
            // update only core rules
            $controller->updateRules(false);
        }

        \CyberX\Utils\Routes::callExtra('UPDATES');
    }
}
