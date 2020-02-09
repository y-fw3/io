<?php

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\consts;

interface IndexHintConst
{
    public const INDEX_HINT_USE     = 'USE';
    public const INDEX_HINT_IGNORE  = 'IGNORE';
    public const INDEX_HINT_FORCE   = 'FORCE';

    public const INDEX_HINT_MAP = [
        self::INDEX_HINT_USE    => self::INDEX_HINT_USE,
        self::INDEX_HINT_IGNORE => self::INDEX_HINT_IGNORE,
        self::INDEX_HINT_FORCE  => self::INDEX_HINT_FORCE,
    ];

    public const INDEX_HINT_TARGET_INDEX    = 'INDEX';
    public const INDEX_HINT_TARGET_KEY      = 'KEY';

    public const INDEX_HINT_TARGET_MAP  = [
        self::INDEX_HINT_TARGET_INDEX   => self::INDEX_HINT_TARGET_INDEX,
        self::INDEX_HINT_TARGET_KEY     => self::INDEX_HINT_TARGET_KEY,
    ];

    public const INDEX_HINT_SCOPE_JOIN      = 'JOIN';
    public const INDEX_HINT_SCOPE_ORDER_BY  = 'ORDER BY';
    public const INDEX_HINT_SCOPE_GROUP_BY  = 'GROUP BY';

    public const INDEX_HINT_SCOPE_MAP   = [
        self::INDEX_HINT_SCOPE_JOIN     => self::INDEX_HINT_SCOPE_JOIN,
        self::INDEX_HINT_SCOPE_ORDER_BY => self::INDEX_HINT_SCOPE_ORDER_BY,
        self::INDEX_HINT_SCOPE_GROUP_BY => self::INDEX_HINT_SCOPE_GROUP_BY,
    ];
}
