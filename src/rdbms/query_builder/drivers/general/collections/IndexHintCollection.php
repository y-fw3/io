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
use fw3\io\rdbms\query_builder\drivers\general\predicates\IndexHintPredicate;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * IndexHintコレクション
 */
class IndexHintCollection implements
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
     * @return  IndexHintCollection Whereコレクション
     */
    public static function factory(...$arguments): IndexHintCollection
    {
        return new static();
    }

    //==============================================
    // property access shortcut
    //==============================================

    //==============================================
    // feature
    //==============================================
    /**
     *
     * @param unknown $index_list
     * @param string $type
     * @param string $target
     * @param string $scope
     * @return \fw3\io\rdbms\query_builder\drivers\general\collections\IndexHintCollection
     */
    public function indexHint($index_list, ?string $type = null, ?string $target = null, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->indexHint($index_list, $type, $target, $scope)->parentReference($this);
        return $this;
    }

    public function useIndex($index_list = [], ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->useIndex($index_list, $scope)->parentReference($this);
        return $this;
    }

    public function useKey($index_list, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->useKey($index_list, $scope)->parentReference($this);
        return $this;
    }

    public function ignoreIndex($index_list, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->ignoreIndex($index_list, $scope)->parentReference($this);
        return $this;
    }

    public function ignoreKey($index_list, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->ignoreKey($index_list, $scope)->parentReference($this);
        return $this;
    }

    public function forceIndex($index_list, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->forceIndex($index_list, $scope)->parentReference($this);
        return $this;
    }

    public function forceKey($index_list, ?string $scope = null)
    {
        $this->collection[] = IndexHintPredicate::factory()->forceKey($index_list, $scope)->parentReference($this);
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

        foreach ($this->collection as $indexHintPredicate) {
            ['clause_stack' => $clause_stack, 'conditions' => $conditions, 'values' => $values] = $indexHintPredicate->build()->merge($clause_stack, $conditions, $values);
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
