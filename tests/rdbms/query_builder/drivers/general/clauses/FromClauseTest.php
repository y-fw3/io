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
 * FromClauseテスト
 */
class FromClauseTest extends AbstractQueryBuilderTestCase
{
    public function testFromClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX FOR ORDER BY (`idx_message_id_message`) JOIN `contributer` ON `message`.`message_id` = `contributer`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME], Query::INDEX_HINT_SCOPE_ORDER_BY)->join(Spec::TABLE_NAME_2, Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
