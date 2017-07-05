<?php
namespace common\widgets\FContact;

use common\widgets\BaseWidget;

class FContact extends BaseWidget
{
    public function run()
    {
        self::prepareData();

        return $this->render($this->display_type,
            [
                'model' => $this->model,
                'title' => $this->title,
            ]);
    }

    protected function prepareData()
    {
        if (empty($this->display_type))
            $this->display_type = 'contact';

        parent::prepareData(); // TODO: Change the autogenerated stub
    }
}

?>