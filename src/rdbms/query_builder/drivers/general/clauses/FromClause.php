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
use fw3\io\rdbms\query_builder\drivers\general\collections\IndexHintCollection;
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\io\rdbms\query_builder\drivers\general\factorys\clauses\JoinClauseFactory;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriable;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriableTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * From句
 */
class FromClause implements
    Buildable,
    ClauseInterface,
    ComparisonOperatorConst,
    LogicalOperatorConst,
    ParentReferencePropertyInterface,
    SubQueriable,
    TablePropertyInterface
{
    use BuildableTrait;
    use ClauseTrait;
    use ParentReferencePropertyTrait;
    use SubQueriableTrait;
    use TablePropertyTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'joinClause',
        'indexHintCollection',
    ];

    /**
     * @var JoinClause Join句
     */
    protected JoinClause    $joinClause;

    /**
     *
     * @var IndexHintCollection IndexHintコレクション
     */
    protected IndexHintCollection   $indexHintCollection;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->joinClause           = JoinClauseFactory::factory()->parentReference($this);
        $this->indexHintCollection  = IndexHintCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  WhereClause   Where述部
     */
    public static function factory(...$arguments): FromClause
    {
        return new static();
    }

    //==============================================
    // property access shortcut
    //==============================================
    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|TableReferenceExpression||FromClause    $table  テーブル参照
     * @param   null|string|TableReferenceExpression||FromClause    $alias  テーブル参照別名
     * @return  FromClause|TableReferenceExpression                 このインスタンスまたはテーブル
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this->table;
        }

        if ($table instanceof TableReferenceExpression) {
            $this->table = $table->withParentReference($this);
            if (!is_null($alias)) {
                $this->table->alias($alias);
            }
        } elseif ($table instanceof TablePropertyInterface) {
            $this->table = $table->table()->withParentReference($this);
            if (!is_null($alias)) {
                $this->table->alias($alias);
            }
        } else {
            $this->table = TableReferenceExpression::factory()->table($table, $alias)->parentReference($this);
        }

        return $this;
    }

    //==============================================
    // feature
    //==============================================
    public function from($table, $alias = null)
    {
        return $this->table($table, $alias);
    }

    public function join($table, ...$where)
    {
        $this->joinClause->join($table, ...$where);
        return $this;
    }

    public function innerJoin($table, ...$where)
    {
        $this->joinClause->innerJoin($table, ...$where);
        return $this;
    }

    public function outerJoin($table, ...$where)
    {
        $this->joinClause->outerJoin($table, ...$where);
        return $this;
    }

    public function crossJoin($table, ...$where)
    {
        $this->joinClause->crossJoin($table, ...$where);
        return $this;
    }

    public function straightJoin($table, ...$where)
    {
        $this->joinClause->straightJoin($table, ...$where);
        return $this;
    }

    public function leftJoin($table, ...$where)
    {
        $this->joinClause->leftJoin($table, ...$where);
        return $this;
    }

    public function outerLeftJoin($table, ...$where)
    {
        $this->joinClause->outerLeftJoin($table, ...$where);
        return $this;
    }

    public function rightJoin($table, ...$where)
    {
        $this->joinClause->rightJoin($table, ...$where);
        return $this;
    }

    public function outerRightJoin($table, ...$where)
    {
        $this->joinClause->outerRightJoin($table, ...$where);
        return $this;
    }

    public function naturalJoin($table, ...$where)
    {
        $this->joinClause->naturalJoin($table, ...$where);
        return $this;
    }

    public function naturalLeftJoin($table, ...$where)
    {
        $this->joinClause->naturalLeftJoin($table, ...$where);
        return $this;
    }

    public function naturalLeftOuterJoin($table, ...$where)
    {
        $this->joinClause->naturalLeftOuterJoin($table, ...$where);
        return $this;
    }

    public function naturalRightJoin($table, ...$where)
    {
        $this->joinClause->naturalRightJoin($table, ...$where);
        return $this;
    }

    public function naturalRightOuterJoin($table, ...$where)
    {
        $this->joinClause->naturalRightOuterJoin($table, ...$where);
        return $this;
    }

    // index hint
    public function indexHint($index_list, ?string $type = null, ?string $target = null, ?string $scope = null)
    {
        $this->indexHintCollection->indexHint($index_list, $type, $target, $scope);
        return $this;
    }

    public function useIndex($index_list = [], ?string $scope = null)
    {
        $this->indexHintCollection->useIndex($index_list, $scope);
        return $this;
    }

    public function useKey($index_list, ?string $scope = null)
    {
        $this->indexHintCollection->useKey($index_list, $scope);
        return $this;
    }

    public function ignoreIndex($index_list, ?string $scope = null)
    {
        $this->indexHintCollection->ignoreIndex($index_list, $scope);
        return $this;
    }

    public function ignoreKey($index_list, ?string $scope = null)
    {
        $this->indexHintCollection->ignoreKey($index_list, $scope);
        return $this;
    }

    public function forceIndex($index_list, ?string $scope = null)
    {
        $this->indexHintCollection->forceIndex($index_list, $scope);
        return $this;
    }

    public function forceKey($index_list, ?string $scope = null)
    {
        $this->indexHintCollection->forceKey($index_list, $scope);
        return $this;
    }

    public function hasEmpty()
    {
        return $this->joinClause->hasEmpty() && $this->indexHintCollection->hasEmpty();
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
        $clause_stack   = [];
        $conditions     = [];
        $values         = [];

        //==============================================
        // テーブル参照展開
        //==============================================
        $table = $this->table();
        if (is_null($table)) {
            throw new \Exception('テーブルが指定されていません。');
        }
        $clause_stack[] = $table->build()->getClause();

        //==============================================
        // INDEX HINT句展開
        //==============================================
        if (!$this->indexHintCollection->hasEmpty()) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->indexHintCollection->build()->merge($clause_stack, $conditions, $values);
        }

        //==============================================
        // JOIN句展開
        //==============================================
        if (!$this->joinClause->hasEmpty()) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->joinClause->build()->merge($clause_stack, $conditions, $values);
        }

        //==============================================
        // result
        //==============================================
        return BuildResult::factory(implode(' ', $clause_stack), $conditions, $values);
    }
}
