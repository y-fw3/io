<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

use fw3\io\utilitys\exceptions\UnavailableVarException;

trait ComparisonOperatorTrait
{
    public static function fillPlaceholder(array $expression): array
    {
        return array_fill(0, count($expression), static::PLACEHOLDER);
    }

    public static function validComparisonOperator($comparison_operator)
    {
        if (!isset(static::OP_MAP[$comparison_operator])) {
            return false;
        }

        if (!isset(static::OP_FORMAT_MAP[$comparison_operator])) {
            return false;
        }

        return true;
    }

    public static function assertComparisonOperator($comparison_operator)
    {
        if (!isset(static::OP_MAP[$comparison_operator])) {
            throw new \Exception(sprintf('未定義の比較演算子を指定されました。comparison_operator:%s', $comparison_operator));
        }

        if (!isset(static::OP_FORMAT_MAP[$comparison_operator])) {
            throw new \Exception(sprintf('フォーマットが未実装の比較演算子を指定されました。comparison_operator:%s', $comparison_operator));
        }

        return true;
    }

    public static function adjustComparisonOperator($comparison_operator, $type = self::COMPARISON_VALUE_TYPE_DEFAULT)
    {
        $comparison_operator    = strtoupper($comparison_operator);

        static::assertComparisonOperator($comparison_operator);

        if (isset(static::OP_NOT_EQUAL_ALIAS_MAP[$comparison_operator])) {
            $comparison_operator    = static::OP_NOT_EQUAL_ALIAS_MAP[$comparison_operator];
        }

        switch ($type) {
            case static::COMPARISON_VALUE_TYPE_RANGE:
                if (!isset(static::OP_RANGE_MAP[$comparison_operator])) {
                    UnavailableVarException::raise('未定義の範囲演算子を指定されました。', ['comparison_operator' => $comparison_operator]);
                }
                $comparison_operator    = static::OP_RANGE_MAP[$comparison_operator];
                break;
            case static::COMPARISON_VALUE_TYPE_LIST:
                if (!isset(static::OP_LIST_MAP[$comparison_operator])) {
                    UnavailableVarException::raise('未定義のリスト比較演算子を指定されました。', ['comparison_operator' => $comparison_operator]);
                }
                $comparison_operator    = static::OP_LIST_MAP[$comparison_operator];
                break;
            case static::COMPARISON_VALUE_TYPE_BOOL:
            case static::COMPARISON_VALUE_TYPE_NULL:
                if (!isset(static::OP_IS_MAP[$comparison_operator])) {
                    UnavailableVarException::raise('未定義のテスト演算子を指定されました。', ['comparison_operator' => $comparison_operator]);
                }
                $comparison_operator    = static::OP_IS_MAP[$comparison_operator];
                break;
        }

        if (!isset(static::OP_MAP[$comparison_operator])) {
            UnavailableVarException::raise('未定義の比較演算子を指定されました。', ['comparison_operator' => $comparison_operator]);
        }

        return static::OP_MAP[$comparison_operator];
    }
}
