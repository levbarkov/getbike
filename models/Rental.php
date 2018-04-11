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
 * @property RentalGarage $id0
 * @property RegionList $region
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
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => RentalGarage::className(), 'targetAttribute' => ['id' => 'rental_id']],
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
    public function getId0()
    {
        return $this->hasOne(RentalGarage::className(), ['rental_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(RegionList::className(), ['id' => 'region_id']);
    }
}
