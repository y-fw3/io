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

use fw3\io\rdbms\query_builder\drivers\general\expressions\RawExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\expressions\ExpressionFactoryTrait;

/**
 * Raw式ファクトリ
 */
abstract class RawExpressionFactory implements ExpressionFactoryInterface
{
    use ExpressionFactoryTrait;

    /**
     * factory
     *
     * @param   array   ...$arguments   初期化引数
     * @return  RawExpression           Raw式
     */
    public static function factory(...$arguments): RawExpressionFactory
    {
        return RawExpressionFactory::factory(...$arguments);
    }

    /**
     * Raw式を生成し返します。
     *
     * @param   string      $clause     生の文字列として扱う句
     * @param   null|array  $conditions 検索条件値
     * @param   null|array  $values     挿入・変更値
     * @return  RawExpression   Raw式
     */
    public static function raw(string $clause, ?array $conditions = null, ?array $values = null): RawExpression
    {
        return RawExpression::factory()->raw($clause, $conditions, $values);
    }

    /**
     * 生の文字列として扱う句を設定したRaw式を生成して返します。
     *
     * @param   string  $clause 生の文字列として扱う句
     * @return  RawExpression   Raw式
     */
    public static function clause(string $clause): RawExpression
    {
        return RawExpression::factory()->clause($clause);
    }

    /**
     * 検索条件値を設定したRaw式を生成して返します。
     *
     * @param   array   $conditions 検索条件値
     * @return  RawExpression   Raw式
     */
    public function conditions(array $conditions): RawExpression
    {
        return RawExpression::factory()->conditions($conditions);
    }

    /**
     * 挿入・変更値を設定したRaw式を生成して返します。
     *
     * @param   array   $values 挿入・変更値
     * @return  RawExpression   Raw式
     */
    public function values(array $values = null): RawExpression
    {
        return RawExpression::factory()->values($values);
    }
}
