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

namespace fw3\io\rdbms\query_builder\drivers\general\statements;

use fw3\io\rdbms\query_builder\drivers\general\clauses\SetClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\UpdateClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpressionUseWhere;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;

/**
 * Update文
 *
 * @method UpdateStatement|bool explain(bool|StatementInterface $explain = false)
 * @method ?UpdateStatement parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method UpdateStatement withParentReference(?object $parentReference)
 * @method UpdateStatement|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method UpdateStatement|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method UpdateStatement with()
 * @method UpdateStatement|WhereClause where(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator, null|string|UnionTypeWhere $logical_operator)
 * @method UpdateStatement|WhereClause andWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method UpdateStatement|WhereClause orWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method UpdateStatement|WhereClause wheres(array ...$wheres)
 */
class UpdateStatement implements
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
        'updateClause',
        'setClause',
        'whereClause',
    ];

    /**
     * @var UpdateClause    Update句
     */
    protected UpdateClause $updateClause;

    /**
     * @var SetClause      From句
     */
    protected SetClause $setClause;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->updateClause = UpdateClause::factory()->parentReference($this);
        $this->setClause    = SetClause::factory()->parentReference($this);
        $this->whereClause  = WhereClause::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     *  ([
     *      'table'     => null|string|StatementInterface|UnionTypeTable    テーブル参照,
     *      'update'    => null|string|UpdateStatement|UpdateClause         Update句,
     *      'set'       => null|string|UpdateStatement|SetClause            Set句,
     *      'where'     => null|string|UnionTypeWhere                       Where
     *  ])
     *  または
     *  (
     *      null|string|StatementInterface|UnionTypeTable   $table  テーブル参照
     *      null|string|UpdateStatement|UpdateClause        $update Update句
     *      null|string|UpdateStatement|SetClause           $set    Set句
     *      null|string|UnionTypeWhere                      $where  Where句
     *  )
     * @return  UpdateStatement このインスタンス
     */
    public static function factory(...$arguments): UpdateStatement
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = static::adjustFactoryArgByKey($arguments, [
                    'table',
                    'updateClause',
                    'setClause',
                    'whereClause',
                ]);
            }
            $instance->update(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property access
    //==============================================
    /**
     * Update句を取得・設定します。
     *
     * @param   null|UpdateClause|UpdateStatement   $updateClause   Update句
     * @return  UpdateClause|UpdateStatement        Update句またはこのインスタンス
     */
    public function updateClause($updateClause = null)
    {
        if (is_null($updateClause) && func_num_args() === 0) {
            return $this->updateClause;
        }

        if ($updateClause instanceof UpdateStatement) {
            $updateClause = $updateClause->updateClause();
        }

        $this->updateClause = $updateClause;
        return $this;
    }

    /**
     * Set句を取得・設定します。
     *
     * @param   null|SetClause              $setClause  Set句
     * @return  SetClause|UpdateStatement   Set句またはこのインスタンス
     */
    public function setClause($setClause = null)
    {
        if (is_null($setClause) && func_num_args() === 0) {
            return $this->setClause;
        }

        if ($setClause instanceof UpdateStatement) {
            $setClause = $setClause->updateClause();
        }

        $this->setClause    = $setClause;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    /**
     * Update文を取得・設定します。
     *
     * @param null|string|StatementInterface|UnionTypeTable $table          テーブル参照
     * @param null|UpdateStatement|UpdateClause             $updateClause   Update句
     * @param null|UpdateStatement|SetClause                $setClause      Set句
     * @param null|UpdateStatement|WhereClause              $whereClause    Where句
     */
    public function update($table = null, $updateClause = null, $setClause = null, $whereClause = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this;
        }

        $is_update_statement = $table instanceof UpdateStatement;

        $this->table($table);

        if ($updateClause instanceof UpdateClause) {
            $this->updateClause($updateClause);
        } elseif ($is_update_statement) {
            $this->updateClause($table);
        }

        if ($setClause instanceof SetClause) {
            $this->setClause($setClause);
        } elseif ($is_update_statement) {
            $this->setClause($table);
        }

        if ($whereClause instanceof WhereClause) {
            $this->whereClause($whereClause);
        } elseif ($is_update_statement) {
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
     * @param   null|string|UnionTypeTable|StatementInterface   $table  テーブル参照
     * @param   null|string|UnionTypeTable|StatementInterface   $alias  テーブル参照別名
     * @return  TableReferenceExpression|UpdateStatement        テーブル参照またはこのインスタンス
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this->updateClause->table();
        }
        $this->updateClause->table($table, $alias);
        return $this;
    }

    //----------------------------------------------
    // SetClause
    //----------------------------------------------
    /**
     * 更新する値を設定します。
     *
     * @param   string|ColumnExpression $column 更新対象カラム
     * @param   mixed                   $value  更新値
     * @return  UpdateStatement         このインスタンス
     */
    public function value($column, $value): UpdateStatement
    {
        $this->setClause->value($column, $value);
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
     * @return  UpdateStatement         このインスタンス
     */
    public function values(...$values): UpdateStatement
    {
        $this->setClause->values(...$values);
        return $this;
    }

    /**
     * 更新する値を設定します。
     *
     * @param   string|ColumnExpression $column 更新対象カラム
     * @param   mixed                   $value  更新値
     * @return  UpdateStatement         このインスタンス
     */
    public function set($column, $value): UpdateStatement
    {
        $this->setClause->value($column, $value);
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
     * @return  UpdateStatement         このインスタンス
     */
    public function sets(...$values): UpdateStatement
    {
        $this->setClause->values(...$values);
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
    public function setInt($column, int $value): UpdateStatement
    {
        $this->setClause->setInt($column, $value);
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

        // updateClause
        $clause_stack[] = 'UPDATE';
        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->updateClause->build()->merge($clause_stack, $conditions, $values);

        // setClause
        $clause_stack[] = 'SET';
        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->setClause->build()->merge($clause_stack, $conditions, $values);

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
