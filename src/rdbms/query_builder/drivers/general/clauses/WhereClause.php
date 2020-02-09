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

declare(strict_types=1);

namespace fw3\io\rdbms\query_builder\drivers\general\clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseInterface;
use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseTrait;
use fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesEncloseExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriable;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriableTrait;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeWhere;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;

/**
 * Where句
 *
 * @property WhereCollection $collection
 * @method WhereCollection|array collection(null|array|WhereCollection $collection)
 * @method WherePredicate get(int|string $offset)
 * @method WhereClause set(int|string $offset, mixed $value)
 * @method WherePredicate first()
 * @method WherePredicate last()
 */
class WhereClause implements
    Buildable,
    ClauseInterface,
    CollectionPropertyInterface,
    ComparisonOperatorConst,
    LogicalOperatorConst,
    ParentReferencePropertyInterface,
    SubQueriable,
    TablePropertyInterface,
    WherePredicatesEncloseExpression,
    // type exnteds
    ChildrenDoNotUseAliasKeyword,
    // union types
    UnionTypeWhere
{
    use BuildableTrait;
    use ClauseTrait;
    use CollectionPropertyTrait;
    use ParentReferencePropertyTrait;
    use SubQueriableTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [];

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->collection   = WhereCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  WhereClause   Where述部
     */
    public static function factory(...$arguments): WhereClause
    {
        return new static(...$arguments);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @param   null|string|UnionTypeWhere              $logical_operator   前方に対する論理演算子
     * @return  WhereClause   このインスタンス
     */
    public function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ, ?string $logical_operator = null): WhereClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->where($left_expression);
            return $this;
        }
        $this->collection->where($left_expression, $right_expression, $operator, $logical_operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClause   このインスタンス
     */
    public function andWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WhereClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->andWhere($left_expression);
            return $this;
        }
        $this->collection->addWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClause このインスタンス
     */
    public function orWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WhereClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->orWhere($left_expression);
            return $this;
        }
        $this->collection->orWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    public function wheres(...$wheres)
    {
        $this->collection->wheres(...$wheres);
        return $this;
    }

    //==============================================
    // operator
    //==============================================
    // between
    //----------------------------------------------
    public function whereBetween($left_expression, $from, $to): WhereClause
    {
        $this->collection->whereBetween($left_expression, $from, $to);
        return $this;
    }

    //----------------------------------------------
    // in
    //----------------------------------------------
    public function whereIn($left_expression, array $conditions): WhereClause
    {
        $this->collection->whereIn($left_expression, $conditions);
        return $this;
    }

    //----------------------------------------------
    // bool
    //----------------------------------------------
    public function whereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClause
    {
        $this->collection->whereBool($left_expression, $condition, $operator);
        return $this;
    }

    public function whereIsBool($left_expression, bool $condition): WhereClause
    {
        $this->collection->whereIsBool($left_expression, $condition);
        return $this;
    }

    public function whereIsTrue($left_expression): WhereClause
    {
        $this->collection->whereIsTrue($left_expression);
        return $this;
    }

    //----------------------------------------------
    // Default
    //----------------------------------------------
    public function whereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClause
    {
        $this->collection->whereDefault($left_expression, $operator);
        return $this;
    }

    //----------------------------------------------
    // null
    //----------------------------------------------
    public function whereIsNull($left_expression): WhereClause
    {
        $this->collection->whereIsNull($left_expression);
        return $this;
    }

    //----------------------------------------------
    // Int
    //----------------------------------------------
    public function whereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClause
    {
        $this->collection->whereInt($left_expression, $condition, $operator);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResultInterface    ビルド結果
     */
    public function build(): BuildResultInterface
    {
        return $this->collection->build();
    }
}
