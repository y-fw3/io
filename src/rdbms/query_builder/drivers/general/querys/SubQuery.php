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

namespace fw3\io\rdbms\query_builder\drivers\general\querys;

use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;

/**
 * SubQueryです。
 */
class SubQuery implements
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
    use ParentReferencePropertyTrait;
    use TablePropertyTrait;

    //==============================================
    // property
    //==============================================
    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
        'query',
    ];

    /**
     * @var mixed   Query
     */
    protected $query    = null;

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
     * @return  SubQuery    SubQuery
     */
    public static function factory(...$arguments): SubQuery
    {
        $instance   = new static();
        if (!empty($arguments)) {
            $instance->query($arguments[0]);
        }
        return $instance;
    }

    //==============================================
    // property accessor
    //==============================================
    /**
     * Queryを設定、取得します。
     *
     * @param   mixed   クエリ
     * @return  mixed   クエリまたはこのインスタンス
     */
    public function query(?ParentReferencePropertyInterface $query = null)
    {
        if (is_null($query) && func_num_args() === 0) {
            return $this->query;
        }

        $this->parentReference($query->parentReference());
        $this->query    = $query->withParentReference($this);

        return $this;
    }

    /**
     * SubQueryとして設定されたクエリをクエリとして返します。
     *
     * @return  ParentReferencePropertyInterface    クエリ
     */
    public function convertQuery()
    {
        if (is_null($this->query)) {
            return $this;
        }

        return $this->query->withParentReference($this->parentReference);
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
        $clause     = '';
        $conditions = [];
        $values     = [];

        $query = $this->query->withParentReference($this);

        $clause_stack   = [];

        ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $query->build()->merge($clause_stack, $conditions, $values);
        $clause = sprintf('(%s)', current($clause_stack));

        return BuildResult::factory($clause, $conditions, $values);
    }
}
