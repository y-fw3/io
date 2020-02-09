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

namespace fw3\io\utilitys\exceptions;

use fw3\io\utilitys\vars\Strings;

/**
 * 使用できない変数例外
 */
class UnavailableVarException extends \Exception
{
    /**
     * factory
     *
     * @param   string|array    $message    基底メッセージ 配列を指定された場合、詳細
     * @param   array           $values     詳細を表示する[変数名 => 値]の配列
     * @param   string          $format     変数表示フォーマット
     * @param   string          $separator  変数間セパレータ
     * @return  \fw3\io\utilitys\exceptions\UnavailableVarException 使用できない変数例外
     */
    public static function factory($message, array $values = [], string $format = '%s:%s', string $separator = ', ')
    {
        if (is_array($message)) {
            $values     = $message;
            $message    = '使用できない型を値に設定されました。';

            if (is_string($values)) {
                $format = $values;
            }
        }

        $vars   = [];
        foreach ($values as $name => $value) {
            $vars[] = sprintf($format, $name, Strings::toText($value));
        }
        return new static(sprintf('%s%s', $message, implode($separator, $vars)));
    }

    /**
     * 例外を生成しthrowします。
     *
     * @param   string  $message    基底メッセージ
     * @param   array   $values     詳細を表示する[変数名 => 値]の配列
     * @param   string  $format     変数表示フォーマット
     * @param   string  $separator  変数間セパレータ
     * @exception   \fw3\io\utilitys\exceptions\UnavailableVarException 使用できない変数例外
     */
    public static function raise($message, array $values = [], string $format = '%s:%s', string $separator = ', ')
    {
        throw static::factory($message, $values, $format, $separator);
    }
}
