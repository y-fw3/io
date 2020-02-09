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
use fw3\io\rdbms\query_builder\drivers\general\collections\WhereCollection;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\collections\CollectionPropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\value_objects\build_result\interfaces\BuildResultInterface;

/**
 * Having句
 */
class HavingClause implements
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
        $this->collection   = WhereCollection::factory()->parentReference($this);
    }

    /**
     * factory
     *
     * @param   array           ...$arguments   引数
     * @return  SelectClause    Select文
     */
    public static function factory(...$arguments): HavingClause
    {
        return new static(...$arguments);
    }

    //==============================================
    // features
    //==============================================
    /**
     * Having句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|      $left_expression    左辺式
     * @param   string|null $right_expression   右辺式
     * @param   string|null                     $operator           比較演算子
     * @return  HavingClause   このインスタンス
     */
    public function having($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): HavingClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->where($left_expression);
            return $this;
        }
        $this->collection->where($left_expression, $right_expression, $operator);
        return $this;
    }


    /**
     * Having句構築時に前に対して"AND"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  HavingClause   このインスタンス
     */
    public function andHaving($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): HavingClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->andWhere($left_expression);
            return $this;
        }
        $this->collection->addWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Having句構築時に前に対して"OR"として展開するこのインスタンスを返します。
     *
     * @param   string|UnionTypeWhere|ColumnExpression  $left_expression    左辺式
     * @param   string|UnionTypeWhere|ColumnExpression  $right_expression   右辺式
     * @param   null|string|UnionTypeWhere              $operator           比較演算子
     * @return  HavingClause このインスタンス
     */
    public function orHaving($left_expression, $right_expression = null, ?string $operator = self::OP_EQ): HavingClause
    {
        if (is_null($right_expression) && func_num_args() === 1) {
            $this->collection->orWhere($left_expression);
            return $this;
        }
        $this->collection->orWhere($left_expression, $right_expression, $operator);
        return $this;
    }

    /**
     * Havingをまとめて設定します。
     *
     * @param   array   ...$wheres
     * @return  WhereClausePropertyInterface
     */
    public function havings(...$wheres)
    {
        $this->collection->wheres(...$wheres);
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
