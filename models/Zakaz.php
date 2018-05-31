<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "zakaz".
 *
 * @property int $id
 * @property int $rental_id
 * @property string $user_phone
 * @property int $garage_id
 * @property string $date_for
 * @property string $date_to
 * @property string $curr_date
 * @property int $price
 * @property int $pay_id
 * @property int $region_id
 *
 * @property Pay $pay
 * @property RegionList $region
 */
class Zakaz extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'zakaz';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rental_id', 'garage_id', 'price', 'pay_id', 'region_id'], 'integer'],
            [['date_for', 'date_to', 'curr_date'], 'safe'],
            [['user_name', 'user_email', 'user_phone'], 'string', 'max' => 255],
            [['pay_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pay::className(), 'targetAttribute' => ['pay_id' => 'id']],
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
            'user_name' => 'User Name',            
            'user_email' => 'User E-mail',            
            'user_phone' => 'User Phone',            
            'garage_id' => 'Garage ID',
            'date_for' => 'Date For',
            'date_to' => 'Date To',
            'curr_date' => 'Curr Date',
            'price' => 'Price',
            'pay_id' => 'Pay ID',
            'region_id' => 'Region ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPay()
    {
        return $this->hasOne(Pay::className(), ['id' => 'pay_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(RegionList::className(), ['id' => 'region_id']);
    }
}
