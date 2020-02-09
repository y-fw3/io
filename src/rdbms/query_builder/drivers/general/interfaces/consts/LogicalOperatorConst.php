<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

interface LogicalOperatorConst
{
    const LOGICAL_OPERATOR_AND  = 'AND';
    const LOP_AND               = self::LOGICAL_OPERATOR_AND;

    const LOGICAL_OPERATOR_OR   = 'OR';
    const LOP_OR                = self::LOGICAL_OPERATOR_OR;

    const LOGICAL_OPERATOR_MAP  = [
        self::LOP_AND   => self::LOP_AND,
        self::LOP_OR    => self::LOP_OR,
    ];

    const LOP_MAP   = self::LOGICAL_OPERATOR_MAP;
}
