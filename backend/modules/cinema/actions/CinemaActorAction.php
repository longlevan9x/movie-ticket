<?php
namespace backend\modules\cinema\actions;

use common\components\FHtml;
use common\actions\BaseApiAction;


class CinemaActorAction extends BaseApiAction
{
	public function run()
	{
		$this->listname = 'cinema_actor';
		$this->objectname = 'cinema_actor';
		if (!empty($this->objectid)) {
			$object = FHtml::getModelForAPI($this->objectname, '', $this->objectid, null, false);

			$out = FHtml::getOutputForAPI($object, $this->objectname, '', 'data', 1);
			$out['code'] = $this->objectid;
			return $out;
		}
		else {
			$list = FHtml::getModelsList($this->listname,
					FHtml::mergeRequestParams([
						'name' => '%'.$this->keyword],
						$this->paramsArray),
					$this->orderby,
					$this->limit,
					$this->page,
					false
				);

			$models = FHtml::prepareDataForAPI($list->getModels());

			$out = FHtml::getOutputForAPI($models, $this->listname, 'SUCCESS', 'data', $list->pagination->pageCount);
			return $out;
		}
	}
}