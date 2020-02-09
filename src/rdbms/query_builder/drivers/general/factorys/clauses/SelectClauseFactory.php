<?php

namespace fw3\io\rdbms\query_builder\drivers\general\clauses;

namespace fw3\io\rdbms\query_builder\drivers\general\factorys\clauses;

class SelectClauseFactory
{
    //==============================================
    // factory
    //==============================================
    /**
     * Select句を作成し返します。
     *
     * @param   array   ...$arguments   引数
     * @return  SelectClause  Select句
     */
    public static function factory(...$arguments): SelectClause
    {
        return SelectClause::factory(...$arguments);
    }
}
