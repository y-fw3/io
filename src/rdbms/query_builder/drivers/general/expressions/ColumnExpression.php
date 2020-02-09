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

declare(strict_types=1);

namespace fw3\io\rdbms\query_builder\drivers\general\expressions;

use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionTrait;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAlias;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\children\ChildrenDoNotUseAliasKeyword;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\type_extends\where_predicates\expressions\WherePredicatesExpression;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeTable;

/**
 * テーブル参照式
 *
 * @method ?ColumnExpression parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method ColumnExpression withParentReference(?object $parentReference)
 * @method ColumnExpression with()
 */
class ColumnExpression implements
    // group
    ExpressionInterface,
    // trait
    TablePropertyInterface,
    // type extend
    WherePredicatesExpression
{
    use ExpressionTrait;
    use TablePropertyTrait;

    //==============================================
    // const
    //==============================================
    /**
     * @var string[]    インスタンス複製時に合わせてインスタンスを複製するプロパティ名
     */
    protected const CLONE_PROPERTIES    = [];

    //==============================================
    // property
    //==============================================
    /**
     * @var string  カラム名
     */
    protected ?string $name     = null;

    /**
     * @var string  カラム別名
     */
    protected ?string $alias    = null;

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
     *  ([
     *      'name'  => null|string|ColumnExpression カラム名,
     *      'alias' => null|string|ColumnExpression カラム別名,
     *      'table' => null|string|UnionTypeTable   テーブル,
     *  ])
     *  または
     *  (
     *      null|string|ColumnExpression    $name   カラム名,
     *      null|string|ColumnExpression    $alias  カラム別名,
     *      null|string|UnionTypeTable      $table  テーブル
     *  )
     * @return  TableReferenceExpression    このインスタンス
     */
    public static function factory(...$arguments): ColumnExpression
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = static::adjustFactoryArgByKey($arguments, [
                    ['name', 'column'],
                    'alias',
                    'table',
                ]);
            }
            $instance->column(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property access
    //==============================================
    /**
     * カラム名を取得・設定します。
     *
     * @param   null|string|ColumnExpression    $name   カラム名
     * @return  null|string|ColumnExpression    カラム名またはこのインスタンス
     */
    public function name($name = null)
    {
        if (is_null($name) && func_num_args() === 0) {
            return $this->name;
        }

        if (is_string($name)) {
            $this->name = $name;
            return $this;
        }

        if ($name instanceof ColumnExpression) {
            $this->name = $name->name();
            return $this;
        }

        UnavailableVarException::raise('使用できないカラム名を指定されました。', ['name' => $name]);
    }

    /**
     * カラム別名を取得・設定します。
     *
     * @param   null|string|ColumnExpression    $alias  カラム名またはこのインスタンス
     * @return  null|string|ColumnExpression    カラム別名またはこのインスタンス
     */
    public function alias($alias = null)
    {
        if (is_null($alias) && func_num_args() === 0) {
            return $this->alias;
        }

        if (is_string($alias)) {
            $this->alias   = $alias;
            return $this;
        }

        if (is_null($alias)) {
            $this->alias   = $alias;
            return $this;
        }

        if ($alias instanceof ColumnExpression) {
            $this->alias   = $alias->alias();
            return $this;
        }

        UnavailableVarException::raise('使用できないカラム別名を指定されました。', ['alias' => $alias]);
    }

    //==============================================
    // feature
    //==============================================
    /**
     * カラムを取得・設定します。
     *
     * @param   string|ColumnExpression $column カラム
     * @param   string|ColumnExpression $alias  カラム別名
     * @param   string|UnionTypeTable   $table  テーブル
     * @return  ColumnExpression        このインスタンス
     */
    public function column($column = null, $alias = null, $table = null)
    {
        if (is_null($column) && func_num_args() === 0) {
            return $this;
        }

        $this->name($column);

        if (is_null($alias) && func_num_args() === 1 && $column instanceof ColumnExpression) {
            $alias = $column;
        }
        $this->alias($alias);

        if (!is_null($table)) {
            $this->table($table);
        }

        return $this;
    }

    /**
     * 現在の状態におけるカラム参照名を返します。
     *
     * @return  string  現在の状態におけるカラム参照名
     */
    public function getReferenceName(): string
    {
        if (!is_null($this->alias)  && !($this->parentReference instanceof ChildrenDoNotUseAlias)) {
            return is_callable($this->alias) ? $this->alias() : $this->alias;
        }
        return is_callable($this->name) ? $this->name() : $this->name;
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

        $clause_stack       = [];
        $clause_parameters  = [];

        $column_format  = $this->name === '*' ? '%s' : '`%s`';

        $table  = $this->closestTable();

        if ($this->parentReference instanceof ChildrenDoNotUseAliasKeyword) {
            if ($this->parentReference instanceof ChildrenDoNotUseAlias) {
                if (!is_null($table)) {
                    $clause_stack[] = sprintf('`%%s`.%s', $column_format);
                    $clause_parameters[]    = $table->getReferenceName();
                } else {
                    $clause_stack[] = $column_format;
                }
                $clause_parameters[]    = $this->name();
            } else {
                if (is_null($this->alias) && !is_null($table)) {
                    $clause_stack[] = sprintf('`%%s`.%s', $column_format);
                    $clause_parameters[]    = $table->getReferenceName();
                } else {
                    $clause_stack[] = $column_format;
                }
                $clause_parameters[]    = $this->getReferenceName();
            }

        } else {
            if (!is_null($table)) {
                $clause_stack[] = sprintf('`%%s`.%s', $column_format);
                $clause_parameters[]    = $table->getReferenceName();
            } else {
                $clause_stack[] = $column_format;
            }
            $clause_parameters[]    = is_callable($this->name) ? $this->name() : $this->name;

            if (!is_null($this->alias)) {
                $clause_stack[]         = 'AS';
                $clause_stack[]         = '`%s`';
                $clause_parameters[]    = is_callable($this->alias) ? $this->alias() : $this->alias;
            }
        }

        $clause = vsprintf(implode(' ', $clause_stack), $clause_parameters);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
