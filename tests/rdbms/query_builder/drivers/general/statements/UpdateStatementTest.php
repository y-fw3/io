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
 * UpdateStatementテスト
 */
class UpdateStatementTest extends AbstractQueryBuilderTestCase
{
    public function testQueryExplain()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'UPDATE `message` SET `message`.`message_id` = ?',
            'conditions'    => [],
            'values'        => [1],
        ];
        $actual     = Query::update(Spec::TABLE_NAME)->set(Spec::COLUMN_NAME, 1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'UPDATE `message` SET `message`.`message_id` = ?',
            'conditions'    => [],
            'values'        => [1],
        ];
        $actual     = Query::update(Spec::TABLE_NAME)->setInt(Spec::COLUMN_NAME, 1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2);

        $query = [
            'EXPLAIN',
            'UPDATE',
            '`message`',
            'SET',
            '`message`.`message_id` = ?, `message`.`contributer_id` = `message`.`message_id`',
            'WHERE `message`.`message_id` = ? AND `message`.`contributer_id` = ?',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [1, 2],
            'values'        => [1],
        ];
        $actual     = Query::update()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->value(Spec::COLUMN_NAME, 1)
        ->value($contributerIdColumn, $messageIdColumn)
        ->where($messageIdColumn, 1)->where($contributerIdColumn, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2);

        $query = [
            'EXPLAIN',
            'UPDATE',
            '`message`',
            'SET',
            '`message`.`message_id` = ?, `message`.`contributer_id` = `message`.`message_id`',
            'WHERE `m_id` = ? AND `c_id` = ?',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [1, 2],
            'values'        => [1],
        ];
        $actual     = Query::update()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->value(Spec::COLUMN_NAME, 1)
        ->value($contributerIdColumn, $messageIdColumn)
        ->where($messageIdColumn, 1)->where($contributerIdColumn, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);
    }
}
