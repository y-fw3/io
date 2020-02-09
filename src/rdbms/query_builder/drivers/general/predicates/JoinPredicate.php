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

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\JoinConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpression;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * Join述部インスタンスクラス
 *
 * @method ?JoinPredicate parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method JoinPredicate withParentReference(?object $parentReference)
 * @method JoinPredicate|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method JoinPredicate|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method JoinPredicate with()
 */
class JoinPredicate implements
    CollectionPropertyInterface,
    PredicateInterface,
    WherePredicatesExpression,
    // type exnteds
    ChildrenDoNotUseAliasKeyword,
    // Const
    ComparisonOperatorConst,
    LogicalOperatorConst,
    JoinConst
{
    use ComparisonOperatorTrait;
    use CollectionPropertyTrait;
    use LogicalOperatorTrait;
    use PredicateTrait;

    protected string $type = self::JOIN;

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
     * @return  WherePredicate  Where述部
     */
    public static function factory(...$arguments): JoinPredicate
    {
        $instance   = new static();
        return $instance;
    }

    //==============================================
    // properties
    //==============================================
    public function type($type = null)
    {
        if (is_null($type) && func_num_args() === 0) {
            return $this->type;
        }

        if (!isset(static::JOIN_MAP[$type])) {
            UnavailableVarException::raise('未定義のJOIN種別を指定されました。', ['type' => $type]);
        }

        $this->type = $type;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    public function join($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function innerJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::INNER_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function outerJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::OUTER_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function crossJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::CROSS_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function straightJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::STRAIGHT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function leftJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::LEFT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function outerLeftJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::OUTER_LEFT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function rightJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::RIGHT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function outerRightJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::RIGHT_OUTER_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function naturalJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::NATURAL_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function naturalLeftJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::NATURAL_LEFT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function naturalLeftOuterJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::NATURAL_LEFT_OUTER_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function naturalRightJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::NATURAL_RIGHT_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    public function naturalRightOuterJoin($table, ...$where)
    {
        if (!isset($where[1])) {
            $where[1] = $where[0];
        }

        $this->type(static::NATURAL_RIGHT_OUTER_JOIN);
        $this->table($table);
        $this->where(...$where);
        return $this;
    }

    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|WherePredicatesExpression        $left_expression    左辺式
     * @param   string|WherePredicatesExpression|null   $right_expression   右辺式
     * @param   string|null                                     $operator           比較演算子
     * @return  WherePredicate  このインスタンス
     */
    public function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinPredicate
    {
        if ($right_expression instanceof WhereClause) {
            foreach ($right_expression->getIterator() as $wherePredicate) {
                $this->collection->where($wherePredicate);
            }
            return $this;
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->where($left_expression);
            return $this;
        }
        $this->collection->where($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|WherePredicatesExpression        $left_expression    左辺式
     * @param   string|WherePredicatesExpression|null   $right_expression   右辺式
     * @param   string|null                                     $operator           比較演算子
     * @return  WherePredicate  このインスタンス
     */
    public function andWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinPredicate
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->andWhere($left_expression);
            return $this;
        }
        $this->collection->andWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|WherePredicatesExpression        $left_expression    左辺式
     * @param   string|WherePredicatesExpression|null   $right_expression   右辺式
     * @param   string|null                                     $operator           比較演算子
     * @return  WherePredicate  このインスタンス
     */
    public function orWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): JoinPredicate
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->orWhere($left_expression);
            return $this;
        }
        $this->collection->orWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  WherePredicateBuildResult   ビルド結果
     */
    public function build(): BuildResult
    {
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [
            $this->type,
        ];

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->table->build()->merge($clause_stack, $conditions, $values);

        $clause_stack[] = 'ON';

        $baseTable  = $this->parentReference->closestTable();
        $joinTable  = $this->table->getReferenceName();

        $collection = $this->collection->with();
        foreach ($collection as $idx => $wherePredicate) {
            if (is_null($wherePredicate->leftExpression()->table())) {
                $collection[$idx]->leftExpression()->table($baseTable);
            }

            if (is_null($wherePredicate->rightExpression()->table())) {
                $collection[$idx]->rightExpression()->table($joinTable);
            }
        }

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $collection->build()->merge($clause_stack, $conditions, $values);

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
