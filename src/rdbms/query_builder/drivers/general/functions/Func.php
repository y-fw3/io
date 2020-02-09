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

namespace fw3\io\rdbms\query_builder\drivers\general\functions;

use fw3\io\rdbms\query_builder\drivers\general\functions\math\AbsFunction;
use fw3\io\rdbms\query_builder\drivers\general\functions\strings\CharFunction;

/**
 * Function Factory
 */
abstract class Func
{
    /**
     * Xの絶対値を求める関数を返します。
     *
     * @param   int $x
     */
    public static function abs($value)
    {
        return AbsFunction::factory()->value($value);
    }

    public static function char($value)
    {
        return CharFunction::factory()->value($value);
    }
}
