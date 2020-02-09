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

/**
 * DBIコレクション
 */
class DbiCollection
{
    /**
     * DBIコレクション
     */
    protected array $collection = [];

    public static function factory()
    {
        return new static();
    }

    public function add($spec)
    {
        $this->collection[$spec->name()]['spec'] = $spec;
        return $this;
    }

    public function set($name, $spec)
    {
    }

    public function get($name)
    {
        if ($name instanceof DbiCollection) {
            $name = $name->name();
        }
        return $this->collection[$name];
    }

    public static function __callStatic(string $name, array $arguments)
    {
        if (empty($arguments)) {
            return static::spec($name);
        } else{
            return static::spec($name, $arguments);
        }
    }
}
