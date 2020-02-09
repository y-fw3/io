<?php

namespace fw3\io\rdbms\query_builder\drivers\general\factorys;

use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryInterface;
use fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\QueryBuilderFactoryTrait;
use fw3\io\rdbms\query_builder\drivers\general\statements\InsertStatement;

abstract class InsertQueryBuilder implements
    QueryBuilderFactoryInterface
{
    use QueryBuilderFactoryTrait;

    /**
     * @var string  このファクトリが取り扱うインスタンスクラスパス
     */
    protected const INSTANCE_CLASS_PATH = InsertQueryBuilder::class;

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
     * @return  InsertStatement Select文
     */
    public static function factory(...$arguments): InsertStatement
    {
        return InsertStatement::factory(...$arguments);
    }

    //==============================================
    // feature shortcut
    //==============================================
    public static function set($column, $value)
    {
        return InsertStatement::factory()->set($column, $value);
    }

    public static function sets()
    {}

    public static function column()
    {}

    public static function columns()
    {}

    public static function value()
    {}

    public static function values()
    {}
}
