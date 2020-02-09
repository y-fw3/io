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

namespace fw3\io\rdbms\query_builder\drivers\general\expressions\traits;

use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\factory_methods\FactoryMethodTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;

/**
 * 式特性
 */
trait ExpressionTrait
{
    use BuildableTrait;
    use FactoryMethodTrait;
    use ParentReferencePropertyTrait;
}
