<?php

namespace CyberX\Rules\Core;

class C06 extends \CyberX\Assets\Rule {
    public const NAME = 'Indonesia IP address';
    public const DESCRIPTION = 'IP address located in Indonesia. This region is associated with a higher risk.';
    public const ATTRIBUTES = ['ip'];

    protected function prepareParams(array $params): array {
        $params['eip_has_specific_country'] = in_array(\CyberX\Utils\Constants::get()->COUNTRY_CODE_INDONESIA, $params['eip_country_id']);

        return $params;
    }

    protected function defineCondition(): \Ruler\Operator\LogicalOperator {
        return $this->rb->logicalAnd(
            $this->rb['eip_has_specific_country']->equalTo(true),
        );
    }
}
