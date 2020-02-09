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
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionTrait;
use fw3\io\rdbms\query_builder\drivers\general\predicates\SetColumnPredicate;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;

/**
 * Set句
 *
 * @property    SetColumnCollection $collection
 */
class SetClause implements
    Buildable,
    ClauseInterface,
    CollectionInterface,
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
    use BuildableTrait;
    use ClauseTrait;
    use CollectionTrait;
    use ParentReferencePropertyTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
    }

    /**
     * factory
     *
     * @param   array           ...$arguments   引数
     * @return  SetClause       Set句
     */
    public static function factory(...$arguments): SetClause
    {
        return new static(...$arguments);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * 更新する値を設定します。
     *
     * @param   string|ColumnExpression $column 更新対象カラム
     * @param   mixed                   $value  更新値
     * @return  SetClause               このインスタンス
     */
    public function value($column, $value): SetClause
    {
        $this->collection[]  = SetColumnPredicate::factory()->set($column, $value)->parentReference($this);
        return $this;
    }

    /**
     * 更新する値を纏めて設定します。
     *
     * @param   array       ...$values  更新する値セット
     *  [
     *      [string|ColumnExpression $column, mixed $value]...
     *  ]
     *  または
     *  [
     *      string $column => mixed $value,...
     *  ]
     * @return  SetClause               このインスタンス
     */
    public function values(...$values): SetClause
    {
        if (!isset($values[1])) {
            $values = $values[0];
        }

        foreach ($values as $column => $value) {
            if (is_array($value)) {
                $column = $value[0];
                $value  = $value[1];
            }
            $this->collection[]  = SetColumnPredicate::factory()->set($column, $value)->parentReference($this);
        }

        return $this;
    }

    //==============================================
    // typed value
    //==============================================
    /**
     * 更新する整数値を設定します。
     *
     * @param   string|ColumnExpression $column 更新対象カラム
     * @param   int                     $value  更新値
     * @return  UpdateStatement         このインスタンス
     */
    public function setInt($column, int $value): SetClause
    {
        $this->collection[]  = SetColumnPredicate::factory()->setInt($column, $value)->parentReference($this);
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

        foreach ($this->collection as $setColumnPredicate) {
            /** @var BuildResult $setColumnPredicateBuildResult */
            $setColumnPredicateBuildResult  = $setColumnPredicate->build();

            $clause_stack[] = $setColumnPredicateBuildResult->getClause();
            foreach ($setColumnPredicateBuildResult->getValues() as $value) {
                $values[]   = $value;
            }
        }

        $clause = implode(', ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
