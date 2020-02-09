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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\factory_methods;

/**
 * FactoryMethod特性
 */
trait FactoryMethodTrait
{
    /**
     * 引数の調整を行います。
     *
     * @param   array       $arguments  元々の引数
     * @param   array       $key_set    検索キー
     * @return  array       adjust後の引数
     */
    public static function adjustFactoryArgByKey($arguments, $key_set)
    {
        $result = [];

        foreach ($key_set as $idx => $keys) {
            foreach ((array) $keys as $key) {
                if (isset($arguments[0][$key]) || array_key_exists($key, $arguments[0])) {
                    $result[$idx] = $arguments[0][$key];
                    continue;
                }

                if (isset($arguments[0][$idx]) || array_key_exists($idx, $arguments[0])) {
                    $result[$idx] = $arguments[0][$idx];
                    continue;
                }

                if ($idx !== 0) {
                    if (isset($arguments[$idx]) || array_key_exists($idx, $arguments)) {
                        $result[$idx] = $arguments[$idx];
                        continue;
                    }
                }
            }
        }

        return $result;
    }
}