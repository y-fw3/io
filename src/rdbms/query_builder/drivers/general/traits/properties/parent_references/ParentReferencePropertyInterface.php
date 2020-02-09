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

namespace fw3\io\rdbms\query_builder\drivers\general\traits\properties\parent_references;

use fw3\io\rdbms\query_builder\drivers\general\expressions\TableReferenceExpression;

/**
 * 親オブジェクト参照プロパティ特性インターフェース
 */
interface ParentReferencePropertyInterface
{
    //==============================================
    // property access
    //==============================================
    /**
     * オブジェクト生成元への参照を取得・設定します。
     *
     * @param   object|null オブジェクト生成元への参照
     * @return  ParentReferencePropertyTrait|object オブジェクト生成元への参照またはこのインスタンス
     */
    public function parentReference(?ParentReferencePropertyInterface $parentReference = null): ?ParentReferencePropertyInterface;

    //==============================================
    // feature
    //==============================================
    /**
     * 自身を含めた直近のテーブル参照を取得します。
     *
     * @return  TableReferenceExpression|null   自身を含めた直近のテーブル参照
     */
    public function closestTable(): ?TableReferenceExpression;

    /**
     * 可能ならプレースホルダを使うべきかどうかを判定します。
     *
     * @return  bool    可能ならプレースホルダを使うべきな場合はtrue、そうでない場合はfalse
     */
    public function doUsePlaceholder(): bool;

    /**
     * `alias`を使ってはならない親クラスの下にいるかどうかを判定します。
     *
     * @return  bool    `alias`を使ってはならない親クラスの下にいる場合はtrue、そうでない場合はfalse
     */
    public function doNotUseAlias(): bool;

    /**
     * `AS`を使ってはならない親クラスの下にいるかどうかを判定します。
     *
     * @return  bool    `AS`を使ってはならない親クラスの下にいる場合はtrue、そうでない場合はfalse
     */
    public function doNotUseAliasKeyword(): bool;

    //==============================================
    // clone
    //==============================================
    /**
     * オブジェクトのクローン作成
     */
    public function __clone();

    /**
     * このインスタンスを複製し、オブジェクト生成元への参照を設定して返します。
     *
     * @param   object|null $parentReference    オブジェクト生成元
     * @return  ParentReferencePropertyTrait    このインスタンスまたはオブジェクト生成元への参照
     */
    public function withParentReference(?object $parentReference): ParentReferencePropertyInterface;

    //==============================================
    // debuger
    //==============================================
    /**
     * 親子オブジェクト参照情報を返します。
     *
     * @return  array   親子オブジェクト参照情報
     */
    public function getFamilyReferenceInfomation($only_classname = false): array;

    /**
     * 詳細な親オブジェクト参照情報を返します。
     *
     * @param   bool    $text_mode  オブジェクトの表示情報をテキストのみとするかどうか
     * @return  array   親オブジェクト参照情報
     */
    public function getParentReferenceInfomationDetail($text_mode = false): array;

    /**
     * 親オブジェクト参照ツリーを返します。
     *
     * @param   bool    $only_class_path    クラスパスだけを返すかどうか
     * @return  array   親オブジェクト参照ツリー
     */
    public function getParentReferenceInfomation($only_class_path = false): array;
}
