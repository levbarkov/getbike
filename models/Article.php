<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property int $country_id
 * @property int $region_id
 * @property string $title
 * @property string $page_title
 * @property string $page_desc
 * @property string $en_title
 * @property string $text
 *
 * @property Country $country
 * @property Regions $region
 */
class Article extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['country_id', 'region_id'], 'integer'],
            [['title', 'en_title', 'text'], 'required'],
            [['title', 'en_title', 'text'], 'string'],
            [['page_title', 'page_desc'], 'string', 'max' => 255],
            [['country_id'], 'exist', 'skipOnError' => true, 'targetClass' => Country::className(), 'targetAttribute' => ['country_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => Regions::className(), 'targetAttribute' => ['region_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'country_id' => 'Country ID',
            'region_id' => 'Region ID',
            'title' => 'Title',
            'page_title' => 'Page Title',
            'page_desc' => 'Page Desc',
            'en_title' => 'Alias',
            'text' => 'Text',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCountry()
    {
        return $this->hasOne(Country::className(), ['id' => 'country_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRegion()
    {
        return $this->hasOne(Regions::className(), ['id' => 'region_id']);
    }
    public function beforeSave($insert)
    {
        if(parent::beforeSave($insert)){
            if(!$this->page_title)
                $this->page_title = $this->title;
            return true;
        }
        return false;

    }
}
