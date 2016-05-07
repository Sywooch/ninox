<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Articles;

/**
 * ArticlesSearch represents the model behind the search form about `app\models\Articles`.
 */
class BlogArticlesSearch extends BlogArticle
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'show', 'views'], 'integer'],
            [['title', 'content', 'description', 'date', 'keywords', 'ico', 'link', 'mod', 'video', 'future_publish'], 'safe'],
            [['rate'], 'number'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'date' => $this->date,
            'show' => $this->show,
            'mod' => $this->mod,
            'rate' => $this->rate,
            'views' => $this->views,
            'future_publish' => $this->future_publish,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'content', $this->content])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'keywords', $this->keywords])
            ->andFilterWhere(['like', 'ico', $this->photo])
            ->andFilterWhere(['like', 'link', $this->link])
            ->andFilterWhere(['like', 'video', $this->video]);

        return $dataProvider;
    }
}
