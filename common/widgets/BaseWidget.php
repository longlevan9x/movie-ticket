<?php
namespace common\widgets;

use backend\modules\cms\models\CmsWidgets;
use common\components\FConfig;
use common\components\FHtml;
use common\components\FModel;
use common\widgets\fheadline\FHeadline;
use yii\base\Widget;
use yii\helpers\BaseInflector;
use yii\helpers\Html;
use yii\helpers\StringHelper;

class BaseWidget extends Widget
{
    public $items; // View model list that display in that page
    public $data; // Data List
    public $model; // Model Object
    public $items_filter = [];
    public $pagination;

    public $keyword;
    public $total;

    public $display_type = '';
    public $content;
    public $is_active = true;
    public $title = '';
    public $title_display_type = '';
    public $overview = '';
    public $alignment;
    public $columns_count;
    public $items_count;
    public $color = '';
    public $image_width = '100%';
    public $image_height = 0;

    public $field_title = ['name', 'title'];
    public $field_overview = ['overview', 'description', 'content'];
    public $field_type = 'status';
    public $field_type_colors = [];

    public $item_layout = FHtml::LAYOUT_NO_LABEL;
    public $label_viewmore = 'View more';
    public $margin;
    public $image_folder;
    public $link_url;

    public $background_css = '';
    public $width_css = '';
    public $style = '';
    public $item_style = '';

    public $show_viewmore;
    public $show_border = true;
    public $show_headline = true;
    public $show_panel = false;
    public $show_price = false;
    public $show_rate = false;
    public $color_bg = false;

    public $object_type;
    public $container;
    public $object_id;

    public $viewmore_url = '';
    public $is_preview = true;
    public $view_path = '';

    public $admin_url = '';
    public $controller;
    public $action;
    public $is_widget = true;
    public $page_code;
    public $widget_id;
    public $params = [];

    public function run()
    {
        self::prepareData();
        return $this->render($this->display_type);
    }

    public function RenderWidget($view = '', $params = [])
    {
        if ($this->is_active == 0 || $this->is_active == false)
            return '';

        $widget_id = FHtml::getClassName($this, false);
        $widget_id1 = $widget_id . '_'. $this->getId();

        $result = !$this->is_widget ? '' : "<!--- START: $widget_id1 : $this->object_type ----------------->" . FHtml::CODE_NEWLINE;
        $result .= $this->buildAdminLink() . FHtml::CODE_NEWLINE;

        $result .= FHtml::CODE_TAB . "<div class='$this->background_css $widget_id $widget_id1 $this->color_bg' style='$this->style'>" . FHtml::CODE_NEWLINE;
        $result .= FHtml::CODE_TAB2 . "<div class='$this->width_css'>" . FHtml::CODE_NEWLINE;

        if (!empty($this->title) && $this->show_headline && !$this->show_panel) {
            $result .= (new FHeadline(['is_widget' => false, 'title' => $this->title, 'overview' => $this->overview, 'title_display_type' => $this->title_display_type, 'color' => $this->color, 'margin' => $this->margin]))->run();
        }

        if ($this->show_panel) {
            $id = uniqid();
            $result .= '<div class="panel-group" id="accordion' . $id . '">
    <div class="panel panel-' . $this->color . '">
        <div class="panel-heading">
            <h2 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion' . $id . '" href="#collapseOne' . $id . '">
                    ' . (empty($this->title) ? '&nbsp;' : $this->title) . '
                    <i class="fa fa-angle-down pull-right" style="margin-top:8px"></i>
                </a>
            </h2>
        </div>
        <div id="collapseOne' . $id . '" class="panel-collapse collapse in">
            <div class="panel-body" style="margin-left:0px;margin-right:0px">';
        }

        if (!empty($this->content)) {
            $result .= FHtml::CODE_TAB3 . FHtml::showHtml($this->content, $this->display_type) . FHtml::CODE_NEWLINE;
        } else {
            $view = self::getViewFile($view);
            $result .= FHtml::CODE_TAB3 . FHtml::render($view, '', array_merge($this->params, $params), $this) . FHtml::CODE_NEWLINE;
        }

        if (count($this->items) > 0 && !empty($this->viewmore_url) && $this->viewmore_url != '#' && $this->viewmore_url != 'none') {
            $result .= '<div class="heading margin-bottom-30"><a class="btn-u btn-brd btn-brd-hover btn-u-' . $this->color . ' btn-u-block" href="' . FHtml::createUrl($this->viewmore_url) . '">' . FHtml::t('common', 'View more') . '</a></div>';
        }

        if ($this->show_panel) {
            $result .= '</div>
        </div>
    </div>
</div>';

        }

        $result .= FHtml::CODE_TAB2 . '</div>' . FHtml::CODE_NEWLINE;
        $result .= FHtml::CODE_TAB . '</div>' . FHtml::CODE_NEWLINE;
        $result .= !$this->is_widget ? '' : "<!--- END: $widget_id1 : $this->object_type ----------------->" . FHtml::CODE_NEWLINE;

        return Html::decode($result);
    }

