<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bikes_price".
 *
 * @property int $id
 * @property int $bike_id
 * @property int $condition_id
 * @property string $photo
 * @property string $price
 * @property int $region_id
 *
 * @property Bikes $bikes
 * @property Condition $condition
 * @property RegionList $region
 */
class BikesPrice extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bikes_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_id', 'condition_id', 'region_id'], 'integer'],
            [['photo', 'price'], 'string', 'max' => 255],
            [['condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Condition::className(), 'targetAttribute' => ['condition_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegionList::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bike_id' => 'Bike ID',
            'condition_id' => 'Condition ID',
            'photo' => 'Photo',
            'price' => 'Price',
            'region_id' => 'Region ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBikes()
    {
        return $this->hasOne(Bikes::className(), ['id' => 'bike_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCondition()
    {
        return $this->hasOne(Condition::className(), ['id' => 'condition_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(RegionList::className(), ['id' => 'region_id']);
    }
}
