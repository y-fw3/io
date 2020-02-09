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
use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Update句
 */
class UpdateClause implements
    Buildable,
    ClauseInterface,
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
    use BuildableTrait;
    use ClauseTrait;
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
     * @param   array   ...$arguments   引数
     * @return  WhereClause   Where述部
     */
    public static function factory(...$arguments): UpdateClause
    {
        return new static();
    }

    /**
     * テーブルを取得・設定します。
     *
     * @param   null|string|TableReferenceExpression||UpdateClause  $table  テーブル参照
     * @param   null|string|TableReferenceExpression||UpdateClause  $alias  テーブル参照別名
     * @return  UpdateClause|TableReferenceExpression               このインスタンスまたはテーブル
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
        // result
        //==============================================
        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