    protected function getViewFile($view) {
        if (StringHelper::endsWith($view,'.php'))
            $view = str_replace('.php', '', $view);

        $application_id = FHtml::currentApplicationFolder();
        $arr1 = [];
        $widget_id = strtolower(FHtml::getClassName($this));

        if (is_string($view) && !empty($application_id)) {
            $arr1[] =  "@applications/$application_id/widgets/$widget_id.php";
            $arr1[] =  "@applications/$application_id/widgets/$widget_id/$view.php";
        }
        if (is_string($view))
            $arr1[] = $view;
        else if (is_array($view))
            $arr1 = array_merge($arr1, $view);
        if (is_array($arr1)) {
            foreach ($arr1 as $view1) {
                $result = FHtml::findViewFile($view1);
                if (is_file($result)) {
                    return $view1;
                }
            }
        }

        return $view;
    }

    public function buildAdminLink($url = '')
    {
        if (empty($url))
            $url = self::buildAdminURL();

        if (!empty($url))
            $url = "<a style='color:lightgray; font-size:10px' target='_blank' title='Edit $this->object_type' href='$url'><span class='glyphicon glyphicon-pencil'></span></a>";

        $widget_url = empty($this->widget_id) ? '' : FHtml::createUrl('cms/cms-widgets/update', ['id' => $this->widget_id], FRONTEND);
        if (!empty($widget_url))
            $widget_url = "<a style='color:lightgray; font-size:10px' target='_blank' title='' href='$widget_url'><span class='glyphicon glyphicon-cog'></span></a>";

        if (!empty($url) && FHtml::isRoleAdmin()) {
            $url = "<span class='pull-right' style='z-index:100;margin-top:0px;position:relative'>$url $widget_url</span>";
            return $url;
        }

        return '';
    }

    public function buildAdminURL()
    {
        $url = '';
        if (!empty($this->admin_url))
            $url = $this->admin_url;
        else if (!empty($this->object_type)) {
            $url = FHtml::createBackendUrl($this->object_type);
        }
        return $url;
    }

    protected function prepareData()
    {
        if (empty($this->page_code)) {
            $page_code = FHtml::currentPageCode();
            $widget_id = FHtml::getClassName($this, false) . '_' . $this->getId();
        } else {
            $page_code = $this->page_code;
            $widget_id = FHtml::getClassName($this, false);
        }

        if ($this->is_widget) {
            $model = CmsWidgets::findOne(['page_code' => $page_code, 'name' => $widget_id]);
            if (isset($model)) {
                FModel::copyFieldValues($this, ['display_type', 'content', 'is_active', 'title', 'overview', 'color', 'color_bg', 'width_css', 'background_css', 'columns_count'], $model, false);
                $this->is_active = $model->is_active;

            } else {
                $model = new CmsWidgets();
                $model->page_code = $page_code;
                $model->name = $widget_id;
                FModel::copyFieldValues($model, ['display_type', 'content', 'is_active', 'title', 'overview', 'color', 'color_bg', 'width_css', 'background_css', 'columns_count'], $this);
                $model->is_active = 1;
                $model->created_date = FHtml::Today();
                $model->created_user = FHtml::currentUserId();
                $model->application_id = FHtml::currentApplicationCode();
                $model->save();
            }

            $this->widget_id = $model->id;
        }

        if (!isset($this->show_viewmore))
            $this->show_viewmore = true;

        $this->columns_count = (isset($this->columns_count)) ? $this->columns_count : FHtml::getRequestParam('GRID_COLUMNS', 4);
        $this->items = (isset($this->items)) ? $this->items : FHtml::getDummyViewModels($this->items_count > 0 ? $this->items_count : rand(3, 50));

        if ($this->background_css == 'random')
            $this->background_css = FHtml::generateRandomInArray(FHtml::WIDGET_BACKGROUND_ARRAYS);

        if (empty($this->field_title))
            $this->field_title = ['name', 'title'];

        if (empty($this->field_overview))
            $this->field_overview = ['overview', 'description', 'content'];
    }

    /**
     * Creates a widget instance and runs it.
     * The widget rendering result is returned by this method.
     * @param array $config name-value pairs that will be used to initialize the object properties
     * @return string the rendering result of the widget.
     * @throws \Exception
     */
    public static function widget($config = [])
    {
        ob_start();
        ob_implicit_flush(false);
        try {
            /* @var $widget Widget */
            $config['class'] = get_called_class();
            $widget = \Yii::createObject($config);
            $out = $widget->run();
        } catch (\Exception $e) {
            // close the output buffer opened above if it has not been closed already
            if (ob_get_level() > 0) {
                ob_end_clean();
            }

            return FHtml::showErrorMessage($e->getMessage());
            //throw $e;
        }

        return ob_get_clean() . $out;
    }

    public function render($view, $params = [], $widgetRender = true)
    {
        if ($this->is_active == 0 || $this->is_active == false)
            return "<!--- View: $view is disabled ! ----------------->";

        if ($widgetRender && $this->is_widget) {
            $result =  FHtml::CODE_TAB . $this->RenderWidget($view, $params) . FHtml::CODE_NEWLINE;
        }
        else {
            $widget_id = FHtml::getClassName($this, false);
            $widget_id1 = $widget_id . '_'. $this->getId();

            $result = !$this->is_widget ? '' : "<!--- START: $widget_id1 : $this->object_type ----------------->" . FHtml::CODE_NEWLINE;

            $result .= $this->buildAdminLink() . FHtml::CODE_NEWLINE;

            $result .= FHtml::CODE_TAB . FHtml::render($view, '', array_merge($this->params, $params), $this) . FHtml::CODE_NEWLINE;
            $result .= !$this->is_widget ? '' : "<!--- END: $widget_id1 : $this->object_type ----------------->" . FHtml::CODE_NEWLINE;
        }


        return Html::decode($result);
    }
}

?>