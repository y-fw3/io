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
use fw3\io\rdbms\query_builder\drivers\general\collections\InsertColumnCollection;
use fw3\io\rdbms\query_builder\drivers\general\collections\InsertValueCollection;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * InsertValue句
 */
class InsertValueClause implements
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
        'insertColumnCollection',
        'insertValueCollection',
    ];

    protected InsertColumnCollection $insertColumnCollection;
    protected InsertValueCollection $insertValueCollection;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->insertColumnCollection   = InsertColumnCollection::factory()->parentReference($this);
        $this->insertValueCollection    = InsertValueCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     * @return  WhereClause   Where述部
     */
    public static function factory(...$arguments): InsertValueClause
    {
        return new static();
    }

    public function set($column, $value)
    {
        $this->column($column);
        $this->value($value);
        return $this;
    }

    public function sets(...$value_set)
    {
        foreach (isset($value_set[1]) ? $value_set : (array) $value_set[0] as $column => $value) {
            if (is_array($value)) {
                $column = $value[0];
                $value  = $value[1];
            }
            $this->column($column);
            $this->value($value);
        }
        return $this;
    }

    public function column($column)
    {
        $this->insertColumnCollection->column($column);
        return $this;
    }

    public function columns(...$columns)
    {
        $this->insertColumnCollection->column(...$columns);
        return $this;
    }

    public function value($value)
    {
        $this->insertValueCollection->value($value);
        return $this;
    }

    public function values(...$values)
    {
        $this->insertValueCollection->values(...$values);
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
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [];

        if (!$this->insertColumnCollection->hasEmpty()) {
            $format = '(%s)';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->insertColumnCollection->build()->merge($clause_stack, $conditions, $values, $format);
        }

        if (!$this->insertValueCollection->hasEmpty()) {
            if ($this->insertValueCollection->isOnlySubQuery()) {
                ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->insertValueCollection->build()->merge($clause_stack, $conditions, $values);
            } else {
                $clause_stack[] = 'VALUES';
                $format = '(%s)';
                ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $this->insertValueCollection->build()->merge($clause_stack, $conditions, $values, $format);
            }
        } else {
            $clause_stack[] = '()';
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
