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

namespace fw3\io\utilitys\vars;

/**
 * 文字列ユーティリティ
 */
class Strings
{
    //==============================================
    // const
    //==============================================
    /**
     * @var string  空文字
     */
    public const EMPTY  = '';

    /**
     * @var string  タブ文字
     */
    public const TAB    = "\t";

    /**
     *  @var string  改行コード：CR
     */
    public const CR     = "\r";

    /**
     * @var string  改行コード：LF
     */
    public const LF     = "\n";

    /**
     * @var string  改行コード：CRLF
     */
    public const CRLF   = "\r\n";

    //==============================================
    // 文字列操作
    //==============================================
    /**
     * 空文字列かどうか判定します。
     *
     * @param   string  $str    空かどうか判定する文字列
     * @param   mixed   $trim   トリムを行うかどうか
     * @return  bool    空文字列の場合はbool true、そうでない場合はfalse
     */
    public static function isEmpty(string $str, bool $trim = false): bool
    {
        return is_null($str) || static::STRING_EMPTY === ((!$trim) ? $str : trim($str, $trim));
    }

    /**
     * 文字列をスネークケースに変換します。
     *
     * @param   string  $subject    スネークケースに変換する文字列
     * @param   bool    $trim       変換後に先頭の"_"をトリムするかどうか trueの場合はトリムする
     * @return  string  スネークケースに変換された文字列。
     */
    public static function toSnakeCase(string $subject, bool $trim = true): string
    {
        $subject = strtolower(preg_replace('/[A-Z]/', '_\0', $subject));
        return $trim ? ltrim($subject, '_') : $subject;
    }

    /**
     * 文字列をキャメルケースに変換します。
     *
     * @param   string  $subject    キャメルケースに変換する文字列
     * @param   string  $prefix     単語の閾に用いる文字
     * @return  string  キャメルケースに変換された文字列
     */
    public static function toCamelCase(string $subject, string $prefix = '_'): string
    {
        return lcfirst(strtr(ucwords(strtr($subject, [$prefix => ' '])), [' ' => '']));
    }

    /**
     * 文字列をアッパーキャメルケースに変換します。
     *
     * @param   string  $subject    アッパーキャメルケースに変換する文字列
     * @param   string  $prefix     単語の閾に用いる文字
     * @return  string  アッパーキャメルケースに変換された文字列
     */
    public static function toUpperCamelCase(string $subject, string $prefix = '_'): string
    {
        return ucfirst(static::toCamelCase($subject, $prefix));
    }

    /**
     * 文字列をロウアーキャメルケースに変換します。
     *
     * @param   string  $subject    ロウアーキャメルケースに変換する文字列
     * @param   string  $prefix     単語の閾に用いる文字
     * @return  string  ロウアーキャメルケースに変換された文字列
     */
    public static function toLowerCamelCase(string $subject, string $prefix = '_'): string
    {
        return lcfirst(static::toCamelCase($subject, $prefix));
    }

    /**
     * 文字列の長さを取得します。
     *
     * @param   string  $string     長さを調べたい文字列。
     * @param   string  $encoding   文字エンコーディング
     * @return  int     文字列長
     */
    public static function length(string $string, ?string $encoding = null): int
    {
        if (is_null($encoding)) {
            return mb_strlen($string);
        }
        return mb_strlen($string, $encoding);
    }

    /**
     * 値を JSON 形式にして返す。
     *
     * @param   mixed       $data   エンコードする値。resource 型以外の任意の型を指定できます。
     *                              すべての文字列データは、UTF-8 エンコードされていなければいけません。
     * @param   int         $depth  最大の深さを設定します。正の数でなければいけません。
     * @return  string|bool 成功した場合に、JSON エンコードされた文字列を返します。 失敗した場合に FALSE を返します。
     */
    public static function toJson($data, int $depth = 512): string
    {
        return json_encode($data, \JSON_HEX_TAG | \JSON_HEX_AMP | \JSON_HEX_APOS | \JSON_HEX_QUOT, $depth);
    }

    /**
     * 変数の型情報付きの文字列表現を返します。
     *
     * @param   mixed   $var    文字列表現化したい変数
     * @param   int     $depth  文字列表現化する階層
     * @return  string  文字列表現化した変数
     */
    public static function toText($var, int $depth = 0): string
    {
        $type   = gettype($var);
        switch ($type) {
            case 'boolean':
                return $var ? 'true' : 'false';
            case 'integer':
                return (string) $var;
            case 'double':
                if (false === mb_strpos($var, '.')) {
                    return sprintf('%s.0', $var);
                }
                return (string) $var;
            case 'string':
                return sprintf('\'%s\'', $var);
            case 'array':
                if ($depth < 1) {
                    return 'Array';
                }
                --$depth;
                $ret = [];
                foreach ($var as $key => $value) {
                    $ret[] = sprintf('%s => %s', static::toText($key), static::toText($value, $depth));
                }
                return sprintf('[%s]', implode(', ', $ret));
            case 'object':
                ob_start();
                var_dump($var);
                $object_status  = ob_get_clean();

                $object_status  = substr($object_status, 0, strpos($object_status, ' ('));
                $object_status  = sprintf('object(%s)', substr($object_status, 6));

                if ($depth < 1) {
                    return $object_status;
                }

                --$depth;

                $ro = new \ReflectionObject($var);

                $tmp_properties = [];
                foreach ($ro->getProperties() as $property) {
                    $state      = $property->isStatic() ? 'static' : 'dynamic';
                    $modifier   = $property->isPublic() ? 'public' : ($property->isProtected() ? 'protected' : ($property->isPrivate() ? 'private' : 'unkown modifier'));
                    $tmp_properties[$state][$modifier][]    = $property;
                }

                $properties = [];
                foreach (['static', 'dynamic'] as $state) {
                    $state_text = $state === 'static' ? 'static ' : '';
                    foreach (['public', 'protected', 'private', 'unkown modifier'] as $modifier) {
                        foreach (isset($tmp_properties[$state][$modifier]) ? $tmp_properties[$state][$modifier] : [] as $property) {
                            $property->setAccessible(true);
                            $properties[] = sprintf('%s%s %s = %s', $state_text, $modifier, static::toText($property->getName()), static::toText($property->getValue($var), $depth));
                        }
                    }
                }

                return sprintf('%s {%s}', $object_status, implode(', ', $properties));
            case 'resource':
                return sprintf('%s %s', get_resource_type($var), $var);
            case 'resource (closed)':
                return sprintf('resource (closed) %s', $var);
            case 'NULL':
                return 'NULL';
            case 'unknown type':
            default:
                return 'unknown type';
        }
    }
}
