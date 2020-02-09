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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\clauses;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * WhereClauseテスト
 */
class WhereClauseTest extends AbstractQueryBuilderTestCase
{
    public function testWhereClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, 1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn    = Query::table(Spec::TABLE_NAME)->createColumn(Spec::COLUMN_NAME)->alias(Spec::COLUMN_ALIAS);
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `m_id` = ?',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->where($messageIdColumn, 1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ? AND `message`.`contributer_id` = ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, 1)->where(Spec::COLUMN_NAME_2, 2)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ? AND `message`.`contributer_id` = ?',
            'conditions'    => [1, 2],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->wheres([[Spec::COLUMN_NAME, 1], [Spec::COLUMN_NAME_2, 2]])->build();
        $this->assertBuildResult($expected, $actual);
    }
}
