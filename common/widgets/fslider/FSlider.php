<?php
namespace common\widgets\fslider;

use common\components\FHtml;
use common\widgets\BaseWidget;

class FSlider extends BaseWidget
{
    public function run()
    {
        $this::prepareData();

        return $this->RenderWidget($this->display_type, array(
                'items' => $this->items,
                'label_viewmore' => $this->label_viewmore,
                'image_folder' => $this->image_folder,
                'field_title' => $this->field_title,
                'field_overview' => $this->field_overview,
                'link_url' => $this->link_url
            )
        );
    }

    protected function prepareData()
    {
        if (empty($this->image_folder))
            $this->image_folder = SLIDER_DIR;

        if (empty($this->field_title))
            $this->field_title = 'name';

        if (empty($this->field_overview))
            $this->field_overview = ['overview', 'description', 'content'];

        $this->display_type = !empty($this->display_type) ? $this->display_type : 'slider';

        parent::prepareData(); // TODO: Change the autogenerated stub
    }
}

?>