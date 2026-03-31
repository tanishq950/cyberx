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

namespace CyberX\Controllers\Admin\Base;

abstract class Data extends \CyberX\Controllers\Base {
    protected function idMapIterate(array $map, object $model, ?string $default = 'getAll', mixed ...$extra): array {
        $result = [];

        foreach ($map as $param => $method) {
            $id = \CyberX\Utils\Conversion::getIntRequestParam($param, true);
            if ($id !== null) {
                $result = $model->$method($id, ...$extra);
            }

            if ($result) {
                break;
            }
        }

        if (!$result && $default !== null) {
            $result = $model->$default(...$extra);
        }

        return $result;
    }

    protected function extractRequestParams(array $params): array {
        $result = [];

        foreach ($params as $key) {
            $result[$key] = \Base::instance()->get('REQUEST.' . $key);
        }

        return $result;
    }
}
