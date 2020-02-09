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

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\parent_references;

use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyInterface;

/**
 * 親オブジェクト参照ファクトリ特性
 */
trait ParentReferenceFactoryTrait
{
    /**
     * 親オブジェクト参照を設定したインスタンスを返します。
     *
     * @param   null|object $parentReference        参照する親オブジェクト
     * @return  ParentReferencePropertyInterface    ParentReferencePropertyInterfaceを実装したインスタンス
     */
    public static function parentReference(?object $parentReference): ParentReferencePropertyInterface
    {
        $instance_class_path    = static::INSTANCE_CLASS_PATH;
        return $instance_class_path::factory()->parentReference($parentReference);
    }
}
