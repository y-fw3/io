<?php
/**    _______       _______
 *    / ____/ |     / /__  /
 *   / /_   | | /| / / /_ <
 *  / __/   | |/ |/ /___/ /
 * /_/      |__/|__//____/
 *
 * Flywheel3: the inertia php framework
 *
 * @category    Flywheel3
 * @package     io
 * @author      wakaba <wakabadou@gmail.com>
 * @copyright   2017 - Wakabadou (http://www.wakabadou.net/) / Project ICKX (https://ickx.jp/)
 * @license     http://opensource.org/licenses/MIT The MIT License MIT
 * @varsion     0.0.1
 */

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\JoinClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;

/**
 * Join句ファクトリ
 */
abstract class JoinClauseFactory implements
    ComparisonOperatorConst,
    LogicalOperatorConst
{
    //==============================================
    // factory
    //==============================================
    /**
     * Join句を作成し返します。
     *
     * @param   array   ...$arguments   引数
     *  ([
     *      'left_expression'   => 左辺式,
     *      'right_expression'  => 右辺式,
     *      'operator'          => 比較演算子,
     *      'logical_operator'  => 論理演算子,
     *      'enable_arg_num'    => 使用する引数の数,
     *  ])
     *  または
     *  (
     *      $left_expression    左辺式,
     *      $right_expression   右辺式,
     *      $operator           比較演算子,
     *      $logical_operator   論理演算子,
     *      $enable_arg_num     使用する引数の数
     *  )
     * @return  JoinClause   Join句
     */
    public static function factory(...$arguments): JoinClause
    {
        return JoinClause::factory(...$arguments);
    }

    //==============================================
    // property access shortcut
    //==============================================
    /**
     * Join句構築時にテーブル参照を設定したJoin句を返します。
     *
     * @param   string|TableReferenceExpression|AliasKeywordEntity    $table  テーブル参照
     * @return  JoinClause           Join句
     */
    public static function table($table, $alias = null): JoinClause
    {
        return JoinClause::factory()->table($table, $alias);
    }

    //==============================================
    // feature shortcut
    //==============================================
    /**
     * Join句構築時に前に対して"AND"として展開するJoin句を返します。
     *
     * @param   string|ClauseInterface      $left_expression    左辺式
     * @param   string|ClauseInterface|null $right_expression   右辺式
     * @param   string|null                 $operator           比較演算子
     * @return  JoinClause           Join句
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return JoinClause::factory()->where($left_expression);
        }
        return JoinClause::factory()->where($left_expression, $right_expression, $operator);
    }

    /**
     * Join句構築時に前に対して"AND"として展開するJoin句を返します。
     *
     * @param   string|ClauseInterface      $left_expression    左辺式
     * @param   string|ClauseInterface|null $right_expression   右辺式
     * @param   string|null                 $operator           比較演算子
     * @return  JoinClause           Join句
     */
    public static function andWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return JoinClause::factory()->where($left_expression);
        }
        return JoinClause::factory()->where($left_expression, $right_expression, $operator);
    }

    /**
     * Join句構築時に前に対して"OR"として展開するJoin句を返します。
     *
     * @param   string|ClauseInterface      $left_expression    左辺式
     * @param   string|ClauseInterface|null $right_expression   右辺式
     * @param   string|null                 $operator           比較演算子
     * @return  JoinClause           Join句
     */
    public static function orWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return JoinClause::factory()->where($left_expression);
        }
        return JoinClause::factory()->where($left_expression, $right_expression, $operator);
    }
}
