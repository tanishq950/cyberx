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

namespace CyberX\Controllers\Admin\Domain;

class Page extends \CyberX\Controllers\Admin\Base\Page {
    public ?string $page = 'AdminDomain';

    public function getPageParams(): array {
        $dataController = new Data();
        $apiKey = \CyberX\Utils\ApiKeys::getCurrentOperatorApiKeyId();
        $domainId = \CyberX\Utils\Conversion::getIntUrlParam('domainId');
        $hasAccess = $dataController->checkIfOperatorHasAccess($domainId, $apiKey);

        if (!$hasAccess) {
            $this->f3->error(404);
        }

        $domain = $dataController->getDomainDetails($domainId, $apiKey);
        $pageTitle = $this->getInternalPageTitleWithPostfix($domain['domain']);
        $isEnrichable = $dataController->isEnrichable($apiKey);

        $pageParams = [
            'LOAD_DATATABLE'                => true,
            'LOAD_JVECTORMAP'               => true,
            'LOAD_AUTOCOMPLETE'             => true,
            'HTML_FILE'                     => 'admin/domain.html',
            'DOMAIN'                        => $domain,
            'PAGE_TITLE'                    => $pageTitle,
            'LOAD_UPLOT'                    => true,
            'LOAD_ACCEPT_LANGUAGE_PARSER'   => true,
            'JS'                            => 'admin_domain.js',
            'IS_ENRICHABLE'                 => $isEnrichable,
        ];

        if ($this->isPostRequest()) {
            $operationResponse = $dataController->proceedPostRequest();

            $pageParams = array_merge($pageParams, $operationResponse);
            // recall domain data
            $pageParams['DOMAIN'] = $dataController->getDomainDetails($domainId, $apiKey);
        }

        return parent::applyPageParams($pageParams);
    }
}
