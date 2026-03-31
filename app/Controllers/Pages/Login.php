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

namespace CyberX\Controllers\Pages;

class Login extends Base {
    public ?string $page = 'Login';

    public function getPageParams(): array {
        if (!\CyberX\Utils\Variables::completedConfig()) {
            $this->f3->error(422);
        }

        $pageParams = [
            'HTML_FILE'             => 'login.html',
            'JS'                    => 'user_main.js',
            'ALLOW_FORGOT_PASSWORD' => \CyberX\Utils\Variables::getForgotPasswordAllowed(),
        ];

        if (!$this->isPostRequest()) {
            return parent::applyPageParams($pageParams);
        }

        $params = $this->extractRequestParams(['token', 'email', 'password']);
        $errorCode = \CyberX\Utils\Validators::validateLogin($params);

        $pageParams['VALUES'] = $params;
        $pageParams['ERROR_CODE'] = $errorCode;

        if ($errorCode) {
            return parent::applyPageParams($pageParams);
        }

        \CyberX\Utils\Updates::syncUpdates();

        $email      = \CyberX\Utils\Conversion::getStringRequestParam('email');
        $password   = \CyberX\Utils\Conversion::getStringRequestParam('password');

        $model = new \CyberX\Models\Operator();
        $operatorId = $model->getActivatedByEmail($email);

        if ($operatorId && $model->verifyPassword($password, $operatorId)) {
            $this->f3->set('SESSION.active_user_id', $operatorId);

            $this->f3->set('SESSION.active_key_id', \CyberX\Utils\ApiKeys::getFirstKeyByOperatorId($operatorId));

            // blacklist first because it uses review_queue_updated_at for cache check
            $controller = new \CyberX\Controllers\Admin\Blacklist\Navigation();
            $controller->setBlacklistUsersCount(true);      // use cache

            $controller = new \CyberX\Controllers\Admin\ReviewQueue\Navigation();
            $controller->setNotReviewedCount(true);         // use cache

            $pageParams['VALUES'] = \CyberX\Utils\Routes::callExtra('LOGIN', $params) ?? $params;
            $this->f3->reroute('/');
        } else {
            $pageParams['VALUES'] = \CyberX\Utils\Routes::callExtra('LOGIN_FAIL', $params) ?? $params;
            $pageParams['ERROR_CODE'] = \CyberX\Utils\ErrorCodes::EMAIL_OR_PASSWORD_IS_NOT_CORRECT;
        }

        return parent::applyPageParams($pageParams);
    }
}
