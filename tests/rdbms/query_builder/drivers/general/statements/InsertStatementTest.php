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
 * InsertStatementテスト
 */
class InsertStatementTest extends AbstractQueryBuilderTestCase
{
    public function testQueryExplain()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'INSERT INTO `message` (`message`.`message_id`) VALUES (?)',
            'conditions'    => [],
            'values'        => [1],
        ];
        $actual     = Query::insert(Spec::TABLE_NAME)->set(Spec::COLUMN_NAME, 1)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $query = [
            'EXPLAIN',
            'INSERT',
            'INTO',
            '`message`',
            '(`message`.`message_id`, `message`.`contributer_id`)',
            'VALUES',
            '(?, ?)',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [],
            'values'        => [1, 2],
        ];
        $actual     = Query::insert()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->set(Spec::COLUMN_NAME, 1)
        ->set(Spec::COLUMN_NAME_2, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2);

        $query = [
            'EXPLAIN',
            'INSERT',
            'INTO',
            '`message`',
            '(`message`.`message_id`, `message`.`contributer_id`)',
            'VALUES',
            '(?, ?)',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [],
            'values'        => [1, 2],
        ];
        $actual     = Query::insert()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->set($messageIdColumn, 1)
        ->set($contributerIdColumn, 2)
        ->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn        = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $contributerIdColumn    = Query::column(Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2);

        $query = [
            'EXPLAIN',
            'INSERT',
            'INTO',
            '`message`',
            '(`message`.`message_id`)',
            'VALUES',
            '(`message`.`contributer_id`)',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::insert()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->set($messageIdColumn, $contributerIdColumn)
        ->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $messageIdColumn    = Query::column(Spec::COLUMN_NAME, Spec::COLUMN_ALIAS);
        $selectStatement    = Query::select()->table(Spec::TABLE_NAME)->column($messageIdColumn)->where($messageIdColumn, 100)->limit(1);

        $query = [
            'EXPLAIN',
            'INSERT',
            'INTO',
            '`message`',
            '(`message`.`message_id`)',
            'SELECT `message`.`message_id` AS `m_id` FROM `message` WHERE `m_id` = ? LIMIT 1',
        ];
        $expected   = [
            'clause'        => implode(' ', $query),
            'conditions'    => [100],
            'values'        => [],
        ];
        $actual     = Query::insert()->table(Spec::TABLE_NAME)
        ->explain(true)
        ->set($messageIdColumn, $selectStatement)
        ->build();
        $this->assertBuildResult($expected, $actual);
    }
}
