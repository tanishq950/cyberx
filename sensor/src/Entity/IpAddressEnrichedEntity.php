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

namespace Sensor\Entity;

class IpAddressEnrichedEntity {
    /**
     * @param string[] $domainsCount
     */
    public function __construct(
        public int $apiKeyId,
        public string $ipAddress,
        public ?string $hash,
        public int $countryId,
        public bool $hosting,
        public bool $vpn,
        public bool $tor,
        public bool $relay,
        public bool $starlink,
        public bool $blocklist,
        public array $domainsCount,
        public ?string $cidr,
        public ?bool $alertList,
        public bool $fraudDetected,
        public ?IspEnrichedEntity $isp,
        public bool $checked,
        public \DateTimeImmutable $lastSeen,
    ) {
    }
}
