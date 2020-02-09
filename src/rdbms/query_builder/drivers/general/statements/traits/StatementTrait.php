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

namespace fw3\io\rdbms\query_builder\drivers\general\statements\traits;

use fw3\io\rdbms\query_builder\drivers\general\traits\buildables\BuildableTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\factory_methods\FactoryMethodTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references\ParentReferencePropertyTrait;
use fw3\io\rdbms\query_builder\drivers\general\traits\properties\tables\TablePropertyTrait;
use fw3\io\utilitys\exceptions\UnavailableVarException;
use fw3\tests\io\rdbms\query_builder\drivers\general\SelectStatementTest;

/**
 * 文特性
 */
trait StatementTrait
{
    use BuildableTrait;
    use ParentReferencePropertyTrait;
    use TablePropertyTrait;
    use FactoryMethodTrait;

    /**
     * @var bool    実行計画を表示を使用するかどうか。
     */
    protected bool $explain     = false;

    /**
     * 実行計画を表示するかどうかを取得・設定します。
     *
     * @param   bool|StatementInterface $explain    実行計画を表示を使用するかどうか
     * @return  bool|StatementInterface 実行計画を表示を使用するかどうかまたはこのインスタンス
     */
    public function explain($explain = false)
    {
        if (false === $explain && func_num_args() === 0) {
            return $this->explain;
        }

        if ($explain instanceof SelectStatementTest) {
            $explain    = $explain->explain();
        }

        if (is_bool($explain)) {
            $this->explain  = $explain;
            return $this;
        }

        UnavailableVarException::raise(['explain' => $explain]);
    }
}
