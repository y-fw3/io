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

namespace fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result;

use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultTrait;

/**
 * query_builder名前空間隷下の"ビルド結果"値オブジェクト
 */
class BuildResult implements BuildResultInterface
{
    use BuildResultTrait;

    /**
     * factory
     *
     * @param   string  $clause     句
     * @param   array   $conditions 検索条件値
     * @param   array   $values     挿入・変更値
     * @return  \fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult  ビルド結果
     */
    public static function factory(string $clause, array $conditions, array $values): BuildResult
    {
        $instance   = new static();

        $instance->clause           = $clause;
        $instance->conditions       = $conditions;
        $instance->values           = $values;

        return $instance;
    }

    /**
     * 外部から与えられた要素にこのビルド結果が持つ値をマージして返します。
     *
     * @param   array   $clause_stack   句スタック
     * @param   array   $conditions     検索条件値
     * @param   array   $values         挿入・変更値
     * @return  array   マージ後のビルド結果の値を持つ配列
     */
    public function merge(array $clause_stack, array $conditions, array $values, ?string $format = null): array
    {
        $clause_stack[]  = is_null($format) ? $this->clause : sprintf($format, $this->clause);

        return [
            'clause_stack'      => $clause_stack,
            'conditions'        => array_merge($conditions, $this->conditions),
            'values'            => array_merge($values, $this->values),
        ];
    }
}
