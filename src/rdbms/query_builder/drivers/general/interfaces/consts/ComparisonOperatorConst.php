<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

/**
 * 比較演算子
 */
interface ComparisonOperatorConst
{
    //==============================================
    // プレースホルダ
    //==============================================
    public const PLACEHOLDER    = '?';

    //==============================================
    // 演算子
    //==============================================
    // =
    //----------------------------------------------
    public const COMPARISON_OPERATOR_EQUAL  = '=';
    public const OP_EQUAL                   = self::COMPARISON_OPERATOR_EQUAL;
    public const OP_EQ                      = self::COMPARISON_OPERATOR_EQUAL;

    //----------------------------------------------
    // <=>
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NULL_SAFE_EQUAL    = '<=>';
    public const OP_NULL_SAFE_EQUAL                     = self::COMPARISON_OPERATOR_NULL_SAFE_EQUAL;
    public const OP_NULL_SAFE_EQ                        = self::COMPARISON_OPERATOR_NULL_SAFE_EQUAL;

    //----------------------------------------------
    // IN
    //----------------------------------------------
    public const COMPARISON_OPERATOR_IN = 'IN';
    public const OP_IN                  = self::COMPARISON_OPERATOR_IN;

    //----------------------------------------------
    // NOT IN
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NOT_IN = 'NOT IN';
    public const OP_NOT_IN                  = self::COMPARISON_OPERATOR_NOT_IN;
    public const OP_N_IN                    = self::COMPARISON_OPERATOR_NOT_IN;

    //----------------------------------------------
    // <>
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NOT_EQUAL  = '<>';
    public const OP_NOT_EQUAL                   = self::COMPARISON_OPERATOR_NOT_EQUAL;
    public const OP_NOT_EQ                      = self::COMPARISON_OPERATOR_NOT_EQUAL;
    public const OP_N_EQ                        = self::COMPARISON_OPERATOR_NOT_EQUAL;

    public const COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_EXCLAMATION    = '!=';
    public const OP_NOT_EQUAL_ALIAS_EXCL                            = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_EXCLAMATION;
    public const OP_NOT_EQ_ALIAS_EXCL                               = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_EXCLAMATION;
    public const OP_N_EQ_ALIAS_EXCL                                 = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_EXCLAMATION;

    public const COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_HAT    = '^=';
    public const OP_NOT_EQUAL_ALIAS_HAT                     = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_HAT;
    public const OP_NOT_EQ_ALIAS_HAT                        = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_HAT;
    public const OP_N_EQ_ALIAS_HAT                          = self::COMPARISON_OPERATOR_NOT_EQUAL_ALIAS_HAT;

    //----------------------------------------------
    // <
    //----------------------------------------------
    public const COMPARISON_OPERATOR_LESS_THAN  = '<';
    public const OP_LESS_THAN                   = self::COMPARISON_OPERATOR_LESS_THAN;
    public const OP_LT                          = self::COMPARISON_OPERATOR_LESS_THAN;

    //----------------------------------------------
    // >
    //----------------------------------------------
    public const COMPARISON_OPERATOR_GREATER_THAN   = '>';
    public const OP_GREATER_THAN                    = self::COMPARISON_OPERATOR_GREATER_THAN;
    public const OP_GT                              = self::COMPARISON_OPERATOR_GREATER_THAN;

    //----------------------------------------------
    // <=
    //----------------------------------------------
    public const COMPARISON_OPERATOR_LESS_THAN_EQUAL    = '<=';
    public const OP_LESS_THAN_EQUAL                     = self::COMPARISON_OPERATOR_LESS_THAN_EQUAL;
    public const OP_LT_EQ                               = self::COMPARISON_OPERATOR_LESS_THAN_EQUAL;

    //----------------------------------------------
    // >=
    //----------------------------------------------
    public const COMPARISON_OPERATOR_GREATER_THAN_EQUAL = '>=';
    public const OP_GREATER_THAN_EQUAL                  = self::COMPARISON_OPERATOR_GREATER_THAN_EQUAL;
    public const OP_GT_EQ                               = self::COMPARISON_OPERATOR_GREATER_THAN_EQUAL;

    //----------------------------------------------
    // IS
    //----------------------------------------------
    public const COMPARISON_OPERATOR_IS = 'IS';
    public const OP_IS                  = self::COMPARISON_OPERATOR_IS;

