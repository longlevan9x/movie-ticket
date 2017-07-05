<?php
namespace common\widgets\formfield;

use yii\base\Widget;

class FormFieldWidget extends Widget
{
    public $items;
    public $items_filter = [];
    public $title = '';
    public $modulePath = '';
    public $view_path = '';
    public $form;
    public $model;
    public $field_name;
    public $modelMeta;
    public $canEdit;
    public $moduleKey;
    public $object_type;
    public $object_id;
    public $relation_type;
    public $meta_key;
    public $id; // control id

    public function run()
    {
        if (!empty($this->view_path)) {
            return $this->renderWidget($this->view_path, array(
                    'items' => $this->items,
                    'title' => $this->title,
                    'items_filter' => $this->items_filter,
                    'form' => $this->form,
                    'id' => $this->id,
                    'model' => $this->model, 'field_name' => $this->field_name, 'object_type' => $this->object_type, 'meta_key' => $this->meta_key,
                    'relation_type' => $this->relation_type,
                    'modelMeta' => $this->modelMeta, 'canEdit' => $this->canEdit, 'moduleKey' => $this->moduleKey, 'modulePath' => $this->modulePath
                )
            );
        }
    }

    public function RenderWidget($view = '', $params = [])
    {
        $result = '';

        $result .= $this->render($view, $params);

        return $result;
    }

    protected function prepareData()
    {

    }
}

?>