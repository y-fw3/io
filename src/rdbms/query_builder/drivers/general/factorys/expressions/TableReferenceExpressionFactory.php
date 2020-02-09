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

use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;

/**
 * テーブル参照式ファクトリ
 */
abstract class TableReferenceExpressionFactory implements ExpressionFactoryInterface
{
    use ExpressionFactoryTrait;

    const INSTANCE_CLASS_PATH   = TableReferenceExpression::class;

    /**
     * factory
     *
     * @param   array   ...$arguments       初期化引数
     * @return  TableReferenceExpression    テーブル参照式
     */
    public static function factory(...$arguments): TableReferenceExpression
    {
        return TableReferenceExpression::factory(...$arguments);
    }

    /**
     * テーブル参照名を設定したテーブル参照式を返します。
     *
     * @param   string|TableReferenceExpression|TablePropertyInterface  $name   テーブル参照名
     * @return  TableReferenceExpression    テーブル参照式
     */
    public static function name($name): TableReferenceExpression
    {
        return TableReferenceExpression::factory()->name($name);
    }

    /**
     * テーブル参照別名を設定したテーブル参照式を返します。
     *
     * @param   string|TableReferenceExpression|TablePropertyInterface  $alias  テーブル参照別名
     * @return  TableReferenceExpression    テーブル参照式
     */
    public static function alias($alias): TableReferenceExpression
    {
        return TableReferenceExpression::factory()->alias($alias);
    }
}
