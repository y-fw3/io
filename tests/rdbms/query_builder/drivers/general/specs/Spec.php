<?php

namespace fw3\tests\io\rdbms\query_builder\drivers\general\specs;

use fw3\io\rdbms\query_builder\drivers\general\Query;

class Spec
{
    const TABLE_NAME        = 'message';
    const TABLE_ALIAS       = 'm';
    const COLUMN_NAME       = 'message_id';
    const COLUMN_ALIAS      = 'm_id';
    const INDEX_NAME        = 'idx_message_id_message';

    const TABLE_NAME_2      = 'contributer';
    const TABLE_ALIAS_2     = 'c';
    const COLUMN_NAME_2     = 'contributer_id';
    const COLUMN_ALIAS_2    = 'c_id';
    const INDEX_NAME_2      = 'idx_contributer_id_contributer';

    const DATE      = '2020-06-07';
    const FLOAT     = 12345.6;
    const STRING    = '"あ"い"';
    const TIME      = '12:13:14';
    const TIMESTAMP = '1591531994.123456';

    public static function messageIdColumn($use_alias = false)
    {
        $column = Query::column(static::COLUMN_NAME)->table(static::TABLE_NAME);
        if ($use_alias) {
            $column->alias(static::COLUMN_ALIAS);
        }
        return $column;
    }
}
