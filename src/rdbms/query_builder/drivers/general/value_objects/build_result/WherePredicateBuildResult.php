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
 * query_builder名前空間隷下の"Where述部ビルド結果"値オブジェクト
 */
class WherePredicateBuildResult implements BuildResultInterface
{
    use BuildResultTrait;

    /**
     * @var string  前方要素に対する論理演算子
     */
    protected string $logicalOperator   = '';

    /**
     * factory
     *
     * @param   string  $clause     句
     * @param   array   $conditions 検索条件値
     * @param   array   $values     挿入・変更値
     * @return  WherePredicateBuildResult   ビルド結果
     */
    public static function factory(string $clause, array $conditions, array $values, string $logical_operator): WherePredicateBuildResult
    {
        $instance   = new static();

        $instance->clause           = $clause;
        $instance->conditions       = $conditions;
        $instance->values           = $values;
        $instance->logicalOperator  = $logical_operator;

        return $instance;
    }

    /**
     * 前方要素に対する論理演算子を返します。
     *
     * @return  string  前方要素に対する論理演算子
     */
    public function getLogicalOperator(): string
    {
        return $this->logicalOperator;
    }

    /**
     * 外部から与えられた要素にこのビルド結果が持つ値をマージして返します。
     *
     * @param   array   $clause_stack   句スタック
     * @param   array   $conditions     検索条件値
     * @param   array   $values         挿入・変更値
     * @return  array   マージ後のビルド結果の値を持つ配列
     */
    public function merge(array $clause_stack, array $conditions, array $values): array
    {
        if (!empty($clause_stack)) {
            $clause_stack[]  = $this->logicalOperator;
        }
        $clause_stack[]  = $this->clause;

        return [
            'clause_stack'      => $clause_stack,
            'conditions'        => array_merge($conditions, $this->conditions),
            'values'            => array_merge($values, $this->values),
        ];
    }

    /**
     * ビルド結果の配列表現を返します。
     *
     * @return  array   ビルド結果の配列表現
     */
    public function toArray(): array
    {
        return [
            'clause'            => $this->clause,
            'conditions'        => $this->conditions,
            'values'            => $this->values,
            'logical_operator'  => $this->logicalOperator,
        ];
    }
}
