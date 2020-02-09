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

namespace fw3\io\rdbms\query_builder\drivers\general\collections\traits;

use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;

/**
 * コレクションインターフェース
 */
interface CollectionInterface extends
    \ArrayAccess,
    \Countable,
    \IteratorAggregate,
    Buildable,
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
    //==============================================
    // factory
    //==============================================
    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  CollectionInterface コレクション
     */
    public static function factory(...$arguments): CollectionInterface;

    //==============================================
    // feature
    //==============================================
    /**
     * コレクションを取得・設定します。
     *
     * @param   null|array|CollectionInterface  $collection コレクション
     * @return  array|CollectionInterface   コレクション
     */
    public function collection($collection = null);

    /**
     * 指定したオフセットの値を取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed   オフセットの値
     */
    public function get($offset);

    /**
     * 指定したオフセットにの値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed   オフセットの値
     * @return  CollectionInterface このインスタンス
     */
    public function set($offset, $value): CollectionInterface;

    /**
     * コレクションの先頭要素を取得します。
     *
     * @return  mixed   コレクションの先頭要素
     */
    public function first();

    /**
     * コレクションの最終要素を取得します。
     *
     * @return  mixed   コレクションの最終要素
     */
    public function last();

    /**
     * コレクション要素数が0かどうかを返します。
     *
     * @return  bool    コレクション要素数が0の場合はtrue
     */
    public function hasEmpty(): bool;
}
