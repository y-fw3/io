<?php

namespace fw3\io\rdbms\query_builder\drivers\general\factorys;

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\DeleteStatement;

/**
 * Delete文ファクトリクラス
 *
 * @method static return DeleteStatement table(string|TableReferenceExpression|TablePropertyInterface, null|string|TableReferenceExpression|TablePropertyInterface) テーブル参照を設定したインスタンスを返します。
 */
abstract class DeleteQueryBuilder implements
    // group
    QueryBuilderFactoryInterface
{
    use QueryBuilderFactoryTrait;

    /**
     * @var string  このファクトリが取り扱うインスタンスクラスパス
     */
    protected const INSTANCE_CLASS_PATH = DeleteStatement::class;

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
     * @return  DeleteStatement Select文
     */
    public static function factory(...$arguments): DeleteStatement
    {
        return DeleteStatement::factory(...$arguments);
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
     * @return  DeleteStatement             Select文
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): DeleteStatement
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return DeleteStatement::factory()->where($left_expression);
        }
        return DeleteStatement::factory()->where($left_expression, $right_expression, $operator);
    }
}
