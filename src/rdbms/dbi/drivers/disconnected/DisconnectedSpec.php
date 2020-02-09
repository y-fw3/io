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

namespace fw3\io\rdbms\dbi\drivers\disconnected;

use fw3\io\rdbms\dbi\interfaces\specs\SpecInterface;
use fw3\io\rdbms\dbi\interfaces\specs\SpecTrait;

/**
 * 未接続時に使用するDBI用スペッククラス
 */
class DisconnectedSpec implements
    SpecInterface
{
    use SpecTrait;

    public function fromArray(array $spec): DisconnectedSpec
    {
        if (isset($spec['name'])) {
            $this->name($spec['name']);
        }
        return $this;
    }
}
