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

namespace CyberX\Controllers\Admin\Enrichment;

class Data extends \CyberX\Controllers\Admin\Base\Data {
    public function enrichEntity(string $type, ?string $search, ?int $entityId, int $apiKey, ?string $enrichmentKey): array {
        if ($enrichmentKey === null) {
            return ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::ENRICHMENT_API_KEY_NOT_EXISTS];
        }
        set_error_handler([\CyberX\Utils\ErrorHandler::class, 'exceptionErrorHandler']);
        $search = $search !== null ? ['value' => $search] : null;
        $result = $this->enrichEntityProcess($type, $search, $entityId, $apiKey, $enrichmentKey);
        restore_error_handler();

        return $result;
    }

    private function enrichEntityProcess(string $type, ?array $search, ?int $entityId, int $apiKey, ?string $enrichmentKey): array {
        $processErrorMessage = ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::ENRICHMENT_API_UNKNOWN_ERROR];

        if ($type === 'device') {
            if ($entityId !== null) {
                $model = new \CyberX\Models\Device();
                $device = $model->getFullDeviceInfoById($entityId, $apiKey);
                if ($device !== []) {
                    $entityId = $device['ua_id'];
                    $type = 'ua';
                } else {
                    return $processErrorMessage;
                }
            } else {
                return $processErrorMessage;
            }
        }

        $model = new \CyberX\Models\ApiKeys();
        $attributes = $model->enrichableAttributes($apiKey);

        if (!array_key_exists($type, $attributes)) {
            return ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::ENRICHMENT_API_ATTR_UNAVAILABLE];
        }

        $modelDb = null;
        $modelResult = null;
        $extraModel = null;
        switch ($type) {
            case 'ip':
                $modelDb        = new \CyberX\Models\Ip();
                $modelResult    = new \CyberX\Models\Enrichment\Ip();
                $extraModel     = new \CyberX\Models\Enrichment\LocalhostIp();
                break;
            case 'email':
                $modelDb        = new \CyberX\Models\Email();
                $modelResult    = new \CyberX\Models\Enrichment\Email();
                break;
            case 'domain':
                $modelDb        = new \CyberX\Models\Domain();
                $modelResult    = new \CyberX\Models\Enrichment\DomainFound();
                $extraModel     = new \CyberX\Models\Enrichment\DomainNotFound();
                break;
            case 'phone':
                $modelDb        = new \CyberX\Models\Phone();
                $modelResult    = new \CyberX\Models\Enrichment\PhoneValid();
                $extraModel     = new \CyberX\Models\Enrichment\PhoneInvalid();
                break;
            case 'ua':
                $modelDb        = new \CyberX\Models\Device();
                $modelResult    = new \CyberX\Models\Enrichment\Device();
                break;
        }

        if ($modelDb === null) {
            return $processErrorMessage;
        }

        $value = $entityId !== null ? $modelDb->extractById($entityId, $apiKey) : $search;

        if ($value === null || $value === []) {
            return $processErrorMessage;
        }

        $apiError = null;

        try {
            [$statusCode, $response,] = $this->enrichEntityByValue($type, $value, $enrichmentKey);
            $error = \CyberX\Utils\ApiResponseFormats::getErrorResponseFormat();
            $apiError = \CyberX\Utils\ApiResponseFormats::matchResponse($response[$type] ?? [], $error) ? $response[$type]['error'] : null;
        } catch (\ErrorException $e) {
            return $processErrorMessage;
        }

        if ($statusCode === 403) {
            return ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::ENRICHMENT_API_KEY_OVERUSE];
        }

        if ($type === 'ip') {
            // do not raise on bogon ip
            if ($apiError === \CyberX\Utils\Constants::get()->ENRICHMENT_IP_IS_NOT_FOUND) {
                return ['ERROR_CODE' => \CyberX\Utils\ErrorCodes::ENRICHMENT_API_IP_NOT_FOUND];
            } elseif ($apiError !== null && $apiError !== \CyberX\Utils\Constants::get()->ENRICHMENT_IP_IS_BOGON || $statusCode !== 200 || $response[$type] === null) {
                return $processErrorMessage;
            }
        } elseif ($apiError !== null || $statusCode !== 200 || $response[$type] === null) {
            return $processErrorMessage;
        }

        try {
            $modelResult->init($response[$type]);
        } catch (\ErrorException $e) {
            if ($extraModel === null) {
                return $processErrorMessage;
            }
            try {
                $extraModel->init($response[$type]);
                $modelResult = $extraModel;
            } catch (\ErrorException $e) {
                return $processErrorMessage;
            }
        }

        // change value in db only if $entityId was passed
        if ($entityId !== null) {
            try {
                $modelResult->updateEntityInDb($entityId, $apiKey);
            } catch (\ErrorException $e) {
                return $processErrorMessage;
            }
        }

        return [
            $type             => $response[$type],
            'SUCCESS_MESSAGE' => $this->f3->get('AdminEnrichment_success_message'),
        ];
    }

    private function validateResponse(string $requestType, int $statusCode, ?array $result, string $errorMessage): bool|string|int {
        if (!is_array($result)) {
            return \CyberX\Utils\ErrorCodes::ENRICHMENT_API_UNKNOWN_ERROR;
        }

        if ($statusCode === 200 && is_array($result[$requestType])) {
            return false;
        }

        $details = $result['detail'] ?? null;
        if ($details) {
            if (is_array($details)) {
                $messages = array_map(function ($detail) {
                    if (isset($detail['msg']) && $detail['msg'] !== '') {
                        return $detail['msg'];
                    }
                }, $details);
                $messages = implode('; ', $messages);
            } else {
                $messages = $details;
            }
        }

        if (strlen($errorMessage) > 0) {
            \CyberX\Utils\Logger::log('Enrichment API web error', $errorMessage);
        }

        if (!isset($messages) || strlen($messages) < 1) {
            return \CyberX\Utils\ErrorCodes::ENRICHMENT_API_UNKNOWN_ERROR;
        }

        return $messages;
    }

    private function enrichEntityByValue(string $type, array $value, string $enrichmentKey): array {
        $postFields = [
            $type => $value['value'],
        ];
        $response = \CyberX\Utils\Network::sendApiRequest($postFields, '/query', 'POST', $enrichmentKey);
        $code = $response->code();
        $result = $response->body();

        $statusCode = $code ?? 0;
        $errorMessage = $response->error() ?? '';
        $errorMessages = $this->validateResponse($type, $statusCode, $result, $errorMessage);

        return [$statusCode, $result, $errorMessages];
    }

    public function getNotCheckedEntitiesCount(int $apiKey): array {
        $model = new \CyberX\Models\ApiKeys();
        $models = $model->enrichableAttributes($apiKey);
        $result = [];

        foreach ($models as $type => $model) {
            $result[$type] = (new $model())->countNotChecked($apiKey);
        }

        return $result;
    }

    public function getNotCheckedExists(int $apiKey): bool {
        $model = new \CyberX\Models\ApiKeys();
        $models = $model->enrichableAttributes($apiKey);

        foreach ($models as $model) {
            if ((new $model())->notCheckedExists($apiKey)) {
                return true;
            }
        }

        return false;
    }

    public function getNotCheckedEntitiesByUserId(int $userId, int $apiKey): array {
        $model = new \CyberX\Models\ApiKeys();
        $models = $model->enrichableAttributes($apiKey);
        $result = [];

        foreach ($models as $type => $model) {
            $result[$type] = (new $model())->notCheckedForUserId($userId, $apiKey);
        }

        return $result;
    }
}
