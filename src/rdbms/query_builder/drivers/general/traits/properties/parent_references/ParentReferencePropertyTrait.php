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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references;

use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAlias;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoUsePlaceholder;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\utilitys\vars\Strings;

/**
 * 親オブジェクト参照プロパティ特性
 */
trait ParentReferencePropertyTrait
{
    //==============================================
    // property
    //==============================================
    /**
     * @var ParentReferencePropertyInterface    親オブジェクト参照特定を持つ親オブジェクト
     */
    protected ?ParentReferencePropertyInterface $parentReference    = null;

    //==============================================
    // property access
    //==============================================
    /**
     * オブジェクト生成元への参照を取得・設定します。
     *
     * @param   object|null オブジェクト生成元への参照
     * @return  ParentReferencePropertyTrait|object オブジェクト生成元への参照またはこのインスタンス
     */
    public function parentReference(?ParentReferencePropertyInterface $parentReference = null): ?ParentReferencePropertyInterface
    {
        if (is_null($parentReference) && func_num_args() === 0) {
            return $this->parentReference;
        }

        $this->parentReference  = $parentReference;

        return $this;
    }

    //==============================================
    // feature
    //==============================================
    /**
     * 自身を含めた直近のテーブル参照を取得します。
     *
     * @return  TableReferenceExpression|null   自身を含めた直近のテーブル参照
     */
    public function closestTable(): ?TableReferenceExpression
    {
        if (!is_null($this->table)) {
            return $this->table;
        }

        for (
            $parentReference = $this->parentReference();
            $parentReference instanceof ParentReferencePropertyInterface;
            $parentReference = $parentReference->parentReference()
        ) {
            $table = $parentReference->table();
            if (!is_null($table)) {
                return $table;
            }
        }

        return null;
    }

    /**
     * 可能ならプレースホルダを使うべきかどうかを判定します。
     *
     * @return  bool    可能ならプレースホルダを使うべきな場合はtrue、そうでない場合はfalse
     */
    public function doUsePlaceholder(): bool
    {
        return $this->parentReference instanceof ChildrenDoUsePlaceholder;
    }

    /**
     * `alias`を使ってはならない親クラスの下にいるかどうかを判定します。
     *
     * @return  bool    `alias`を使ってはならない親クラスの下にいる場合はtrue、そうでない場合はfalse
     */
    public function doNotUseAlias(): bool
    {
        for (
            $parentReference = $this->parentReference();
            !is_null($parentReference);
            $parentReference = $parentReference->parentReference()
        ) {
            if ($parentReference instanceof ChildrenDoNotUseAlias) {
                return true;
            }
        }

        return false;
    }

    /**
     * `AS`を使ってはならない親クラスの下にいるかどうかを判定します。
     *
     * @return  bool    `AS`を使ってはならない親クラスの下にいる場合はtrue、そうでない場合はfalse
     */
    public function doNotUseAliasKeyword(): bool
    {
        for (
            $parentReference = $this->parentReference();
            !is_null($parentReference);
            $parentReference = $parentReference->parentReference()
        ) {
            if ($parentReference instanceof ChildrenDoNotUseAliasKeyword) {
                return true;
            }
        }

        return false;
    }

    //==============================================
    // clone
    //==============================================
    /**
     * オブジェクトのクローン作成
     */
    public function __clone()
    {
        $clone_properties   = static::CLONE_PROPERTIES;

        if ($this instanceof TablePropertyInterface) {
            $clone_properties[] = 'table';
        }

        if ($this instanceof CollectionPropertyInterface) {
            $clone_properties[] = 'collection';
        }

        $this->cloneProperties($clone_properties);
    }

    /**
     * このインスタンスを複製し、オブジェクト生成元への参照を設定して返します。
     *
     * @param   object|null $parentReference    オブジェクト生成元
     * @return  ParentReferencePropertyTrait    このインスタンスまたはオブジェクト生成元への参照
     */
    public function withParentReference(?object $parentReference): ParentReferencePropertyInterface
    {
        return (clone $this)->parentReference($parentReference);
    }

