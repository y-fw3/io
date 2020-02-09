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
use fw3\io\rdbms\query_builder\drivers\general\collections\GroupCollection;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Group句
 */
class GroupClause implements
    ClauseInterface,
    CollectionPropertyInterface,
    TablePropertyInterface,
    ParentReferencePropertyInterface
{
    use ClauseTrait;
    use CollectionPropertyTrait;
    use TablePropertyTrait;
    use ParentReferencePropertyTrait;

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
        $this->collection   = GroupCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array           ...$arguments   引数
     * @return  SelectClause    Select文
     */
    public static function factory(...$arguments): GroupClause
    {
        return new static(...$arguments);
    }

    //==============================================
    // features
    //==============================================
    public function group($column, $table = null)
    {
        $this->collection->group($column, $table);
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
        return $this->collection->build();
    }
}
