<?php
namespace backend\actions;

use common\components\FHtml;

class ViewImageAction extends BaseAction
{
    public function run()
    {
        $d = FHtml::getRequestParam(['d', 'folder']); //directory
        $f = FHtml::getRequestParam(['f', 'file', 'file_name']);   //file name
        $s = FHtml::getRequestParam(['s', 'thumb', 'thumbnail']);  //thumb

        $file = FHtml::getImagePath($s.$f, $d); ///also works
        //$file = FHtml::getFileURL($s.$f, $d, BACKEND, \Globals::NO_IMAGE);

        $info = getimagesize($file);
        $size = filesize($file);
        header("Content-type: {$info['mime']}");
        header("Content-length: {$size}");

        readfile($file);
    }
}
