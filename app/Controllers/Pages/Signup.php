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

class Signup extends Base {
    public ?string $page = 'Signup';

    public function getPageParams(): array {
        $model = new \CyberX\Models\Operator();
        if (count($model->getAll())) {
            $this->f3->error(404);
        }

        $pageParams = [
            'HTML_FILE'     => 'signup.html',
            'TIMEZONES'     => \CyberX\Utils\Timezones::timezonesList(),
            'RULES_PRESETS' => \CyberX\Utils\Constants::get()->RULES_PRESETS,
        ];

        if ($this->isPostRequest()) {
            \CyberX\Utils\Updates::syncUpdates();

            $params = $this->extractRequestParams(['token', 'email', 'password', 'timezone', 'rules-preset']);
            $errorCode = \CyberX\Utils\Validators::validateSignup($params);

            $pageParams['ERROR_CODE'] = $errorCode;

            if ($errorCode) {
                $pageParams['VALUES'] = $params;
            } else {
                $operatorId = $this->addUser($params);

                $apiKey = $this->addDefaultApiKey($operatorId);
                (new \CyberX\Controllers\Admin\Rules\Data())->applyRulesPresetById($params['rules-preset'], $apiKey);

                //$this->sendActivationEmail($operatorId);
                $pageParams['SUCCESS_CODE'] = \CyberX\Utils\ErrorCodes::ACCOUNT_CREATED;
            }
        }

        return parent::applyPageParams($pageParams);
    }

    private function addDefaultApiKey(int $operatorId): int {
        $skipEnrichingAttr = json_encode(array_keys(\CyberX\Utils\Constants::get()->ENRICHING_ATTRIBUTES));
        $model = new \CyberX\Models\ApiKeys();

        return $model->insertRecord($skipEnrichingAttr, true, $operatorId);
    }

    protected function addUser(array $data): int {
        $model = new \CyberX\Models\Operator();

        return $model->insertRecord($data['password'], $data['email'], $data['timezone']);
    }

    /*private function sendActivationEmail(int $operatorId): void {
        $operator = \CyberX\Entities\Operator::getById($operatorId);
        $url = \CyberX\Utils\Variables::getHostWithProtocolAndBase();

        $toName = $operator->firstname;
        $toAddress = $operator->email;
        $activationKey = $operator->activationKey;

        $subject = $this->f3->get('Signup_activation_email_subject');
        $message = $this->f3->get('Signup_activation_email_body');

        $activationUrl = sprintf('%s/account-activation/%s', $url, $activationKey);
        $message = sprintf($message, $activationUrl);

        \CyberX\Utils\Mailer::send($toName, $toAddress, $subject, $message);
    }*/
}
