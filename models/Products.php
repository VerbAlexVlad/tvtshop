<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\helpers\Html;

/**
 * This is the model class for table "{{%products}}".
 *
 * @property int $id
 * @property int $product_status
 * @property float $product_price
 * @property float $product_price_wholesale
 * @property int $product_floor
 * @property int $product_season
 * @property int $product_model_id
 * @property string $product_datecreate
 * @property int $product_shop_id
 * @property int|null $product_discount
 * @property string $product_alias
 * @property string $product_title
 * @property string $product_h1
 * @property int|null $product_description_id
 *
 * @property Cart[] $carts
 * @property FavoritesProducts[] $favoritesProducts
 * @property ProductColors[] $productColors
 * @property ProductSizes[] $productSizes
 * @property Shops $productShop
 * @property Descriptions $productDescription
 * @property Models $productModel
 * @property Discount $productDiscount
 * @property Image $id0
 * @property ProductsCharacteristics[] $productsCharacteristics
 * @property ProductsKeys[] $productsKeys
 * @property-read string $productSvailability
 * @property-read string $discountValue
 * @property-read string $productH1
 * @property-read mixed $mainImage
 * @property-read string $productTitle
 * @property-read float|int $currentPrice
 * @property-read string $implodeColors
 * @property-read ActiveQuery|ImageQuery $images
 * @property-read ActiveQuery|ImageQuery $image
 * @property int $product_views [int(11)]  Просмотры
 */
