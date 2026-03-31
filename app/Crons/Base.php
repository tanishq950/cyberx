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

namespace CyberX\Crons;

abstract class Base {
    protected array $log = [];

    abstract public function process(): void;

    public function getLog(): array {
        return $this->log;
    }

    protected function addLog(string $msg): void {
        $this->log[] = \CyberX\Utils\Logger::logCronLine($msg, $this->getName());
    }

    protected function getName(): string {
        $cronName = get_class($this);

        return substr($cronName, strrpos($cronName, '\\') + 1);
    }
}
