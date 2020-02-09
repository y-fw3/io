<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

interface SortOrderConst
{
    public const ASC     = 'ASC';
    public const DESC    = 'DESC';

    public const SORT_ORDER_MAP = [
        self::ASC   => self::ASC,
        self::DESC  => self::DESC,
    ];
}
