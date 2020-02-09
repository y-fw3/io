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

namespace fw3\tests\io\rdbms\query_builder\drivers\general\traits;

use fw3\io\rdbms\query_builder\drivers\general\Query;
use fw3\tests\io\rdbms\query_builder\AbstractQueryBuilderTestCase;
use fw3\tests\io\rdbms\query_builder\drivers\general\specs\Spec;
use fw3\io\rdbms\query_builder\drivers\general\querys\SubQuery;

/**
 * SubQueriablesテスト
 */
class SubQueriablesTest extends AbstractQueryBuilderTestCase
{
    public function testConvertSubQuery()
    {
        //----------------------------------------------
        $expected   = SubQuery::class;
        $actual     = Query::select()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), 1)->convertSubQuery();
        $this->assertInstanceOf($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => '(SELECT `message`.* FROM `message` WHERE `message`.`message_id` = ?)',
            'conditions'    => [1],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->where(Spec::messageIdColumn(), 1)->convertSubQuery()->build();
        $this->assertBuildResult($expected, $actual);
    }
}
