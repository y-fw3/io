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

namespace fw3\io\rdbms\query_builder\drivers\general\collections;

use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionInterface;
use fw3\io\rdbms\query_builder\drivers\general\collections\traits\CollectionTrait;
use fw3\io\rdbms\query_builder\drivers\general\predicates\JoinPredicate;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Joinコレクション
 *
 * @property    JoinPredicate[] $collection
 */
class JoinCollection implements
    CollectionInterface
{
    use CollectionTrait;

    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [
    ];

    //==============================================
    // factory
    //==============================================
    /**
     * Whereコレクションを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  JoinCollection Whereコレクション
     */
    public static function factory(...$arguments): JoinCollection
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
        $this->collection[] = JoinPredicate::factory()->join($table, ...$where)->parentReference($this);
        return $this;
    }

    public function innerJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->innerJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function outerJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->outerJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function crossJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->crossJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function straightJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->straightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function leftJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->leftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function outerLeftJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->outerLeftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function rightJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->rightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function outerRightJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->outerRightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->naturalJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalLeftJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->naturalLeftJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalLeftOuterJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->naturalLeftOuterJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalRightJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->naturalRightJoin($table, ...$where)->parentReference($this);
        return $this;
    }

    public function naturalRightOuterJoin($table, ...$where)
    {
        $this->collection[] = JoinPredicate::factory()->naturalRightOuterJoin($table, ...$where)->parentReference($this);
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
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [];

        foreach ($this->collection as $joinPredicate) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $joinPredicate->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
