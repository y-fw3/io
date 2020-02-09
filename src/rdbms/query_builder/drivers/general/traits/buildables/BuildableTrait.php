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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\buildables;

/**
 * ビルダブル特性
 */
trait BuildableTrait
{
    //==============================================
    // with
    //==============================================
    /**
     * このインスタンスをクローンして返します。
     *
     * @return  Buildable   クローンしたこのインスタンス
     */
    public function with(): Buildable
    {
        return clone $this;
    }

    /**
     * ビルド結果からclauseを返します。
     *
     * @return  string  clause
     */
    public function getClause(): string
    {
        return $this->build()->getClause();
    }

    /**
     * ビルド結果からconditionsを返します。
     *
     * @return  array   conditions
     */
    public function getConditions(): array
    {
        return $this->build()->getConditions();
    }

    /**
     * ビルド結果からvaluesを返します。
     *
     * @return  array   values
     */
    public function getValues(): array
    {
        return $this->build()->getValues();
    }
}
