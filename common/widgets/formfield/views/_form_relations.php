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
            <?= $form->field($model, $field_name)->label(false)->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'name',
                        'type' => \kartik\select2\Select2::className(),
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Name'),
                        'options' =>
                            FHtml::getSelect2Options('name', ['id' => 'id', 'description' => 'name'], $object_type, 'name', 'id', true)
                        ,
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-4'
                        ]
                    ],
                    [
                        'name' => 'description',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Description'),
                        'type' => 'static',
                        'options' => [
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-6'
                        ]
                    ],
//                [
//                    'name' => 'relation_type',
//                    'type' => \kartik\select2\Select2::className(),
//                    'enableError' => true,
//                    'title' => FHtml::t('common', 'Relation'),
//                    'options' =>
//                        FHtml::getComboArray('', 'object_relation', 'relation_type', 'id', true)
//
//                    ,
//                    'headerOptions' => [
//                        'style' => 'border:none',
//                        'class' => 'col-md-1'
//                    ]
//                ],
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
    </div>

<?php } else { ?>

<?php } ?>



