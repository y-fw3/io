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

namespace fw3\io\rdbms\query_builder\drivers\general\collections;

use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionTrait;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAlias;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;
use fw3\io\rdbms\query_builder\drivers\general\statements\InsertStatement;
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * InsertValueコレクション
 */
class InsertValueCollection implements
    CollectionInterface,
    // type extends
    ChildrenDoNotUseAlias,
    ChildrenDoNotUseAliasKeyword
{
    use CollectionTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // facade
    //==============================================
    /**
     * Whereコレクションを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  InsertValueCollection Whereコレクション
     */
    public static function factory(...$arguments): InsertValueCollection
    {
        return new static();
    }

    //==============================================
    // property access shortcut
    //==============================================

    //==============================================
    // feature
    //==============================================
    /**
     * 値を追加します。
     *
     * @param   string  $value  値
     * @return  \fw3\io\rdbms\query_builder\drivers\general\collections\InsertValueCollection|\fw3\io\rdbms\query_builder\drivers\general\traits\query\collections\CollectionInterface
     */
    public function value($value): InsertValueCollection
    {
        if (is_object($value)) {
            if ($value instanceof InsertStatement) {
                $this->collection  = $this->merge($value->valueCollection());
                return $this;
            }

            if ($value instanceof InsertValueCollection) {
                $this->collection  = $this->merge($value->collection());
                return $this;
            }

            if ($value instanceof ColumnExpression) {
                $this->collection[] = $value->withParentReference($this);
                return $this;
            }
        }

        $this->collection[] = $value;
        return $this;
    }

    /**
     * INSERT文用のcolumnをまとめて設定、追加します。
     *
     * @param   array   ...$column カラム
     * @return  \fw3\io\rdbms\query_builder\drivers\general\collections\InsertValueCollection|\fw3\io\rdbms\query_builder\drivers\general\traits\query\collections\CollectionInterface
     */
    public function values(...$values): InsertValueCollection
    {
        foreach (isset($values[1]) ? $values : (array) $values[0] as $value) {
            $this->value($value);
        }
        return $this;
    }

    public function isOnlySubQuery(): bool
    {
        if (isset($this->collection[1])) {
            return false;
        }

        $value = $this->collection[0];
        if ($value instanceof SelectStatement) {
            return true;
        }

        if ($value instanceof SubQuery) {
            return true;
        }

        return false;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResult ビルド結果
     */
    public function build(): BuildResultInterface
    {
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [];

        foreach ($this->collection as $value) {
            if ($value instanceof Buildable) {
                ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $value->build()->merge($clause_stack, $conditions, $values);
            } else {
                $clause_stack[] = '?';
                $values[]       = $value;
            }
        }

        $clause = implode(', ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
