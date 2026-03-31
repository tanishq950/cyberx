<?php

namespace CyberX\Rules\Core;

class I12 extends \CyberX\Assets\Rule {
    public const NAME = 'IP belongs to LAN';
    public const DESCRIPTION = 'IP address belongs to local access network.';
    public const ATTRIBUTES = [];

    protected function defineCondition(): \Ruler\Operator\LogicalOperator {
        return $this->rb->logicalAnd(
            $this->rb['eip_lan']->equalTo(true),
        );
    }
}
