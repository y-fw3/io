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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections;

use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;

/**
 * コレクションプロパティ特性インターフェース
 */
interface CollectionPropertyInterface extends
    \ArrayAccess,
    \Countable,
    \IteratorAggregate
{
    //==============================================
    // property access
    //==============================================
    /**
     * コレクションを取得・設定します。
     *
     * @param   null|CollectionInterface|CollectionPropertyInterface    $collection コレクション
     * @return  CollectionInterface|CollectionPropertyInterface         コレクションまたはこのインスタンス
     */
    public function collection($collection = null);

    //==============================================
    // feature
    //==============================================
    /**
     * コレクションをマージします。
     *
     * @param   CollectionPropertyInterface $instance
     * @return  CollectionPropertyInterface このインスタンス
     */
    public function collectionMerge(CollectionPropertyInterface $instance): CollectionPropertyInterface;

    /**
     * 指定したオフセットの値を取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed       オフセットの値
     */
    public function get($offset);

    /**
     * 指定したオフセットにの値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed       $value  オフセットの値
     * @return  CollectionPropertyInterface このインスタンス
     */
    public function set($offset, $value): CollectionPropertyInterface;

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
