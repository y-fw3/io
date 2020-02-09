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
use fw3\io\rdbms\query_builder\drivers\general\functions\Func;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;

/**
 * GroupClauseテスト
 */
class GroupClauseTest extends AbstractQueryBuilderTestCase
{
    public function testGroupClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` GROUP BY `message`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->groupBy(Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` GROUP BY `message`.`message_id`, `message`.`contributer_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->groupBy(Spec::COLUMN_NAME)->groupBy(Spec::COLUMN_NAME_2)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $func       = Func::abs(1)->alias('abs');
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` GROUP BY `abs`, `message`.`contributer_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->groupBy($func)->groupBy(Spec::COLUMN_NAME_2)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
