<?php

namespace app\models;

use app\base\NestedSetsBehavior;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "{{%categories}}".
 *
 * @property int $id
 * @property string $name
 * @property string|null $name_singular_category
 * @property string|null $alias
 * @property int $parent_id
 * @property string|null $description
 * @property string|null $categories_extended_description
 * @property string|null $keywords
 * @property int|null $floor Пол категории
 * @property string|null $title
 * @property int $lft
 * @property int $rgt
 * @property int|null $depth
 *
 * @property CategoriesCharacteristics[] $categoriesCharacteristics
 * @property CategoriesParameters[] $categoriesParameters
 * @property Models[] $models
 * @property Params[] $params0
 */
class Categories extends \yii\db\ActiveRecord
{
    public int $count = 0;
    public bool $hidden = false;
    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return '{{%categories}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => NestedSetsBehavior::class,
            ],
            'image' => [
                'class' => 'app\behaviors\ImageBehave',
            ]
        ];
    }


    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            [['name', 'parent_id', 'lft', 'rgt'], 'required'],
            [['parent_id', 'floor', 'lft', 'rgt', 'depth', 'count'], 'integer'],
            [['categories_extended_description'], 'string'],
            [['name', 'name_singular_category', 'alias'], 'string', 'max' => 50],
            [['description', 'keywords', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'name_singular_category' => Yii::t('app', 'Name Singular Category'),
            'alias' => Yii::t('app', 'Alias'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'description' => Yii::t('app', 'Description'),
            'categories_extended_description' => Yii::t('app', 'Categories Extended Description'),
            'keywords' => Yii::t('app', 'Keywords'),
            'floor' => Yii::t('app', 'Пол категории'),
            'title' => Yii::t('app', 'Title'),
            'lft' => Yii::t('app', 'Lft'),
            'rgt' => Yii::t('app', 'Rgt'),
            'depth' => Yii::t('app', 'Depth'),
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoriesCharacteristics(): ActiveQuery
    {
        return $this->hasMany(CategoriesCharacteristics::class, ['ch_category_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoriesDescriptions(): ActiveQuery
    {
        return $this->hasOne(CategoriesDescriptions::class, ['category_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getCategoriesParameters(): ActiveQuery
    {
        return $this->hasMany(CategoriesParameters::class, ['cp_category_id' => 'id']);
    }

    /**
     * @return ActiveQuery
     */
    public function getModels(): ActiveQuery
    {
        return $this->hasMany(Models::class, ['category_id' => 'id']);
    }


    /**
     * @return ActiveQuery
     */
    public function getParams(): ActiveQuery
    {
        return $this->hasMany(Params::class, ['param_category_id' => 'id']);
    }

    /**
     * Получить ближайшего родителя
     * @param $category_alias
     * @return Categories
     */
    public function getParentForAlias($category_alias): Categories
    {
        return self::findOne(['alias' => $category_alias])->getParent()->one();
    }

//    /**
//     * Получить пол
//     * @return ActiveQuery
//     */
//    public function getCategoryFloor(): ActiveQuery
//    {
//        return $this->hasOne(Floor::class, ['id' => 'floor']);
//    }

    /**
     * Получить ближайшего родителя
     * @return Categories
     */
    public function getParentForId(): Categories
    {
        return self::findOne($this->id)->getParent()->one();
    }

    /**
     * Получение всех категорий в виде дерева
     * @return mixed
     */
    public function getAllCategoriesInTreeView($category_id = 1, $select = ['alias', 'id', 'name', 'lft', 'rgt', 'depth'])
    {
        $categoryInfo = self::find()
            ->select($select)
            ->where(['id' => $category_id])
            ->limit(1)
            ->one();

        return $categoryInfo->populateTree(null, null, $select);
    }

    /**
     * {@inheritdoc}
     * @return CategoriesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoriesQuery(get_called_class());
    }

    /**
     * Получение всех категорий в виде дерева
     * @return mixed
     */
    public function getCategoryTitle()
    {
        if (get('categories') !== null) {
            return 'Товары по фильтру:';
        }

        $result = '';
        $brand_name = '';
        $season_name = '';

        if (get('brands') !== null && count(get('brands')) == 1 && !empty(get('brands')[0])) {
            $brand_name = ' ' . (new Brands)->getBrandById(get('brands')[0])->brand_name;
        }

        if ($this->name !== 'root') {
            switch (get('all')) {
                case 'man':
                    $result = "{$this->name}{$brand_name} для мужчин";
                    break;
                case 'woman':
                    $result = "{$this->name}{$brand_name} для женщин";
                    break;
                case 'me':
                    $result = "{$this->name}{$brand_name} моих размеров";
                    break;
                default:
                    $result = "{$this->name}{$brand_name} для мужчин и женщин";
            }
        } else {
            switch (get('all')) {
                case 'man':
                    $result = "Все {$season_name}товары{$brand_name} для мужчин";
                    break;
                case 'woman':
                    $result = "Все {$season_name}товары{$brand_name} для женщин";
                    break;
                case 'me':
                    $result = "Все {$season_name}товары{$brand_name} моих размеров";
                    break;
                default:
                    $result = "Все {$season_name}товары{$brand_name} для мужчин и женщин";
            }
        }

        if (get('colors') !== null && count(get('colors')) == 1 && !empty(get('colors')[0])) {
            $color = (new Colors)->getColorById(get('colors')[0])->color_name;
            $result .= " цвет {$color}";
        }

        if (get('seasons') !== null && count(get('seasons')) == 1 && !empty(get('seasons')[0])) {
            switch (get('seasons')[0]) {
                case 1:
                    $season_name = ' демисезон';
                    break;
                case 2:
                    $season_name = ' зима';
                    break;
                case 3:
                    $season_name = ' всесезон';
                    break;
                case 4:
                    $season_name = ' лето';
            }
            $result .= " {$season_name}";
        }

        return $result;
    }

    /**
     * @param $categoryId
     * @return false
     */
    public function getCategoryImage($categoryId): bool
    {
        $categoryInfo = $this->getCategoryInfo(false, $categoryId);

        // Ищем все категории
        $allCategories = $this->getAllCategories();

        // Ищем всех предков и подкатегории
        $categoryParentsAndChildren = $this->getParentsAndChildren($allCategories, $categoryInfo);

        foreach ($categoryParentsAndChildren['childrens'] as $item) {
            if ($item['lft'] == ($item['rgt'] - 1)) {
                $cat[] = $item['id'];
            }
        }

        $image = Products::find()
            ->select(['products.id', 'products.product_model_id'])
            ->where(['in', 'models.category_id', $cat])
            ->joinWith(['productModel'])
            ->addOrderBy(['products.product_views' => SORT_DESC])
            ->limit(1)
            ->one();

        if ($image) {
            return $image->image->getUrl('300x345');
        }

        return false;
    }

    /**
     * Получение полной информации о категории
     * @param string|null $category_alias
     * @param integer|null $category_id
     * @param string|null $all
     * @return Categories
     */
    public function getCategoryInfo(string $category_alias = null, int $category_id = null, string $all = null): Categories
    {
        $category_info = self::find();

        if ($category_alias) {
            $category_info->where(['alias' => $category_alias]);
        } elseif ($category_id) {
            $category_info->where(['id' => $category_id]);
        } else {
            $category_info->where(['name' => 'root']);
        }

        // Если выбран пол, то показать описание именно для этого пола
        if ($all && ($floor = (new Floor())->floorId($all))) {
            $category_info->with([
                'categoriesDescriptions' => function ($categoriesDescriptions) use ($floor) {
                    $categoriesDescriptions->andWhere(['category_floor_id' => $floor]);
                }
            ]);
        }

        return $category_info->limit(1)->one();
    }

    /**
     * Получить ближайшего родителя
     * @return Categories[]|array
     */
    public function getAllCategories(): array
    {
        return self::find()
            ->select(['alias', 'id', 'name', 'lft', 'rgt', 'depth'])
            ->where(['!=', 'name', 'root'])
            ->asArray()
            ->addOrderBy(["lft" => SORT_ASC])
            ->all();
    }

    /**
     * Получение всех родителей и потомков
     * @param $allCategories
     * @param $categoryInfo
     * @return mixed
     */
    public function getParentsAndChildren($allCategories, $categoryInfo)
    {
        $result = ['childrens' => [], 'parents' => []];

        foreach ($allCategories as $category) {
            if ($category['lft'] >= $categoryInfo['lft'] && $category['lft'] <= $categoryInfo['rgt']) {
//                if ($category['lft'] == ($category['rgt'] - 1)) {
                $result['childrens'][] = $category;
//                }
            } elseif ($category['lft'] < $categoryInfo['lft'] && $category['rgt'] > $categoryInfo['rgt']) {
                $result['parents'][] = $category;
            }
        }

        return $result;
    }
}
