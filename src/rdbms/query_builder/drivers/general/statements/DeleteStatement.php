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

namespace fw3\io\rdbms\query_builder\drivers\general\statements;

use fw3\io\rdbms\query_builder\drivers\general\clauses\DeleteClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpressionUseWhere;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * Delete文
 *
 * @method DeleteStatement|bool explain(bool|StatementInterface $explain = false)
 * @method ?DeleteStatement parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method DeleteStatement withParentReference(?object $parentReference)
 * @method DeleteStatement|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method DeleteStatement|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method DeleteStatement with()
 * @method DeleteStatement|WhereClause where(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator, null|string|UnionTypeWhere $logical_operator)
 * @method DeleteStatement|WhereClause andWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method DeleteStatement|WhereClause orWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method DeleteStatement|WhereClause wheres(array ...$wheres)
 */
class DeleteStatement implements
    // group
    StatementInterface,
    // property
    WhereClausePropertyInterface,
    // union types
    WherePredicatesExpressionUseWhere
{
    use StatementTrait;
    use WhereClausePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'deleteClause',
        'whereClause',
    ];

    /**
     * @var DeleteClause    Delete句
     */
    protected DeleteClause $deleteClause;

    //==============================================
    // factory
    //==============================================
    protected function __construct()
    {
        $this->deleteClause = DeleteClause::factory()->parentReference($this);
        $this->whereClause  = WhereClause::factory()->parentReference($this);
    }

    /**
     *
     * @param array ...$argments
     * @return DeleteStatement
     */
    public static function factory(...$arguments): DeleteStatement
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments   = static::adjustFactoryArgByKey($arguments, [
                    'table',
                    'deleteClause',
                    'whereClause',
                ]);
            }
            $instance->delete(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property accessor
    //==============================================
    /**
     * Delete句を取得・設定します。
     *
     * @param   null|DeleteClause|DeleteStatement   $deleteClause   Delete句
     * @return  DeleteClause|DeleteStatement        Delete句またはこのインスタンス
     */
    public function deleteClause($deleteClause = null)
    {
        if (is_null($deleteClause) && func_num_args() === 0) {
            return $this->deleteClause;
        }

        if ($deleteClause instanceof DeleteStatement) {
            $deleteClause = $deleteClause->whereClause();
        }

        if ($deleteClause instanceof DeleteClause) {
            $this->deleteClause = $deleteClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['deleteClause' => $deleteClause]);
    }

    //==============================================
    // feature
    //==============================================
    public function delete($table = null, $deleteClause = null, $whereClause = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this;
        }

        $is_delete_statement = $table instanceof DeleteStatement;

        $this->table($table);

        if ($deleteClause instanceof DeleteClause) {
            $this->deleteClause($deleteClause);
        } elseif ($is_delete_statement) {
            $this->deleteClause($table);
        }

        if ($whereClause instanceof WhereClause) {
            $this->whereClause($whereClause);
        } elseif ($is_delete_statement) {
            $this->whereClause($table);
        }

        return $this;
    }

    //----------------------------------------------
    // UpdateClause
    //----------------------------------------------
    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|TableReferenceExpression||DeleteStatement   $table  テーブル参照
     * @param   null|string|TableReferenceExpression||DeleteStatement   $alias  テーブル参照別名
     * @return  TableReferenceExpression|DeleteStatement    テーブル参照またはこのインスタンス
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this->deleteClause->table();
        }
        $this->deleteClause->table($table, $alias);
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
    public function build(): BuildResult
    {
        $clause_stack   = [];
        $conditions     = [];
        $values         = [];

        // use EXPLAIN
        if ($this->explain) {
            $clause_stack[] = 'EXPLAIN';
        }

        // deleteClause
        $clause_stack[] = 'DELETE';
        $clause_stack[] = 'FROM';
        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->deleteClause->build()->merge($clause_stack, $conditions, $values);

        /** @var BuildResult $whereClauseResult */
        if (!$this->whereClause->hasEmpty() && '' !== ($whereClauseResult = $this->whereClause->build())->getClause()) {
            $clause_stack[] = 'WHERE';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $whereClauseResult->merge($clause_stack, $conditions, $values);
        }

        //==============================================
        // result
        //==============================================
        return BuildResult::factory(implode(' ', $clause_stack), $conditions, $values, null);
    }
}
