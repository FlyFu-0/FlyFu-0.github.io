<?php

namespace Controllers;

use Core\Controller;

class Error extends Controller
{
	public function notFound()
	{
		$this->title = 'Page Not Found!';

		return $this->render('error/notFound');
	}
}