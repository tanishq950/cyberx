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

namespace CyberX\Utils\Assets\Lists;

class FileExtensions extends Base {
    protected static string $extensionFile = 'file-extensions.php';
    protected static array $list = [];

    public static function getList(): array {
        return static::getExtension() ?? [];
    }

    public static function getKeys(): array {
        return array_keys(static::getList());
    }

    public static function getValues(string $key): array {
        return static::getList()[$key] ?? [];
    }
}
