<?php
namespace APP\Controller;
use Think\Controller;
header("content-type:text/html;charset=utf-8");
class CourseController extends Controller {
	public function index()
	{
		$this->display();
	}
}