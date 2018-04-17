<?php
defined('IN_IA') or exit('Access Denied');

class Share_cardModuleWebapp extends WeModuleWebapp {

	public function doPageFengmian() {
		global $_W;
		$lists = pdo_getall('share_card_products', array('uniacid' => $_W['uniacid']), '', 'id desc', array(1, 20));
		$setting = $this->getWebSetting();
		include $this->template('webappindex');
	}

	public function doPageDetail() {
		global $_GPC;
		$id = $_GPC['id'];
		$list = pdo_get('share_card_products', array('id' => $id));
		$setting = $this->getWebSetting();
		include $this->template('products-detail');
	}

	public function doPageList() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$lists = pdo_getslice('share_card_products', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		$setting = $this->getWebSetting();
		include $this->template('products-list');
	}

	public function doPageProductList() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$lists = pdo_getslice('share_card_products', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		$setting = $this->getWebSetting();
		include $this->template('products-list');
	}

	public function doPageProductDetail() {
		global $_GPC, $_W;
		$id = $_GPC['id'];
		$list = pdo_get('share_card_products', array('id' => $id));
		$setting = $this->getWebSetting();
		include $this->template('products-detail');
	}

	public function doPageNoticeList() {
		global $_GPC, $_W;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$lists = pdo_getslice('share_card_notices', array('uniacid' => $_W['uniacid']), array($pindex, $psize), $total, array(), '', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		$setting = $this->getWebSetting();
		include $this->template('notices-list');
	}

	public function doPageNoticeDetail() {
		global $_GPC, $_W;
		$id = $_GPC['id'];
		$list = pdo_get('share_card_notices', array('id' => $id));
		$setting = $this->getWebSetting();
		include $this->template('notices-detail');
	}

	public function doPageAbout() {
		global $_GPC, $_W;
		$setting = $this->getWebSetting();
		include $this->template('about');
	}

	public function getWebSetting() {
		global $_W, $_GPC;
		$setting = pdo_get('share_card_setting', array('key' => 'setting', 'uniacid' => $_W['uniacid']));
		if (!empty($setting['value'])) {
			$setting = iunserializer($setting['value']);
		}
		return $setting;
	}
}