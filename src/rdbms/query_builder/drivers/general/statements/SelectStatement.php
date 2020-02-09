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

use fw3\io\rdbms\query_builder\drivers\general\clauses\FromClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\GroupClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\HavingClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\LimitClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\OrderClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\SelectClause;
use fw3\io\rdbms\query_builder\drivers\general\clauses\WhereClause;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesEncloseExpression;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\StatementTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\statements\traits\properties\where_clauses\WhereClausePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriable;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriableTrait;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeTable;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeWhere;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;
use fw3\io\rdbms\query_builder\drivers\general\expressions\ColumnExpression;

/**
 * Select文
 *
 * @method SelectStatement|bool explain(bool|StatementInterface $explain = false)
 * @method ?SelectStatement parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method SelectStatement withParentReference(?object $parentReference)
 * @method SelectStatement|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method SelectStatement|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method SelectStatement with()
 * @method SelectStatement|WhereClause where(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator, null|string|UnionTypeWhere $logical_operator)
 * @method SelectStatement|WhereClause andWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method SelectStatement|WhereClause orWhere(string|UnionTypeWhere|ColumnExpression  $left_expression, string|UnionTypeWhere|ColumnExpression $right_expression, null|string|UnionTypeWhere $operator)
 * @method SelectStatement|WhereClause wheres(array ...$wheres)
 */
