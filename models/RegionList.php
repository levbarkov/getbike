<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "region_list".
 *
 * @property int $id
 * @property string $text
 * @property string $alias
 * @property string $coord
 * @property string $tag_line
 * @property string $adress
 * @property string $description
 * @property int $country_id
 *
 * @property BikesPrice[] $bikesPrices
 * @property CountryList $country
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
            [['country_id'], 'integer'],
            [['tag_line','adress','description'], 'string'],
            [['text','alias','coord'], 'string', 'max' => 255],
            [['alias', 'text'], 'required'],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => CountryList::className(), 'targetAttribute' => ['country_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('admin', 'ID'),
            'text' => Yii::t('admin', 'Text'),
            'country_id' => Yii::t('admin', 'Country ID'),
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
    public function getCountry()
    {
        return $this->hasOne(CountryList::className(), ['id' => 'country_id']);
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
