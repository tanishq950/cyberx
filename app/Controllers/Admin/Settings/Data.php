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

namespace CyberX\Controllers\Admin\Settings;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function proceedPostRequest(): array {
        return match (\CyberX\Utils\Conversion::getStringRequestParam('cmd')) {
            'changeEmail'                   => $this->changeEmail(),
            'changeTimezone'                => $this->changeTimezone(),
            'changePassword'                => $this->changePassword(),
            'closeAccount'                  => $this->closeAccount(),
            'updateNotificationPreferences' => $this->updateNotificationPreferences(),
            'changeRetentionPolicy'         => $this->changeRetentionPolicy(),
            'inviteCoOwner'                 => $this->inviteCoOwner(),
            'removeCoOwner'                 => $this->removeCoOwner(),
            'checkUpdates'                  => $this->checkUpdates(),
            default => []
        };
    }

    public function getSharedApiKeyOperators(int $operatorId): array {
        $model = new \CyberX\Models\ApiKeyCoOwner();

        return $model->getSharedApiKeyOperators($operatorId);
    }

    protected function changePassword(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'current-password', 'new-password', 'password-confirmation']);
        $errorCode = \CyberX\Utils\Validators::validateChangePassword($params);

        if ($errorCode) {
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $password = \CyberX\Utils\Conversion::getStringRequestParam('new-password');
            $operatorId = \CyberX\Utils\Routes::getCurrentRequestOperator()->id;

            $model = new \CyberX\Models\Operator();
            $model->updatePassword($password, $operatorId);

            // update operator obj
            \CyberX\Utils\Routes::setCurrentRequestOperator();

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminSettings_changePassword_success_message');
        }

        return $pageParams;
    }

    protected function changeEmail(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'email']);
        $errorCode = \CyberX\Utils\Validators::validateChangeEmail($params);

        if ($errorCode) {
            $pageParams['EMAIL_VALUES'] = $params;
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $operatorId = \CyberX\Utils\Routes::getCurrentRequestOperator()->id;
            $email = \CyberX\Utils\Conversion::getStringRequestParam('email');

            $model = new \CyberX\Models\Operator();
            $model->updateEmail($email, $operatorId);

            // update operator obj
            \CyberX\Utils\Routes::setCurrentRequestOperator();

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminSettings_changeEmail_success_message');
        }

        return $pageParams;
    }

    protected function changeTimezone(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'timezone']);
        $errorCode = \CyberX\Utils\Validators::validateChangeTimezone($params);

        if ($errorCode) {
            $pageParams['TIME_ZONE_VALUES'] = $params;
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $timezone = \CyberX\Utils\Conversion::getStringRequestParam('timezone');
            $operatorId = \CyberX\Utils\Routes::getCurrentRequestOperator()->id;

            $model = new \CyberX\Models\Operator();
            $model->updateTimezone($timezone, $operatorId);

            // update operator in f3 hive for clock
            \CyberX\Utils\Routes::setCurrentRequestOperator();

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminTimezone_changeTimezone_success_message');
        }

        return $pageParams;
    }

    protected function closeAccount(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token']);
        $errorCode = \CyberX\Utils\Validators::validateCloseAccount($params);

        if ($errorCode) {
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $operatorId = \CyberX\Utils\Routes::getCurrentRequestOperator()->id;
            $model = new \CyberX\Models\Operator();
            $model->closeAccount($operatorId);
            $model->removeData($operatorId);

            $this->f3->clear('SESSION');
            if (session_status() === PHP_SESSION_ACTIVE) {
                session_destroy();
            } else {
                session_commit();
            }

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminSettings_closeAccount_success_message');
        }

        return $pageParams;
    }

    protected function checkUpdates(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token']);
        $errorCode = \CyberX\Utils\Validators::validateCheckUpdates($params);

        if ($errorCode) {
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $currentVersion = \CyberX\Utils\VersionControl::versionString();

            $response = \CyberX\Utils\Network::sendApiRequest(null, '/version', 'GET', null);
            $code = $response->code();
            $result = $response->body();

            $statusCode = $code ?? 0;
            $errorMessage = $response->error() ?? '';

            if (strlen($errorMessage) > 0 || $statusCode !== 200 || !is_array($result)) {
                $pageParams['ERROR_CODE'] = \CyberX\Utils\ErrorCodes::ENRICHMENT_API_IS_NOT_AVAILABLE;
            } else {
                if (version_compare($currentVersion, $result['version'], '<')) {
                    $pageParams['SUCCESS_MESSAGE'] = sprintf('An update is available. Released date: %s.', $result['release_date']);
                } else {
                    $pageParams['SUCCESS_MESSAGE'] = 'Current version is up to date.';
                }
            }
        }

        return $pageParams;
    }

    protected function updateNotificationPreferences(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'review-reminder-frequency']);
        $errorCode = \CyberX\Utils\Validators::validateUpdateNotificationPreferences($params);

        if ($errorCode) {
            $pageParams['PROFILE_VALUES'] = $params;
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $reminder = \CyberX\Utils\Conversion::getStringRequestParam('review-reminder-frequency');
            $operatorId = \CyberX\Utils\Routes::getCurrentRequestOperator()->id;

            $model = new \CyberX\Models\Operator();
            $model->updateNotificationPreferences($reminder, $operatorId);

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminSettings_notificationPreferences_success_message');
        }

        return $pageParams;
    }

    protected function changeRetentionPolicy(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'keyId', 'retention-policy']);
        $errorCode = \CyberX\Utils\Validators::validateRetentionPolicy($params);

        if ($errorCode) {
            $pageParams['RETENTION_POLICY_VALUES'] = $params;
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $keyId = \CyberX\Utils\Conversion::getIntRequestParam('keyId');
            $retentionPolicy = \CyberX\Utils\Conversion::getIntRequestParam('retention-policy');

            $model = new \CyberX\Models\ApiKeys();
            $model->updateRetentionPolicy($retentionPolicy, $keyId);
            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminRetentionPolicy_changeTimezone_success_message');
        }

        return $pageParams;
    }

    protected function inviteCoOwner(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'email']);
        $errorCode = \CyberX\Utils\Validators::validateInvitingCoOwner($params);

        if ($errorCode) {
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $currentOperator = \CyberX\Utils\Routes::getCurrentRequestOperator();
            $currentOperatorId = $currentOperator->id;

            $apiKey = \CyberX\Utils\Routes::getCurrentRequestApiKey();

            $params['timezone'] = 'UTC';
            $model = new \CyberX\Models\Operator();
            $invitedOperatorId = $model->insertRecord(null, $params['email'], 'UTC');

            $passwordReset = new \CyberX\Models\ForgotPassword();
            $renewKey = $passwordReset->insertRecord($invitedOperatorId);

            $this->makeOperatorCoOwner($invitedOperatorId, $apiKey->id);
            $this->sendInvitationEmail($params['email'], $currentOperatorId, $renewKey);

            $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminApi_add_co_owner_success_message');
        }

        return $pageParams;
    }

    protected function removeCoOwner(): array {
        $pageParams = [];
        $params = $this->extractRequestParams(['token', 'operatorId']);
        $errorCode = \CyberX\Utils\Validators::validateRemovingCoOwner($params);

        if ($errorCode) {
            $pageParams['ERROR_CODE'] = $errorCode;
        } else {
            $operatorId = \CyberX\Utils\Conversion::getIntRequestParam('operatorId');

            $coOwnerModel = new \CyberX\Models\ApiKeyCoOwner();
            $keyId = $coOwnerModel->getCoOwnershipKeyId($operatorId);

            $apiKey = \CyberX\Utils\Routes::getCurrentSessionApiKey();

            if ($apiKey->id === $keyId && \CyberX\Utils\Routes::getCurrentRequestOperator()->id === $apiKey->creator) {
                $coOwnerModel->deleteCoOwnership($operatorId);

                $operatorModel = new \CyberX\Models\Operator();
                $operatorModel->deleteAccount($operatorId);

                $pageParams['SUCCESS_MESSAGE'] = $this->f3->get('AdminApi_remove_co_owner_success_message');
            } else {
                $pageParams['ERROR_MESSAGE'] = $this->f3->get('AdminApi_remove_co_owner_error_message');
            }
        }

        return $pageParams;
    }

    protected function makeOperatorCoOwner(int $operatorId, int $apiKey): void {
        $model = new \CyberX\Models\ApiKeyCoOwner();
        $model->insertRecord($operatorId, $apiKey);
    }

    protected function sendInvitationEmail(string $email, int $inviterId, string $renewKey): void {
        $toAddress = $email;

        $inviter = \CyberX\Entities\Operator::getById($inviterId);

        $site = \CyberX\Utils\Variables::getHostWithProtocolAndBase();

        $inviterDisplayName = $inviter->email;
        if ($inviter->firstname && $inviter->lastname) {
            $inviterDisplayName = sprintf('%s %s (%s)', $inviter->firstname, $inviter->lastname, $inviterDisplayName);
        }

        $toName = null;
        //$toAddress = $operator->email;

        $subject = $this->f3->get('AdminApi_invitation_email_subject');
        $message = $this->f3->get('AdminApi_invitation_email_body');

        $renewUrl = sprintf('%s/password-recovering/%s', $site, $renewKey);
        $message = sprintf($message, $inviterDisplayName, $renewUrl);

        \CyberX\Utils\Mailer::send($toName, $toAddress, $subject, $message);
    }
}
