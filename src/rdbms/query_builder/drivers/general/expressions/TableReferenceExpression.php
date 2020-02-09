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

use fw3\io\rdbms\dbi\Dbi;
use fw3\io\rdbms\dbi\drivers\traits\dbi_drivers\DbiDriver;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionInterface;
use fw3\io\rdbms\query_builder\drivers\general\expressions\traits\ExpressionTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\union_types\UnionTypeTable;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\BuildResult;
use fw3\io\utilitys\exceptions\UnavailableVarException;

/**
 * テーブル参照式
 *
 * @method ?TableReferenceExpression parentReference(?ParentReferencePropertyInterface $parentReference = null)
 * @method TableReferenceExpression withParentReference(?object $parentReference)
 * @method TableReferenceExpression with()
 */
class TableReferenceExpression implements
    // group
    ExpressionInterface,
    // union types
    UnionTypeTable
{
    use ExpressionTrait;

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
     * @var string  テーブル参照名
     */
    protected ?string $name     = null;

    /**
     * @var string  テーブル参照別名
     */
    protected ?string $alias    = null;

    /**
     * @var DbiDriver   このテーブルが参照するDBIドライバ
     *
     * defaultでは未接続時用のdriverが設定される
     */
    protected DbiDriver $driver;

    //==============================================
    // constructor
    //==============================================
    /**
     * constructor
     */
    protected function __construct()
    {
        $this->driver = Dbi::getDisconnectedDriver();
    }

    /**
     * factory
     *
     * @param   array   ...$arguments   引数
     *  ([
     *      'name'  => null|string|UnionTypeTable テーブル名,
     *      'alias' => null|string|UnionTypeTable テーブル別名,
     *  ])
     *  または
     *  (
     *      null|string|UnionTypeTable $name    テーブル名,
     *      null|string|UnionTypeTable $alias   テーブル別名
     *  )
     * @return  TableReferenceExpression    このインスタンス
     */
    public static function factory(...$arguments): TableReferenceExpression
    {
        $instance   = new static();
        if (!empty($arguments)) {
            if (is_array($arguments[0])) {
                $arguments  = static::adjustFactoryArgByKey($arguments, [
                    ['name', 'table'],
                    'alias',
                ]);
            }
            $instance->table(...$arguments);
        }
        return $instance;
    }

    //==============================================
    // property access
    //==============================================
    /**
     * テーブル名を取得・設定します。
     *
     * @param   null|string|UnionTypeTable      $name   テーブル名
     * @return  TableReferenceExpression|string このインスタンスまたはテーブル名
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

        if ($name instanceof TablePropertyInterface) {
            $name   = $name->table();
        }

        if ($name instanceof TableReferenceExpression) {
            $this->name = $name->name();
            return $this;
        }

        UnavailableVarException::raise('使用できないテーブル参照名を指定されました。', ['name' => $name]);
    }

    /**
     * テーブル参照別名を取得・設定します。
     *
     * @param   null|string|UnionTypeTable      $alias  テーブル参照別名
     * @return  TableReferenceExpression|string このインスタンスまたはテーブル参照別名
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

        if ($alias instanceof TablePropertyInterface) {
            $alias  = $alias->table();
        }

        if ($alias instanceof TableReferenceExpression) {
            $this->alias = $alias->alias();
            return $this;
        }

        UnavailableVarException::raise('使用できないテーブル参照別名を指定されました。', ['alias' => $alias]);
    }

    /**
     * このテーブルが参照するDBIドライバを取得・設定します。
     *
     * @param   null|DbiDriver  $driver DBIドライバ
     * @return  DbiDriver       DBIドライバ
     */
    public function driver($driver = null): DbiDriver
    {
        if (is_null($driver) && func_num_args() === 0) {
            return $this->driver;
        }
        $this->driver = $driver;
        return $this->driver;
    }

    //==============================================
    // feature
    //==============================================
    /**
     * テーブル参照を取得・設定します。
     *
     * @param   null|string|UnionTypeTable  $table  テーブル参照
     * @param   null|string|UnionTypeTable  $alias  テーブル参照別名
     * @return  TableReferenceExpression    テーブル参照式
     */
    public function table($table = null, $alias = null)
    {
        if (is_null($table) && func_num_args() === 0) {
            return $this;
        }

        $this->name($table);

        if (is_null($alias) && func_num_args() === 1 && $table instanceof UnionTypeTable) {
            $this->alias($table);
            return $this;
        }

        $this->alias($alias);
        return $this;
    }

    /**
     * テーブル参照を持ったカラムを生成して返します。
     *
     * @param   string|ColumnExpression $column カラム
     * @param   string|ColumnExpression $alias  カラム別名
     * @return  ColumnExpression    テーブル参照を持ったカラム
     */
    public function createColumn($column, $alias = null)
    {
        return ColumnExpression::factory()->table($this)->column($column, $alias);
    }

    /**
     * 現在の状態におけるテーブル参照名を返します。
     *
     * @return  string  現在の状態におけるテーブル参照名
     */
    public function getReferenceName(): string
    {
        return $this->alias ?? $this->name;
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

        $clause_stack[]         = '`%s`';
        $clause_parameters[]    = is_callable($this->name) ? $this->name() : $this->name;

        if (!is_null($this->alias)) {
            $clause_stack[]         = 'AS';
            $clause_stack[]         = '`%s`';
            $clause_parameters[]    = is_callable($this->alias) ? $this->alias() : $this->alias;
        }

        $clause = vsprintf(implode(' ', $clause_stack), $clause_parameters);

        return BuildResult::factory($clause, $conditions, $values);
    }
}
