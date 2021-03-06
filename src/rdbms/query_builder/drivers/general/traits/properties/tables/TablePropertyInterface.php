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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables;

use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeTable;

/**
 * テーブル参照プロパティ特性インターフェース
 */
interface TablePropertyInterface extends UnionTypeTable
{
    //==============================================
    // property access
    //==============================================
    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|UnionTypeTable  $table  テーブル
     * @param   null|string|UnionTypeTable  $alias  テーブル別名
     * @return  static|UnionTypeTable       テーブルまたはこのインスタンス
     */
    public function table($table = null, $alias = null);

    //==============================================
    // clone
    //==============================================
    /**
     * このインスタンスを複製し、テーブルを設定して返します。
     *
     * @param   null|string|UnionTypeTable  $table  テーブル
     * @param   null|string|UnionTypeTable  $alias  テーブル別名
     * @return  static                      複製しテーブルを設定したこのインスタンス
     */
    public function withTable($table, $alias = null): TablePropertyInterface;
}
