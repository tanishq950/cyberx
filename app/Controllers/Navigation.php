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

namespace CyberX\Controllers;

class Navigation extends Base {
    public \CyberX\Views\Base $response;

    public function beforeroute(): void {
        // CSRF assignment in base page
        $this->response = new \CyberX\Views\Frontend();
    }

    /**
     * kick start the View, which creates the response
     * based on our previously set content data.
     * finally echo the response or overwrite this method
     * and do something else with it.
     */
    public function afterroute(): void {
        echo $this->response->render();
    }

    public function visitSignupPage(): void {
        \CyberX\Utils\Routes::redirectIfLogged();

        $pageController = new \CyberX\Controllers\Pages\Signup();
        $this->response->data = $pageController->getPageParams();
    }

    public function visitLoginPage(): void {
        \CyberX\Utils\Routes::redirectIfLogged();

        $pageController = new \CyberX\Controllers\Pages\Login();
        $this->response->data = $pageController->getPageParams();
    }

    public function visitForgotPasswordPage(): void {
        \CyberX\Utils\Routes::redirectIfLogged();

        if (!\CyberX\Utils\Variables::getForgotPasswordAllowed()) {
            $this->f3->reroute('/');
        }

        $pageController = new \CyberX\Controllers\Pages\ForgotPassword();
        $this->response->data = $pageController->getPageParams();
    }

    public function visitPasswordRecoveringPage(): void {
        \CyberX\Utils\Routes::redirectIfLogged();

        $pageController = new \CyberX\Controllers\Pages\PasswordRecovering();
        $this->response->data = $pageController->getPageParams();
    }

    public function visitLogoutPage(): void {
        \CyberX\Utils\Routes::redirectIfUnlogged();

        $pageController = new \CyberX\Controllers\Pages\Logout();
        $this->response->data = $pageController->getPageParams();
    }
}
