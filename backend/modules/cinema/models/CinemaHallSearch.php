<?php

namespace backend\modules\cinema\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\cinema\models\CinemaHall;

/**
 * CinemaHall represents the model behind the search form about `backend\modules\cinema\models\CinemaHall`.
 */
class CinemaHallSearch extends CinemaHall{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'seat_count', 'is_active', 'sort_order'], 'integer'],
            [['name', 'status', 'type', 'created_date', 'modified_date'], 'safe'],
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
        $query = CinemaHall::find();

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
            'name' => $this->name,
            'seat_count' => $this->seat_count,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'type' => $this->type,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
            'seat_count' => $this->seat_count,
            'status' => $this->status,
            'is_active' => $this->is_active,
            'sort_order' => $this->sort_order,
            'type' => $this->type,
            'created_date' => $this->created_date,
            'modified_date' => $this->modified_date,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name]);
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
