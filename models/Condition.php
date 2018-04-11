<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "condition".
 *
 * @property int $id
 * @property string $text
 *
 * @property BikesPrice[] $bikesPrices
 * @property RentalGarage[] $rentalGarages
 */
class Condition extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'condition';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBikesPrices()
    {
        return $this->hasMany(BikesPrice::className(), ['condition_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalGarages()
    {
        return $this->hasMany(RentalGarage::className(), ['condition_id' => 'id']);
    }
}
