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

namespace CyberX\Models\Search;

class Ip extends \CyberX\Models\BaseSql {
    protected ?string $DB_TABLE_NAME = 'event_ip';

    public function searchByIp(string $query, int $apiKey): array {
        $params = [
            ':api_key' => $apiKey,
            ':query' => "%{$query}%",
        ];

        $query = (
            "SELECT
                event_ip.id AS id,
                'IP'        AS \"groupName\",
                'ip'        AS \"entityId\",
                event_ip.ip AS value

            FROM
                event_ip

            WHERE
                LOWER(TEXT(event_ip.ip)) LIKE LOWER(:query) AND
                event_ip.key = :api_key

            LIMIT 25 OFFSET 0"
        );

        return $this->execQuery($query, $params);
    }
}
