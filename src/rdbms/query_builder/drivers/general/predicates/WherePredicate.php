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
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\factorys\expressions\ColumnExpressionFactory;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoUsePlaceholder;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesEncloseExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpressionUseWhere;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralBool;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNull;
use fw3\io\rdbms\query_builder\drivers\general\literals\traits\LiteralInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateTrait;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\WherePredicateBuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDefault;

/**
 * Where述部インスタンスクラス
 *
 * @method ?WherePredicate parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method WherePredicate withParentReference(?object $parentReference)
 * @method WherePredicate|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method WherePredicate|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method WherePredicate with()
 */
class WherePredicate implements
    ComparisonOperatorConst,
    LogicalOperatorConst,
    PredicateInterface,
    WherePredicatesEncloseExpression,
    // type exnteds
    ChildrenDoUsePlaceholder,
    ChildrenDoNotUseAliasKeyword
{
    use ComparisonOperatorTrait;
    use LogicalOperatorTrait;
    use PredicateTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'logicalOperator',
        'leftExpression',
        'rightExpression',
        'operator',
    ];

    /**
     * @var string  論理演算子
     */
    protected string $logicalOperator   = self::LOP_AND;

    /**
     * @var string|WherePredicatesExpression    左辺式
     */
    protected $leftExpression   = null;

    /**
     * @var string|WherePredicatesExpression    右辺式
     */
    protected $rightExpression  = null;

    /**
     * @var string  比較演算子
     */
    protected $operator         = null;

    /**
     * @var bool    左辺式のみの述部かどうか
     */
    protected $onlyLeftExpression   = false;

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
     *  ([
     *      'left_expression'   => 左辺式,
     *      'right_expression'  => 右辺式,
     *      'operator'          => 比較演算子,
     *      'logical_operator'  => 論理演算子,
     *  ])
     *  または
     *  (
     *      $left_expression    左辺式,
     *      $right_expression   右辺式,
     *      $operator           比較演算子,
     *      $logical_operator   論理演算子,
     *  )
     * @return  WherePredicate  Where述部
     */
    public static function factory(...$arguments): WherePredicate
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $exists_right_expression = isset($arguments[0]['right_expression']) || array_keys($arguments[0], 'right_expression');
                $exists_right_expression = $exists_right_expression || isset($arguments[0][1]) || array_keys($arguments[0], 1);
                $exists_right_expression = $exists_right_expression || isset($arguments[1]) || array_keys($arguments, 1);

                $arguments  = [
                    $arguments[0]['left_expression'] ?? $arguments[0][0] ?? null,
                    $arguments[0]['right_expression'] ?? $arguments[0][1] ?? $arguments[1] ?? null,
                    $arguments[0]['operator'] ?? $arguments[0][2] ?? $arguments[2] ?? null,
                    $arguments[0]['logical_operator'] ?? $arguments[0][3] ?? $arguments[3] ?? null,
                ];

                if (!$exists_right_expression) {
                    unset($arguments[1]);
                }
            }

            !isset($arguments[0]) ?: $instance->leftExpression($arguments[0]);

            $instance->onlyLeftExpression = !(isset($arguments[1]) || array_keys($arguments, 1));
            !$instance->onlyLeftExpression && !isset($arguments[1]) ?: $instance->rightExpression($arguments[1]);

            !isset($arguments[2]) ?: $instance->operator($arguments[2]);
            !isset($arguments[3]) ?: $instance->logicalOperator($arguments[3]);
        }
        return $instance;
    }

    //==============================================
    // accessor
    //==============================================
    /**
     * 論理演算子を設定、取得します。
     *
     * @param   string|null             $logical_operator   論理演算子
     * @return  WherePredicate|string   このインスタンスまたは論理演算子
     */
    public function logicalOperator(?string $logical_operator = null)
    {
        if (is_null($logical_operator) && func_num_args() === 0) {
            return $this->logicalOperator;
        }

        self::assertLogicalOperator($logical_operator = strtoupper($logical_operator));

        $this->logicalOperator  = $logical_operator;
        return $this;
    }

    /**
     * 左辺式を設定、取得します。
     *
     * @param   string|WherePredicatesExpression|null           $left_expression    左辺式
     * @return  WherePredicate|string|WherePredicatesExpression このインスタンスまたは左辺式
     */
    public function leftExpression($left_expression = null)
    {
        if (is_null($left_expression) && func_num_args() === 0) {
            return $this->leftExpression;
        }

        if (is_string($left_expression)) {
            $this->leftExpression   = ColumnExpressionFactory::column($left_expression)->parentReference($this);
            return $this;
        }

        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $this->leftExpression   = $left_expression->withParentReference($this);
            return $this;
        }

        if (is_object($left_expression)) {
            $this->leftExpression   = clone $left_expression;
            return $this;
        }

        $this->leftExpression   = $left_expression;
        return $this;
    }

    /**
     * 右辺式を設定、取得します。
     *
     * @param   string|WherePredicatesExpression|null           $right_expression   右辺式
     * @return  WherePredicate|string|WherePredicatesExpression このインスタンスまたは左辺式
     */
    public function rightExpression($right_expression = null)
    {
        if (is_null($right_expression) && func_num_args() === 0) {
            return $this->rightExpression;
        }

        if (is_string($right_expression)) {
            $this->rightExpression  = ColumnExpressionFactory::column($right_expression)->parentReference($this);
            return $this;
        }

        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $this->rightExpression  = $right_expression->withParentReference($this);
            return $this;
        }

        if (is_object($right_expression)) {
            $this->rightExpression  = clone $right_expression;
            return $this;
        }

        $this->rightExpression  = $right_expression;
        return $this;
    }

    /**
     * 比較演算子を設定、取得します。
     *
     * @param   string|null $operator   比較演算子
     * @return  WherePredicate|string   このインスタンスまたは比較演算子
     */
    public function operator(?string $operator = null)
    {
        if (is_null($operator) && func_num_args() === 0) {
            return $this->operator;
        }

        self::assertComparisonOperator($operator = strtoupper($operator));

        $this->operator = $operator;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|WherePredicatesExpression        $left_expression    左辺式
     * @param   string|WherePredicatesExpression|null   $right_expression   右辺式
     * @param   string|null                                     $operator           比較演算子
     * @return  WherePredicate  このインスタンス
     */
    public function where($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WherePredicate
    {
        $this->onlyLeftExpression   = is_null($right_expression) && func_num_args() === 1;

        if (is_object($left_expression)) {
            if ($left_expression instanceof WherePredicate) {
                if ($this->onlyLeftExpression) {
                    return $left_expression->withParentReference($this->parentReference);
                }
            }
        }

        $this->leftExpression($left_expression);

        if ($this->onlyLeftExpression) {
            return $this;
        }

        $this->rightExpression($right_expression);
        $this->operator($operator);
        $this->logicalOperator(static::LOP_AND);

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
    public function andWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WherePredicate
    {
        $this->onlyLeftExpression   = is_null($right_expression) && func_num_args() === 1;

        if (is_object($left_expression)) {
            if ($left_expression instanceof WherePredicate) {
                if ($this->onlyLeftExpression) {
                    return $left_expression->withParentReference($this->parentReference);
                }
            }
        }

        $this->leftExpression($left_expression);

        if ($this->onlyLeftExpression) {
            return $this;
        }

        $this->rightExpression($right_expression);
        $this->operator($operator);
        $this->logicalOperator(static::LOP_AND);

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
    public function orWhere($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): WherePredicate
    {
        $this->onlyLeftExpression   = is_null($right_expression) && func_num_args() === 1;

        if (is_object($left_expression)) {
            if ($left_expression instanceof WherePredicate) {
                if ($this->onlyLeftExpression) {
                    return $left_expression->withParentReference($this->parentReference);
                }
            }
        }

        $this->leftExpression($left_expression);

        if ($this->onlyLeftExpression) {
            return $this;
        }

        $this->rightExpression($right_expression);
        $this->operator($operator);
        $this->logicalOperator(static::LOP_OR);

        return $this;
    }

    //==============================================
    // operator
    //==============================================
    // between
    //----------------------------------------------
    public function whereBetween($left_expression, $from, $to): WherePredicate
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression([$from, $to]);
        $this->operator(static::OP_BETWEEN);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //----------------------------------------------
    // in
    //----------------------------------------------
    public function whereIn($left_expression, array $conditions): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression($conditions);
        $this->operator(static::OP_IN);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //----------------------------------------------
    // bool
    //----------------------------------------------
    public function whereBool($left_expression, bool $condition, ?string $operator = self::OP_IS): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression($condition);
        $this->operator($operator);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    public function whereIsBool($left_expression, bool $condition): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression($condition);
        $this->operator(static::OP_IS);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    public function whereIsTrue($left_expression): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression(true);
        $this->operator(static::OP_IS);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //----------------------------------------------
    // Default
    //----------------------------------------------
    public function whereDefault($left_expression, ?string $operator = self::OP_EQ): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression(LiteralDefault::factory());
        $this->operator($operator);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //----------------------------------------------
    // null
    //----------------------------------------------
    public function whereIsNull($left_expression): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression(null);
        $this->operator(static::OP_IS);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //----------------------------------------------
    // Int
    //----------------------------------------------
    public function whereInt($left_expression, int $condition, ?string $operator = self::OP_EQ): WhereClause
    {
        $this->onlyLeftExpression   = false;

        $this->leftExpression($left_expression);
        $this->rightExpression($condition);
        $this->operator($operator);
        $this->logicalOperator(static::LOP_AND);

        return $this;
    }

    //==============================================
    // with
    //==============================================
    /**
     * このインスタンスを複製し、論理演算子を設定して返します。
     *
     * @return  WherePredicate    複製されたこのインスタンス
     */
    public function withLogicalOperator(string $logical_operator): WherePredicate
    {
        return (clone $this)->logicalOperator($logical_operator);
    }

    //==============================================
    // builder
    //==============================================
    /**
     * 式をビルドし、先行状態にマージして返します。
     *
     * @param   mixed   $expression     ビルドする対象の式
     * @param   array   $clause_stack   先行状態の句スタック
     * @param   array   $conditions     先行状態のWhere句で使用する条件値
     * @param   array   $values         先行状態のデータのSETなどで使用する値
     * @param   bool    $exist_array_expression 先行状態の式が持つ値が配列であったかどうか
     * @return  array   ビルド結果をマージした値
     *  [
     *      0   => array    ビルド結果をマージした句スタック
     *      1   => array    ビルド結果をマージしたWhere句で使用する条件値
     *      2   => bool     ビルド結果をマージしたデータのSETなどで使用する値
     *      3   => array    ビルド結果をマージした式が持つ値が配列であったかどうか
     *  ]
     */
    protected function buildExpression($expression, array $clause_stack, array $conditions, array $values, $before_expression_is_column = false, ?string $operator = null): array
    {
        //==============================================
        // array
        //==============================================
        if (isset(static::OP_IN_MAP[$operator]) || isset(static::OP_RANGE_MAP[$operator])) {
            $expression = (array) $expression;
        }

        if (is_array($expression)) {
            if (is_null($operator)) {
                UnavailableVarException::raise('左辺に配列を指定することはできません。', ['left_expression' => $expression]);
            }

            if (isset(static::OP_RANGE_MAP[$operator])) {
                $operator       = self::adjustComparisonOperator($operator, static::COMPARISON_VALUE_TYPE_RANGE);
                return [
                    [vsprintf(self::OP_FORMAT_MAP[$operator], array_merge($clause_stack, static::fillPlaceholder($expression)))],
                    array_merge($conditions, $expression),
                    $values,
                    false
                ];
            }

            $operator   = self::adjustComparisonOperator($operator, static::COMPARISON_VALUE_TYPE_LIST);
            return [
                [vsprintf(self::OP_FORMAT_MAP[$operator], array_merge($clause_stack, [sprintf('(%s)', implode(', ', static::fillPlaceholder($expression)))]))],
                array_merge($conditions, $expression),
                $values,
                false,
            ];
        }

        //==============================================
        // literal
        //==============================================
        if (is_bool($expression)) {
            // bool
            $expression = LiteralBool::factory($expression)->parentReference($this);
        } elseif (is_null($expression)) {
            // null
            $expression = LiteralNull::factory()->parentReference($this);
        } elseif ($expression instanceof LiteralInterface) {
            $expression = $expression->withParentReference($this);
        }

        //==============================================
        // object
        //==============================================
        if (is_object($expression)) {
            //----------------------------------------------
            // Literal
            //----------------------------------------------
            if ($expression instanceof LiteralInterface) {
                if ($expression instanceof LiteralNull) {
                    $value_type = static::COMPARISON_VALUE_TYPE_NULL;
                } elseif ($expression instanceof LiteralBool) {
                    $value_type = static::COMPARISON_VALUE_TYPE_BOOL;
                } else {
                    $value_type = static::COMPARISON_VALUE_TYPE_DEFAULT;
                }

                $clause_stack[] = $expression->getClause();

                if (!is_null($operator)) {
                    $operator       = self::adjustComparisonOperator($operator, $value_type);
                    $clause_stack   = [vsprintf(self::OP_FORMAT_MAP[$operator], $clause_stack)];
                }

                return [
                    $clause_stack,
                    $conditions,
                    $values,
                    false,
                ];
            }

            //----------------------------------------------
            // sub query
            //----------------------------------------------
            if ($expression instanceof \Closure) {
                $expression->call($whereClause = WhereClause::factory()->parentReference($this));
                $expression = $whereClause->convertSubQuery();
            }

            if ($expression instanceof SelectStatement) {
                $expression = $expression->convertSubQuery();
            }

            if ($expression instanceof SubQuery) {
                $buildResult    = $expression->build();

                $clause_stack[] = $buildResult->getClause();

                if (!is_null($operator)) {
                    $operator       = self::adjustComparisonOperator($operator, $before_expression_is_column ? static::COMPARISON_VALUE_TYPE_LIST : static::COMPARISON_VALUE_TYPE_DEFAULT);
                    $clause_stack   = [vsprintf(self::OP_FORMAT_MAP[$operator], $clause_stack)];
                }

                return [
                    $clause_stack,
                    array_merge($conditions, $buildResult->getConditions()),
                    array_merge($values, $buildResult->getValues()),
                    false,
                ];
            }

            //----------------------------------------------
            // WherePredicatesExpression
            //----------------------------------------------
            $before_expression_is_column    = $expression instanceof ColumnExpression;

            if ($expression instanceof WherePredicatesExpressionUseWhere) {
                $expression = $expression->whereClause();
            }

            if ($expression instanceof WherePredicatesEncloseExpression) {
                $buildResult    = $expression->build();

                $clause_stack[] = $this->onlyLeftExpression ? $buildResult->getClause() : sprintf('(%s)', $buildResult->getClause());

                if (!is_null($operator)) {
                    $operator       = self::adjustComparisonOperator($operator, static::COMPARISON_VALUE_TYPE_DEFAULT);
                    $clause_stack   = [vsprintf(self::OP_FORMAT_MAP[$operator], $clause_stack)];
                }

                return [
                    $clause_stack,
                    array_merge($conditions, $buildResult->getConditions()),
                    array_merge($values, $buildResult->getValues()),
                    $before_expression_is_column,
                ];
            }

            if ($expression instanceof WherePredicatesExpression) {
                $buildResult    = $expression->build();

                $clause_stack[] = $this->onlyLeftExpression ? $buildResult->getClause() : sprintf('%s', $buildResult->getClause());

                if (!is_null($operator)) {
                    $operator       = self::adjustComparisonOperator($operator, static::COMPARISON_VALUE_TYPE_DEFAULT);
                    $clause_stack   = [vsprintf(self::OP_FORMAT_MAP[$operator], $clause_stack)];
                }

                return [
                    $clause_stack,
                    array_merge($conditions, $buildResult->getConditions()),
                    array_merge($values, $buildResult->getValues()),
                    $before_expression_is_column,
                ];
            }

            UnavailableVarException::raise('使用できない式を与えられました。', ['expression' => $expression]);
        }

        //==============================================
        // other
        //==============================================
        $clause_stack[] = static::PLACEHOLDER;
        $conditions[]   = $expression;

        if (!is_null($operator)) {
            $operator       = self::adjustComparisonOperator($operator, static::COMPARISON_VALUE_TYPE_DEFAULT);
            $clause_stack   = [vsprintf(self::OP_FORMAT_MAP[$operator], $clause_stack)];
        }

        return [
            $clause_stack,
            $conditions,
            $values,
            false,
        ];
    }

    /**
     * ビルダ
     *
     * @return  WherePredicateBuildResult   ビルド結果
     */
    public function build(): WherePredicateBuildResult
    {
        $clause_stack   = [];
        $conditions     = [];
        $values         = [];

        //==============================================
        // left expression
        //==============================================
        [$clause_stack, $conditions, $values, $before_expression_is_column] = $this->buildExpression($this->leftExpression, $clause_stack, $conditions, $values);

        //==============================================
        // comparison
        //==============================================
        if (!$this->onlyLeftExpression) {
            //==============================================
            // operator
            //==============================================
            $operator       = $this->operator;
            if (is_callable($operator)) {
                $operator   = $operator();
            }

            if (is_null($operator)) {
                $operator   = self::OP_EQ;
            }

            //==============================================
            // right expression
            //==============================================
            [$clause_stack, $conditions, $values, $before_expression_is_column] = $this->buildExpression($this->rightExpression, $clause_stack, $conditions, $values, $before_expression_is_column, $operator);
        }

        //==============================================
        // build clause
        //==============================================
        $clause  = implode('', $clause_stack);

        //==============================================
        // result
        //==============================================
        return WherePredicateBuildResult::factory($clause, $conditions, $values, $this->logicalOperator);
    }
}
