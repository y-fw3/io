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
 * SelectClauseテスト
 */
class SelectClauseTest extends AbstractQueryBuilderTestCase
{
    public function testQueryDistinct()
    {
        //----------------------------------------------
        $actual     = Query::select()->distinct(true)->column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT DISTINCT `message`.`message_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);
    }

    public function testQueryDisplayAll()
    {
        //----------------------------------------------
        $actual     = Query::select()->displayAll(true)->column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT ALL `message`.`message_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);
    }

    public function testSelectColumn()
    {
        //----------------------------------------------
        $actual     = Query::select()->column(Spec::COLUMN_NAME)->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT `message`.`message_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $actual     = Query::select()->column(Spec::COLUMN_NAME)->column(Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT `message`.`message_id`, `message`.`contributer_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $actual     = Query::select()->columns(Spec::COLUMN_NAME, Spec::COLUMN_NAME_2)->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT `message`.`message_id`, `message`.`contributer_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $actual     = Query::select()->columns([Spec::COLUMN_NAME, [Spec::COLUMN_NAME_2, Spec::COLUMN_ALIAS_2]])->table(Spec::TABLE_NAME)->build();
        $expected   = [
            'clause'        => 'SELECT `message`.`message_id`, `message`.`contributer_id` AS `c_id` FROM `message`',
            'conditions'    => [],
            'values'        => [],
        ];
        $this->assertBuildResult($expected, $actual);
    }
}
