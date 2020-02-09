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
use fw3\io\rdbms\query_builder\drivers\general\collections\JoinCollection;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\ComparisonOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\LogicalOperatorConst;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriable;
use fw3\io\rdbms\query_builder\drivers\general\traits\sub_queriables\SubQueriableTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Join句
 *
 * @property    JoinCollection  $collection Joinコレクション
 */
class JoinClause implements
    Buildable,
    ClauseInterface,
    CollectionPropertyInterface,
    ComparisonOperatorConst,
    LogicalOperatorConst,
    ParentReferencePropertyInterface,
    SubQueriable,
    TablePropertyInterface
{
    use BuildableTrait;
    use ClauseTrait;
    use CollectionPropertyTrait;
    use ParentReferencePropertyTrait;
    use SubQueriableTrait;
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
        $this->collection   = JoinCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  JoinClause   Where述部
     */
    public static function factory(...$arguments): JoinClause
    {
        return new static();
    }

    //==============================================
    // property access shortcut
    //==============================================

    //==============================================
    // feature
    //==============================================
    public function join($table, ...$where)
    {
        $this->collection->join($table, ...$where);
        return $this;
    }

    public function innerJoin($table, ...$where)
    {
        $this->collection->innerJoin($table, ...$where);
        return $this;
    }

    public function outerJoin($table, ...$where)
    {
        $this->collection->outerJoin($table, ...$where);
        return $this;
    }

    public function crossJoin($table, ...$where)
    {
        $this->collection->crossJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function straightJoin($table, ...$where)
    {
        $this->collection->straightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function leftJoin($table, ...$where)
    {
        $this->collection->leftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function outerLeftJoin($table, ...$where)
    {
        $this->collection->outerLeftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function rightJoin($table, ...$where)
    {
        $this->collection->rightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function outerRightJoin($table, ...$where)
    {
        $this->collection->outerRightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalJoin($table, ...$where)
    {
        $this->collection->naturalJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalLeftJoin($table, ...$where)
    {
        $this->collection->naturalLeftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalLeftOuterJoin($table, ...$where)
    {
        $this->collection->naturalLeftOuterJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalRightJoin($table, ...$where)
    {
        $this->collection->naturalRightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalRightOuterJoin($table, ...$where)
    {
        $this->collection->naturalRightOuterJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    //==============================================
    // builder
    //==============================================
    /**
     * ビルダ
     *
     * @return  BuildResultInterface ビルド結果
     */
    public function build(): BuildResultInterface
    {
        return $this->collection->build();
    }
}
