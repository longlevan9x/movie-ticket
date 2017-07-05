<?php
namespace backend\modules\cinema\actions;

use common\actions\BaseApiAction;
use common\components\FHtml;


class CinemaMovieAcion extends BaseApiAction
{
	public function run()
	{
		$this->listname = 'cinema_movie';
	}
}