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
 * JoinClauseテスト
 */
class JoinClauseTest extends AbstractQueryBuilderTestCase
{
    public function testQueryJoinClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` JOIN `contributer` ON `message`.`message_id` = `contributer`.`message_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->join(Spec::TABLE_NAME_2, Spec::COLUMN_NAME)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` JOIN `contributer` ON `message`.`message_id` = `contributer`.`contributer_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->join(Spec::TABLE_NAME_2, Spec::COLUMN_NAME, Spec::COLUMN_NAME_2)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` JOIN `contributer` ON `message`.`message_id` = `contributer`.`contributer_id`',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->join(Spec::TABLE_NAME_2, Query::where(Spec::COLUMN_NAME, Spec::COLUMN_NAME_2))->build();
        $this->assertBuildResult($expected, $actual);
    }

    public function testQueryIndexHintClause()
    {
        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX ()',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex()->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX ()',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX FOR ORDER BY ()',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([], Query::INDEX_HINT_SCOPE_ORDER_BY)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX (`idx_message_id_message`, `idx_contributer_id_contributer`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME, Spec::INDEX_NAME_2])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX FOR ORDER BY (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME], Query::INDEX_HINT_SCOPE_ORDER_BY)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX FOR JOIN (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME], Query::INDEX_HINT_SCOPE_JOIN)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX FOR GROUP BY (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex([Spec::INDEX_NAME], Query::INDEX_HINT_SCOPE_GROUP_BY)->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE KEY (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useKey([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` IGNORE INDEX (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->ignoreIndex([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` IGNORE KEY (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->ignoreKey([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` FORCE INDEX (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->forceIndex([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` FORCE KEY (`idx_message_id_message`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->forceKey([Spec::INDEX_NAME])->build();
        $this->assertBuildResult($expected, $actual);

        //----------------------------------------------
        $expected   = [
            'clause'        => 'SELECT `message`.* FROM `message` USE INDEX () IGNORE KEY (`idx_message_id_message`) FORCE INDEX (`idx_contributer_id_contributer`)',
            'conditions'    => [],
            'values'        => [],
        ];
        $actual     = Query::select()->table(Spec::TABLE_NAME)->useIndex()->ignoreKey([Spec::INDEX_NAME])->indexHint([Spec::INDEX_NAME_2], Query::INDEX_HINT_FORCE, Query::INDEX_HINT_TARGET_INDEX)->build();
        $this->assertBuildResult($expected, $actual);
    }
}
