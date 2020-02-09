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

use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\factory_methods\FactoryMethodTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;

/**
 * コレクション特性
 */
trait CollectionTrait
{
    use BuildableTrait;
    use ParentReferencePropertyTrait;
    use TablePropertyTrait;
    use FactoryMethodTrait;

    //==============================================
    // property
    //==============================================
    /**
     * @var array   コレクション
     */
    protected array $collection = [];

    //==============================================
    // factory
    //==============================================
    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  CollectionInterface コレクション
     */
    public static function factory(...$arguments): CollectionInterface
    {
        return new static();
    }

    //==============================================
    // feature
    //==============================================
    /**
     * コレクションを取得・設定します。
     *
     * @param   null|array|CollectionInterface  $collection コレクション
     * @return  array|CollectionInterface   コレクション
     */
    public function collection($collection = null)
    {
        if (is_null($collection) && func_num_args() === 0) {
            return $this->collection;
        }

        if (is_array($collection)) {
            $this->collection = $collection;
            return $this;
        }

        if ($collection instanceof CollectionInterface) {
            $this->collection = $collection->collection();
            return $this;
        }

        return isset($this->collection[$collection]) ? $this->collection[$collection] : null;
    }

    /**
     * 指定されたコレクションをマージします。
     *
     * @param   CollectionInterface $instance   マージするコレクション
     * @return  CollectionInterface マージした後のこのインスタンス
     */
    public function merge(CollectionInterface $instance): CollectionInterface
    {
        foreach ($instance->collection() as $collection) {
            $this->collection[] = $collection->withParentReference($this->parentReference);
        }
        return $this;
    }

    /**
     * 指定したオフセットの値を取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed   オフセットの値
     */
    public function get($offset)
    {
        return $this->collection[$offset] ?? null;
    }

    /**
     * 指定したオフセットにの値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed   オフセットの値
     * @return  CollectionInterface このインスタンス
     */
    public function set($offset, $value): CollectionInterface
    {
        $this->collection[$offset] = $value;
        return $this;
    }

    /**
     * コレクションの先頭要素を取得します。
     */
    public function first()
    {
        $collection = $this->collection;
        reset($collection);
        return $this->collection[key($collection)] ?? null;
    }

    /**
     * コレクションの最終要素を取得します。
     */
    public function last()
    {
        $collection = $this->collection;
        end($collection);
        return $this->collection[key($collection)] ?? null;
    }

    /**
     * コレクション要素数が0かどうかを返します。
     *
     * @return  bool    コレクション要素数が0の場合はtrue
     */
    public function hasEmpty(): bool
    {
        return empty($this->collection);
    }

    //==============================================
    // implement
    //==============================================
    // \ArrayAccess
    //----------------------------------------------
    /**
     * オフセットが存在するかどうかを返します。
     *
     * @param   bool    $offset オフセットが存在する場合はtrue、そうでない場合はfalse
     */
    public function offsetExists($offset): bool
    {
        return isset($this->collection[$offset]) && array_keys($offset, $this->collection);
    }

    /**
     * オフセットを取得します。
     *
     * @param   int|string  $offset オフセット
     * @return  mixed   オフセットの値
     */
    public function offsetGet($offset)
    {
        return $this->collection[$offset];
    }

    /**
     * 指定したオフセットに値を設定します。
     *
     * @param   int|string  $offset オフセット
     * @param   mixed   $value  オフセットの値
     */
    public function offsetSet($offset, $value): void
    {
        $this->collection[$offset] = $value;
    }

    /**
     * オフセットの設定を解除します。
     *
     * @param   int|string  $offset オフセット
     */
    public function offsetUnset($offset): void
    {
        unset($this->collection[$offset]);
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
        return \count($this->collection);
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
        return new \ArrayIterator($this->collection);
    }
}