class SelectStatement implements
    // group
    StatementInterface,
    // traits
    SubQueriable,
    // property
    WhereClausePropertyInterface,
    // type exnteds
    WherePredicatesEncloseExpression,
    // uinon types
    UnionTypeWhere,
    UnionTypeTable
{
    use StatementTrait;
    use SubQueriableTrait;
    use WhereClausePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'selectClause',
        'fromClause',
        'whereClause',
        'orderClause',
        'havingClause',
        'windowClause',
        'groupClause',
        'limitClause',
    ];

    /**
     * @var SelectClause    Select句
     */
    protected SelectClause $selectClause;

    /**
     * @var FromClause      From句
     */
    protected FromClause $fromClause;

    /**
     * @var GroupClause     Group句
     */
    protected GroupClause $groupClause;

    /**
     * @var HavingClause    Having句
     */
    protected HavingClause $havingClause;

    /**
     * @var WindowClause    Window句
     */
    protected $windowClause = null;

    /**
     * @var OrderClause     Order句
     */
    protected OrderClause $orderClause;

    /**
     * @var LimitClause     Limit句
     */
    protected LimitClause $limitClause;

    //==============================================
    // factory
    //==============================================
    protected function __construct()
    {
        $this->selectClause = SelectClause::factory()->parentReference($this);
        $this->fromClause   = FromClause::factory()->parentReference($this);
        $this->whereClause  = WhereClause::factory()->parentReference($this);
        $this->groupClause  = GroupClause::factory()->parentReference($this);
        $this->havingClause = HavingClause::factory()->parentReference($this);
//        $this->windowClause = WineowClause::factory()->parentReference($this);
        $this->orderClause  = OrderClause::factory()->parentReference($this);
        $this->limitClause  = LimitClause::factory();
    }

    /**
     * factory
     *
     * @param array ...$argments
     * @return SelectStatement
     */
    public static function factory(...$arguments): SelectStatement
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = static::adjustFactoryArgByKey($arguments, [
                    'table',
                    'selectClause',
                    'fromClause',
                    'whereClause',
                    'groupClause',
                    'havingClause',
                    'windowClause',
                    'orderClause',
                    'limitClause',
                ]);
            }
            $instance->select(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property accessor
    //==============================================
    /**
     * 値の重複を除去するかどうかを取得・設定します。
     *
     * @param   bool    $distinct       値の重複を除去するかどうか
     * @return  bool|SelectStatement    値の重複を除去するかどうかまたはこのインスタンス
     */
    public function distinct(bool $distinct = false)
    {
        if ($distinct === false && func_num_args() === 0) {
            return $this->selectClause->distinct();
        }
        $this->selectClause->distinct($distinct);
        return $this;
    }

    /**
     * DISTINCTを使わない場合にALLを明示するかどうかを取得・設定します。
     *
     * @param   bool    $explain        EXPLAINを使用するかどうか
     * @return  bool|SelectStatement    EXPLAINを使用するかどうかまたはこのインスタンス
     */
    public function displayAll(bool $display_all = false)
    {
        if ($display_all === false && func_num_args() === 0) {
            return $this->selectClause->displayAll();
        }
        $this->selectClause->displayAll($display_all);
        return $this;
    }

    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|UnionTypeTable $table  テーブル参照
     * @param   null|string|UnionTypeTable $alias  テーブル参照別名
     * @return  SelectStatement|TableReferenceExpression    このインスタンスまたはテーブル
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this->fromClause->table() ?? $this->closestTable();
        }
        $this->fromClause->table($table, $alias);
        return $this;
    }

    /**
     * Select句を取得・設定します。
     *
     * @param   null|SelectClause|SelectStatement   $select_clause  Select句
     * @return  SelectClause|SelectStatement        Select句またはこのインスタンス
     */
    public function selectClause($selectClause = null)
    {
        if (is_null($selectClause) && func_num_args() === 0) {
            return $this->selectClause;
        }

        if ($selectClause instanceof SelectStatement) {
            $selectClause   = $selectClause->selectClause();
        }

        if ($selectClause instanceof SelectClause) {
            $this->selectClause = $selectClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['selectClause' => $selectClause]);
    }

    /**
     * From句を取得・設定します。
     *
     * @param   null|FromClause|SelectStatement $from_clause    From句
     * @return  FromClause|SelectStatement      From句またはこのインスタンス
     */
    public function fromClause($fromClause = null)
    {
        if (is_null($fromClause) && func_num_args() === 0) {
            return $this->fromClause;
        }

        if ($fromClause instanceof SelectStatement) {
            $fromClause = $fromClause->fromClause();
        }

        if ($fromClause instanceof FromClause) {
            $this->fromClause   = $fromClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['fromClause' => $fromClause]);
    }

    /**
     * Group句を取得・設定します。
     *
     * @param   null|GroupClause|SelectStatement    $group_clause   Group句
     * @return  GroupClause|SelectStatement         Group句またはこのインスタンス
     */
    public function groupClause($groupClause = null)
    {
        if (is_null($groupClause) && func_num_args() === 0) {
            return $this->groupClause;
        }

        if ($groupClause instanceof SelectStatement) {
            $groupClause = $groupClause->whereClause();
        }

        if ($groupClause instanceof GroupClause) {
            $this->groupClause   = $groupClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['groupClause' => $groupClause]);
    }

    /**
     * Having句を取得・設定します。
     *
     * @param   null|HavingClause|SelectStatement   $having_clause  Having句
     * @return  HavingClause|SelectStatement        Having句またはこのインスタンス
     */
    public function havingClause($havingClause = null)
    {
        if (is_null($havingClause) && func_num_args() === 0) {
            return $this->havingClause;
        }

        if ($havingClause instanceof SelectStatement) {
            $havingClause = $havingClause->havingClause();
        }

        if ($havingClause instanceof HavingClause) {
            $this->havingClause   = $havingClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['havingClause' => $havingClause]);
    }

    /**
     * Order句を取得・設定します。
     *
     * @param   null|OrderClause|SelectStatement    $orderClause    Order句
     * @return  OrderClause|SelectStatement         Order句またはこのインスタンス
     */
    public function orderClause($orderClause = null)
    {
        if (is_null($orderClause) && func_num_args() === 0) {
            return $this->orderClause;
        }

        if ($orderClause instanceof SelectStatement) {
            $orderClause = $orderClause->orderClause();
        }

        if ($orderClause instanceof OrderClause) {
            $this->orderClause   = $orderClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['orderClause' => $orderClause]);
    }

    /**
     * Limit句を取得・設定します。
     *
     * @param   null|LimitClause|SelectStatement    $limitClause    Limit句
     * @return  LimitClause|SelectStatement         Limit句またはこのインスタンス
     */
    public function limitClause($limitClause = null)
    {
        if (is_null($limitClause) && func_num_args() === 0) {
            return $this->limitClause;
        }

        if ($limitClause instanceof SelectStatement) {
            $limitClause = $limitClause->orderClause();
        }

        if ($limitClause instanceof LimitClause) {
            $this->limitClause   = $limitClause->withParentReference($this);
            return $this;
        }

        UnavailableVarException::raise('使用できない型の値を渡されました', ['limitClause' => $limitClause]);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * Select文を取得・設定します。
     *
     * @param unknown $table
     * @param unknown $fromClause
     * @param unknown $whereClause
     * @param unknown $windowClause
     * @param unknown $groupClause
     * @param unknown $havingClause
     * @param unknown $orderClause
     * @param unknown $limitClause
     * @return  SelectStatement このインスタンス
     */
    public function select($table = null, $selectClause = null, $fromClause = null, $whereClause = null, $groupClause = null, $havingClause = null, $windowClause = null, $orderClause = null, $limitClause = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this;
        }

        $is_select_statement = $table instanceof SelectStatement;

        $this->table($table);

        if ($selectClause instanceof SelectClause) {
            $this->selectClause($selectClause);
        } elseif ($is_select_statement) {
            $this->fromClause($table);
        }

        if ($fromClause instanceof FromClause) {
            $this->fromClause($fromClause);
        } elseif ($is_select_statement) {
            $this->fromClause($table);
        }

        if ($whereClause instanceof WhereClause) {
            $this->whereClause($whereClause);
        } elseif ($is_select_statement) {
            $this->whereClause($table);
        }

        if ($groupClause instanceof GroupClause) {
            $this->groupClause($groupClause);
        } elseif ($is_select_statement) {
            $this->groupClause($table);
        }

        if ($havingClause instanceof HavingClause) {
            $this->havingClause($havingClause);
        } elseif ($is_select_statement) {
            $this->havingClause($table);
        }

//         if ($windowClause instanceof WindowClause) {
//             $this->windowClause($windowClause);
//         } elseif ($is_select_statement) {
//             $this->windowClause($table);
//         }

        if ($orderClause instanceof OrderClause) {
            $this->orderClause($orderClause);
        } elseif ($is_select_statement) {
            $this->orderClause($table);
        }
        if ($limitClause instanceof LimitClause) {
            $this->limitClause($limitClause);
        } elseif ($is_select_statement) {
            $this->limitClause($table);
        }

        return $this;
    }

    //----------------------------------------------
    // SelectClause
    //----------------------------------------------
    /**
     * Select カラムを追加します。
     *
     * @param   string|ColumnExpression         $column カラム
     * @param   null|string|ColumnExpression    $alias  カラム別名
     * @param   string|UnionTypeTable           $table  テーブル
     * @return  SelectStatement このインスタンス
     */
    public function column($column, $alias = null, $table = null): SelectStatement
    {
        $this->selectClause->column($column, $alias, $table);
        return $this;
    }

    /**
     * Select カラムを纏めて追加します。
     *
     * @param   array   ...$columns 纏めて設定するためのカラムセット
     *  ([
     *      {string|ColumnExpression カラム名|[string|ColumnExpression カラム名, [string|ColumnExpression カラム別名, [string|UnionTypeTable テーブル]]]}...
     *  ])
     *  または
     *  (
     *      {string|ColumnExpression カラム名|[string|ColumnExpression カラム名, [string|ColumnExpression カラム別名, [string|UnionTypeTable テーブル]]]}...
     *  )
     * @return  SelectStatement このインスタンス
     */
    public function columns(...$columns): SelectStatement
    {
        $this->selectClause->columns(...$columns);
        return $this;
    }

    //----------------------------------------------
    // FromClause
    //----------------------------------------------
    /**
     * テーブル参照を設定します。
     *
     * @param   string|UnionTypeTable      $table  テーブル名
     * @param   null|string|UnionTypeTable $alias  テーブル別名
     * @return  SelectStatement このインスタンス
     */
    public function from($table, $alias = null): SelectStatement
    {
        return $this->table($table, $alias);
    }

    /**
     * joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function join($table, ...$where): SelectStatement
    {
        $this->fromClause->join($table, ...$where);
        return $this;
    }

    /**
     * inner joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function innerJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->innerJoin($table, ...$where);
        return $this;
    }

    /**
     * outer joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function outerJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->outerJoin($table, ...$where);
        return $this;
    }

    /**
     * cross joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function crossJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->crossJoin($table, ...$where);
        return $this;
    }

    /**
     * straight joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function straightJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->straightJoin($table, ...$where);
        return $this;
    }

    /**
     * left joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function leftJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->leftJoin($table, ...$where);
        return $this;
    }

    /**
     * outer left joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function outerLeftJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->outerLeftJoin($table, ...$where);
        return $this;
    }

    /**
     * right joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function rightJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->rightJoin($table, ...$where);
        return $this;
    }

    /**
     * outer right joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function outerRightJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->outerRightJoin($table, ...$where);
        return $this;
    }

    /**
     * natural joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function naturalJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->naturalJoin($table, ...$where);
        return $this;
    }

    /**
     * natural left joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function naturalLeftJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->naturalLeftJoin($table, ...$where);
        return $this;
    }

    /**
     * natural join outerを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function naturalLeftOuterJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->naturalLeftOuterJoin($table, ...$where);
        return $this;
    }

    /**
     * natural right joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function naturalRightJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->naturalRightJoin($table, ...$where);
        return $this;
    }

    /**
     * natural right outer joinを追加します。
     *
     * @param   string|UnionTypeTable                   $table      テーブル名
     * @param   array|UnionTypeWhere||UnionTypeWhere[]  ...$where   結合時条件
     * @return  SelectStatement                         このインスタンス
     */
    public function naturalRightOuterJoin($table, ...$where): SelectStatement
    {
        $this->fromClause->naturalRightOuterJoin($table, ...$where);
        return $this;
    }

    // index hint
    /**
     * index hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $type       index hint type
     * @param   string  $target     index hintの対象
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function indexHint($index_list, ?string $type = null, ?string $target = null, ?string $scope = null): SelectStatement
    {
        $this->fromClause->indexHint($index_list, $type, $target, $scope);
        return $this;
    }

    /**
     * use indexとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function useIndex($index_list = [], ?string $scope = null): SelectStatement
    {
        $this->fromClause->useIndex($index_list, $scope);
        return $this;
    }

    /**
     * use keyとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function useKey($index_list = [], ?string $scope = null): SelectStatement
    {
        $this->fromClause->useKey($index_list, $scope);
        return $this;
    }

    /**
     * ignore indexとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function ignoreIndex($index_list, ?string $scope = null): SelectStatement
    {
        $this->fromClause->ignoreIndex($index_list, $scope);
        return $this;
    }

    /**
     * ignore keyとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function ignoreKey($index_list, ?string $scope = null): SelectStatement
    {
        $this->fromClause->ignoreKey($index_list, $scope);
        return $this;
    }

    /**
     * force indexとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function forceIndex($index_list, ?string $scope = null): SelectStatement
    {
        $this->fromClause->forceIndex($index_list, $scope);
        return $this;
    }

    /**
     * force keyとしてindex hintを追加します。
     *
     * @param   array   $index_list インデックスリスト
     * @param   string  $scope      index hintのスコープ
     * @return  SelectStatement     このインスタンス
     */
    public function forceKey($index_list, ?string $scope = null): SelectStatement
    {
        $this->fromClause->forceKey($index_list, $scope);
        return $this;
    }

    //----------------------------------------------
    // LimitClause
    //----------------------------------------------
    /**
     * リミットとオフセットを設定します。
     *
     * @param   int|LimitClause|SelectStatement         $row_count  リミット
     * @param   null|int|LimitClause|SelectStatement    $offset     オフセット
     * @return  SelectStatement     このインスタンス
     */
    public function limit($row_count, $offset = null): SelectStatement
    {
        $this->limitClause->limit($row_count, $offset);
        return $this;
    }

    //----------------------------------------------
    // OrderClause
    //----------------------------------------------
    /**
     * ORDER BY句を設定します。
     *
     * @param   string|OrderClause|SelectStatement|ColumnExpression $column     カラム
     * @param   string|OrderClause|SelectStatement                  $sort_order ソートオーダー
     * @return  SelectStatement このインスタンス
     */
    public function orderBy($column, $sort_order = null): SelectStatement
    {
        $this->orderClause->orderBy($column, $sort_order);
        return $this;
    }

    /**
     * ソートオーダーをASCとしてORDER BY句を設定します。
     *
     * @param   string|OrderClause|SelectStatement|ColumnExpression $column カラム
     * @return  SelectStatement このインスタンス
     */
    public function orderByAsc($column, $sort_order = null): SelectStatement
    {
        $this->orderClause->orderByAsc($column, $sort_order);
        return $this;
    }

    /**
     * ソートオーダーをDESCとしてORDER BY句を設定します。
     *
     * @param   string|OrderClause|SelectStatement|ColumnExpression $column カラム
     * @return  SelectStatement このインスタンス
     */
    public function orderByDesc($column, $sort_order = null): SelectStatement
    {
        $this->orderClause->orderByDesc($column, $sort_order);
        return $this;
    }

    //----------------------------------------------
    // GroupClause
    //----------------------------------------------
    /**
     * group by句を設定します。
     *
     * @param   string|GroupClause|SelectStatement      $column カラム
     * @param   null|string|GroupClause|SelectStatement $table  テーブル
     * @return  SelectStatement このインスタンス
     */
    public function groupBy($column, $table = null): SelectStatement
    {
        $this->groupClause->group($column, $table);
        return $this;
    }

    //----------------------------------------------
    // HavingClause
    //----------------------------------------------
    /**
     * having句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $left_expression    左辺式
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $right_expression   右辺式
     * @param   null|string|HavingClause|UnionTypeWhere             $operator           比較演算子
     * @param   null|string|HavingClause|UnionTypeWhere             $logical_operator   前方に対する論理演算子
     * @return  SelectStatement                         このインスタンス
     */
    public function having($left_expression, $right_expression = null, $operator = WhereClause::OP_EQ, $logical_operator = null): SelectStatement
    {
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->havingClause->having($left_expression);
            return $this;
        }

        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        $this->havingClause->having($left_expression, $right_expression, $operator, $logical_operator);
        return $this;
    }

    /**
     * having句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $left_expression    左辺式
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $right_expression   右辺式
     * @param   null|string|HavingClause|UnionTypeWhere             $operator           比較演算子
     * @return  SelectStatement                         このインスタンス
     */
    public function andHaving($left_expression, $right_expression = null, $operator = WhereClause::OP_EQ): SelectStatement
    {
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->havingClause->andHaving($left_expression);
            return $this;
        }

        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        $this->havingClause->andHaving($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * having句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $left_expression    左辺式
     * @param   string|HavingClause|UnionTypeWhere|ColumnExpression $right_expression   右辺式
     * @param   null|string|HavingClause|UnionTypeWhere             $operator           比較演算子
     * @return  SelectStatement                         このインスタンス
     */
    public function orHaving($left_expression, $right_expression = null, $operator = WhereClause::OP_EQ): SelectStatement
    {
        if ($left_expression instanceof ParentReferencePropertyInterface) {
            $left_expression = $left_expression->withParentReference($this);
        }

        if (is_null($right_expression) && func_num_args() === 1) {
            $this->havingClause->orHaving($left_expression);
            return $this;
        }

        if ($right_expression instanceof ParentReferencePropertyInterface) {
            $right_expression = $right_expression->withParentReference($this);
        }

        $this->havingClause->orHaving($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * havingをまとめて設定します。
     *
     * @param   array   ...$wheres
     * @return  SelectStatement
     */
    public function havings(...$havings): SelectStatement
    {
        $this->havingClause->havings(...$havings);
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

        /** @var BuildResult $selectClauseResult */
        if ('' !== ($selectClauseResult = $this->selectClause->build())->getClause()) {
            $clause_stack[] = 'SELECT';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $selectClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $fromClauseResult */
        if (is_null($this->fromClause->table())) {
            $this->fromClause->table($this->table());
        }

        if ('' !== ($fromClauseResult = $this->fromClause->build())->getClause()) {
            $clause_stack[] = 'FROM';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $fromClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $whereClauseResult */
        if (!$this->whereClause->hasEmpty() && '' !== ($whereClauseResult = $this->whereClause->build())->getClause()) {
            $clause_stack[] = 'WHERE';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $whereClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $groupClauseResult */
        if (!$this->groupClause->hasEmpty() && '' !== ($groupClauseResult = $this->groupClause->build())->getClause()) {
            $clause_stack[] = 'GROUP BY';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $groupClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $havingClauseResult */
        if (!$this->havingClause->hasEmpty() && '' !== ($havingClauseResult = $this->havingClause->build())->getClause()) {
            $clause_stack[] = 'HAVING';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $havingClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $orderClauseResult */
        if (!$this->orderClause->hasEmpty() && '' !== ($orderClauseResult = $this->orderClause->build())->getClause()) {
            $clause_stack[] = 'ORDER BY';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $orderClauseResult->merge($clause_stack, $conditions, $values);
        }

        /** @var BuildResult $limitClauseResult */
        if (!$this->limitClause->hasEmpty() && '' !== ($limitClauseResult = $this->limitClause->build())->getClause()) {
            $clause_stack[] = 'LIMIT';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $limitClauseResult->merge($clause_stack, $conditions, $values);
        }

        //==============================================
        // result
        //==============================================
        return BuildResult::factory(implode(' ', $clause_stack), $conditions, $values, null);
    }
}
