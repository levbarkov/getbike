<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "country_list".
 *
 * @property int $id
 * @property string $iso
 * @property string $alias
 * @property string $text
 * @property string $currency
 * @property string $base_cord
 *
 * @property RegionList[] $regionLists
 */
class CountryList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'country_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['iso', 'text', 'currency','alias'], 'required'],
            [['base_cord'], 'string'],
            [['iso', 'text', 'currency', 'alias'], 'string', 'max' => 255],
            [['alias'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin/message', 'ID'),
            'iso' => Yii::t('admin/message', 'Iso'),
            'alias' => Yii::t('admin/message', 'Alias'),
            'text' => Yii::t('admin/message', 'Text'),
            'currency' => Yii::t('admin/message', 'Currency'),
            'base_cord' => Yii::t('admin/message', 'Base Cord'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegionLists()
    {
        return $this->hasMany(RegionList::className(), ['country_id' => 'id']);
    }
}
