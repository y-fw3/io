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
use fw3\io\rdbms\query_builder\drivers\general\statements\SelectStatement;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * SelectColumnコレクション
 */
class SelectColumnCollection implements
    CollectionInterface
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
     * @return  SelectColumnCollection Whereコレクション
     */
    public static function factory(...$arguments): SelectColumnCollection
    {
        return new static();
    }

    //==============================================
    // property access shortcut
    //==============================================

    //==============================================
    // feature
    //==============================================
    public function column($column, $alias = null, $table = null)
    {
        if (is_object($column)) {
            if ($column instanceof SelectStatement) {
                return $this->merge($column->collection());
            }

            if ($column instanceof SelectColumnCollection) {
                return $this->merge($column->collection());
            }

            if ($column instanceof ParentReferencePropertyInterface) {
                $this->collection[] = $column->withParentReference($this);
                return $this;
            }
        }

        $this->collection[] = ColumnExpression::factory()->column($column, $alias, $table)->parentReference($this);
        return $this;
    }

    public function columns(...$columns)
    {
        foreach (isset($columns[1]) ? $columns : (array) $columns[0] as $column) {
            if (is_object($column)) {
                $this->column($column);
            } else {
                $this->column(...((array) $column));
            }
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

        $collection = empty($this->collection) ? [ColumnExpressionFactory::column('*')->parentReference($this)] : $this->collection;
        foreach ($collection as $column) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $column->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(', ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
