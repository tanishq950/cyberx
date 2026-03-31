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

namespace CyberX\Views;

class Frontend extends Base {
    public function render(): string|false|null {
        if ($this->data) {
            $this->f3->mset($this->data);
        }

        \CyberX\Utils\Routes::callExtra('FRONTEND_VIEW');

        // Use anti-CSRF token in templates.
        $this->f3->set('CSRF', $this->f3->get('SESSION.csrf'));

        $tpl = $this->f3->get('TPL') ?? null;
        if ($tpl) {
            $tpl::registerExtends();
        }

        return \Template::instance()->render('templates/layout.html');
    }
}
