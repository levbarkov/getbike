<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "bikes".
 *
 * @property int $id
 * @property string $model
 *
 * @property BikesPrice $id0
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
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => BikesPrice::className(), 'targetAttribute' => ['id' => 'bike_id']],
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
    public function getId0()
    {
        return $this->hasOne(BikesPrice::className(), ['bike_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRentalGarages()
    {
        return $this->hasMany(RentalGarage::className(), ['bike_id' => 'id']);
    }
}
