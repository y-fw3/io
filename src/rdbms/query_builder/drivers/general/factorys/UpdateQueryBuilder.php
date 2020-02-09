<?php

namespace fw3\io\rdbms\query_builder\drivers\general\factorys;

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\UpdateStatement;

abstract class UpdateQueryBuilder implements
    QueryBuilderFactoryInterface
{
    use QueryBuilderFactoryTrait;

    /**
     * @var string  このファクトリが取り扱うインスタンスクラスパス
     */
    protected const INSTANCE_CLASS_PATH = UpdateStatement::class;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  UpdateStatement Select文
     */
    public static function factory(...$arguments): UpdateStatement
    {
        return UpdateStatement::factory(...$arguments);
    }

    //==============================================
    // feature shortcut
    //==============================================
    public static function value()
    {}

    public static function values()
    {}

    /**
     * Update文構築時にWhere句を設定し返します。
     *
     * @param   string|ClauseInterface      $left_expression    左辺式
     * @param   string|ClauseInterface|null $right_expression   右辺式
     * @param   string|null                 $operator           比較演算子
     * @return  UpdateStatement             Select文
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): UpdateStatement
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return UpdateStatement::factory()->where($left_expression);
        }
        return UpdateStatement::factory()->where($left_expression, $right_expression, $operator);
    }
}
