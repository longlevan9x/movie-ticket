<?php

namespace backend\modules\system\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\components\FHtml;

use backend\modules\system\models\SettingsText;

/**
 * SettingsText represents the model behind the search form about `backend\models\SettingsText`.
 */
class SettingsTextSearch extends SettingsText{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['group', 'name', 'description', 'description_en', 'description_es', 'description_pt', 'description_de', 'description_fr', 'description_it', 'description_ko', 'description_ja', 'description_vi', 'description_zh'], 'safe'],
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
        $query = SettingsText::find();

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
            'group' => $this->group,
            'name' => $this->name,
            'description' => $this->description,
            'description_en' => $this->description_en,
            'description_es' => $this->description_es,
            'description_pt' => $this->description_pt,
            'description_de' => $this->description_de,
            'description_fr' => $this->description_fr,
            'description_it' => $this->description_it,
            'description_ko' => $this->description_ko,
            'description_ja' => $this->description_ja,
            'description_vi' => $this->description_vi,
            'description_zh' => $this->description_zh,
        ]);
        } else {
            $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'group', $this->group])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'description_en', $this->description_en])
            ->andFilterWhere(['like', 'description_es', $this->description_es])
            ->andFilterWhere(['like', 'description_pt', $this->description_pt])
            ->andFilterWhere(['like', 'description_de', $this->description_de])
            ->andFilterWhere(['like', 'description_fr', $this->description_fr])
            ->andFilterWhere(['like', 'description_it', $this->description_it])
            ->andFilterWhere(['like', 'description_ko', $this->description_ko])
            ->andFilterWhere(['like', 'description_ja', $this->description_ja])
            ->andFilterWhere(['like', 'description_vi', $this->description_vi])
            ->andFilterWhere(['like', 'description_zh', $this->description_zh]);
        }

        if (empty(FHtml::getRequestParam('sort')))
            $query->orderby((new SettingsText())->getOrderBy());

        return $dataProvider;
    }
}
