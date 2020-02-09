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

namespace fw3\io\rdbms\query_builder\drivers\general\predicates;

use fw3\io\rdbms\query_builder\drivers\general\interfaces\consts\IndexHintConst;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateInterface;
use fw3\io\rdbms\query_builder\drivers\general\predicates\traits\PredicateTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * IndexHint述部インスタンスクラス
 *
 * @method ?IndexHintPredicate parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method IndexHintPredicate withParentReference(?object $parentReference)
 * @method IndexHintPredicate|TableReferenceExpression table(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method IndexHintPredicate|TableReferenceExpression withTable(null|string|UnionTypeTable $table, null|string|UnionTypeTable $alias)
 * @method IndexHintPredicate with()
 */
class IndexHintPredicate implements
    // group
    PredicateInterface,
    // const
    IndexHintConst
{
    use PredicateTrait;

    protected ?string $type     = null;
    protected ?string $target   = null;
    protected ?string $scope    = null;
    protected $indexList  = [];

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
     * @return  WherePredicate  Where述部
     */
    public static function factory(...$arguments): IndexHintPredicate
    {
        $instance   = new static();
        return $instance;
    }

    //==============================================
    // properties
    //==============================================
    public function type(?string $type = null)
    {
        if (is_null($type) && func_num_args() === 0) {
            return $this->type;
        }

        if (!isset(static::INDEX_HINT_MAP[$type])) {
            UnavailableVarException::raise('未定義のtypeを指定されました。', ['type' => $type]);
        }

        $this->type = $type;
        return $this;
    }

    public function target(?string $target = null)
    {
        if (is_null($target) && func_num_args() === 0) {
            return $this->target;
        }

        if (!isset(static::INDEX_HINT_TARGET_MAP[$target])) {
            UnavailableVarException::raise('未定義のtargetを指定されました。', ['target' => $target]);
        }

        $this->target = $target;
        return $this;
    }

    public function scope(?string $scope = null)
    {
        if (is_null($scope) && func_num_args() === 0) {
            return $this->scope;
        }

        if (!is_null($scope) && !isset(static::INDEX_HINT_SCOPE_MAP[$scope])) {
            UnavailableVarException::raise('未定義のscopeを指定されました。', ['scope' => $scope]);
        }

        $this->scope = $scope;
        return $this;
    }

    public function indexList($index_list = null)
    {
        if (is_null($index_list) && func_num_args() === 0) {
            return $this->indexList;
        }

        $this->indexList = $index_list;
        return $this;
    }

    //==============================================
    // feature
    //==============================================
    public function indexHint($index_list, ?string $type = null, ?string $target = null, ?string $scope = null)
    {
        return $this->indexList($index_list)->type($type)->target($target)->scope($scope);
    }

    public function useIndex($index_list = [], ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_USE)->target(static::INDEX_HINT_TARGET_INDEX)->scope($scope);
    }

    public function useKey($index_list = [], ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_USE)->target(static::INDEX_HINT_TARGET_KEY)->scope($scope);
    }

    public function ignoreIndex($index_list, ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_IGNORE)->target(static::INDEX_HINT_TARGET_INDEX)->scope($scope);
    }

    public function ignoreKey($index_list, ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_IGNORE)->target(static::INDEX_HINT_TARGET_KEY)->scope($scope);
    }

    public function forceIndex($index_list, ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_FORCE)->target(static::INDEX_HINT_TARGET_INDEX)->scope($scope);
    }

    public function forceKey($index_list, ?string $scope = null)
    {
        return $this->indexList($index_list)->type(static::INDEX_HINT_FORCE)->target(static::INDEX_HINT_TARGET_KEY)->scope($scope);
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
        $clause         = '';
        $conditions     = [];
        $values         = [];

        $clause_stack   = [
            $this->type,
            $this->target,
        ];

        if (!is_null($this->scope)) {
            $clause_stack[] = 'FOR';
            $clause_stack[] = $this->scope;
        }

        $index_list         = (array) $this->indexList;
        $index_list_empty   = empty($index_list);
        if ($this->type !== static::INDEX_HINT_USE && $index_list_empty) {
            // error
        }

        if ($index_list_empty) {
            $clause_stack[] = '()';
        } else {
            $clause_stack[] = sprintf('(`%s`)', implode('`, `', $index_list));
        }

        $clause = implode(' ', $clause_stack);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
