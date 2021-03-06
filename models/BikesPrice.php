<?php

namespace app\models;

use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "bikes_price".
 *
 * @property int $id
 * @property int $bike_id
 * @property int $condition_id
 * @property string $photo
 * @property string $price
 * @property string $pricepm
 * @property int $region_id
 * @property int $cCheck
 *
 * @property Bikes $bike
 * @property Condition $condition
 * @property RegionList $region
 */
class BikesPrice extends \yii\db\ActiveRecord
{

    public $imgFile;
    public $cCheck;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'bikes_price';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['bike_id', 'condition_id', 'region_id'], 'integer'],
            [['photo', 'price', 'pricepm'], 'string', 'max' => 255],
            [['price'], 'required'],
            [['bike_id'], 'exist', 'skipOnError' => true, 'targetClass' => Bikes::className(), 'targetAttribute' => ['bike_id' => 'id']],
            [['condition_id'], 'exist', 'skipOnError' => true, 'targetClass' => Condition::className(), 'targetAttribute' => ['condition_id' => 'id']],
            [['region_id'], 'exist', 'skipOnError' => true, 'targetClass' => RegionList::className(), 'targetAttribute' => ['region_id' => 'id']],
            [['imgFile'], 'file', 'extensions' => 'png, jpg'],
            [['imgFile','cCheck'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'bike_id' => 'Bike ID',
            'condition_id' => 'Condition ID',
            'photo' => 'Photo',
            'price' => 'Price',
            'pricepm' => 'Price per month',
            'region_id' => 'Region ID',
            'cCheck' => 'Save',
        ];
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

    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
            if($this->imgFile) {
                if($this->photo && file_exists(Yii::getAlias('@uploadBikePhoto/').$this->photo))
                    unlink(Yii::getAlias('@uploadBikePhoto/').$this->photo);
                /** @var $file UploadedFile*/
                $file = $this->imgFile;
                $dir = Yii::getAlias('@uploadBikePhoto/');
                if(!file_exists($dir))
                    FileHelper::createDirectory($dir, 755, true);
                $fileName = time(). '_' . $this->id . '.' . $file->extension;
                $file->saveAs($dir . $fileName);
                $this->photo = $fileName;
                $this->updateAttributes(['photo']);
            }else{
                $bikes = BikesPrice::findAll(['bike_id' => $this->bike_id, 'condition_id' => $this->condition_id]);
                foreach ($bikes as $bike){
                    if($bike->photo && !empty($bike->photo)){
                        $this->photo = $bike->photo;
                        $this->updateAttributes(['photo']);
                        break;
                    }
                }
            }
    }
    public function beforeDelete(){
        if (parent::beforeDelete()) {
            $check_bike = BikesPrice::findAll(['condition_id' => $this->condition_id, 'bike_id' => $this->bike_id]);
            if(count($check_bike) > 1){
                return true;
            }
            if($this->photo && file_exists(Yii::getAlias('@uploadBikePhoto/').$this->photo))
                unlink(Yii::getAlias('@uploadBikePhoto/').$this->photo);
            return true;
        } else {
            return false;
        }
    }
}
