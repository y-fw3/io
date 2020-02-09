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

namespace fw3\tests\io\rdbms\query_builder\drivers\general;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * DeleteStatementテスト
 */
class DeleteStatementTest extends AbstractQueryBuilderTestCase
{
    public function testQueryExplain()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'DELETE FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::delete(Spec::TABLE_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2);

        $query = [
            'EXPLAIN',
            'DELETE',
            'FROM',
            '`message`',
            'WHERE `message`.`message_id` = ? AND `message`.`contributer_id` = ?',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = Query::delete()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->where($messageIdColumn, 1)->where($contributerIdColumn, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2);

        $query = [
            'EXPLAIN',
            'DELETE',
            'FROM',
            '`message`',
            'WHERE `m_id` = ? AND `c_id` = ?',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = Query::delete()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->where($messageIdColumn, 1)->where($contributerIdColumn, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);
    }
}
