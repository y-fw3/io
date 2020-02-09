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

use fw3\io\rdbms\query_builder\drivers\general\clauses\InsertClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\InsertValueClause;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * Insert文
 *
 * @method InsertStatement|bool explain(bool|StatementInterface $explain = false)
 * @method ?InsertStatement parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method InsertStatement withParentReference(?object $parentReference)
 * @method InsertStatement|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method InsertStatement|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method InsertStatement with()
 */
class InsertStatement implements
    StatementInterface
{
    use StatementTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'insertClause',
        'insertValueClause',
    ];

    /**
     * @var InsertClause    Insert句
     */
    protected InsertClause $insertClause;

    /**
     * @var InsertValueClause   InsertValue句
     */
    protected InsertValueClause $insertValueClause;

    //==============================================
    // factory
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->insertClause         = InsertClause::factory()->parentReference($this);
        $this->insertValueClause    = InsertValueClause::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  InsertStatement このインスタンス
     */
    public static function factory(...$arguments): InsertStatement
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = static::adjustFactoryArgByKey($arguments, [
                    'table',
                    'insertClause',
                    'insertValueClause',
                ]);
            }
            $instance->insert(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property access
    //==============================================
    /**
     *
     * @param unknown $insertClause
     * @return  InsertStatement
     */
    public function insertClause($insertClause)
    {
        if (is_null($insertClause) && func_num_args() === 0) {
            return $this->insertClause;
        }

        if ($insertClause instanceof InsertStatement) {
            $insertClause   = $insertClause->insertClause();
        }

        if ($insertClause instanceof InsertClause) {
            $this->insertClause = $insertClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['insertClause' => $insertClause]);
    }

    /**
     *
     * @param   unknown $insertValueClause
     * @return  InsertStatement
     */
    public function insertValueClause($insertValueClause)
    {
        if (is_null($insertValueClause) && func_num_args() === 0) {
            return $this->insertValueClause;
        }

        if ($insertValueClause instanceof InsertStatement) {
            $insertValueClause   = $insertValueClause->insertValueClause();
        }

        if ($insertValueClause instanceof InsertValueClause) {
            $this->insertValueClause = $insertValueClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['insertValueClause' => $insertValueClause]);
    }

    //==============================================
    // feature
    //==============================================
    public function insert($table = null, $insertClause = null, $insertValueClause = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this;
        }

        $is_insert_statement = $table instanceof InsertStatement;

        $this->table($table);

        if ($insertClause instanceof InsertClause) {
            $this->insertClause($insertClause);
        } elseif ($is_insert_statement) {
            $this->insertClause($table);
        }

        if ($insertValueClause instanceof InsertValueClause) {
            $this->insertValueClause($insertValueClause);
        } elseif ($is_insert_statement) {
            $this->insertValueClause($table);
        }

        return $this;
    }

    //----------------------------------------------
    // InsertClause
    //----------------------------------------------
    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|TableReferenceExpression|InsertStatement   $table  テーブル参照
     * @param   null|string|TableReferenceExpression|InsertStatement   $alias  テーブル参照別名
     * @return  TableReferenceExpression|InsertStatement    テーブル参照またはこのインスタンス
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this->insertClause->table();
        }
        $this->insertClause->table($table, $alias);
        return $this;
    }

    //----------------------------------------------
    // InsertValueClause
    //----------------------------------------------
    /**
     *
     * @param unknown $column
     * @param unknown $value
     * @return  InsertStatement
     */
    public function set($column, $value)
    {
        $this->insertValueClause->set($column, $value);
        return $this;
    }

    /**
     *
     * @param unknown ...$value_set
     * @return  InsertStatement
     */
    public function sets(...$value_set)
    {
        $this->insertValueClause->sets(...$value_set);
        return $this;
    }

    /**
     *
     * @param unknown $column
     * @return  InsertStatement
     */
    public function column($column)
    {
        $this->insertValueClause->column($column);
        return $this;
    }

    /**
     *
     * @param unknown ...$columns
     * @return  InsertStatement
     */
    public function columns(...$columns)
    {
        $this->insertValueClause->column(...$columns);
        return $this;
    }

    /**
     *
     * @param unknown $value
     * @return  InsertStatement
     */
    public function value($value)
    {
        $this->insertValueClause->value($value);
        return $this;
    }

    /**
     *
     * @param unknown ...$values
     * @return  InsertStatement
     */
    public function values(...$values)
    {
        $this->insertValueClause->values(...$values);
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

        $clause_stack[] = 'INSERT';
        $clause_stack[] = 'INTO';

        // insertClause
        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->insertClause->build()->merge($clause_stack, $conditions, $values);

        // insertValueClause
        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->insertValueClause->build()->merge($clause_stack, $conditions, $values);

        //==============================================
        // result
        //==============================================
        return BuildResult::factory(implode(' ', $clause_stack), $conditions, $values, null);
    }
}
