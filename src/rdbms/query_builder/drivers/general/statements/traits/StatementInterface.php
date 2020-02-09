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

namespace fw3\io\rdbms\query_builder\drivers\general\statements\traits;

use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;

/**
 * 文特性インターフェース
 */
interface StatementInterface extends
    Buildable,
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
    /**
     * factory
     *
     * @param   array               ...$arguments   引数
     * @return  StatementInterface  文
     */
    public static function factory(...$arguments): StatementInterface;

    /**
     * 実行計画を表示するかどうかを取得・設定します。
     *
     * @param   bool|StatementInterface $explain    実行計画を表示を使用するかどうか
     * @return  bool|StatementInterface 実行計画を表示を使用するかどうかまたはこのインスタンス
     */
    public function explain($explain = false);
}
