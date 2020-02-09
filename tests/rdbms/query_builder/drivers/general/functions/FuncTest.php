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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\functions;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\io\rdbms\query_builder\drivers\general\functions\Func;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * Funtテスト
 */
class FuncTest extends AbstractQueryBuilderTestCase
{
    public function testAbs()
    {
        //----------------------------------------------
        $value  = 111;
        $expected   = [
            'clause'        => 'abs(111)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Func::abs($value)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $value  = 111;
        $expected   = [
            'clause'        => '`message_id` = abs(?)',
            'conditions'    => [$value],
            'values'        => [],
        ];
        $actual     = Query::where(Spec::COLUMN_NAME, Func::abs($value))->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $value  = 111;
        $subSelectStatement = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, Func::abs($value));
        $selectStatement    = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, $subSelectStatement);
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` IN (SELECT `message`.* FROM `message` WHERE `message`.`message_id` = abs(?))',
            'conditions'    => [$value],
            'values'        => [],
        ];
        $actual     = $selectStatement->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testChar()
    {
        //----------------------------------------------
        $value  = 111;
        $expected   = [
            'clause'        => 'char(111)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Func::char($value)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $value  = 111;
        $expected   = [
            'clause'        => '`message_id` = char(?)',
            'conditions'    => [$value],
            'values'        => [],
        ];
        $actual     = Query::where(Spec::COLUMN_NAME, Func::char($value))->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $value  = 111;
        $subSelectStatement = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, Func::char($value));
        $selectStatement    = Query::select()->table(Spec::TABLE_NAME)->where(Spec::COLUMN_NAME, $subSelectStatement);
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` IN (SELECT `message`.* FROM `message` WHERE `message`.`message_id` = char(?))',
            'conditions'    => [$value],
            'values'        => [],
        ];
        $actual     = $selectStatement->build();
        $this->assertBuildResult($expected, $actual);
    }
}
