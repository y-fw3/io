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

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\expressions;

use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryTrait;

/**
 * カラム式ファクトリ
 */
abstract class ColumnExpressionFactory implements ExpressionFactoryInterface
{
    use ExpressionFactoryTrait;

    /**
     * factory
     *
     * @param   array   ...$arguments   初期化引数
     * @return  ColumnExpression        テーブル参照式
     */
    public static function factory(...$arguments): ColumnExpression
    {
        return ColumnExpression::factory(...$arguments);
    }

    /**
     * Column式を生成し返します。
     *
     * @param   string|ColumnExpression         $column カラム名
     * @param   null|string|ColumnExpression    $alias  カラム別名
     * @param   null|string|ColumnExpression|\fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface $table  テーブル参照
     * @return  ColumnExpression    Column式
     */
    public static function column($column, $alias = null, $table = null): ColumnExpression
    {
        if (is_null($alias) && func_num_args() === 0) {
            return ColumnExpression::factory()->column($column);
        }
        return ColumnExpression::factory()->column($column, $alias, $table);
    }

    /**
     * カラム名を設定したColumn式を生成し返します。
     *
     * @param   string  $name   カラム名
     * @return  ColumnExpression    Column式
     */
    public static function name(string $name): ColumnExpression
    {
        return ColumnExpression::factory()->name($name);
    }

    /**
     * カラム別名を設定したColumn式を生成し返します。
     *
     * @param   string  $alias      カラム別名
     * @return  ColumnExpression    Column式
     */
    public function alias(string $alias): ColumnExpression
    {
        return ColumnExpression::factory()->alias($alias);
    }
}
