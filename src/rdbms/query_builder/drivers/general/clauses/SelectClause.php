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

namespace fw3\io\rdbms\query_builder\drivers\general\clauses;

use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseInterface;
use fw3\io\rdbms\query_builder\drivers\general\clauses\traits\ClauseTrait;
use fw3\io\rdbms\query_builder\drivers\general\collections\SelectColumnCollection;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriable;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriableTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Select句
 *
 * @property    SelectColumnCollection  $collection
 */
class SelectClause implements
    Buildable,
    ClauseInterface,
    CollectionPropertyInterface,
    ComparisonOperatorConst,
    LogicalOperatorConst,
    ParentReferencePropertyInterface,
    SubQueriable,
    TablePropertyInterface
{
    use BuildableTrait;
    use ClauseTrait;
    use CollectionPropertyTrait;
    use ParentReferencePropertyTrait;
    use SubQueriableTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    /**
     * @var bool    値の重複を除去するかどうか。
     */
    protected bool $distinct    = false;

    /**
     * @var bool    DISTINCTを使用しない場合にALLを明示するかどうか。
     */
    protected bool $displayAll  = false;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->collection  = SelectColumnCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array           ...$arguments   引数
     * @return  SelectClause    Select文
     */
    public static function factory(...$arguments): SelectClause
    {
        return new static(...$arguments);
    }

    /**
     * 値の重複を除去するかどうかを取得・設定します。
     *
     * @param   bool    $distinct   値の重複を除去するかどうか
     * @return  bool|SelectClause   値の重複を除去するかどうかまたはこのインスタンス
     */
    public function distinct(bool $distinct = false)
    {
        if (false === $distinct && func_num_args() === 0) {
            return $this->distinct;
        }
        $this->distinct = $distinct;
        return $this;
    }

    /**
     * DISTINCTを使わない場合にALLを明示するかどうかを取得・設定します。
     *
     * @param   bool    $explain    EXPLAINを使用するかどうか
     * @return  bool|SelectClause   EXPLAINを使用するかどうかまたはこのインスタンス
     */
    public function displayAll(bool $displayAll = false)
    {
        if (false === $displayAll && func_num_args() === 0) {
            return $this->displayAll;
        }
        $this->displayAll   = $displayAll;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    /**
     *
     * @param   string $column
     * @param   string $alias
     * @param   string $table
     * @return  SelectClause
     */
    public function column($column, $alias = null, $table = null): SelectClause
    {
        $this->collection->column($column, $alias, $table);
        return $this;
    }

    /**
     *
     * @param   ...$columns
     * @return  SelectClause
     */
    public function columns(...$columns): SelectClause
    {
        $this->collection->columns(...$columns);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResultInterface    ビルド結果
     */
    public function build(): BuildResultInterface
    {
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [];

        if ($this->distinct) {
            $clause_stack[] = 'DISTINCT';
        } elseif ($this->displayAll) {
            $clause_stack[] = 'ALL';
        }

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->collection->build()->merge($clause_stack, $conditions, $values);

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
