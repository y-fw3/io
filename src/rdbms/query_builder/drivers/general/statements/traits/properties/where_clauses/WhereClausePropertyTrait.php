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

namespace fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;

/**
 * WhereClausesプロパティ特性
 */
trait WhereClausePropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var WhereClause     Where句
     */
    protected WhereClause $whereClause;

    //==============================================
    // property access
    //==============================================
    /**
     * Where句を取得・設定します。
     *
     * @param   null|WhereClause|WhereClausePropertyInterface   $whereClause  Where句
     * @return  WhereClause|WhereClausePropertyInterface        Where句またはこのインスタンス
     */
    public function whereClause($whereClause = null)
    {
        if (is_null($whereClause) && func_num_args() === 0) {
            return $this->whereClause;
        }

        if ($whereClause instanceof WhereClausePropertyInterface) {
            $whereClause = $whereClause->whereClause();
        }

        $this->whereClause  = $whereClause;
        return $this;
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
     * @return  WhereClausePropertyInterface    このインスタンス
     */
    public function where($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ, ?string $logical_operator = null): WhereClausePropertyInterface
    {
        // left expression
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->whereClause->where($left_expression);
            return $this;
        }

        // right expression
        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        // where
        $this->whereClause->where($left_expression, $right_expression, $operator, $logical_operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClausePropertyInterface    このインスタンス
     */
    public function andWhere($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
    {
        // left expression
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->whereClause->andWhere($left_expression);
            return $this;
        }

        // right expression
        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        // where
        $this->whereClause->andWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Where句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClausePropertyInterface    このインスタンス
     */
    public function orWhere($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
    {
        // left expression
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->whereClause->orWhere($left_expression);
            return $this;
        }

        // right expression
        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        // where
        $this->whereClause->orWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Whereをまとめて設定します。
     *
     * @param   array   ...$wheres
     * @return  WhereClausePropertyInterface
     */
    public function wheres(...$wheres): WhereClausePropertyInterface
    {
        $this->whereClause->wheres(...$wheres);
        return $this;
    }

    //==============================================
    // operator
    //==============================================
    // between
    //----------------------------------------------
    public function whereBetween($left_expression, $from, $to): WhereClausePropertyInterface
    {
        $this->whereClause->whereBetween($left_expression, $from, $to);
        return $this;
    }

//     public function andWhereBetween($left_expression, $from, $to): WhereClausePropertyInterface
//     {
//         $this->whereClause->andWhere($left_expression, [$from, $to], static::OP_BETWEEN);
//         return $this;
//     }

//     public function orWhereBetween($left_expression, $from, $to): WhereClausePropertyInterface
//     {
//         $this->whereClause->orWhere($left_expression, [$from, $to], static::OP_BETWEEN);
//         return $this;
//     }

//     public function whereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface
//     {
//         $this->whereClause->where($left_expression, [$from, $to], static::OP_NOT_BETWEEN);
//         return $this;
//     }

//     public function andWhereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface
//     {
//         $this->whereClause->andWhere($left_expression, [$from, $to], static::OP_NOT_BETWEEN);
//         return $this;
//     }

//     public function orWhereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface
//     {
//         $this->whereClause->orWhere($left_expression, [$from, $to], static::OP_NOT_BETWEEN);
//         return $this;
//     }

//     public function whereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface
//     {
//         $this->whereClause->orWhere($left_expression, $values, static::OP_BETWEEN);
//         return $this;
//     }

//     public function andWhereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface
//     {
//         $this->whereClause->andWhere($left_expression, $values, static::OP_BETWEEN);
//         return $this;
//     }

//     public function orWhereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface
//     {
//         $this->whereClause->orWhere($left_expression, $values, static::OP_BETWEEN);
//         return $this;
//     }

//     public function whereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface
//     public function andWhereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface
//     public function orWhereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface

    //----------------------------------------------
    // in
    //----------------------------------------------
    public function whereIn($left_expression, array $conditions): WhereClausePropertyInterface
    {
        $this->whereClause->whereIn($left_expression, $conditions);
        return $this;
    }

//     public function andWhereIn($left_expression, array $values): WhereClausePropertyInterface
//     public function orWhereIn($left_expression, array $values): WhereClausePropertyInterface

//     public function whereNotIn($left_expression, array $values): WhereClausePropertyInterface
//     public function andWhereNotIn($left_expression, array $values): WhereClausePropertyInterface
//     public function orWhereNotIn($left_expression, array $values): WhereClausePropertyInterface

//     //----------------------------------------------
//     // like
//     //----------------------------------------------
//     public function whereLike($left_expression, $right_expression): WhereClausePropertyInterface
//     public function andWhereLike($left_expression, $right_expression): WhereClausePropertyInterface
//     public function orWhereLike($left_expression, $right_expression): WhereClausePropertyInterface

//     public function whereNotLike($left_expression, $right_expression): WhereClausePropertyInterface
//     public function andWhereNotLike($left_expression, $right_expression): WhereClausePropertyInterface
//     public function orWhereNotLike($left_expression, $right_expression): WhereClausePropertyInterface

//     //==============================================
//     // type safe
//     //==============================================
//     // Literal
//     //----------------------------------------------
//     public function whereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     public function whereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface
//     public function andWhereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface
//     public function orWhereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface

//     public function whereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     public function whereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     public function whereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     public function whereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     public function whereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

    //----------------------------------------------
    // bool
    //----------------------------------------------
    public function whereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface
    {
        $this->whereClause->whereBool($left_expression, $condition, $operator);
        return $this;
    }

//     public function andWhereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface
//     public function orWhereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface

    public function whereIsBool($left_expression, bool $condition): WhereClausePropertyInterface
    {
        $this->whereClause->whereIsBool($left_expression, $condition);
        return $this;
    }

//     public function andWhereIsBool($left_expression, bool $condition): WhereClausePropertyInterface
//     public function orWhereIsBool($left_expression, bool $condition): WhereClausePropertyInterface

//     public function whereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface
//     public function andWhereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface
//     public function orWhereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface

    public function whereIsTrue($left_expression): WhereClausePropertyInterface
    {
        $this->whereClause->whereIsTrue($left_expression);
        return $this;
    }

//     public function andWhereIsTrue($left_expression): WhereClausePropertyInterface
//     public function orWhereIsTrue($left_expression): WhereClausePropertyInterface

//     public function whereIsFalse($left_expression): WhereClausePropertyInterface
//     public function andWhereIsFalse($left_expression): WhereClausePropertyInterface
//     public function orWhereIsFalse($left_expression): WhereClausePropertyInterface

//     //----------------------------------------------
//     // Date
//     //----------------------------------------------
//     public function whereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

    //----------------------------------------------
    // Default
    //----------------------------------------------
    public function whereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
    {
        $this->whereClause->whereDefault($left_expression, $operator);
        return $this;
    }

//     public function andWhereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

    //----------------------------------------------
    // null
    //----------------------------------------------
    public function whereIsNull($left_expression): WhereClausePropertyInterface
    {
        $this->whereClause->whereIsNull($left_expression);
        return $this;
    }

//     public function andWhereIsNull($left_expression): WhereClausePropertyInterface
//     public function orWhereIsNull($left_expression): WhereClausePropertyInterface

//     public function whereIsNotNull($left_expression): WhereClausePropertyInterface
//     public function andWhereIsNotNull($left_expression): WhereClausePropertyInterface
//     public function orWhereIsNotNull($left_expression): WhereClausePropertyInterface

    //----------------------------------------------
    // Int
    //----------------------------------------------
    public function whereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
    {
        $this->whereClause->whereInt($left_expression, $condition, $operator);
        return $this;
    }

//     public function andWhereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     //----------------------------------------------
//     // Float
//     //----------------------------------------------
//     public function whereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     //----------------------------------------------
//     // FloatFromString
//     //----------------------------------------------
//     public function whereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     //----------------------------------------------
//     // FloatFromString
//     //----------------------------------------------
//     public function whereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface

//     //----------------------------------------------
//     // Time
//     //----------------------------------------------
//     public function whereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function andWhereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
//     public function orWhereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface
}
