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
use fw3\io\rdbms\query_builder\drivers\general\factorys\expressions\ColumnExpressionFactory;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAlias;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\statements\InsertStatement;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * InsertColumnコレクション
 */
class InsertColumnCollection implements
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
     * @return  InsertColumnCollection Whereコレクション
     */
    public static function factory(...$arguments): InsertColumnCollection
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
     * カラムを追加します。
     *
     * @param   string  $column カラム
     * @return  InsertColumnCollection
     */
    public function column($column): InsertColumnCollection
    {
        if (is_string($column)) {
            $this->collection[] = ColumnExpressionFactory::column($column)->parentReference($this);
            return $this;
        }

        if (is_object($column)) {
            if ($column instanceof InsertStatement) {
                $this->collection = $this->merge($column->columnCollection());
                return $this;
            }

            if ($column instanceof InsertColumnCollection) {
                $this->collection = $this->merge($column->collection());
                return $this;
            }

            if ($column instanceof ColumnExpression) {
                $this->collection[] = $column->withParentReference($this);
                return $this;
            }
        }

        UnavailableVarException::raise('使用できない型を与えられました。', ['column' => $column]);
    }

    /**
     * INSERT文用のcolumnをまとめて設定、追加します。
     *
     * @param   array   ...$column カラム
     * @return  \fw3\io\rdbms\query_builder\drivers\general\collections\InsertColumnCollection|\fw3\io\rdbms\query_builder\drivers\general\traits\query\collections\CollectionInterface
     */
    public function columns(...$columns): InsertColumnCollection
    {
        foreach (isset($columns[1]) ? $columns : (array) $columns[0] as $column) {
            $this->column($column);
        }
        return $this;
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

        if (empty($this->collection)) {
            return BuildResult::factory('', $conditions, $values);
        }

        foreach ($this->collection as $column) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $column->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(', ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
