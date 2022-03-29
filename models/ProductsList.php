<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%products_list}}".
 *
 * @property int $id
 * @property string $product_alias
 * @property string $product_h1 Заголовок H1
 * @property string $product_price
 * @property int $season_id
 * @property string $season_name
 * @property string $floor_name
 * @property int $floor_id
 * @property int $brand_id
 * @property string $brand_name
 * @property int $category_id
 * @property string $category_name
 * @property double $discount_value
 * @property string $discount_unit
 * @property string $image_alias
 */
class ProductsList extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products_list}}';
    }

    public static function primaryKey()
    {
        return ['id'];
    }
  
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'season_id', 'floor_id', 'brand_id', 'category_id'], 'integer'],
            [['product_alias', 'product_price', 'season_id', 'floor_id'], 'required'],
            [['product_price', 'discount_value'], 'number'],
            [['product_alias'], 'string', 'max' => 100],
            [['product_h1'], 'string', 'max' => 150],
            [['season_name', 'brand_name', 'category_name'], 'string', 'max' => 50],
            [['floor_name'], 'string', 'max' => 8],
            [['discount_unit'], 'string', 'max' => 11],
            [['image_alias'], 'string', 'max' => 400],
            [['image_path'], 'string', 'max' => 400],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'product_alias' => Yii::t('app', 'Product Alias'),
            'product_h1' => Yii::t('app', 'Заголовок H1'),
            'product_price' => Yii::t('app', 'Product Price'),
            'season_id' => Yii::t('app', 'Season ID'),
            'season_name' => Yii::t('app', 'Season Name'),
            'floor_name' => Yii::t('app', 'Floor Name'),
            'floor_id' => Yii::t('app', 'Floor ID'),
            'brand_id' => Yii::t('app', 'Brand ID'),
            'brand_name' => Yii::t('app', 'Brand Name'),
            'category_id' => Yii::t('app', 'Category ID'),
            'category_name' => Yii::t('app', 'Category Name'),
            'discount_value' => Yii::t('app', 'Discount Value'),
            'discount_unit' => Yii::t('app', 'Discount Unit'),
            'image_alias' => Yii::t('app', 'Image Alias'),
        ];
    }
  
  
    /**
     * Gets query for [[ProductSizes]].
     *
     * @return \yii\db\ActiveQuery|ProductSizeQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(ProductSizes::class, ['ps_product_id' => 'id']);
    }
  
    /**
     * Gets query for [[ProductColors]].
     *
     * @return \yii\db\ActiveQuery|ProductColorQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductColors::class, ['product_id' => 'id']);
    }
  
  
    /**
     * Gets query for [[Id0]].
     *
     * @return \yii\db\ActiveQuery|ImageQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['itemId' => 'id'])->where(['image.modelName' => "Products", 'image.isMain' => 1]);
    }
  
    /**
     * @return float|int
     */
    public function getCurrentPrice()
    {
        $currentPrice = $this->product_price;
      
        if (!empty($this->productDiscount)) {
            switch ($this->productDiscount->discount_unit_id) {
                case 1:
                    $currentPrice = $this->product_price * (1 - 1 / 100 * $this->productDiscount->discount_value);
                    break;
                case 2:
                    $currentPrice = $this->product_price - $this->productDiscount->discount_value;
            }
        }
        return $currentPrice;
    }
  
    
    /**
     * @param false $favoritesProducts
     * @return string
     */
    public function getFavoritesButtonClass($favoritesProducts = false){
        if(isset($favoritesProducts[$this->id])){
            return 'product-heart-button-active';
        }
        return 'product-heart-button';
    }
  
    /**
     * {@inheritdoc}
     * @return ProdctsListQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsListQuery(get_called_class());
    }
}
