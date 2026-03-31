<?php

namespace CyberX\Rules\Core;

class I03 extends \CyberX\Assets\Rule {
    public const NAME = 'IP appears in spam list';
    public const DESCRIPTION = 'User may have exhibited unwanted activity before at other web services.';
    public const ATTRIBUTES = ['ip'];

    protected function defineCondition(): \Ruler\Operator\LogicalOperator {
        return $this->rb->logicalAnd(
            $this->rb['eip_blocklist']->equalTo(true),
        );
    }
}
