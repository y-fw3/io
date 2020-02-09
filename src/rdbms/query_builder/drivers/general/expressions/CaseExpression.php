<?php

namespace fw3\io\rdbms\query_builder\drivers\general\expressions;

use fw3\io\rdbms\query_builder\drivers\general\collections\WhenCollection;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\aliases\AliasPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\aliases\AliasPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 *
 *
 * @property    WhenCollection  $collection
 */
class CaseExpression implements
    AliasPropertyInterface,
    ExpressionInterface,
    TablePropertyInterface,
    WherePredicatesExpression
{
    use AliasPropertyTrait;
    use ExpressionTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    protected $caseValue    = null;

    /**
     * @var WhenCollection
     */
    protected WhenCollection $whenCollection;

    protected $elseResult   = null;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->collection  = WhenCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array           ...$arguments   引数
     * @return  CaseExpression    Select文
     */
    public static function factory(...$arguments): CaseExpression
    {
        return new static(...$arguments);
    }

    //==============================================
    // feature
    //==============================================
    public function caseValue($case_value = null)
    {
        if (is_null($case_value) && func_num_args() === 0) {
            return $this->caseValue;
        }

        if ($case_value instanceof ParentReferencePropertyInterface) {
            $case_value = $case_value->withParentReference($this);
        } else {
            $case_value = RawExpression::factory()->clause('?')->conditions([$case_value]);
        }

        $this->caseValue    = $case_value;
        return $this;
    }

    /**
     *
     * @param   string $column
     * @param   string $alias
     * @param   string $table
     * @return  CaseExpression
     */
    public function when($condition, $result): CaseExpression
    {
        $this->collection->when($condition, $result);
        return $this;
    }

    public function elseResult($result)
    {
        if (is_null($result) && func_num_args() === 0) {
            return $this->elseResult;
        }

        if ($result instanceof ParentReferencePropertyInterface) {
            $result = $result->withParentReference($this);
        } else {
            $result = RawExpression::factory()->clause('?')->conditions([$result]);
        }

        $this->elseResult    = $result;
        return $this;
    }

    public function hasEmpty()
    {
        return $this->collection->hasEmpty();
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

        $clause_stack   = [
            'CASE',
        ];

        if (!is_null($this->caseValue)) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->caseValue->build()->merge($clause_stack, $conditions, $values);
        }

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->collection->build()->merge($clause_stack, $conditions, $values);

        if (!is_null($this->elseResult)) {
            $clause_stack[] = 'ELSE';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->elseResult->build()->merge($clause_stack, $conditions, $values);
        }

        $clause_stack[] = 'END';

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
