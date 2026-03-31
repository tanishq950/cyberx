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

namespace CyberX\Controllers\Admin\Rules;

class Navigation extends \CyberX\Controllers\Admin\Base\Navigation {
    public function __construct() {
        parent::__construct();

        $this->controller = new Data();
        $this->page = new Page();
    }

    public function saveRule(): array {
        $ruleUid = \CyberX\Utils\Conversion::getStringRequestParam('rule');
        $score = \CyberX\Utils\Conversion::getIntRequestParam('value');

        $this->controller->saveUserRule($ruleUid, $score, $this->apiKey);

        return ['success' => true];
    }

    public function checkRule(): array {
        set_time_limit(0);
        ini_set('max_execution_time', '0');

        $ruleUid = \CyberX\Utils\Conversion::getStringRequestParam('ruleUid');

        [$allUsersCnt, $users] = $this->controller->checkRule($ruleUid, $this->apiKey);
        $proportion = $this->controller->getRuleProportion($allUsersCnt, count($users));
        $this->controller->saveRuleProportion($ruleUid, $proportion, $this->apiKey);

        return [
            'users'                 => array_slice($users, 0, \CyberX\Utils\Constants::get()->RULE_CHECK_USERS_PASSED_TO_CLIENT),
            'count'                 => count($users),
            'section'               => $allUsersCnt,
            'proportion'            => $proportion,
            'proportion_updated_at' => date('Y-m-d H:i:s'),
        ];
    }
}
