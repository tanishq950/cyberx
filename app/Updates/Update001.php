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

namespace CyberX\Updates;

class Update001 extends Base {
    public static string $version = 'v0.9.5';

    public static function apply(\DB\SQL $database): void {
        $queries = [
            'ALTER TABLE dshb_api ADD COLUMN blacklist_threshold INTEGER DEFAULT -1',
            'ALTER TABLE dshb_api ADD COLUMN review_queue_threshold INTEGER DEFAULT 33',
        ];
        foreach ($queries as $sql) {
            $database->exec($sql);
        }
    }
}