class Products extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%products}}';
    }

    /**
     * {@inheritdoc}
     * @return ProductsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProductsQuery(get_called_class());
    }

    /**
     * @return array|\string[][]
     */
    public function behaviors()
    {
        return [
            'image' => [
                'class' => 'app\behaviors\ImageBehave',
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['product_status', 'product_floor', 'product_season', 'product_model_id', 'product_shop_id', 'product_discount', 'product_description_id', 'product_views'], 'integer'],
            [['product_price', 'product_price_wholesale', 'product_floor', 'product_season', 'product_model_id', 'product_alias'], 'required'],
            [['product_price', 'product_price_wholesale'], 'number'],
            [['product_datecreate'], 'safe'],
            [['product_alias'], 'string', 'max' => 100],
            [['product_title', 'product_h1'], 'string', 'max' => 150],
            [['product_shop_id'], 'exist', 'skipOnError' => true, 'targetClass' => Shops::class, 'targetAttribute' => ['product_shop_id' => 'id']],
            [['product_description_id'], 'exist', 'skipOnError' => true, 'targetClass' => Descriptions::class, 'targetAttribute' => ['product_description_id' => 'id']],
            [['product_model_id'], 'exist', 'skipOnError' => true, 'targetClass' => Models::class, 'targetAttribute' => ['product_model_id' => 'id']],
            [['product_discount'], 'exist', 'skipOnError' => true, 'targetClass' => Discounts::class, 'targetAttribute' => ['product_discount' => 'id']],
            [['id'], 'exist', 'skipOnError' => true, 'targetClass' => Image::class, 'targetAttribute' => ['id' => 'itemId']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'Арт.'),
            'product_status' => Yii::t('app', 'Статус'),
            'product_price' => Yii::t('app', 'Цена'),
            'product_price_wholesale' => Yii::t('app', 'Цена опт.'),
            'product_floor' => Yii::t('app', 'Пол'),
            'product_season' => Yii::t('app', 'Сезон'),
            'product_model_id' => Yii::t('app', 'Модель'),
            'product_datecreate' => Yii::t('app', 'Создано'),
            'product_shop_id' => Yii::t('app', 'Магазин'),
            'product_discount' => Yii::t('app', 'Скидка'),
            'product_alias' => Yii::t('app', 'Алиас'),
            'product_description_id' => Yii::t('app', 'Описание'),
            'product_title' => Yii::t('app', 'Title товара'),
            'product_h1' => Yii::t('app', 'Заголовок H1 товара'),
            'product_views' => Yii::t('app', 'Просмотры'),
            'product_colors' => Yii::t('app', 'Цвета'),
        ];
    }

    /**
     * Gets query for [[Carts]].
     *
     * @return ActiveQuery|CartQuery
     */
    public function getCarts()
    {
        return $this->hasMany(Cart::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[FavoritesProducts]].
     *
     * @return ActiveQuery|FavoritesProductQuery
     */
    public function getFavoritesProducts()
    {
        return $this->hasMany(FavoritesProducts::class, ['fp_product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductColors]].
     *
     * @return ActiveQuery|ProductColorQuery
     */
    public function getProductColors()
    {
        return $this->hasMany(ProductColors::class, ['product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductSizes]].
     *
     * @return ActiveQuery|ProductSizeQuery
     */
    public function getProductSizes()
    {
        return $this->hasMany(ProductSizes::class, ['ps_product_id' => 'id']);
    }

    /**
     * Gets query for [[ProductSizes]].
     *
     * @return ActiveQuery|ProductSizeQuery
     */
    public function getFloor()
    {
        return $this->hasOne(Floor::class, ['id' => 'product_floor']);
    }

    /**
     * Gets query for [[ProductShop]].
     *
     * @return ActiveQuery|ShopQuery
     */
    public function getProductShop()
    {
        return $this->hasOne(Shops::class, ['id' => 'product_shop_id']);
    }

    /**
     * Gets query for [[ProductDescription]].
     *
     * @return ActiveQuery|DescriptionQuery
     */
    public function getProductDescription()
    {
        return $this->hasOne(Descriptions::class, ['id' => 'product_description_id']);
    }

    /**
     * Gets query for [[ProductModel]].
     *
     * @return ActiveQuery|ModelQuery
     */
    public function getProductModel()
    {
        return $this->hasOne(Models::class, ['id' => 'product_model_id']);
    }

    /**
     * Gets query for [[ProductDiscount]].
     *
     * @return ActiveQuery|DiscountQuery
     */
    public function getProductDiscount()
    {
        return $this->hasOne(Discounts::class, ['id' => 'product_discount']);
    }

    /**
     * Gets query for [[Id0]].
     *
     * @return ActiveQuery|ImageQuery
     */
    public function getImage()
    {
        return $this->hasOne(Image::class, ['itemId' => 'id'])->where(['image.modelName' => "Products", 'image.isMain' => 1]);
    }

    public function getMainImage()
    {
        return $this->hasOne(Image::class, ['itemId' => 'id'])->where(['image.modelName' => "Products", 'image.isMain' => 1]);
    }

    /**
     * @return ActiveQuery
     */
    public function getImages(): ActiveQuery
    {
        return $this->hasMany(Image::class, ['itemId' => 'id'])->where(['image.modelName' => "Products"]);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductsCharacteristics(): ActiveQuery
    {
        return $this->hasMany(ProductsCharacteristics::class, ['ph_product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductsKeys(): ActiveQuery
    {
        return $this->hasMany(ProductsKeys::class, ['pk_product_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getProductsSeason(): ActiveQuery
    {
        return $this->hasOne(Seasons::class, ['id' => 'product_season']);
    }

    /**
     * @return string|void
     */
    public function getDiscountValue()
    {
        if (!empty($this->productDiscount)) {
            return Html::tag('span', "{$this->productDiscount->discount_value} {$this->productDiscount->discountUnit->discount_units_name}", ['class' => 'discount-value_product-item']);
        }
    }

    /**
     * @return string
     */
    public function getProductSvailability(): string
    {
        if ((int)$this->product_status === 0) {
            return Html::tag('span', 'Отсутствует на складе', ['class' => 'not-availability']);
        }

        return Html::tag('span', 'В наличии', ['class' => 'availability']);
    }

    /**
     * @return string
     */
    public function getProductH1(): string
    {
        if(empty($this->product_h1)) {
            $product_category = $this->productModel->category->name ?? '';
            $product_model = $this->productModel->model_name ?? '';

            $this->product_h1 = "{$product_category} {$product_model}";
        }

        return $this->product_h1;
    }

    /**
     * @return string
     */
    public function getProductTitle(): string
    {
        if(empty($this->product_title)){
            $product_category = $this->productModel->category->name_singular_category;
            $product_model = $this->productModel->model_name ?? '';
            $product_brand = $this->productModel->brand->brand_name ?? '';
            $product_color = $this->productColors->color_name ?? '';

            $this->product_title = "{$product_category} {$product_model} Бренд - {$product_brand}. Цвет - {$product_color}. Купить недорого";
        }

        return $this->product_title;
    }

    /**
     * @return float|int|mixed|null
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
     * @return string
     */
    public function getImplodeColors()
    {
        $colorArray = [];

        if(isset($this->productColors) && !empty($this->productColors)) {
            foreach ($this->productColors as $item) {
                $colorArray[] = $item->color->color_name;
            }

            return implode(', ', $colorArray);
        }

        return false;
    }

    /**
     * @param $favoritesProducts
     * @return string
     */
    public function getFavoritesButtonClass($favoritesProducts = null): string
    {
        if(isset($favoritesProducts[$this->id])){
            return 'product-heart-button-active';
        }
        return 'product-heart-button';
    }
}
