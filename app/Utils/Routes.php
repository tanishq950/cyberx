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

class Routes {
    private static function getF3(): \Base {
        return \Base::instance();
    }

    public static function getCurrentRequestOperator(): ?\CyberX\Entities\Operator {
        return self::getF3()->get('CURRENT_USER');
    }

    public static function setCurrentRequestOperator(): void {
        self::getF3()->set('CURRENT_USER', self::getCurrentSessionOperator());
    }

    public static function getCurrentSessionOperator(): ?\CyberX\Entities\Operator {
        $loggedInOperatorId = \CyberX\Utils\Conversion::intValCheckEmpty(self::getF3()->get('SESSION.active_user_id'));

        return $loggedInOperatorId ? \CyberX\Entities\Operator::getById($loggedInOperatorId) : null;
    }

    public static function getCurrentRequestApiKey(): ?\CyberX\Entities\ApiKey {
        return self::getF3()->get('CURRENT_KEY');
    }

    public static function setCurrentRequestApiKey(): void {
        self::getF3()->set('CURRENT_KEY', self::getCurrentSessionApiKey());
    }

    public static function getCurrentSessionApiKey(): ?\CyberX\Entities\ApiKey {
        $keyId = self::getF3()->get('TEST_API_KEY_ID');

        if (!$keyId) {
            $keyId = \CyberX\Utils\Conversion::intValCheckEmpty(self::getF3()->get('SESSION.active_key_id'));
        }

        return $keyId ? \CyberX\Entities\ApiKey::getById($keyId) : null;
    }

    public static function redirectIfUnlogged(string $targetPage = '/'): void {
        if (!boolval(self::getCurrentRequestOperator())) {
            self::getF3()->reroute($targetPage);
        }
    }

    public static function redirectIfLogged(): void {
        if (boolval(self::getCurrentRequestOperator())) {
            self::getF3()->reroute('/');
        }
    }

    public static function callExtra(string $method, mixed ...$extra): string|array|null {
        $method = \Base::instance()->get('EXTRA_' . $method);

        return $method && is_callable($method) ? $method(...$extra) : null;
    }
}
