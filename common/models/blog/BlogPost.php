<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace common\models\blog;

use common\models\blog\BlogPostTag;
use Yii;
use common\models\siteobject\BaseSiteObject;
use frontend\components\Declension;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

/**
 * This is the base-model class for table "blog_post".
 *
 * @property integer $id
 * @property string $name
 * @property string $alias
 * @property string $intro
 * @property string $short_intro
 * @property integer $published
 * @property integer $featured
 * @property integer $sort
 * @property string $html
 * @property integer $created_by
 * @property string $created_at
 * @property integer $updated_by
 * @property string $updated_at
 * @property string $published_at
 *
 * @property \common\models\User $createdBy
 * @property \common\models\User $updatedBy
 * @property \common\models\blog\BlogPostBlock[] $blogPostBlocks
 * @property \common\models\blog\BlogPostTag[] $blogPostTags
 * @property \common\models\blog\BlogTag[] $blogTags
 * @property string $aliasModel
 */
class BlogPost extends BaseSiteObject
{

    use \common\utility\SortableTrait;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'blog_post';
    }

    public static function crudAlias()
    {
        return 'blog-post';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return
            ArrayHelper::merge(
                [
                    'class' => BlameableBehavior::className(),
                ],
                $this->sortableModelBehavior()
            );
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'alias'], 'required'],
            [['intro', 'html'], 'string'],
            [['published', 'featured', 'sort'], 'integer'],
            [['name', 'alias', 'short_intro'], 'string', 'max' => 255],
            [['published_at'], 'date', 'format' => 'php:Y-m-d H:i:s'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['updated_by'], 'exist', 'skipOnError' => true, 'targetClass' => \common\models\User::className(), 'targetAttribute' => ['updated_by' => 'id']]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('models', 'ID'),
            'name' => Yii::t('models', 'Name'),
            'alias' => Yii::t('models', 'Alias'),
            'intro' => Yii::t('models', 'Intro'),
            'short_intro' => 'Описание для карточки',
            'published' => Yii::t('models', 'Published'),
            'featured' => Yii::t('models', 'Featured'),
            'created_by' => Yii::t('models', 'Created By'),
            'created_at' => Yii::t('models', 'Created At'),
            'updated_by' => Yii::t('models', 'Updated By'),
            'updated_at' => Yii::t('models', 'Updated At'),
            'published_at' => Yii::t('models', 'Published At'),
            'sort' => Yii::t('models', 'Sort'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(\common\models\User::className(), ['id' => 'updated_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPostBlocks()
    {
        return $this->hasMany(\common\models\blog\BlogPostBlock::className(), ['blog_post_id' => 'id'])->orderBy('sort');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogPostTags()
    {
        return $this->hasMany(\common\models\blog\BlogPostTag::className(), ['blog_post_id' => 'id'])->orderBy('sort');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getBlogTags()
    {
        return $this->hasMany(\common\models\blog\BlogTag::className(), ['id' => 'blog_tag_id'])->viaTable('blog_post_tag', ['blog_post_id' => 'id']);
    }

    public function getRusDate()
    {
        $timestamp = strtotime($this->published_at);
        return Declension::dateWithRusMonth($timestamp);
    }

    public function getHtml($extraData = [])
    {
        if (empty($extraData) && $this->hasProperty('html') && !empty($this->html)) {
            return $this->html;
        }
        return $this->getBodyHtml($extraData);
    }

    public function saveHtml()
    {
        $this->html = $this->getBodyHtml();
        return $this->save();
    }

    public function getTableOfContentsArray()
    {
        //получаем все блоки у которых проставлен чекбокс оглавления
        $tableArray =  array_filter(array_map(function ($blogPostBlock) {
            return $blogPostBlock->getParagraph();
        }, $this->blogPostBlocks), function ($item) {
            return !empty($item['isset']);
        });
        //[1,1,2,2,2,1,1] -> [1,1[2,2,2],1,1] для удобства вывода оглавления на фронте
        $result = [];
        //TODO сделать элегеантнее
        $subHeadingItems = [];
        foreach ($tableArray as $paragraph) {
            if ($paragraph['level'] == 1) {
                if (!empty($subHeadingItems)) {
                    $result[] = $subHeadingItems;
                    $subHeadingItems = [];
                }
                $result[] = $paragraph;
            } else if ($paragraph['level'] == 2) {
                $subHeadingItems[] = $paragraph;
            }
        }
        if (!empty($subHeadingItems)) {
            $result[] = $subHeadingItems;
        }
        return $result;
    }

    public function getBodyHtml($extraData = [])
    {
        return array_reduce($this->blogPostBlocks, function ($acc, $blogPostBlock) use ($extraData) {
            return $acc . $blogPostBlock->getHtml($extraData);
        }, '');
    }

    public function getUrl()
    {
        return '/blog/' . $this->alias . '/';
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($this->published && $this->published_at == null) {
            $this->published_at = date('Y-m-d H:i:s');
        }

        if (\Yii::$app->request->isPost) {
            BlogPostTag::deleteAll(['blog_post_id' => $this->id]);
            if (isset($_POST['blogPostTags'])) {
                $tags = $_POST['blogPostTags'];
                $columns = ['blog_post_id', 'blog_tag_id', 'sort'];
                $rows = array_map(function ($idx) use ($tags) {
                    return [$this->id, $tags[$idx], $idx + 1];
                }, array_keys($tags));
                Yii::$app->db->createCommand()->batchInsert(BlogPostTag::tableName(), $columns, $rows)->execute();
            }
        }

        return true;
    }
}
