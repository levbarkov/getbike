<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rental_garage".
 *
 * @property int $id
 * @property int $rental_id
 * @property int $bike_id
 * @property int $condition_id
 * @property string $number
 * @property int $status
 * @property string $year
 * @property string $millage
 * @property int $radius
 * @property int $region_id
 *
 * @property Rental $rental
 * @property Bikes $bike
 * @property Condition $condition
 * @property RegionList $region
 */
class RentalGarage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rental_garage';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rental_id', 'bike_id', 'condition_id', 'status', 'radius', 'region_id'], 'integer'],
            [['number', 'year', 'millage', 'price'], 'string', 'max' => 255],
            [['bike_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bikes::className(), 'targetAttribute' => ['bike_id' => 'id']],
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
            'rental_id' => 'Rental ID',
            'bike_id' => 'Bike ID',
            'condition_id' => 'Condition ID',
            'number' => 'Number',
            'status' => 'Status',
            'year' => 'Year',
            'millage' => 'Millage',
            'radius' => 'Radius',
            'region_id' => 'Region ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRental()
    {
        return $this->hasOne(Rental::className(), ['id' => 'rental_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBike()
    {
        return $this->hasOne(Bikes::className(), ['id' => 'bike_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBikeprice()
    {
		//return Yii::$app->db->createCommand("SELECT id, bike_id, condition_id, photo, max(price), region_id FROM WHERE bike_id=".bike_id." AND condition_id=".condition_id)->execute();
		//return BikesPrice::className()::find()->where(['bike_id' => 'bike_id'])->andWhere(max(['price']))->one();
        return $this->hasMany(BikesPrice::className(), ['bike_id' => 'bike_id','condition_id' => 'condition_id']);
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
