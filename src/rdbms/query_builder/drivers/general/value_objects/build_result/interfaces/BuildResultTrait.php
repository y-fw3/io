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

namespace fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces;

/**
 * query_builder名前空間隷下の"ビルド結果"値オブジェクト特性
 */
trait BuildResultTrait
{
    /**
     * @var string 句
     */
    protected string $clause    = '';

    /**
     * @var array   検索条件値
     */
    protected array $conditions = [];

    /**
     * @var array   挿入・変更値
     */
    protected array $values     = [];

    /**
     * 句を返します。
     *
     * @return  string  句
     */
    public function getClause(): string
    {
        return $this->clause;
    }

    /**
     * 検索条件値を返します。
     *
     * @return  array   検索条件値
     */
    public function getConditions(): array
    {
        return $this->conditions;
    }

    /**
     * 挿入・変更値を返します。
     *
     * @return  array   挿入・変更値
     */
    public function getValues(): array
    {
        return $this->values;
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
        ];
    }
}
