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
 * LimitClauseテスト
 */
class LimitClauseTest extends AbstractQueryBuilderTestCase
{
    public function testLimitClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` LIMIT 5',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->limit(5)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` LIMIT 5 OFFSET 10',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->limit(5, 10)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
