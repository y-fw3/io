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
use fw3\io\rdbms\query_builder\drivers\general\expressions\RawExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Whenコレクション
 *
 * @property    \fw3\io\rdbms\query_builder\drivers\general\predicates\WhenPredicateFactory[] $collection
 */
class WhenCollection implements
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
     * Whenコレクションを作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  WhenCollection Whenコレクション
     */
    public static function factory(...$arguments): WhenCollection
    {
        return new static();
    }

    //==============================================
    // feature
    //==============================================
    public function when($condition, $result): WhenCollection
    {
        $this->collection[] = [$this->adjustArgument($condition), $this->adjustArgument($result)];
        return $this;
    }

    protected function adjustArgument($argument)
    {
        if ($argument instanceof ParentReferencePropertyInterface) {
            return $argument->withParentReference($this);
        }

        if (is_object($argument)) {
            return clone $argument;
        }

        return RawExpression::factory()->clause('?')->conditions([$argument]);
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

        foreach ($this->collection as $when) {
            $condition  = $when[0];
            $clause_stack[] = 'WHEN';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $condition->build()->merge($clause_stack, $conditions, $values);

            $result     = $when[1];
            $clause_stack[] = 'THEN';
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $result->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
