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
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralBool;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDate;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNumber;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralString;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTime;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTimestamp;
use fw3\io\rdbms\query_builder\drivers\general\literals\traits\LiteralInterface;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeWhere;


/**
 * Where句プロパティ特性インターフェース
 */
interface WhereClausePropertyInterface extends
    UnionTypeWhere
{
    //==============================================
    // property access
    //==============================================
    /**
     * Where句を取得・設定します。
     *
     * @param   null|WhereClause                $whereClause  Where句
     * @return  WhereClause|StatementInterface  Where句またはこのインスタンス
     */
    public function whereClause($whereClause = null);

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
    public function where($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ, ?string $logical_operator = null): WhereClausePropertyInterface;

    /**
     * Where句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClausePropertyInterface    このインスタンス
     */
    public function andWhere($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

    /**
     * Where句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  WhereClausePropertyInterface    このインスタンス
     */
    public function orWhere($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

    /**
     * Whereをまとめて設定します。
     *
     * @param   array   ...$wheres
     * @return  WhereClausePropertyInterface
     */
    public function wheres(...$wheres): WhereClausePropertyInterface;

//     //==============================================
//     // operator
//     //==============================================
//     // between
//     //----------------------------------------------
//     public function whereBetween($left_expression, $from, $to): WhereClausePropertyInterface;
//     public function andWhereBetween($left_expression, $from, $to): WhereClausePropertyInterface;
//     public function orWhereBetween($left_expression, $from, $to): WhereClausePropertyInterface;

//     public function whereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface;
//     public function andWhereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface;
//     public function orWhereNotBetween($left_expression, $from, $to): WhereClausePropertyInterface;

//     public function whereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;
//     public function andWhereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;
//     public function orWhereBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;

//     public function whereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;
//     public function andWhereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;
//     public function orWhereNotBetweenFromArray($left_expression, array $values): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // in
//     //----------------------------------------------
//     public function whereIn($left_expression, array $values): WhereClausePropertyInterface;
//     public function andWhereIn($left_expression, array $values): WhereClausePropertyInterface;
//     public function orWhereIn($left_expression, array $values): WhereClausePropertyInterface;

//     public function whereNotIn($left_expression, array $values): WhereClausePropertyInterface;
//     public function andWhereNotIn($left_expression, array $values): WhereClausePropertyInterface;
//     public function orWhereNotIn($left_expression, array $values): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // like
//     //----------------------------------------------
//     public function whereLike($left_expression, $right_expression): WhereClausePropertyInterface;
//     public function andWhereLike($left_expression, $right_expression): WhereClausePropertyInterface;
//     public function orWhereLike($left_expression, $right_expression): WhereClausePropertyInterface;

//     public function whereNotLike($left_expression, $right_expression): WhereClausePropertyInterface;
//     public function andWhereNotLike($left_expression, $right_expression): WhereClausePropertyInterface;
//     public function orWhereNotLike($left_expression, $right_expression): WhereClausePropertyInterface;

//     //==============================================
//     // type safe
//     //==============================================
//     // Literal
//     //----------------------------------------------
//     public function whereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteral($left_expression, LiteralInterface $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     public function whereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;
//     public function andWhereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;
//     public function orWhereLiteralBool($left_expression, LiteralBool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;

//     public function whereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteralDate($left_expression, LiteralDate $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     public function whereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteralNumber($left_expression, LiteralNumber $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     public function whereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteralString($left_expression, LiteralString $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     public function whereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteralTime($left_expression, LiteralTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     public function whereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereLiteralTimestamp($left_expression, LiteralTimestamp $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // bool
//     //----------------------------------------------
//     public function whereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;
//     public function andWhereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;
//     public function orWhereBool($left_expression, bool $condition, ?string $operator = WhereClause::OP_IS): WhereClausePropertyInterface;

//     public function whereIsBool($left_expression, bool $condition): WhereClausePropertyInterface;
//     public function andWhereIsBool($left_expression, bool $condition): WhereClausePropertyInterface;
//     public function orWhereIsBool($left_expression, bool $condition): WhereClausePropertyInterface;

//     public function whereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface;
//     public function andWhereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface;
//     public function orWhereIsNotBool($left_expression, bool $condition): WhereClausePropertyInterface;

//     public function whereIsTrue($left_expression): WhereClausePropertyInterface;
//     public function andWhereIsTrue($left_expression): WhereClausePropertyInterface;
//     public function orWhereIsTrue($left_expression): WhereClausePropertyInterface;

//     public function whereIsFalse($left_expression): WhereClausePropertyInterface;
//     public function andWhereIsFalse($left_expression): WhereClausePropertyInterface;
//     public function orWhereIsFalse($left_expression): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // Date
//     //----------------------------------------------
//     public function whereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereDate($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // Default
//     //----------------------------------------------
//     public function whereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereDefault($left_expression, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // null
//     //----------------------------------------------
//     public function whereIsNull($left_expression): WhereClausePropertyInterface;
//     public function andWhereIsNull($left_expression): WhereClausePropertyInterface;
//     public function orWhereIsNull($left_expression): WhereClausePropertyInterface;

//     public function whereIsNotNull($left_expression): WhereClausePropertyInterface;
//     public function andWhereIsNotNull($left_expression): WhereClausePropertyInterface;
//     public function orWhereIsNotNull($left_expression): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // Int
//     //----------------------------------------------
//     public function whereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereInt($left_expression, int $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // Float
//     //----------------------------------------------
//     public function whereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereFloat($left_expression, float $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // FloatFromString
//     //----------------------------------------------
//     public function whereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereFromString($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // FloatFromString
//     //----------------------------------------------
//     public function whereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereStringLiteral($left_expression, string $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;

//     //----------------------------------------------
//     // Time
//     //----------------------------------------------
//     public function whereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function andWhereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
//     public function orWhereTime($left_expression, \DateTime $condition, ?string $operator = WhereClause::OP_EQ): WhereClausePropertyInterface;
}
