<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

trait LogicalOperatorTrait
{
    public static function validLogicalOperator($logical_operator)
    {
        if (!isset(self::LOP_MAP[$logical_operator])) {
            return false;
        }

        return true;
    }

    public static function assertLogicalOperator($logical_operator)
    {
        if (!isset(self::LOP_MAP[$logical_operator])) {
            throw new \Exception(sprintf('未定義の論理演算子を指定されました。logical_operator:%s', $logical_operator));
        }

        return true;
    }
}
