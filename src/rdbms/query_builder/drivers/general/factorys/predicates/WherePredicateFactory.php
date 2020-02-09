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

declare(strict_types = 1);

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\predicates;

use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\parent_references\ParentReferenceFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\parent_references\ParentReferenceFactoryTrait;
use fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate;
use fw3\io\rdbms\query_builder\drivers\general\traits\query\expressions\ExpressionInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\tables\TableFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\tables\TableFactoryTrait;

/**
 * Where述部ファクトリ
 */
abstract class WherePredicateFactory implements
    ComparisonOperatorConst,
    LogicalOperatorConst,
    ParentReferenceFactoryInterface,
    TableFactoryInterface
{
    use ParentReferenceFactoryTrait;
    use TableFactoryTrait;

    /**
     * @var string  このファクトリが取り扱うインスタンスクラスパス
     */
    protected const INSTANCE_CLASS_PATH = WherePredicate::class;

    //==============================================
    // facade
    //==============================================
    /**
     * Where述部を作成し返します。
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
     * @return  WherePredicate    Where述部
     */
    public static function factory(...$arguments): WherePredicate
    {
        return WherePredicate::factory(...$arguments);
    }

    //==============================================
    // property access shortcut
    //==============================================
    /**
     * 論理演算子を設定済みのWhere述部を返します。
     *
     * @param   string  $logical_operator   論理演算子
     * @return  WherePredicate        Where述部
     */
    public static function logicalOperator(string $logical_operator): WherePredicate
    {
        return WherePredicate::factory()->logicalOperator($logical_operator);
    }

    /**
     * 左辺式を設定済みのWhere述部を返します。
     *
     * @param   string|ExpressionInterface  $left_expression    左辺式
     * @return  WherePredicate        Where述部
     */
    public static function leftExpression($left_expression): WherePredicate
    {
        return WherePredicate::factory()->leftExpression($left_expression);
    }

    /**
     * 右辺式を設定済みのWhere述部を返します。
     *
     * @param   string|ExpressionInterface  $right_expression   右辺式
     * @return  WherePredicate        Where述部
     */
    public static function rightExpression($right_expression): WherePredicate
    {
        return WherePredicate::factory()->rightExpression($right_expression);
    }

    /**
     * 比較演算子を設定済みのWhere述部を返します。
     *
     * @param   string                  $operator   比較演算子
     * @return  WherePredicate    Where述部
     */
    public static function operator(string $operator): WherePredicate
    {
        return WherePredicate::factory()->operator($operator);
    }

    //==============================================
    // feature shortcut
    //==============================================
    /**
     * Where句構築時に前に対して"AND"として展開するWhere述部を返します。
     *
     * @param   string|ExpressionInterface      $left_expression    左辺式
     * @param   string|ExpressionInterface|null $right_expression   右辺式
     * @param   string|null                     $operator           比較演算子
     * @return  WherePredicate            Where述部
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WherePredicate
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return WherePredicate::factory()->where($left_expression);
        }
        return WherePredicate::factory()->where($left_expression, $right_expression, $operator);
    }
}
