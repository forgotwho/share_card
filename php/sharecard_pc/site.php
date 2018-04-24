<?php
/**
 * 乐享名片PC版模块微站定义
 *
 * @author webfunny
 * @url http://bbs.we7.cc
 */
defined('IN_IA') or exit('Access Denied');

class Sharecard_pcModuleSite extends WeModuleSite {

	public function doWebCatalogs() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;
		$keyword = safe_gpc_string($_GPC['name'], '');
		if (!empty($keyword)) {
			$condition['name LIKE'] = "%{$keyword}%";
		}
		$lists = pdo_getslice('share_card_catalogs', $condition, array($pindex, $psize), $total,'', 'id', 'priority desc');
		$pager = pagination($total, $pindex, $psize);
		include $this->template('catalogs-list');
	}

	public function doWebCatalogs_add() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);

		$id = safe_gpc_int($_GPC['id']);
		$list = pdo_get('share_card_catalogs', array('id' => $id));

		if (checksubmit('submit')) {
			if (empty($_GPC['name'])) {
				itoast('名称不能为空', referer());
			}

			$data = array(
				'userId' => $userId,
				'name' => safe_gpc_string($_GPC['name']),
				'icon' => safe_gpc_string($_GPC['icon']),
				'priority' => safe_gpc_string($_GPC['priority']),
				'status' => safe_gpc_string($_GPC['status']),
				'description' => safe_gpc_string($_GPC['description']),
				'create_time' => TIMESTAMP,
				'uniacid' => $_W['uniacid']
			);

			if (!empty($id)) {
				pdo_update('share_card_catalogs', $data, array('id' => $id));
				message('修改成功', $this->createWebUrl('catalogs',array('op' => 'catalogs','userId'=>$userId)));
			} else {
				pdo_insert('share_card_catalogs', $data);
				message('添加成功', $this->createWebUrl('catalogs',array('op' => 'catalogs','userId'=>$userId)));
			}
		}
		include $this->template('catalogs-post');
	}

	public function doWebCatalogs_delete() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$id = safe_gpc_int($_GPC['id']);
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('catalogs',array('op' => 'catalogs','userId'=>$userId)));
		}
		pdo_delete('share_card_catalogs', array('id' => $id));
		itoast('删除成功', $this->createWebUrl('catalogs',array('op' => 'catalogs','userId'=>$userId)));
	}

	public function doWebProducts() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;
		$keyword = safe_gpc_string($_GPC['name'], '');
		if (!empty($keyword)) {
			$condition['name LIKE'] = "%{$keyword}%";
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('share_card_products').' WHERE uniacid =:uniacid and userId=:userId',array(':uniacid'=>$_W['uniacid'],':userId'=>userId) );		
		$lists = pdo_fetchall('SELECT p.*,c.name as catalog_name FROM '.tablename('share_card_products').' p left join '.tablename('share_card_catalogs').' c on p.catalog_id = c.id and p.userId = c.userId WHERE p.uniacid =:uniacid and  p.userId =:userId ORDER BY `id` desc LIMIT '.($pindex - 1) * $psize . "," . $psize,array(':uniacid'=>$_W['uniacid'],':userId'=>$userId));
		//$lists = pdo_getslice('share_card_products', $condition, array($pindex, $psize), $total,'', 'id', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		include $this->template('products-display');
	}

	public function doWebProducts_add() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;
		$catalog_lists = pdo_getslice('share_card_catalogs', $condition, null, $total,'', 'id', 'priority desc');

		$id = safe_gpc_int($_GPC['id']);
		$list = pdo_get('share_card_products', array('id' => $id));
		$list['picture'] = explode(',',$list['picture']);

		if (checksubmit('submit')) {
			if (empty($_GPC['name'])) {
				itoast('名称不能为空', referer());
			}

			$data = array(
				'userId' => $userId,
				'name' => safe_gpc_string($_GPC['name']),
				'price' => safe_gpc_string($_GPC['price']),
				'mainpic' => safe_gpc_string($_GPC['mainpic']),
				'content' => htmlspecialchars_decode($_GPC['content']),
				'picture' => implode(',',safe_gpc_string($_GPC['picture'])),
				'catalog_id' => safe_gpc_string($_GPC['catalog_id']),
				'priority' => safe_gpc_string($_GPC['priority']),
				'status' => safe_gpc_string($_GPC['status']),
				'description' => htmlspecialchars_decode($_GPC['description']),
				'create_time' => TIMESTAMP,
				'uniacid' => $_W['uniacid']
			);

			if (!empty($id)) {
				pdo_update('share_card_products', $data, array('id' => $id));
				message('修改成功', $this->createWebUrl('products',array('op' => 'products','userId'=>$userId)));
			} else {
				pdo_insert('share_card_products', $data);
				message('添加成功', $this->createWebUrl('products',array('op' => 'products','userId'=>$userId)));
			}
		}
		include $this->template('products-post');
	}

	public function doWebProducts_delete() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$id = safe_gpc_int($_GPC['id']);
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('products',array('op' => 'products','userId'=>$userId)));
		}
		pdo_delete('share_card_products', array('id' => $id));
		itoast('删除成功', $this->createWebUrl('products',array('op' => 'products','userId'=>$userId)));
	}

	public function doWebNotices() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;
		$keyword = safe_gpc_string($_GPC['name'], '');
		if (!empty($keyword)) {
			$condition['name LIKE'] = "%{$keyword}%";
		}
		$lists = pdo_getslice('share_card_notices', $condition, array($pindex, $psize), $total,'', 'id', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		include $this->template('notices-display');
	}

	public function doWebNotices_add() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];

		$id = safe_gpc_int($_GPC['id']);
		$list = pdo_get('share_card_notices', array('id' => $id));

		if (checksubmit('submit')) {
			if (empty($_GPC['name'])) {
				itoast('标题不能为空', referer());
			}

			$data = array(
				'userId' => $userId,
				'name' => safe_gpc_string($_GPC['name']),
				'mainpic' => safe_gpc_string($_GPC['mainpic']),
				'content' => htmlspecialchars_decode($_GPC['content']),
				'priority' => safe_gpc_string($_GPC['priority']),
				'status' => safe_gpc_string($_GPC['status']),
				'description' => htmlspecialchars_decode($_GPC['description']),
				'create_time' => TIMESTAMP,
				'uniacid' => $_W['uniacid']
			);

			if (!empty($id)) {
				pdo_update('share_card_notices', $data, array('id' => $id));
				message('修改成功', $this->createWebUrl('notices',array('op' => 'notices','userId'=>$userId)));
			} else {
				pdo_insert('share_card_notices', $data);
				message('添加成功', $this->createWebUrl('notices',array('op' => 'notices','userId'=>$userId)));
			}
		}
		include $this->template('notices-post');
	}

	public function doWebNotices_delete() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$id = safe_gpc_int($_GPC['id']);
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('notices',array('op' => 'notices','userId'=>$userId)));
		}
		pdo_delete('share_card_notices', array('id' => $id));
		itoast('删除成功', $this->createWebUrl('notices',array('op' => 'notices','userId'=>$userId)));
	}

	public function doWebComments() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$notice_id = $_GPC['notice_id'];
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;
		$condition['notice_id'] = $notice_id;
		$keyword = safe_gpc_string($_GPC['name'], '');
		if (!empty($keyword)) {
			$condition['name LIKE'] = "%{$keyword}%";
		}
		$total = pdo_fetchcolumn("SELECT COUNT(*) FROM ". tablename('share_card_comments').' WHERE uniacid =:uniacid and userId=:userId',array(':uniacid'=>$_W['uniacid'],':userId'=>$userId) );		
		$lists = pdo_fetchall('SELECT c.*,n.name as notice_name FROM '.tablename('share_card_comments').' c left join '.tablename('share_card_notices').' n on c.notice_id = n.id and c.userId = n.userId WHERE c.uniacid =:uniacid and  c.userId =:userId ORDER BY `id` desc LIMIT '.($pindex - 1) * $psize . "," . $psize,array(':uniacid'=>$_W['uniacid'],':userId'=>$userId));
		//$lists = pdo_getslice('share_card_comments', $condition, array($pindex, $psize), $total,'', 'id', 'id desc');
		$pager = pagination($total, $pindex, $psize);
		include $this->template('comments-display');
	}

	public function doWebComments_add() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$condition['userId'] = $userId;

		$notice_lists = pdo_getslice('share_card_notices', $condition, null, $total,'', 'id', 'priority desc');

		$id = safe_gpc_int($_GPC['id']);
		$list = pdo_get('share_card_comments', array('id' => $id));

		if (checksubmit('submit')) {
			if (empty($_GPC['content'])) {
				itoast('内容不能为空', referer());
			}

			$data = array(
				'userId' => $userId,
				'user_id' => safe_gpc_string($_GPC['user_id']),
				'notice_id' => safe_gpc_string($_GPC['notice_id']),
				'content' => safe_gpc_string($_GPC['content']),
				'priority' => safe_gpc_string($_GPC['priority']),
				'status' => safe_gpc_string($_GPC['status']),
				'create_time' => TIMESTAMP,
				'uniacid' => $_W['uniacid']
			);

			if (!empty($id)) {
				pdo_update('share_card_comments', $data, array('id' => $id));
				message('修改成功', $this->createWebUrl('comments',array('op' => 'comments','userId'=>$userId)));
			} else {
				pdo_insert('share_card_comments', $data);
				message('添加成功', $this->createWebUrl('comments',array('op' => 'comments','userId'=>$userId)));
			}
		}
		include $this->template('comments-post');
	}

	public function doWebComments_delete() {
		global $_W, $_GPC;
		$op = $_GPC['op'];
		$userId = intval($_GPC['userId']);
		$id = safe_gpc_int($_GPC['id']);
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('comments',array('op' => 'comments','userId'=>$userId)));
		}
		pdo_delete('share_card_comments', array('id' => $id));
		itoast('删除成功', $this->createWebUrl('comments',array('op' => 'comments','userId'=>$userId)));
	}

	public function doWebUsers() {
		global $_W, $_GPC;
		$pindex = max(1, intval($_GPC['page']));
		$psize = 20;

		$condition = array();
		$condition['uniacid'] = $_W['uniacid'];
		$keyword = safe_gpc_string($_GPC['name'], '');
		if (!empty($keyword)) {
			$condition['name LIKE'] = "%{$keyword}%";
		}
		$results = array();
		$lists = pdo_getslice('share_card_users', $condition, array($pindex, $psize), $total,'', 'id', 'id desc');
		foreach($lists as $list){
			$detail = iunserializer($list['detail']);
			$list['value'] = $detail;
			$results[] = $list;			
		}
		$lists = $results;
		$pager = pagination($total, $pindex, $psize);
		include $this->template('users-display');
	}

	public function doWebUsers_add() {
		global $_W, $_GPC;
		$op = $_GPC['op']?$_GPC['op']:'users';

		$id = safe_gpc_int($_GPC['id']);
		$list = pdo_get('share_card_users', array('id' => $id));
		if (!empty($list['detail'])) {
			$list['detail'] = iunserializer($list['detail']);
		}

		if (checksubmit('submit')) {
			if (empty($_GPC['name'])) {
				itoast('姓名不能为空', referer());
			}

			$uni_setting['name'] = safe_gpc_string($_GPC['name']);
			$uni_setting['headerImg'] = safe_gpc_string($_GPC['headerImg']);
			$uni_setting['mobile'] = safe_gpc_string($_GPC['mobile']);
			$uni_setting['weixin'] = safe_gpc_string($_GPC['weixin']);
			$uni_setting['weixinQrcode'] = safe_gpc_string($_GPC['weixinQrcode']);
			$uni_setting['gender'] = safe_gpc_string($_GPC['gender']);
			$uni_setting['email'] = safe_gpc_string($_GPC['email']);
			$uni_setting['qq'] = safe_gpc_string($_GPC['qq']);
			$uni_setting['address'] = safe_gpc_string($_GPC['address']);
			$uni_setting['sign'] = safe_gpc_string($_GPC['sign']);
			$uni_setting['show'] = safe_gpc_string($_GPC['show']);
			$uni_setting['tag'] = safe_gpc_string($_GPC['tag']);
			$uni_setting['companyName'] = safe_gpc_string($_GPC['companyName']);
			$uni_setting['companyCode'] = safe_gpc_string($_GPC['companyCode']);
			$uni_setting['companyAddress'] = safe_gpc_string($_GPC['companyAddress']);
			$uni_setting['title'] = safe_gpc_string($_GPC['title']);
			$uni_setting = iserializer($uni_setting);

			$data = array(
				'name' => safe_gpc_string($_GPC['name']),
				'status' => safe_gpc_string($_GPC['status']),
				'create_time' => TIMESTAMP,
				'uniacid' => $_W['uniacid'],
				'detail' => $uni_setting,
			);

			if (!empty($id)) {
				pdo_update('share_card_users', $data, array('id' => $id));
				message('修改成功', $this->createWebUrl('users'));
			} else {
				pdo_insert('share_card_users', $data);
				message('添加成功', $this->createWebUrl('users'));
			}
		}
		include $this->template('users-post');
	}

	public function doWebUsers_delete() {
		global $_W, $_GPC;
		$id = safe_gpc_int($_GPC['id']);
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('users'));
		}
		pdo_delete('share_card_users', array('id' => $id));
		itoast('删除成功', $this->createWebUrl('users'));
	}

	public function doWebUsers_update() {
		global $_W, $_GPC;
		$id = safe_gpc_int($_GPC['id']);
		$status = $_GPC['status'];
		if (empty($id)) {
			itoast('非法操作', $this->createWebUrl('users'));
		}
		$data = pdo_get('share_card_users', array('id' => $id));
		$data['status'] = $status;
		pdo_update('share_card_users', $data, array('id' => $id));
		itoast('更新成功', $this->createWebUrl('users'));
	}

	public function doWebSettings() {
		global $_W, $_GPC;
		$setting = pdo_get('share_card_setting', array('key' => 'setting', 'uniacid' => $_W['uniacid']));
		if (!empty($setting['value'])) {
			$setting = iunserializer($setting['value']);
		}
		if ($_W['ispost']) {
			$uni_setting['companyName'] = safe_gpc_string($_GPC['companyName']);
			$uni_setting['companyCode'] = safe_gpc_string($_GPC['companyCode']);
			$uni_setting['companyAddress'] = safe_gpc_string($_GPC['companyAddress']);
			$uni_setting = iserializer($uni_setting);
			if (!empty($setting)) {
				pdo_update('share_card_setting', array('value' => $uni_setting), array('key' => 'setting', 'uniacid' => $_W['uniacid']));
			} else {
				pdo_insert('share_card_setting', array('key' => 'setting', 'value' => $uni_setting, 'uniacid' => $_W['uniacid']));
			}
			itoast('设置成功！', $this->createWebUrl('settings'), 'success');
		}
		include $this->template('company-setting');
	}
}