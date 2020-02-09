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

namespace fw3\io\rdbms\query_builder\drivers\general;

use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\expressions\CaseExpression;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\expressions\RawExpression;
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\io\rdbms\query_builder\drivers\general\factorys\DeleteQueryBuilder;
use fw3\io\rdbms\query_builder\drivers\general\factorys\InsertQueryBuilder;
use fw3\io\rdbms\query_builder\drivers\general\factorys\SelectQueryBuilder;
use fw3\io\rdbms\query_builder\drivers\general\factorys\UpdateQueryBuilder;
use fw3\io\rdbms\query_builder\drivers\general\factorys\clauses\WhereClauseFactory;
use fw3\io\rdbms\query_builder\drivers\general\factorys\expressions\ColumnExpressionFactory;
use fw3\io\rdbms\query_builder\drivers\general\factorys\expressions\RawExpressionFactory;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\IndexHintConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\JoinConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\SortOrderConst;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralBool;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDate;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralDefault;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNull;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralNumber;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralString;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTime;
use fw3\io\rdbms\query_builder\drivers\general\literals\LiteralTimestamp;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;
use fw3\io\rdbms\query_builder\drivers\general\statements\DeleteStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\InsertStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\UpdateStatement;

/**
 * Query Factory
 */
abstract class Query implements
    // Const
    ComparisonOperatorConst,
    IndexHintConst,
    JoinConst,
    LogicalOperatorConst,
    SortOrderConst
{
    //==============================================
    // RDBMS
    //==============================================
    /**
     * MySQL用のクエリビルダクラスパスを返します。
     */
    public static function mysql()
    {
        return Query::class;
    }

    //==============================================
    // 文
    //==============================================
    /**
     * Insert文を生成して返します。
     *
     * @return  \fw3\io\rdbms\query_builder\drivers\general\statements\InsertStatement  Insert文
     */
    public static function insert(...$arguments): InsertStatement
    {
        return InsertQueryBuilder::factory(...$arguments);
    }

    /**
     * Select文を生成して返します。
     *
     * @return  \fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement  Select文
     */
    public static function select(...$arguments): SelectStatement
    {
        return SelectQueryBuilder::factory(...$arguments);
    }

    /**
     * SubQuery展開用文を生成して返します。
     *
     * @return  SubQuery    SubQuery展開用文
     */
    public static function subQuery(): SubQuery
    {
        return SubQuery::factory();
    }

    /**
     * Update文を生成して返します。
     *
     * @return  \fw3\io\rdbms\query_builder\drivers\general\statements\UpdateStatement  Select文
     */
    public static function update(...$arguments): UpdateStatement
    {
        return UpdateQueryBuilder::factory(...$arguments);
    }

    /**
     * Delete文を生成して返します。
     *
     * @return  \fw3\io\rdbms\query_builder\drivers\general\statements\DeleteStatement  Delete文
     */
    public static function delete(...$arguments): DeleteStatement
    {
        return DeleteQueryBuilder::factory(...$arguments);
    }

    //==============================================
    // 句
    //==============================================
    public static function from()
    {
    }

    /**
     * Where句を生成して返します。
     *
     * @param   string|WhereClause|WhereCollection|ExpressionInterface  $left_expression    左辺式
     * @param   $right_expression   左辺式
     * @param   string              $operator   比較演算子
     * @return  \fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause Where句
     */
    public static function where($left_expression, $right_expression = null, ?string $operator = WhereClause::OP_EQ): WhereClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            return WhereClauseFactory::where($left_expression);
        }
        return WhereClauseFactory::where($left_expression, $right_expression, $operator);
    }

    //==============================================
    // 式
    //==============================================
    /**
     * テーブル参照を生成して返します。
     *
     * @param   $name
     * @param   $alias
     * @return  TableReferenceExpression    テーブル参照
     */
    public static function table($name, $alias = null): TableReferenceExpression
    {
        if (is_null($alias) && func_num_args() === 0) {
            return TableReferenceExpression::factory()->table($name);
        }
        return TableReferenceExpression::factory()->table($name, $alias);
    }

    /**
     * Column式を生成して返します。
     *
     * @param   $name
     * @param   $alias
     * @param   $table
     * @return  \fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression    カラム式
     */
    public static function column($name, $alias = null, $table = null): ColumnExpression
    {
        if (is_null($alias) && func_num_args() === 0) {
            return ColumnExpressionFactory::column($name);
        }
        return ColumnExpression::factory()->column($name, $alias, $table);
    }

    /**
     * Raw式を生成して返します。
     *
     * @param   string      $clause
     * @param   null|array  $conditions
     * @param   null|array  $values
     * @return  RawExpression
     */
    public static function raw(string $clause, ?array $conditions = [], ?array $values = []): RawExpression
    {
        if (is_null($conditions) && func_num_args() === 0) {
            return RawExpressionFactory::column($clause);
        }
        return RawExpressionFactory::raw($clause, $conditions, $values);
    }

    public static function case($case_value = null): CaseExpression
    {
        $caseExpression = CaseExpression::factory();
        if (is_null($case_value) && func_num_args() === 0) {
            return $caseExpression;
        }
        return $caseExpression->caseValue($case_value);
    }

    //==============================================
    // 述部
    //==============================================

    //==============================================
    // Literal
    //==============================================
    /**
     * 審議値リテラルを返します。
     *
     * @param   bool|LiteralBool    $value  真偽値
     * @return  LiteralBool 真偽値リテラル
     */
    public static function bool(bool $value): LiteralBool
    {
        return LiteralBool::factory($value);
    }

    /**
     * 日付リテラルを返します。
     *
     * @param   string|int|float|LiteralDate    $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone            $timezone   タイムゾーン
     * @return  LiteralDate 日付リテラル
     */
    public static function date($timestamp = 'now', $timezone = null): LiteralDate
    {
        return LiteralDate::factory($timestamp, $timezone);
    }

    /**
     * default リテラルを返します。
     *
     * @return  LiteralDefault  default リテラル
     */
    public static function default(): LiteralDefault
    {
        return LiteralDefault::factory();
    }

    /**
     * NULLリテラルを返します。
     *
     * @return  LiteralNull NULLリテラル
     */
    public static function null(): LiteralNull
    {
        return LiteralNull::factory();
    }

    /**
     * 数値リテラルを返します。
     *
     * @param   int|float|LiteralNumber 数値
     * @return  LiteralNumber           数値リテラル
     */
    public static function number($value): LiteralNumber
    {
        return LiteralNumber::factory($value);
    }

    /**
     * 文字列リテラルを返します。
     *
     * @param   string      $string     文字列
     * @param   null|string $encoding   有効な文字列エンコーディング
     * @return  LiteralString   文字列リテラル
     */
    public static function string($string, $encoding = null)
    {
        return LiteralString::factory($string, $encoding);
    }

    /**
     * 時間リテラルを返します。
     *
     * @param   string|int|float|LiteralTime    $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone            $timezone   タイムゾーン
     * @return  LiteralTime 時間リテラル
     */
    public static function time($timestamp = 'now', $timezone = null): LiteralTime
    {
        return LiteralTime::factory($timestamp, $timezone);
    }

    /**
     * タイムスタンプリテラルを返します。
     *
     * @param   string|int|float|LiteralTimestamp   $timestamp  タイムスタンプ
     * @param   string|\DateTimeZone                $timezone   タイムゾーン
     * @return  LiteralTimestamp    タイムスタンプリテラル
     */
    public static function timestamp($timestamp = 'now', $timezone = null): LiteralTimestamp
    {
        return LiteralTimestamp::factory($timestamp, $timezone);
    }
}
