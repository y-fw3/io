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

namespace fw3\io\rdbms\query_builder\drivers\general\interfaces\factorys\tables;

use \fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyInterface;
use \fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;

/**
 * Tableファクトリ特性インターフェース
 */
interface TableFactoryInterface
{
    /**
     * テーブル参照を設定したインスタンスを返します。
     *
     * @param   string|TableReferenceExpression|TablePropertyInterface      $table  テーブル参照名
     * @param   null|string|TableReferenceExpression|TablePropertyInterface $alias  テーブル参照別名
     * @return  TablePropertyInterface  TablePropertyInterfaceを実装したインスタンス
     */
    public static function table($name, $alias = null);
}