    /**
     * 指定されたプロパティを適切にクローニングします。
     *
     * @param   string[]    $property_name_list クローニング対象プロパティ
     */
    protected function cloneProperties($property_name_list): void
    {
        if ($this instanceof CollectionInterface) {
            foreach ($this->collection as $idx => $collection) {
                if ($collection instanceof ParentReferencePropertyInterface) {
                    $this->collection[$idx] = $collection->withParentReference($this);
                } elseif (is_object($collection)) {
                    $this->collection[$idx] = clone $collection;
                }
            }
        }

        foreach ($property_name_list as $property_name) {
            if ($property_name === 'connectionReference') {
                continue;
            }

            $property   = $this->{$property_name};
            if ($property instanceof ParentReferencePropertyInterface) {
                $this->{$property_name} = $property->withParentReference($this);
            } elseif (is_object($property)) {
                $this->{$property_name} = clone $property;
            }
        }
    }

    //==============================================
    // debuger
    //==============================================
    /**
     * 親子オブジェクト参照情報を返します。
     *
     * @return  array   親子オブジェクト参照情報
     */
    public function getFamilyReferenceInfomation($only_classname = false): array
    {
        $parent_id  = $only_classname && !is_null($this->parentReference) ? get_class($this->parentReference) : Strings::toText($this->parentReference);
        $myself_id  = $only_classname ? get_class($this) : Strings::toText($this);

        $tree   = [
            $parent_id => $myself_id,
        ];

        $properties = (new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PROTECTED);
        if (empty($properties)) {
            return $tree;
        }

        $tree[$parent_id] = [
            $myself_id  => [],
        ];

        foreach ($properties as $property) {
            $property->setAccessible(true);
            $name   = $property->getName();
            if ($name === 'parentReference') {
                continue;
            }

            $value  = $property->getValue($this);
            if ($value instanceof CollectionInterface) {
                $collection_parrent = sprintf('%s => %s', $myself_id, $only_classname ? get_class($value) : Strings::toText($value));
                $collections    = [];

                foreach ($value->getIterator() as $collection_idx => $collection_value) {
                    $collections[$collection_parrent][$collection_idx] = $collection_value->getFamilyReferenceTree($only_classname);
                }
                $value = $collections;
            } elseif ($value instanceof ParentReferencePropertyInterface) {
                $value = sprintf('%s => %s', $myself_id, $only_classname ? get_class($value) : Strings::toText($value));
            } else {
                continue;
            }

            $tree[$parent_id][$myself_id][$name] = $value;
        }

        return $tree;
    }

    /**
     * 詳細な親オブジェクト参照情報を返します。
     *
     * @param   bool    $text_mode  オブジェクトの表示情報をテキストのみとするかどうか
     * @return  array   親オブジェクト参照情報
     */
    public function getParentReferenceInfomationDetail($text_mode = false): array
    {
        $tree   = [
            'parent'    => $text_mode ? Strings::toText($this->parentReference) : $this->parentReference,
            'myself'    => $text_mode ? Strings::toText($this) : $this,
            'property'  => [],
        ];

        foreach ((new \ReflectionObject($this))->getProperties(\ReflectionProperty::IS_PROTECTED) as $property) {
            $property->setAccessible(true);

            $name   = $property->getName();
            if ($name === 'parentReference') {
                continue;
            }

            $value  = $property->getValue($this);
            if ($value instanceof CollectionInterface) {
                $collections    = [];
                foreach ($value->getIterator() as $collection_idx => $collection_value) {
                    $collections[$collection_idx] = $collection_value->getParentReferenceInfomationDetail($text_mode);
                }
                $value = $collections;
            } elseif ($value instanceof ParentReferencePropertyInterface) {
                $value = $value->getParentReferenceInfomationDetail($text_mode);
            } else {
                $value = $text_mode ? Strings::toText($value) : $value;
            }

            $tree['property'][$name] = $value;
        }

        return $tree;
    }

    /**
     * 親オブジェクト参照ツリーを返します。
     *
     * @param   bool    $only_class_path    クラスパスだけを返すかどうか
     * @return  array   親オブジェクト参照ツリー
     */
    public function getParentReferenceInfomation($only_class_path = false): array
    {
        $parentReference = $this->parentReference();
        if (is_null($parentReference)) {
            return [];
        }

        for (
            ;
            $parentReference instanceof ParentReferencePropertyInterface;
            $parentReference = $parentReference->parentReference()
        ) {
            $parent_reference_stack[] = $only_class_path ? get_class($parentReference) : $parentReference;
        }

        if (!is_null($parentReference)) {
            $parent_reference_stack[] = $only_class_path ? get_class($parentReference) : $parentReference;
        }

        return $parent_reference_stack;
    }
}