    //----------------------------------------------
    // IS NOT
    //----------------------------------------------
    public const COMPARISON_OPERATOR_IS_NOT = 'IS NOT';
    public const OP_IS_NOT                  = self::COMPARISON_OPERATOR_IS_NOT;
    public const OP_N_IS                    = self::COMPARISON_OPERATOR_IS_NOT;

    //----------------------------------------------
    // BETWEEN
    //----------------------------------------------
    public const COMPARISON_OPERATOR_BETWEEN    = 'BETWEEN';
    public const OP_BETWEEN                     = self::COMPARISON_OPERATOR_BETWEEN;

    //----------------------------------------------
    // NOT BETWEEN
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NOT_BETWEEN    = 'NOT BETWEEN';
    public const OP_NOT_BETWEEN                     = self::COMPARISON_OPERATOR_NOT_BETWEEN;
    public const OP_N_BETWEEN                       = self::COMPARISON_OPERATOR_NOT_BETWEEN;

    //----------------------------------------------
    // LIKE
    //----------------------------------------------
    public const COMPARISON_OPERATOR_LIKE   = 'LIKE';
    public const OP_LIKE                    = self::COMPARISON_OPERATOR_LIKE;

    //----------------------------------------------
    // NOT LIKE
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NOT_LIKE   = 'NOT LIKE';
    public const OP_NOT_LIKE                    = self::COMPARISON_OPERATOR_NOT_LIKE;
    public const OP_N_LIKE                      = self::COMPARISON_OPERATOR_NOT_LIKE;

    //----------------------------------------------
    // SOUNDS LIKE
    //----------------------------------------------
    public const COMPARISON_OPERATOR_SOUNDS_LIKE    = 'SOUNDS LIKE';
    public const OP_SOUNDS_LIKE                     = self::COMPARISON_OPERATOR_SOUNDS_LIKE;

    //----------------------------------------------
    // REGEX
    //----------------------------------------------
    public const COMPARISON_OPERATOR_REGEX  = 'REGEX';
    public const OP_REGEX                   = self::COMPARISON_OPERATOR_REGEX;

    //----------------------------------------------
    // NOT REGEX
    //----------------------------------------------
    public const COMPARISON_OPERATOR_NOT_REGEX  = 'NOT REGEX';
    public const OP_NOT_REGEX                   = self::COMPARISON_OPERATOR_NOT_REGEX;

    //==============================================
    // 比較演算子：フォーマット
    //==============================================
    public const OP_FORMAT_EQ           = '%s = %s';

    public const OP_FORMAT_NULL_SAFE_EQ = '%s <=> %s';

    public const OP_FORMAT_IN           = '%s IN %s';

    public const OP_FORMAT_NOT_IN       = '%s NOT IN %s';

    public const OP_FORMAT_NOT_EQ       = '%s <> %s';

    public const OP_FORMAT_LT           = '%s < %s';

    public const OP_FORMAT_GT           = '%s > %s';

    public const OP_FORMAT_LT_EQ        = '%s <= %s';

    public const OP_FORMAT_GT_EQ        = '%s >= %s';

    public const OP_FORMAT_IS           = '%s IS %s';

    public const OP_FORMAT_IS_NOT       = '%s IS NOT %s';

    public const OP_FORMAT_BETWEEN      = '%s BETWEEN %s AND %s';

    public const OP_FORMAT_NOT_BETWEEN  = '%s NOT BETWEEN %s AND %s';

    public const OP_FORMAT_LIKE         = '%s LIKE %s';

    public const OP_FORMAT_NOT_LIKE     = '%s NOT LIKE %s';

    public const OP_FORMAT_SOUNDS_LIKE  = '%s SOUNDS LIKE %s';

    public const OP_FORMAT_REGEX        = '%s REGEX %s';

    public const OP_FORMAT_NOT_REGEX    = '%s NOT REGEX %s';

