<?php
use common\components\FHtml;
use common\components\Helper;
use unclead\multipleinput\MultipleInput;
?>

<?php
if (empty($field_name))
    $field_name = \yii\helpers\BaseInflector::camelize($object_type) . \yii\helpers\BaseInflector::camelize($relation_type);

?>

<?php if ($canEdit) { ?>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, '_' . $field_name)->label(false)->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'name',
                        'type' => \kartik\select2\Select2::className(),
                        'enableError' => true,
                        'title' => FHtml::t('common', '+ Add more') ,
                        'options' =>
                            FHtml::getSelect2Options('name', ['id' => 'id', 'description' => 'name'], $object_type, 'name', 'id', true)
                        ,
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-11'
                        ]
                    ],

                    [
                        'name' => 'id',
                        'options' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none;width:0px;visible:none',
                        ]
                    ],

                ]
            ])->label(FHtml::t('common', $field_name));
            ?>
        </div>

        <div class="col-md-12">
            <hr/>
            <?= \common\widgets\FGridView::widget([
                'id'=>'crud-datatable2',
                'dataProvider' => FHtml::getDataProvider('object_relation', ['object_id' => $model->id, 'object_type' => FHtml::getTableName($model), 'object2_type' => $object_type]),
                'object_type' => 'object_relation',

                'display_type' => FHtml::DISPLAY_TYPE_WIDGET,
                'pjax' => true,
                'form_enabled' => false,
                'filterEnabled' => false,
                'default_fields' => [

                ],
                'layout' => '{items}{summary}',
                'edit_type' => FHtml::EDIT_TYPE_INLINE,
                'columns' => [
                        [ //name: id, dbType: int(11), phpType: integer, size: 11, allowNull:
                            'class'=>'kartik\grid\DataColumn',
                            'label' => FHtml::t('common', $field_name ),
                            'format'=>'html',
                            'attribute'=>'object2_id',
                            'value' => function($model) { return FHtml::showObjectPreview($model, 'object2_id', 'object2_type'); },

                        ],
                        [
                            'class' => 'common\widgets\FActionColumn',
                            'dropdown' => 'ajax', // Dropdown or Buttons
                            'actionLayout' => '{delete}',
                            'hAlign' => 'center',
                            'vAlign' => 'middle',
                            'width' => '120px'
                        ]
                ]
            ]) ?>
        </div>
    </div>

<?php } else { ?>

<?php } ?>



