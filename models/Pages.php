<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "pages".
 *
 * @property int $id
 * @property string $alias
 * @property string $page_menu
 * @property string $page_title
 * @property string $page_desc
 * @property string $page_code
 * @property string $page_js
 * @property string $page_css
 * @property string $language
 */
class Pages extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pages';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['alias'], 'required'],
            [['page_title', 'page_desc', 'page_code', 'page_js', 'page_css', 'page_menu', 'language'], 'string'],
            [['alias'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'alias' => 'Alias',
            'language' => 'Language',
            'page_title' => 'Page title',
            'page_menu' => 'Menu title',
            'page_desc' => 'Page Desc',
            'page_code' => 'Page Code',
            'page_js' => 'Page Js',
            'page_css' => 'Page Css',
        ];
    }
}
