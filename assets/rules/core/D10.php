<?php

namespace CyberX\Rules\Core;

class D10 extends \CyberX\Assets\Rule {
    public const NAME = 'Potentially vulnerable User-Agent';
    public const DESCRIPTION = 'The user made a request with potentially vulnerable User-Agent.';
    public const ATTRIBUTES = [];

    protected function defineCondition(): \Ruler\Operator\LogicalOperator {
        return $this->rb->logicalAnd(
            $this->rb['eup_vulnerable_ua']->equalTo(true),
        );
    }
}