    //==============================================
    // 演算子：map
    //==============================================
    public const COMPARISON_OPERATOR_MAP  = [
        self::OP_EQ             => self::OP_EQ,
        self::OP_NULL_SAFE_EQ   => self::OP_NULL_SAFE_EQ,
        self::OP_IN             => self::OP_IN,
        self::OP_N_IN           => self::OP_N_IN,
        self::OP_N_EQ           => self::OP_N_EQ,
        self::OP_LT             => self::OP_LT,
        self::OP_GT             => self::OP_GT,
        self::OP_LT_EQ          => self::OP_LT_EQ,
        self::OP_GT_EQ          => self::OP_GT_EQ,
        self::OP_IS             => self::OP_IS,
        self::OP_N_IS           => self::OP_N_IS,
        self::OP_BETWEEN        => self::OP_BETWEEN,
        self::OP_N_BETWEEN      => self::OP_N_BETWEEN,
        self::OP_LIKE           => self::OP_LIKE,
        self::OP_N_LIKE         => self::OP_N_LIKE,
        self::OP_SOUNDS_LIKE    => self::OP_SOUNDS_LIKE,
        self::OP_REGEX          => self::OP_REGEX,
        self::OP_NOT_REGEX      => self::OP_NOT_REGEX,
    ];

    public const OP_MAP   = self::COMPARISON_OPERATOR_MAP;

    public const OP_NOT_EQUAL_ALIAS_MAP    = [
        self::OP_N_EQ_ALIAS_EXCL    => self::OP_N_EQ,
        self::OP_N_EQ_ALIAS_HAT     => self::OP_N_EQ,
        self::OP_N_EQ               => self::OP_N_EQ,
    ];

    public const OP_IN_MAP   = [
        self::OP_IN     => self::OP_IN,
        self::OP_N_IN   => self::OP_N_IN,
    ];

    public const OP_LIST_MAP   = [
        self::OP_EQ     => self::OP_IN,
        self::OP_N_EQ   => self::OP_N_IN,
        self::OP_IN     => self::OP_IN,
        self::OP_N_IN   => self::OP_N_IN,
        self::OP_IS     => self::OP_IN,
        self::OP_N_IS   => self::OP_N_IN,
    ];

    public const OP_RANGE_MAP = [
        self::OP_BETWEEN    => self::OP_BETWEEN,
        self::OP_N_BETWEEN  => self::OP_N_BETWEEN,
    ];

    public const OP_IS_MAP = [
        self::OP_EQ     => self::OP_IS,
        self::OP_N_EQ   => self::OP_N_IS,
        self::OP_IN     => self::OP_IS,
        self::OP_N_IN   => self::OP_N_IS,
        self::OP_IS     => self::OP_IS,
        self::OP_N_IS   => self::OP_N_IS,
    ];

    public const OP_FORMAT_MAP  = [
        self::OP_EQ             => self::OP_FORMAT_EQ,
        self::OP_NULL_SAFE_EQ   => self::OP_FORMAT_NULL_SAFE_EQ,
        self::OP_IN             => self::OP_FORMAT_IN,
        self::OP_N_IN           => self::OP_FORMAT_NOT_IN,
        self::OP_N_EQ           => self::OP_FORMAT_NOT_EQ,
        self::OP_LT             => self::OP_FORMAT_LT,
        self::OP_GT             => self::OP_FORMAT_GT,
        self::OP_LT_EQ          => self::OP_FORMAT_LT_EQ,
        self::OP_GT_EQ          => self::OP_FORMAT_GT_EQ,
        self::OP_IS             => self::OP_FORMAT_IS,
        self::OP_N_IS           => self::OP_FORMAT_IS_NOT,
        self::OP_BETWEEN        => self::OP_FORMAT_BETWEEN,
        self::OP_N_BETWEEN      => self::OP_FORMAT_NOT_BETWEEN,
        self::OP_LIKE           => self::OP_FORMAT_LIKE,
        self::OP_N_LIKE         => self::OP_FORMAT_NOT_LIKE,
        self::OP_SOUNDS_LIKE    => self::OP_FORMAT_SOUNDS_LIKE,
        self::OP_REGEX          => self::OP_FORMAT_REGEX,
        self::OP_NOT_REGEX      => self::OP_FORMAT_NOT_REGEX,
    ];

    //==============================================
    // 比較演算子：フォーマット
    //==============================================
    public const COMPARISON_VALUE_TYPE_DEFAULT      = null;
    public const COMPARISON_VALUE_TYPE_LIST         = 'list';
    public const COMPARISON_VALUE_TYPE_RANGE        = 'range';
    public const COMPARISON_VALUE_TYPE_BOOL         = 'bool';
    public const COMPARISON_VALUE_TYPE_NULL         = 'null';
}
