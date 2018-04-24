<?php

/**
 * [WeEngine System] Copyright (c) 2013 WE7.CC
 * User: fanyk
 * Date: 2017/12/10
 * Time: 14:46.
 */
class Sharecard_pcModuleWxapp extends WeModuleWxapp {

	private $gpc;
	private $w;
	private $uid; // 用户ID
	public function __construct() {
		global $_W;
		global $_GPC;
		$this->gpc = $_GPC;
		$this->w = $_W;
		$this->uid = $_W['openid'];
		$this->uniacid = $_W['uniacid'];
//		$this->uid = 0;
		// 如果需要强制登录 加 下边代码
//		if (empty($this->uid)) {
//			$this->result(41009, '请先登录');
//		}
	}


	public function get($key, $default = null) {
		return isset($this->gpc[$key]) ? $this->gpc[$key] : $default;
	}


	public function doPageSetting() {
		global $_W, $_GPC;
		$setting = pdo_get('share_card_setting', array('key' => 'setting', 'uniacid' => $_W['uniacid']));
		if (!empty($setting['value'])) {
			$setting = iunserializer($setting['value']);
		}
		$setting['attachurl'] = $_W['attachurl'];
		$this->result(0, '个人信息', $setting);
	}

	public function doPageUserInfo() {
		global $_W, $_GPC;
		$openid = safe_gpc_string($_W['openid']);
		$list = pdo_get('share_card_users', array('openid' => $openid,'uniacid' => $_W['uniacid']));

		$id = $list['id'];
		$status = $list['status']?$list['status']:'00';
		$name = $list['name']?$list['name']:safe_gpc_string($_GPC['nickName']);

		$data = array(
			'name' => $name,
			'openid' => safe_gpc_string($_W['openid']),
			'nickname' => safe_gpc_string($_GPC['nickName']),
			'avatarurl' => safe_gpc_string($_GPC['avatarUrl']),
			'gender' => safe_gpc_string($_GPC['gender']),
			'city' => safe_gpc_string($_GPC['city']),
			'province' => safe_gpc_string($_GPC['province']),
			'country' => safe_gpc_string($_GPC['country']),
			'language' => safe_gpc_string($_GPC['language']),
			'status' => $status,
			'uniacid' => $_W['uniacid'],
		);

		if (!empty($id)) {
			$data['update_time'] = TIMESTAMP;
			pdo_update('share_card_users', $data, array('id' => $id));			
		} else {
			$data['create_time'] = TIMESTAMP;
			pdo_insert('share_card_users', $data);
		}
		$list = pdo_get('share_card_users', array('openid' => $openid,'uniacid' => $_W['uniacid']));
		if (!empty($list['detail'])) {
			$list['detail'] = iunserializer($list['detail']);
		}
		$list['detail']['attachurl'] = $_W['attachurl'];
		$this->result(0, '个人信息同步成功', $list);
	}

	public function doPageCatalogs() {
		global $_W, $_GPC;
		$openid = safe_gpc_string($_W['openid']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$user = pdo_get('share_card_users', array('openid' => $openid,'uniacid' => $_W['uniacid']));

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $user['id'];
		$lists = pdo_getslice('share_card_catalogs', $condition, array($pindex, $psize), $total,'', 'id', 'priority desc');
		$pager = pagination($total, $pindex, $psize);
		$this->result(0, '分类列表', $lists);
	}
}
