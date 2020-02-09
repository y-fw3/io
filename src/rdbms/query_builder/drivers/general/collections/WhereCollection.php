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

namespace fw3\io\rdbms\query_builder\drivers\general\collections;

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionTrait;
use fw3\io\rdbms\query_builder\drivers\general\factorys\predicates\WherePredicateFactory;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesEncloseExpression;
use fw3\io\rdbms\query_builder\drivers\general\predicates\WherePredicate;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Whereコレクション
 *
 * @property    WherePredicate[]    $collection
 * @method WhereCollection|array collection(null|array|WhereCollection $collection)
 * @method WherePredicate get(int|string $offset)
 * @method WhereCollection set(int|string $offset, mixed $value)
 * @method WherePredicate first()
 * @method WherePredicate last()
 */
class WhereCollection implements
    WherePredicatesEncloseExpression,
    CollectionInterface,
    // type exnteds
    ChildrenDoNotUseAliasKeyword
{
    use CollectionTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // factory
    //==============================================
    /**
     * Whereコレクションを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  WhereCollection Whereコレクション
     */
    public static function factory(...$arguments): WhereCollection
    {
        return new static();
    }

    //==============================================
    // feature
    //==============================================
    public function add(WhereClause $whereClause)
    {
        $this->collection[] = $whereClause->withParentReference($this);
        return $this;
    }

    /**
     * WhereコレクションにWhere述部を追加します。
     *
     * @param   string|ExpressionInterface|WherePredicateFactory   $left_expression    左辺式
     * @param   string|ExpressionInterface|null             $right_expression   右辺式
     * @param   string|null                                 $operator           比較演算子
     * @param   null|string $logical_operator 論理演算子
     * @return  WhereCollection            このインスタンス
     */
    public function where($left_expression, $right_expression = null, $operator = WherePredicateFactory::OP_EQ, $logical_operator = null): WhereCollection
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            if (is_object($left_expression)) {
                if ($left_expression instanceof WhereClause) {
                    return $this->merge($left_expression->collection());
                }

                if ($left_expression instanceof WhereCollection) {
                    return $this->merge($left_expression->collection());
                }

                if ($left_expression instanceof WherePredicate) {
                    $this->collection[] = $left_expression->withParentReference($this);
                    return $this;
                }
            }

            $this->collection[] = WherePredicate::factory()->where($left_expression)->parentReference($this);
            return $this;
        }

        $this->collection[] = WherePredicate::factory()->where($left_expression, $right_expression, $operator, $logical_operator)->parentReference($this);
        return $this;
    }

    public function wheres(...$wheres): WhereCollection
    {
        foreach (!isset($wheres[1]) ? $wheres[0] : $wheres as $where) {
            if (is_array($where)) {
                if (isset($where['logical_operator'])) {
                    $logical_operator   = $where['logical_operator'];
                    unset($where['logical_operator']);
                } elseif (isset($where[3])) {
                    $logical_operator   = $where[3];
                    unset($where[3]);
                } else {
                    $logical_operator   = null;
                }

                switch ($logical_operator) {
                    case WherePredicate::LOGICAL_OPERATOR_AND:
                        $this->andWhere(...$where);
                        break;
                    case WherePredicate::LOGICAL_OPERATOR_OR:
                        $this->orWhere(...$where);
                        break;
                    default:
                        $this->where(...$where);
                        break;
                }
            } else {
                $this->where($where);
            }
        }
        return $this;
    }

    //==============================================
    // operator
    //==============================================
    // between
    //----------------------------------------------
    public function whereBetween($left_expression, $from, $to): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereBetween($left_expression, $from, $to)->parentReference($this);
    }

    //----------------------------------------------
    // in
    //----------------------------------------------
    public function whereIn($left_expression, array $conditions): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereIn($left_expression, $conditions)->parentReference($this);
        return $this;
    }

    //----------------------------------------------
    // bool
    //----------------------------------------------
    public function whereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereBool($left_expression, $condition, $operator)->parentReference($this);
        return $this;
    }

    public function whereIsBool($left_expression, bool $condition): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereIsBool($left_expression, $condition)->parentReference($this);
        return $this;
    }

    public function whereIsTrue($left_expression): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereIsTrue($left_expression)->parentReference($this);
        return $this;
    }

    //----------------------------------------------
    // Default
    //----------------------------------------------
    public function whereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereDefault($left_expression, $operator)->parentReference($this);
        return $this;
    }

    //----------------------------------------------
    // null
    //----------------------------------------------
    public function whereIsNull($left_expression): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereDefault($left_expression)->parentReference($this);
        return $this;
    }

    //----------------------------------------------
    // Int
    //----------------------------------------------
    public function whereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClause
    {
        $this->collection[] = WherePredicate::factory()->whereInt($left_expression, $condition, $operator)->parentReference($this);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResultInterface
    {
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [];

        foreach ($this->collection as $wherePredicate) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $wherePredicate->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
