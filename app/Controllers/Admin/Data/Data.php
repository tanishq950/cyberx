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

namespace CyberX\Controllers\Admin\Data;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    // POST requests
    public function enrichEntity(): array {
        $controller = new \CyberX\Controllers\Admin\Enrichment\Navigation();

        return $controller->enrichEntity();
    }

    public function saveRule(): array {
        $controller = new \CyberX\Controllers\Admin\Rules\Navigation();

        return $controller->saveRule();
    }

    public function removeFromBlacklist(): array {
        $controller = new \CyberX\Controllers\Admin\Blacklist\Navigation();

        return $controller->removeItemFromList();
    }

    public function removeFromWatchlist(): array {
        $controller = new \CyberX\Controllers\Admin\Watchlist\Navigation();

        return $controller->removeUserFromList();
    }

    public function manageUser(): array {
        $controller = new \CyberX\Controllers\Admin\User\Navigation();

        return $controller->manageUser();
    }

    // GET requests
    public function checkRule(): array {
        $controller = new \CyberX\Controllers\Admin\Rules\Navigation();

        return $controller->checkRule();
    }

    public function getTimeFrameTotal(): array {
        $controller = new \CyberX\Controllers\Admin\Totals\Navigation();

        return $controller->getTimeFrameTotal();
    }

    public function getCountries(): array {
        $controller = new \CyberX\Controllers\Admin\Countries\Navigation();

        return $controller->getList();
    }

    public function getMap(): array {
        $controller = new \CyberX\Controllers\Admin\Countries\Navigation();

        return $controller->getMap();
    }

    public function getIps(): array {
        $controller = new \CyberX\Controllers\Admin\IPs\Navigation();

        return $controller->getList();
    }

    public function getEvents(): array {
        $controller = new \CyberX\Controllers\Admin\Events\Navigation();

        return $controller->getList();
    }

    public function getLogbook(): array {
        $controller = new \CyberX\Controllers\Admin\Logbook\Navigation();

        return $controller->getList();
    }

    public function getUsers(): array {
        $controller = new \CyberX\Controllers\Admin\Users\Navigation();

        return $controller->getList();
    }

    public function getUserAgents(): array {
        $controller = new \CyberX\Controllers\Admin\UserAgents\Navigation();

        return $controller->getList();
    }

    public function getDevices(): array {
        $controller = new \CyberX\Controllers\Admin\Devices\Navigation();

        return $controller->getList();
    }

    public function getResources(): array {
        $controller = new \CyberX\Controllers\Admin\Resources\Navigation();

        return $controller->getList();
    }

    public function getDashboardStat(): array {
        $controller = new \CyberX\Controllers\Admin\Home\Navigation();

        return $controller->getDashboardStat();
    }

    public function getTopTen(): array {
        $controller = new \CyberX\Controllers\Admin\Home\Navigation();

        return $controller->getTopTen();
    }

    public function getChart(): array {
        $controller = new \CyberX\Controllers\Admin\Home\Navigation();

        return $controller->getChart();
    }

    public function getEventDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Events\Navigation();

        return $controller->getEventDetails();
    }

    public function getFieldEventDetails(): array {
        $controller = new \CyberX\Controllers\Admin\FieldAuditTrail\Navigation();

        return $controller->getFieldEventDetails();
    }

    public function getLogbookDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Logbook\Navigation();

        return $controller->getLogbookDetails();
    }

    public function getEmailDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Emails\Navigation();

        return $controller->getEmailDetails();
    }

    public function getPhoneDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Phones\Navigation();

        return $controller->getPhoneDetails();
    }

    public function getUserDetails(): array {
        $controller = new \CyberX\Controllers\Admin\UserDetails\Navigation();

        return $controller->getUserDetails();
    }

    /*public function getUserEnrichmentDetails(): array {
        $controller = new \CyberX\Controllers\Admin\UserDetails\Navigation();

        return $controller->getUserEnrichmentDetails();
    }*/

    public function getNotCheckedEntitiesCount(): array {
        $controller = new \CyberX\Controllers\Admin\Enrichment\Navigation();

        return $controller->getNotCheckedEntitiesCount();
    }

    public function getEmails(): array {
        $controller = new \CyberX\Controllers\Admin\Emails\Navigation();

        return $controller->getList();
    }

    public function getPhones(): array {
        $controller = new \CyberX\Controllers\Admin\Phones\Navigation();

        return $controller->getList();
    }

    public function getFieldAuditTrail(): array {
        $controller = new \CyberX\Controllers\Admin\FieldAuditTrail\Navigation();

        return $controller->getList();
    }

    public function getFieldAudits(): array {
        $controller = new \CyberX\Controllers\Admin\FieldAudits\Navigation();

        return $controller->getList();
    }

    public function getUserScoreDetails(): array {
        $controller = new \CyberX\Controllers\Admin\User\Navigation();

        return $controller->getUserScoreDetails();
    }

    public function getIsps(): array {
        $controller = new \CyberX\Controllers\Admin\ISPs\Navigation();

        return $controller->getList();
    }

    public function getDomains(): array {
        $controller = new \CyberX\Controllers\Admin\Domains\Navigation();

        return $controller->getList();
    }

    public function getReviewUsersQueue(): array {
        $controller = new \CyberX\Controllers\Admin\ReviewQueue\Navigation();

        return $controller->getList();
    }

    public function getReviewUsersQueueCount(): array {
        $controller = new \CyberX\Controllers\Admin\ReviewQueue\Navigation();

        return $controller->setNotReviewedCount(false);     // no cache
    }

    public function getBlacklistUsersCount(): array {
        $controller = new \CyberX\Controllers\Admin\Blacklist\Navigation();

        return $controller->setBlacklistUsersCount(false);  // no cache
    }

    public function getIspDetails(): array {
        $controller = new \CyberX\Controllers\Admin\ISP\Navigation();

        return $controller->getIspDetails();
    }

    public function getIpDetails(): array {
        $controller = new \CyberX\Controllers\Admin\IP\Navigation();

        return $controller->getIpDetails();
    }

    public function getDeviceDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Devices\Navigation();

        return $controller->getDeviceDetails();
    }

    public function getUserAgentDetails(): array {
        $controller = new \CyberX\Controllers\Admin\UserAgent\Navigation();

        return $controller->getUserAgentDetails();
    }

    public function getDomainDetails(): array {
        $controller = new \CyberX\Controllers\Admin\Domain\Navigation();

        return $controller->getDomainDetails();
    }

    public function getSearchResults(): array {
        $controller = new \CyberX\Controllers\Admin\Search\Navigation();

        return $controller->getSearchResults();
    }

    public function getBlacklist(): array {
        $controller = new \CyberX\Controllers\Admin\Blacklist\Navigation();

        return $controller->getList();
    }

    public function getUsageStats(): array {
        $controller = new \CyberX\Controllers\Admin\Api\Navigation();

        return $controller->getUsageStats();
    }

    public function getCurrentTime(): array {
        $controller = new \CyberX\Controllers\Admin\Home\Navigation();

        return $controller->getCurrentTime();
    }

    public function getConstants(): array {
        $controller = new \CyberX\Controllers\Admin\Home\Navigation();

        return $controller->getConstants();
    }
}
