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
use fw3\io\rdbms\query_builder\drivers\general\functions\traits\FunctionInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Groupコレクション
 */
class GroupCollection implements
    CollectionInterface
{
    use CollectionTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // factory
    //==============================================
    /**
     * Whereコレクションを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  JoinCollection Whereコレクション
     */
    public static function factory(...$arguments): GroupCollection
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
     * ORDER BY句を設定します。
     *
     * @param   mixed   $column
     * @param   ?string $sort_group
     * @return  GroupCollection
     */
    public function group($column, $table = null): GroupCollection
    {
        if (is_string($column)) {
            $column = ColumnExpression::factory()->column($column)->parentReference($this);
        } else {
            $column = $column->withParentReference($this);
        }

        if (!is_null($table)) {
            $column->table($table);
        }

        $this->collection[] = $column;
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

        foreach ($this->collection as $Group) {
            if ($Group instanceof FunctionInterface) {
                $clause_stack[] = sprintf('`%s`', $Group->alias());
            } else {
                ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $Group->build()->merge($clause_stack, $conditions, $values);
            }
        }

        $clause = implode(', ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
