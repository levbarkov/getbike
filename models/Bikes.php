<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bikes".
 *
 * @property int $id
 * @property string $model
 *
 * @property BikesPrice[] $bikesPrices
 * @property RentalGarage[] $rentalGarages
 */
class Bikes extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bikes';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['model'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'model' => 'Model',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBikesPrices()
    {
        return $this->hasMany(BikesPrice::className(), ['bike_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalGarages()
    {
        return $this->hasMany(RentalGarage::className(), ['bike_id' => 'id']);
    }
}
