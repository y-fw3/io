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
 * OrderClauseテスト
 */
class OrderClauseTest extends AbstractQueryBuilderTestCase
{
    public function testOrderClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderBy(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id` ASC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderBy(Spec::COLUMN_NAME, Query::ASC)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id` DESC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderBy(Spec::COLUMN_NAME, Query::DESC)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id`, `message`.`contributer_id` DESC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderBy(Spec::COLUMN_NAME)->orderBy(Spec::COLUMN_NAME_2, Query::DESC)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $descColumn = Query::table(Spec::TABLE_NAME_2)->createColumn(Spec::COLUMN_NAME_2)->alias(Spec::COLUMN_ALIAS_2);
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id`, `c_id` DESC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderBy(Spec::COLUMN_NAME)->orderBy($descColumn, Query::DESC)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $descColumn = Query::table(Spec::TABLE_NAME_2)->createColumn(Spec::COLUMN_NAME_2)->alias(Spec::COLUMN_ALIAS_2);
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id` ASC, `c_id` DESC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderByAsc(Spec::COLUMN_NAME)->orderByDesc($descColumn, Query::DESC)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testOrderAsc()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id` ASC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderByAsc(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testOrderDesc()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` ORDER BY `message`.`message_id` DESC',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->orderByDesc(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
