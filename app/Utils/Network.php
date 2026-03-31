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

class Network {
    public static function sendApiRequest(?array $data, string $path, string $method, ?string $enrichmentKey): \CyberX\Entities\HttpResponse {
        $version = \CyberX\Utils\VersionControl::versionString();
        $userAgent = \Base::instance()->get('APP_USER_AGENT');
        $userAgent = ($version && $userAgent) ? $userAgent . '/' . $version : $userAgent;

        $url = \CyberX\Utils\Variables::getEnrichmentApi() . $path;

        $headers = [
            'User-Agent: ' . $userAgent,
        ];

        if ($enrichmentKey !== null) {
            $headers[] = 'Authorization: Bearer ' . $enrichmentKey;
        }

        $body = null;
        if ($data !== null) {
            $body = json_encode($data);
            if ($body === false) {
                return \CyberX\Entities\HttpResponse::failure(null, 'json_encode_failed', []);
            }
        }

        $headers = \CyberX\Utils\Http\HeaderUtils::ensureHeader($headers, 'Content-Type', 'application/json');

        if ($data !== null) {
            $headers[] = 'Content-Type: application/json';
            $data = json_encode($data);
        }

        $request = new \CyberX\Entities\HttpRequest($url, $method, $headers, $data);
        $client = \CyberX\Utils\Http\HttpClient::default();

        return $client->request($request);
    }
}
