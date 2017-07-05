<?php

namespace backend\modules\cinema\actions;

use common\actions\BaseApiAction;
use common\components\FHtml;

class CinemaHallAction extends BaseApiAction
{
	public function run()
	{
		$this->listname = 'cinema_hall';
		$this->objectname = 'cinema_hall';

		if (!empty($this->objectid)) {
			$model = FHtml::getModelForAPI($this->objectname, '', $this->objectid, null, false);

			$out = FHtml::getOutputForAPI($model, $this->objectname, 'SUCCESS', 'data', 1);

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
