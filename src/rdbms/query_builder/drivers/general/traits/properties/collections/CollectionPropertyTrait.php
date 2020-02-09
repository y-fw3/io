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
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * コレクションプロパティ特性
 */
trait CollectionPropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ?CollectionInterface    コレクション
     */
    protected ?CollectionInterface  $collection = null;

    //==============================================
    // property access
    //==============================================
    /**
     * コレクションを取得・設定します。
     *
     * @param   null|CollectionInterface|CollectionPropertyInterface    $collection コレクション
     * @return  CollectionInterface|CollectionPropertyInterface         コレクションまたはこのインスタンス
     */
    public function collection($collection = null)
    {
        if (is_null($collection) && func_num_args() === 0) {
            return $this->collection;
        }

        if ($collection instanceof CollectionInterface) {
            $this->collection = $collection->withParentReference($this);
            return $this;
        }

        if ($collection instanceof CollectionPropertyInterface) {
            $this->collection = $collection->collection()->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型のコレクションを与えられました。', ['collection' => $collection]);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * コレクションをマージします。
     *
     * @param   CollectionPropertyInterface $instance
     * @return  CollectionPropertyInterface このインスタンス
     */
    public function collectionMerge(CollectionPropertyInterface $instance): CollectionPropertyInterface
    {
        $this->collection->merge($instance->collection());
        return $this;
    }

    /**
     * 指定したオフセットの値を取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed       オフセットの値
     */
    public function get($offset)
    {
        return $this->collection->get($offset);
    }

    /**
     * 指定したオフセットにの値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed       $value  オフセットの値
     * @return  CollectionPropertyInterface このインスタンス
     */
    public function set($offset, $value): CollectionPropertyInterface
    {
        $this->collection->set($offset, $value);
        return $this;
    }

    /**
     * コレクションの先頭要素を取得します。
     *
     * @return  mixed   コレクションの先頭要素
     */
    public function first()
    {
        return $this->collection->first();
    }

    /**
     * コレクションの最終要素を取得します。
     *
     * @return  mixed   コレクションの最終要素
     */
    public function last()
    {
        return $this->collection->last();
    }

    /**
     * コレクション要素数が0かどうかを返します。
     *
     * @return  bool    コレクション要素数が0の場合はtrue
     */
    public function hasEmpty(): bool
    {
        return $this->collection->hasEmpty();
    }

    //==============================================
    // implement
    //==============================================
    // \ArrayAccess
    //----------------------------------------------
    /**
     * オフセットが存在するかどうかを返します。
     *
     * @param   int|string  $offset オフセット
     * @return  bool                オフセットが存在する場合はtrue、そうでない場合はfalse
     */
    public function offsetExists($offset): bool
    {
        return $this->collection->offsetExists();
    }

    /**
     * オフセットを取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed       オフセットの値
     */
    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    /**
     * 指定したオフセットに値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed       $value  オフセットの値
     */
    public function offsetSet($offset, $value): void
    {
        $this->collection->offsetSet($offset, $value);
    }

    /**
     * オフセットの設定を解除します。
     *
     * @param   int|string  $offset オフセット
     */
    public function offsetUnset($offset): void
    {
        $this->collection->offsetUnset($offset);
    }

    //----------------------------------------------
    // \Countable
    //----------------------------------------------
    /**
     * オブジェクトの要素数を返します。
     *
     * @return  int     オブジェクトの要素数
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    //----------------------------------------------
    // \IteratorAggregate
    //----------------------------------------------
    /**
     * 外部イテレータを返します。
     *
     * @return  \Traversable    外部イテレータを返します
     */
    public function getIterator(): \Traversable
    {
        return $this->collection->getIterator();
    }
}
