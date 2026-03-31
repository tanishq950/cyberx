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

namespace CyberX\Utils\Http;

class HttpClient {
    /** @var array<int, \CyberX\Interfaces\HttpTransportInterface> */
    private array $transports;

    /**
     * @param array<int, \CyberX\Interfaces\HttpTransportInterface> $transports
     */
    public function __construct(array $transports) {
        $this->transports = $transports;
    }

    public static function default(): self {
        $transports = [
            new \CyberX\Utils\Http\CurlTransport(),
            new \CyberX\Utils\Http\StreamTransport(),
        ];

        return new self($transports);
    }

    public function request(\CyberX\Entities\HttpRequest $request): \CyberX\Entities\HttpResponse {
        foreach ($this->transports as $transport) {
            if ($transport->isAvailable()) {
                return $transport->request($request);
            }
        }

        return \CyberX\Entities\HttpResponse::failure(null, 'no_transport_available', []);
    }
}
