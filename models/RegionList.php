<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "region_list".
 *
 * @property int $id
 * @property string $text
 *
 * @property BikesPrice[] $bikesPrices
 * @property Rental[] $rentals
 * @property RentalGarage[] $rentalGarages
 * @property Zakaz[] $zakazs
 */
class RegionList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'region_list';
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
        return $this->hasMany(BikesPrice::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentals()
    {
        return $this->hasMany(Rental::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalGarages()
    {
        return $this->hasMany(RentalGarage::className(), ['region_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getZakazs()
    {
        return $this->hasMany(Zakaz::className(), ['region_id' => 'id']);
    }
}
