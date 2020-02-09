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

namespace fw3\io\rdbms\query_builder\drivers\general\predicates;

use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\SortOrderConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * Order述部インスタンスクラス
 *
 * @method ?OrderPredicate parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method OrderPredicate withParentReference(?object $parentReference)
 * @method OrderPredicate|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method OrderPredicate|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method OrderPredicate with()
 */
class OrderPredicate implements
    PredicateInterface,
    // Const
    SortOrderConst,
    // type extends
    ChildrenDoNotUseAliasKeyword
{
    use PredicateTrait;

    protected $column   = null;
    protected $order    = null;

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
     * @return  WherePredicate  Where述部
     */
    public static function factory(...$arguments): OrderPredicate
    {
        $instance   = new static();
        return $instance;
    }

    //==============================================
    // properties
    //==============================================
    public function column($column = null)
    {
        if (is_null($column) && func_num_args() === 0) {
            return $this->column;
        }

        if (is_string($column)) {
            $column = ColumnExpression::factory()->column($column)->parentReference($this);
        } elseif ($column instanceof ParentReferencePropertyInterface) {
            $column = $column->withParentReference($this);
        }

        $this->column   = $column;
        return $this;
    }

    public function order($order = null)
    {
        if (is_null($order) && func_num_args() === 0) {
            return $this->order;
        }

        if (is_null($order)) {
            $this->order    = null;
            return $this;
        }

        if (isset(static::SORT_ORDER_MAP[$order])) {
            $this->order    = static::SORT_ORDER_MAP[$order];
            return $this;
        }

        UnavailableVarException::raise('未定義のソートオーダーを指定されました。', ['order' => $order]);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * ORDER BY句を設定します。
     *
     * @param   mixed   $column
     * @param   ?string $sort_order
     * @return  OrderPredicate
     */
    public function orderBy($column, $sort_order = null): OrderPredicate
    {
        return $this->column($column)->order($sort_order);
    }

    /**
     * 昇順でORDER BY句を設定します。
     *
     * @param   mixed   $column
     * @return  OrderPredicate
     */
    public function orderByAsc($column): OrderPredicate
    {
        return $this->column($column)->order(static::ASC);
    }

    /**
     * 降順でORDER BY句を設定します。
     *
     * @param   mixed   $column
     * @return  OrderPredicate
     */
    public function orderByDesc($column): OrderPredicate
    {
        return $this->column($column)->order(static::DESC);
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResult
    {
        $clause         = '';
        $conditions     = [];
        $values         = [];
        $clause_stack   = [];

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->column->build()->merge($clause_stack, $conditions, $values);

        if (!is_null($this->order)) {
            $clause_stack[] = $this->order;
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
