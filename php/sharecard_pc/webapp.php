<?php
/**
 * 乐享名片PC版模块PC接口定义
 *
 * @author webfunny
 * @url http://bbs.we7.cc
 */
defined('IN_IA') or exit('Access Denied');

class Sharecard_pcModuleWebapp extends WeModuleWebapp {
	public function doPageTest(){
		global $_GPC, $_W;
		$errno = 0;
		$message = '返回消息';
		$data = array();
		return $this->result($errno, $message, $data);
	}

	public function doPageSettings() {
		//这个操作被定义用来呈现 PC版
	}
	
	public function doPageUsers() {
		//这个操作被定义用来呈现 PC版
	}
	
	public function doPageCatalogs() {
		//这个操作被定义用来呈现 PC版
	}
	
	public function doPageProducts() {
		//这个操作被定义用来呈现 PC版
	}
	
	public function doPageNotices() {
		//这个操作被定义用来呈现 PC版
	}
	
	public function doPageComments() {
		//这个操作被定义用来呈现 PC版
	}
	
}