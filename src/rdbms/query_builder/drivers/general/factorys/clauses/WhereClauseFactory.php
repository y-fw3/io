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

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\clauses\ClauseFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\clauses\ClauseFactoryTrait;

/**
 * Where句ファクトリ
 */
abstract class WhereClauseFactory implements
    ClauseFactoryInterface,
    ComparisonOperatorConst,
    LogicalOperatorConst
{
    use ClauseFactoryTrait;

    /**
     * @var string  このファクトリが取り扱うインスタンスクラスパス
     */
    protected const INSTANCE_CLASS_PATH = WhereClause::class;

    //==============================================
    // factory
    //==============================================
    /**
     * Where句を作成し返します。
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
     * @return  WhereClause   Where句
     */
    public static function factory(...$arguments): WhereClause
    {
        return WhereClause::factory(...$arguments);
    }

    //==============================================
    // feature shortcut
    //==============================================
    /**
     * Where句構築時に前に対して"AND"として展開するWhere句を返します。
     *
     * @param   string|ClauseInterface      $left_expression    左辺式
     * @param   string|ClauseInterface|null $right_expression   右辺式
     * @param   string|null                 $operator           比較演算子
     * @return  WhereClause           Where句
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WhereClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return WhereClause::factory()->where($left_expression);
        }
        return WhereClause::factory()->where($left_expression, $right_expression, $operator);
    }
}
