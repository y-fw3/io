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

namespace fw3\io\rdbms\query_builder\drivers\general\literals\traits;

use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\Buildable;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;

/**
 * リテラルインターフェース
 */
interface LiteralInterface extends
    Buildable,
    ParentReferencePropertyInterface,
    TablePropertyInterface
{
}
