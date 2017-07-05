<?php
use common\components\FHtml;
use unclead\multipleinput\MultipleInput;
?>

<?php
$type = '';
?>

<?php if ($canEdit) { ?>

    <div class="">
        <div class="col-md-12">
            <?= $form->field($model, 'ObjectAttributes')->widget(MultipleInput::className(), [
                'min' => 0,
                'addButtonPosition' => MultipleInput::POS_HEADER, // show add button in the header
                'columns' => [
                    [
                        'name' => 'meta_key',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Custom Field'),
                        'options' => [
                            'style' => 'border:none;border-bottom:dashed 1px lightgray',
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                            'class' => 'col-md-4'
                        ]
                    ],
                    [
                        'name' => 'meta_value',
                        'enableError' => true,
                        'title' => FHtml::t('common', 'Value'),
                        'options' => [
                            'class' => 'col-md-8'
                        ],
                        'headerOptions' => [
                            'style' => 'border:none',
                        ]
                    ]
                ]
            ])->label(false);
            ?>
        </div>
    </div>

<?php } else { ?>

<?php } ?>


