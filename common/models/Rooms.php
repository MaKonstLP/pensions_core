<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "rooms".
 *
 * @property int $id
 * @property int $gorko_id
 * @property int $name
 * @property int $restaurant_id
 * @property int $price
 * @property int $min_capacity
 * @property int $max_capacity
 * @property int $type
 * @property string $type_name
 */
class Rooms extends \yii\db\ActiveRecord
{

    public $admin_flag = false;

    public static function tableName()
    {
        return 'rooms';
    }

    public function rules()
    {
        return [
            [['gorko_id', 'name'], 'required'],
            [['gorko_id', 'restaurant_id', 'price', 'capacity_reception', 'capacity', 'type', 'rent_only', 'banquet_price', 'bright_room', 'separate_entrance', 'active', 'in_elastic', 'payment_model'], 'integer'],
            [['type_name', 'name', 'features', 'cover_url', 'description', 'graduation_category'], 'string'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'gorko_id' => 'Gorko ID',
            'name' => 'Name',
            'restaurant_id' => 'Restaurant ID',
            'price' => 'Price',
            'min_capacity' => 'Min Capacity',
            'max_capacity' => 'Max Capacity',
            'type' => 'Type',
            'type_name' => 'Type Name',
        ];
    }

    public function getRestaurants(){
        return $this->hasOne(Restaurants::className(), ['gorko_id' => 'restaurant_id']);
    }

    public function getImages(){
        return $this->hasMany(Images::className(), ['item_id' => 'gorko_id'])->where(['type' => 'rooms']);
    }

}
