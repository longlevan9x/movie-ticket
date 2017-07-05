<?php

namespace backend\modules\cinema\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\cinema\models\CinemaMovie;

/**
 * CinemaMovie represents the model behind the search form about `backend\modules\cinema\models\CinemaMovie`.
 */
class CinemaMovieSearch extends CinemaMovie{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'rates', 'count_rates', 'sort_order', 'is_active'], 'integer'],
            [['image', 'code', 'name', 'description', 'content', 'director', 'writer', 'runtime', 'trailer', 'technology', 'mpaa', 'country', 'release_date', 'close_date', 'status', 'type', 'created_date', 'modified_date', 'application_id'], 'safe'],
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
        $query = CinemaMovie::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $searchExact = FHtml::getRequestParam('SearchExact', false);

        //load Params and $_REQUEST
        FHtml::loadParams($this, $params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        if ($searchExact) {
            $query->andFilterWhere([
            'id' => $this->id,
            'image' => $this->image,
            'code' => $this->code,
            'name' => $this->name,
            'description' => $this->description,
            'content' => $this->content,
            'director' => $this->director,
            'writer' => $this->writer,
            'runtime' => $this->runtime,
            'trailer' => $this->trailer,
            'technology' => $this->technology,
            'mpaa' => $this->mpaa,
            'country' => $this->country,
            'rates' => $this->rates,
            'count_rates' => $this->count_rates,
            'release_date' => $this->release_date,
            'close_date' => $this->close_date,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'content' => $this->content,
            'director' => $this->director,
            'writer' => $this->writer,
            'technology' => $this->technology,
            'mpaa' => $this->mpaa,
            'country' => $this->country,
            'rates' => $this->rates,
            'count_rates' => $this->count_rates,
            'release_date' => $this->release_date,
            'close_date' => $this->close_date,
            'status' => $this->status,
            'sort_order' => $this->sort_order,
            'type' => $this->type,
            'is_active' => $this->is_active,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
            'application_id' => $this->application_id,
        ]);

        $query->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'code', $this->code])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'runtime', $this->runtime])
            ->andFilterWhere(['like', 'trailer', $this->trailer]);
        }

        $application_id = FHtml::getFieldValue($this, 'application_id');
        if (FHtml::isApplicationsEnabled($this->getTableName()) && !empty($application_id)) {
            $query->andFilterWhere(['application_id' => $application_id]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby(FHtml::getOrderBy($this));

        return $dataProvider;
    }
}
