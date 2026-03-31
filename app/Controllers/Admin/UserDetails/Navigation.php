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

namespace CyberX\Controllers\Admin\UserDetails;

class Navigation extends \CyberX\Controllers\Admin\Base\Navigation {
    public function __construct() {
        parent::__construct();

        $this->controller = new Data();
        $this->page = null;
    }

    public function getUserDetails(): array {
        $userId = \CyberX\Utils\Conversion::getIntRequestParam('userId');
        $hasAccess = $this->controller->checkIfOperatorHasAccess($userId, $this->apiKey);

        if (!$hasAccess) {
            $this->f3->error(404);
        }

        return $this->controller->getUserDetails($userId, $this->apiKey);
    }
}
