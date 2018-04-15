<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "rental".
 *
 * @property int $id
 * @property string $phone
 * @property string $mail
 * @property string $adress
 * @property int $radius
 * @property string $name
 * @property string $hash
 * @property int $region_id
 *
 * @property RegionList $region
 * @property RentalGarage[] $rentalGarages
 */
class Rental extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rental';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['adress'], 'string'],
            [['radius', 'region_id'], 'integer'],
            [['phone', 'mail', 'name', 'hash'], 'string', 'max' => 255],
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
            'phone' => 'Phone',
            'mail' => 'Mail',
            'adress' => 'Adress',
            'radius' => 'Radius',
            'name' => 'Name',
            'hash' => 'Hash',
            'region_id' => 'Region ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(RegionList::className(), ['id' => 'region_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalGarages()
    {
        return $this->hasMany(RentalGarage::className(), ['rental_id' => 'id']);
    }
}
