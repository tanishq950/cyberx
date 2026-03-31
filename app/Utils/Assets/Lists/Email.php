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

class Email extends Base {
    protected static string $extensionFile = 'email.php';

    protected static array $list = [
        'spam',
        'test',
        'gummie',
        'dummy',
        '123',
        '321',
        '000',
        '111',
        '222',
        '333',
        '444',
        '555',
        '666',
        '777',
        '888',
        '999',
    ];
}
