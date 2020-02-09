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

declare(strict_types = 1);

namespace fw3\io\rdbms\query_builder\drivers\general\predicates;

use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAlias;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Set句Column述部インスタンスクラス
 *
 * @method ?SetColumnPredicate parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method SetColumnPredicate withParentReference(?object $parentReference)
 * @method SetColumnPredicate|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method SetColumnPredicate|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method SetColumnPredicate with()
 */
class SetColumnPredicate implements
    PredicateInterface,
    // type extends
    ChildrenDoNotUseAlias,
    ChildrenDoNotUseAliasKeyword
{
    use PredicateTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'column',
        'value',
    ];

    /**
     * @var null|string 値を設定するカラム
     */
    protected $column   = null;

    /**
     * @var mixed   値
     */
    protected $value    = null;

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
     * @param   array   ...$arguments   引数
     *  ([
     *      'column'    => 更新するカラム,
     *      'value'     => 更新値,
     *  ])
     *  または
     *  (
     *      $column,    更新するカラム
     *      $value      更新値
     *  )
     * @return  SetColumnPredicate  Where述部
     */
    public static function factory(...$arguments): SetColumnPredicate
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = [
                    $arguments[0]['column'] ?? $arguments[0][0] ?? null,
                    $arguments[0]['value'] ?? $arguments[0][1] ?? $arguments[1] ?? null,
                ];
            }

            !isset($arguments[0]) ?: $instance->column($arguments[0]);
            !isset($arguments[1]) ?: $instance->value($arguments[1]);
        }
        return $instance;
    }

    //==============================================
    // property accessor
    //==============================================
    /**
     *
     */
    public function column($column = null)
    {
        if (is_null($column) && func_num_args() === 0) {
            return $this->column;
        }

        if ($column instanceof ParentReferencePropertyInterface) {
            $column = $column->withParentReference($this);
        } else {
            $column = ColumnExpression::factory()->name($column)->parentReference($this);
        }

        $this->column   = $column;
        return $this;
    }

    public function value($value = null)
    {
        if (is_null($value) && func_num_args() === 0) {
            return $this->value;
        }

        if ($value instanceof ParentReferencePropertyInterface) {
            $value = $value->withParentReference($this);
        }

        $this->value    = $value;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    public function set($column, $value): SetColumnPredicate
    {
        $this->column($column);
        $this->value($value);
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
     * @return  SetColumnPredicate      このインスタンス
     */
    public function setInt($column, int $value): SetColumnPredicate
    {
        $this->column($column);
        $this->value($value);
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
        $clause_stack   = [];
        $conditions     = [];
        $values         = [];

        $column_name    = $this->column->getClause();
        $value          = $this->value;

        if ($value instanceof Buildable) {
            $valueBuildResult   = $value->build();
            if ($value instanceof ColumnExpression) {
                $format = '%s = %s';
            } else {
                $format = '%s = (%s)';
            }

            $clause_stack[] = sprintf($format, $column_name, $valueBuildResult->getClause());
            $values = $valueBuildResult->getValues();
            foreach ($valueBuildResult->getConditions() as $condition) {
                $values[]   = $condition;
            }
        } else {
            $clause_stack[] = sprintf('%s = ?', $column_name);
            $values[]       = $value;
        }

        //==============================================
        // build clause
        //==============================================
        $clause  = current($clause_stack);

        //==============================================
        // result
        //==============================================
        return BuildResult::factory($clause, $conditions, $values);
    }
}
