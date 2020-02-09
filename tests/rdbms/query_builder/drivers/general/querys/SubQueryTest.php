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
 * SubQueryTestテスト
 */
class SubQueryTest extends AbstractQueryBuilderTestCase
{
    public function testSubQuery()
    {
        $values = [];
        $messageIdColumn    = Query::column(Spec::COLUMN_NAME);
        $selectStatement    = Query::select()->column($messageIdColumn)->where($messageIdColumn, [1, 2, 3]);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` WHERE `message`.`message_id` IN (SELECT `message`.`message_id` FROM `message` WHERE `message`.`message_id` IN (?, ?, ?))',
            'conditions'    => [1, 2, 3],
            'values'        => $values,
        ];

        $actual     = Query::select()->table(Spec::TABLE_NAME)->where($messageIdColumn, $selectStatement)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
