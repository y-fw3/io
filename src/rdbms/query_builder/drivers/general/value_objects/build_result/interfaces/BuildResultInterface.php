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
 * query_builder名前空間隷下の"ビルド結果"値オブジェクト特性インターフェース
 */
interface BuildResultInterface
{
    /**
     * 句を返します。
     *
     * @return  string  句
     */
    public function getClause(): string;

    /**
     * 検索条件値を返します。
     *
     * @return  array   検索条件値
     */
    public function getConditions(): array;

    /**
     * 挿入・変更値を返します。
     *
     * @return  array   挿入・変更値
     */
    public function getValues(): array;

    /**
     * ビルド結果の配列表現を返します。
     *
     * @return  array   ビルド結果の配列表現
     */
    public function toArray(): array;
}
