<?php

namespace CyberX\Rules\Core;

class C15 extends \CyberX\Assets\Rule {
    public const NAME = 'UAE IP address';
    public const DESCRIPTION = 'IP address located in United Arab Emirates.';
    public const ATTRIBUTES = ['ip'];

    protected function prepareParams(array $params): array {
        $params['eip_has_specific_country'] = in_array(\CyberX\Utils\Constants::get()->COUNTRY_CODE_UAE, $params['eip_country_id']);

        return $params;
    }

    protected function defineCondition(): \Ruler\Operator\LogicalOperator {
        return $this->rb->logicalAnd(
            $this->rb['eip_has_specific_country']->equalTo(true),
        );
    }
}
