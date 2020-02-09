<?php

namespace fw3\io\rdbms\query_builder\drivers\general\expressions;

use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

class RawExpression implements
    // group
    ExpressionInterface,
    // traits
    TablePropertyInterface,
    // union types
    WherePredicatesExpression
{
    use ExpressionTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'clause',
    ];

    protected $clause       = '';
    protected $conditions   = [];
    protected $values       = [];
    protected $alias        = null;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
    }

    public static function factory(...$arguments): RawExpression
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = [
                    $arguments[0]['clause'] ?? $arguments[0][0],
                    $arguments[0]['conditions'] ?? $arguments[0][1] ?? $arguments[1],
                    $arguments[0]['values'] ?? $arguments[0][2] ?? $arguments[2],
                    $arguments[0]['alias'] ?? $arguments[0][3] ?? $arguments[3],
                ];
            }
            !isset($arguments[0]) ?: $instance->clause($arguments[0]);
            !isset($arguments[1]) ?: $instance->alias($arguments[1]);
            !isset($arguments[2]) ?: $instance->table($arguments[2]);
            !isset($arguments[3]) ?: $instance->table($arguments[3]);
        }
        return $instance;
    }

    public function raw(?string $clause, ?array $conditions = null, ?array $values = null, ?string $alias = null): RawExpression
    {
        switch (func_num_args()) {
            case 4:
                $this->alias($alias);
            case 3:
                $this->values($values);
            case 2:
                $this->conditions($conditions);
            case 1:
                return $this->clause($clause);
        }
    }

    public function clause(?string $clause = null)
    {
        if (func_num_args() === 0) {
            return $this->clause;
        }
        $this->clause   = $clause;
        return $this;
    }

    public function conditions(?array $conditions = null)
    {
        if (func_num_args() === 0) {
            return $this->conditions;
        }
        $this->conditions   = $conditions;
        return $this;
    }

    public function values(?array $values = null)
    {
        if (func_num_args() === 0) {
            return $this->values;
        }
        $this->values   = $values;
        return $this;
    }

    public function alias(?string $alias = null)
    {
        if (func_num_args() === 0) {
            return $this->alias;
        }
        $this->alias    = $alias;
        return $this;
    }

    public function build(): BuildResultInterface
    {
        $clause = !is_null($this->alias) && !($this->parentReference instanceof ChildrenDoNotUseAliasKeyword) ? sprintf('%s AS `%s`', $this->clause, $this->alias) : $this->clause;
        return BuildResult::factory($clause, $this->conditions, $this->values, null);
    }
}
