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

namespace CyberX\Controllers\Admin\Country;

class Page extends \CyberX\Controllers\Admin\Base\Page {
    public ?string $page = 'AdminCountry';

    public function getPageParams(): array {
        $dataController = new Data();
        $countryId = \CyberX\Utils\Conversion::getIntUrlParam('countryId');

        $hasAccess = $dataController->checkIfOperatorHasAccess($countryId);

        if (!$hasAccess) {
            $this->f3->error(404);
        }

        $country = $dataController->getCountryById($countryId);
        $pageTitle = $this->getInternalPageTitleWithPostfix($country['value']);

        $pageParams = [
            'LOAD_DATATABLE'                => true,
            'LOAD_UPLOT'                    => true,
            'LOAD_AUTOCOMPLETE'             => true,
            'LOAD_ACCEPT_LANGUAGE_PARSER'   => true,
            'HTML_FILE'                     => 'admin/country.html',
            'COUNTRY'                       => $country,
            'PAGE_TITLE'                    => $pageTitle,
            'JS'                            => 'admin_country.js',
        ];

        return parent::applyPageParams($pageParams);
    }
}
