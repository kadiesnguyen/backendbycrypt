<?php
namespace Admin\Controller;

class ConfigController extends AdminController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array(
			"index",
			"edit",
			"image",
			"coin",
			"coinEdit",
			"coinStatus",
			"textStatus",
			"coinImage",
			"text",
			"textEdit",
			"qita",
			"qitaEdit",
			"daohang",
			"daohangEdit",
			"daohangStatus",
			"dhfooter",
			"dhfooterEdit",
			"dhfooterStatus",
			"dhadmin",
			"dhadminEdit",
			"dhadminStatus",
			"ctmarket",
			"ctmarketEdit",
			"marketo",
			"marketoEdit",
			"marketoEdit2",
			"marketoEdit3",
			"marketoStatus",
			"ctmarketoStatus",
			"mining",
			"miningEdit",
			"uploadImage",
			"depositport",
			"depositportEdit",
			"depositportStatus"
		);
		if (!in_array(ACTION_NAME, $allow_action)) {
			$this->error(L("Page not existed") . ACTION_NAME);
		}
	}

	public function index()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function edit()
	{
		$data = I('post.');

		if (M('Config')->where(array('id' => 1))->save($data)) {
			$config = M('Config')->where(['id' => 1])->find();
			session('sys_config', $config);
			$this->success(L("Edit successfully"));
		} else {
			$this->error(L("Cannot edit"));
		}
	}

	public function image()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/public/';
		$upload->autoSub = false;
		$info = $upload->upload();
		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

	public function coin($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('Coin')->where($where)->count();
		$Page = new \Think\Page($count, 100);
		$show = $Page->show();
		$list = M('Coin')->where($where)->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();

		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function coinEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			} else {
				$this->data = M('Coin')->where(array('id' => trim($_GET['id'])))->find();
			}
			$this->display();
		} else {
			if ($_POST['id']) {
				$_POST['addtime'] = date("Y-m-d H:i:s", time());
				$rs = M('Coin')->save($_POST);
			} else {
				if (!check($_POST['name'], 'n')) {
					$this->error(L("Currency abbreviations can only be lowercase"));
				}
				$_POST['name'] = strtolower($_POST['name']);
				if (check($_POST['name'], 'username')) {
					$this->error(L("Incorrect currency name format"));
				}
				if (M('Coin')->where(array('name' => $_POST['name']))->find()) {
					$this->error(L("Currency exists"));
				}
				$_POST['addtime'] = date("Y-m-d H:i:s", time());
				$rea = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . '` DECIMAL(20,10) UNSIGNED NOT NULL DEFAULT 0.00');
				$reb = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'd` DECIMAL(20,10) UNSIGNED NOT NULL DEFAULT 0.00');
				// $rec = M()->execute('ALTER TABLE  `tw_user_coin` ADD  `' . $_POST['name'] . 'b` VARCHAR(200) NOT NULL DEFAULT 0');
				$rs = M('Coin')->add($_POST);
			}
			if ($rs) {
				$this->success(L("Successfully"), U('Config/coin'));
			} else {
				$this->error(L("Data is not modified"));
			}
		}
	}

	public function coinStatus()
	{
		if (IS_POST) {
			$id = array();
			$id = implode(',', $_POST['id']);
		} else {
			$id = $_GET['id'];
		}

		if (empty($id)) {
			$this->error(L("Please select data to operate"));
		}

		$where['id'] = array('in', $id);
		$method = $_GET['type'];
		// $this->error($method);
		switch (strtolower($method)) {
			case 'forbid':
				$data = array('status' => 0);
				break;

			case 'resume':
				$data = array('status' => 1);
				break;

			case 'delt':
				$rs = M('Coin')->where($where)->select();

				foreach ($rs as $k => $v) {
					$rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name']);
					// $rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'd');
					// $rs[] = M()->execute('ALTER TABLE  `tw_user_coin` DROP COLUMN ' . $v['name'] . 'b');
				}

				if (M('Coin')->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}

				break;

			default:

				$this->error(L("Invalid parameter"));
		}

		if (M('Coin')->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}


	public function coinImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/coin/';
		$upload->autoSub = false;

		$info = $upload->upload();
		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

	public function depositport()
	{
		$list = M('RechargeMethod')->order('id asc')->select();
		$this->assign('list', $list);
		$this->display();
	}

	public function depositportEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('RechargeMethod')->where(array('id' => trim($id)))->find();
			} else {
				$this->data = null;
			}
			$coin_list = M('Coin')->order('sort asc,id asc')->select();
			$this->assign('coin_list', $coin_list);
			$this->display();
		} else {
			if ($_POST['id']) {
				$rs = M('RechargeMethod')->save($_POST);
			} else {
				$rs = M('RechargeMethod')->add($_POST);
			}

			if ($rs) {
				$this->success(L("Edit successfully"), U('Config/depositport'));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function depositportStatus($id = NULL, $type = NULL, $mobile = 'RechargeMethod')
	{
		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}

		if (is_array($id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 0);
				break;

			case 'resume':
				$data = array('status' => 1);
				break;

			case 'delete':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}
				break;

			default:
				$this->error(L("System error"));
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function qita()
	{
		$this->data = M('Config')->where(array('id' => 1))->find();
		$this->display();
	}

	public function qitaEdit()
	{
		$data = I('post.');
		if (isset($data['checkin_notify'])) {
			$data['checkin_notify'] = htmlspecialchars_decode($data['checkin_notify'], ENT_QUOTES);
		}
		if (M('Config')->where(array('id' => 1))->save($data)) {
			$this->success(L("Edit successfully"));
		} else {
			$this->error(L("Cannot edit"));
		}
	}

	public function daohang($name = NULL, $field = NULL, $status = NULL, $lang = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			} else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}
		if ($lang) {
			$where['lang'] = $lang;
		}

		$count = M('Daohang')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Daohang')->where($where)->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function daohangEdit($id = NULL)
	{
		//dump($_POST);
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('Daohang')->where(array('id' => trim($id)))->find();
			} else {
				$this->data = null;
			}

			$this->display();
		} else {
			if (APP_DEMO) {
				$this->error(L("Temporarily unable to be modified"));
			}

			if ($_POST['id']) {
				$rs = M('Daohang')->save($_POST);
			} else {
				$_POST['addtime'] = time();
				$rs = M('Daohang')->add($_POST);
			}

			if ($rs) {
				$closeUrl = S('closeUrl');
				if ($_POST['get_login']) {
					$closeUrl[] = $_POST['url'];
				} else {
					if ($key = array_search($_POST['url'], $closeUrl)) {
						unset($closeUrl[$key]);
					}
				}
				$closeUrl = array_unique($closeUrl);
				sort($closeUrl);
				S('closeUrl', $closeUrl);

				$this->success(L("Edit successfully"), U('Config/daohang'));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function daohangStatus($id = NULL, $type = NULL, $mobile = 'Daohang')
	{
		if (APP_DEMO) {
			$this->error(L("Temporarily unable to be modified"));
		}

		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 0);
				break;

			case 'resume':
				$data = array('status' => 1);
				break;

			case 'repeal':
				$data = array('status' => 2, 'endtime' => time());
				break;

			case 'del':
				$data = array('status' => -1);
				break;

			case 'delete':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}

				break;

			default:
				$this->error(L("System error"));
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function dhfooter($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			} else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}

		$count = M('footer')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('footer')->where($where)->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function dhfooterEdit($id = NULL)
	{
		if (empty($_POST)) {
			if ($id) {
				$this->data = M('footer')->where(array('id' => trim($id)))->find();
			} else {
				$this->data = null;
			}

			$this->display();
		} else {
			if ($_POST['id']) {
				$rs = M('footer')->save($_POST);
			} else {
				$_POST['addtime'] = time();
				$rs = M('footer')->add($_POST);
			}

			if ($rs) {
				$closeUrl = S('closeUrl');
				if ($_POST['get_login']) {
					$closeUrl[] = $_POST['url'];
				} else {
					if ($key = array_search($_POST['url'], $closeUrl)) {
						unset($closeUrl[$key]);
					}
				}
				$closeUrl = array_unique($closeUrl);
				sort($closeUrl);
				S('closeUrl', $closeUrl);
				$this->success(L("Edit successfully"), U('Config/dhfooter'));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function dhfooterStatus($id = NULL, $type = NULL, $mobile = 'footer')
	{
		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 0);
				break;

			case 'resume':
				$data = array('status' => 1);
				break;

			case 'repeal':
				$data = array('status' => 2, 'endtime' => time());
				break;

			case 'del':
				$data = array('status' => -1);
				break;

			case 'delete':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}

				break;

			default:
				$this->error(L("System error"));
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function dhadmin($name = NULL, $field = NULL, $status = NULL, $hide = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else if ($field == 'title') {
				$where['title'] = array('like', '%' . $name . '%');
			} else {
				$where[$field] = $name;
			}
		}

		if ($status) {
			$where['status'] = $status - 1;
		}
		if ($hide) {
			$where['hide'] = $hide;
		}

		$where_1 = $where;
		$where_1['pid'] = 0;
		$where_2 = $where;

		$list = M('menu')->where($where_1)->order('sort asc')->select();
		foreach ($list as $k => $v) {
			$where_2['pid'] = $v['id'];
			$list[$k]['voo'] = M('menu')->where($where_2)->order('sort asc')->select();
		}

		$this->assign('list', $list);
		$this->display();
	}

	public function dhadminEdit($id = NULL)
	{
		if (empty($_POST)) {
			$liste = '';

			if ($id) {
				$this->data = M('menu')->where(array('id' => trim($id)))->find();
			} else {
				$this->data = null;
			}

			$liste = M('menu')->where('pid = 0')->order('sort asc')->select();
			$this->assign('liste', $liste);
			$this->display();
		} else {
			if (APP_DEMO) {
				$this->error(L("Temporarily unable to be modified"));
			}

			if (empty($_POST['title'])) {
				$this->error('Tiêu đề sai');
			}

			if ($_POST['id']) {
				$rs = M('menu')->save($_POST);
			} else {
				$_POST['addtime'] = time();
				$rs = M('menu')->add($_POST);
			}

			if ($rs) {
				$this->success(L("Edit successfully"), U('Config/dhadmin'));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function dhadminStatus($id = NULL, $type = NULL, $mobile = 'menu')
	{
		if (APP_DEMO) {
			$this->error(L("Temporarily unable to be modified"));
		}

		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);
		switch (strtolower($type)) {
			case 'forbid':
				$data = array('hide' => 1);
				break;

			case 'resume':
				$data = array('hide' => 0);
				break;

			case 'repeal':
				$data = array('hide' => 2);
				break;

			case 'delete':
				$data = array('status' => -1);
				break;

			case 'del':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}
				break;

			default:
				$this->error(L("System error"));
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function ctmarket()
	{
		$count = M('ctmarket')->count();
		$Page = new \Think\Page($count, 100);
		$show = $Page->show();
		$list = M('ctmarket')->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function marketo($field = NULL, $name = NULL)
	{
		$where = array();

		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else {
				$where[$field] = $name;
			}
		}

		$count = M('Market')->where($where)->count();
		$Page = new \Think\Page($count, 100);
		$show = $Page->show();
		$list = M('Market')->where($where)->order('sort asc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function ctmarketEdit($id = NULL)
	{
		if (empty($_POST)) {
			$id = $_GET['id'];
			$this->data = M('ctmarket')->where(array('id' => $id))->find();
			$this->display();
		} else {
			$coinname = $_POST['coinname'];
			$status = $_POST['status'];
			$state = $_POST['state'];
			$sort = $_POST['sort'];
			$id = $_POST['id'];
			$data['coinname'] = strtolower($coinname);
			$data['name'] = strtolower($coinname) . "_usdt";
			$data['symbol'] = strtolower($coinname) . "-usdt";
			$data['title'] = strtoupper($coinname) . "/USDT";
			$data['status'] = $status;
			$data['state'] = $state;
			$data['sort'] = $sort;
			$data['addtime'] = date("Y-m-d H:i:s", time());
			//Chỉnh sửa
			if ($id > 0) {
				$re = M("ctmarket")->where(array('id' => $id))->save($data);
				if ($re) {
					$this->success(L("Successfully"), U('Config/ctmarket'));
				} else {
					$this->error(L("System error"));
				}

			} else {//新增
				$re = M("ctmarket")->add($data);
				if ($re) {
					$this->success(L("Successfully"), U('Config/ctmarket'));
				} else {
					$this->error(L("System error"));
				}
			}

		}

	}

	// Token nền tảngCấu hình thị trường修改
	public function marketoEdit($id = NULL)
	{
		$getCoreConfig = getCoreConfig();
		if (!$getCoreConfig) {
			$this->error(L("Configuration error"));
		}

		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			} else {
				$this->data = M('Market')->where(array('id' => $id))->find();
			}
			$time_arr = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
			$time_minute = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59');

			$this->assign('time_arr', $time_arr);
			$this->assign('time_minute', $time_minute);
			$this->assign('getCoreConfig', $getCoreConfig['indexcat']);
			$this->display();
		} else {
			if (APP_DEMO) {
				$this->error(L("Temporarily unable to be modified"));
			}

			$round = array(0, 1, 2, 3, 4, 5, 6);
			if (!in_array($_POST['round'], $round)) {
				$this->error(L("Wrong decimal format"));
			}

			if (!$_POST['hou_price']) {
				$_POST['hou_price'] = '0.00';
			}

			if ($_POST['id']) {
				$rs = M('Market')->save($_POST);
			} else {
				$buyname = $_POST['buyname'];
				$_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
				unset($_POST['buyname']);
				unset($_POST['sellname']);

				if (M('Market')->where(array('name' => $_POST['name']))->find()) {
					$this->error(L("The market exists"));
				}

				$jiaoyiqu = strtolower($getCoreConfig['indexcat'][$_POST['jiaoyiqu']]);
				if ($buyname != $jiaoyiqu) {
					$this->error(L("The trading region it belongs to does not match the buyer's currency") . $buyname);
				}
				$rs = M('Market')->add($_POST);
			}

			if ($rs) {
				$this->success(L("Successfully"), U('Config/marketo'));
			} else {
				$this->error(L("System error"));
			}
		}
	}

	public function marketoEdit2($id = NULL)
	{
		$getCoreConfig = getCoreConfig();
		if (!$getCoreConfig) {
			$this->error(L("Configuration error"));
		}
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			} else {
				$this->data = M('Market')->where(array('id' => $id))->find();
			}

			$time_arr = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
			$time_minute = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59');
			$this->assign('time_arr', $time_arr);
			$this->assign('time_minute', $time_minute);
			$this->assign('getCoreConfig', $getCoreConfig['indexcat']);
			$this->display();
		} else {
			if (APP_DEMO) {
				$this->error(L("Temporarily unable to be modified"));
			}

			$round = array(0, 1, 2, 3, 4, 5, 6);
			if (!in_array($_POST['round'], $round)) {
				$this->error(L("Wrong decimal format"));
			}

			if ($_POST['id']) {
				$rs = M('Market')->save($_POST);
			} else {
				$_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
				unset($_POST['buyname']);
				unset($_POST['sellname']);

				if (M('Market')->where(array('name' => $_POST['name']))->find()) {
					$this->error(L("The market exists"));
				}
				$rs = M('Market')->add($_POST);
			}

			if ($rs) {
				$this->success(L("Successfully"), U('Config/marketo'));
			} else {
				$this->error(L("System error"));
			}
		}
	}

	public function marketoEdit3($id = NULL)
	{
		$getCoreConfig = getCoreConfig();
		if (!$getCoreConfig) {
			$this->error(L("Configuration error"));
		}
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = array();
			} else {
				$this->data = M('Market')->where(array('id' => $id))->find();
			}

			$time_arr = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23');
			$time_minute = array('00', '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59');
			$this->assign('time_arr', $time_arr);
			$this->assign('time_minute', $time_minute);
			$this->assign('getCoreConfig', $getCoreConfig['indexcat']);

			$round = number_format("0", $this->data['round'] - 1) . '1';
			$this->assign('round', $round);

			$this->display();

		} else {
			if (APP_DEMO) {
				$this->error(L("Temporarily unable to be modified"));
			}

			$round = array(0, 1, 2, 3, 4, 5, 6);
			if (!in_array($_POST['round'], $round)) {
				$this->error(L("Wrong decimal format"));
			}

			if ($_POST['id']) {
				$rs = M('Market')->save($_POST);
			} else {
				$_POST['name'] = $_POST['sellname'] . '_' . $_POST['buyname'];
				unset($_POST['buyname']);
				unset($_POST['sellname']);

				if (M('Market')->where(array('name' => $_POST['name']))->find()) {
					$this->error(L("The market exists"));
				}

				$rs = M('Market')->add($_POST);
			}

			if ($rs) {
				$this->success(L("Successfully"), U('Config/marketo'));
			} else {
				$this->error(L("System error"));
			}
		}
	}

	public function marketoStatus($id = NULL, $type = NULL, $mobile = 'Market')
	{
		if (APP_DEMO) {
			$this->error(L("Temporarily unable to be modified"));
		}

		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}

		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}

		$where['id'] = array('in', $id);

		switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 0);
				break;

			case 'resume':
				$data = array('status' => 1);
				break;

			case 'repeal':
				$data = array('status' => 2, 'endtime' => time());
				break;

			case 'delete':
				$data = array('status' => -1);
				break;

			case 'del':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}

				break;

			default:
				$this->error(L("System error"));
		}

		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function ctmarketoStatus($id = NULL, $type = NULL, $mobile = 'ctmarket')
	{
		if (empty($id)) {
			$this->error(L("Parameter error"));
		}
		if (empty($type)) {
			$this->error(L("Parameter error"));
		}
		if (strpos(',', $id)) {
			$id = implode(',', $id);
		}
		$where['id'] = array('in', $id);
		switch (strtolower($type)) {
			case 'forbid':
				$data = array('status' => 2);
				break;
			case 'resume':
				$data = array('status' => 1);
				break;
			case 'del':
				if (M($mobile)->where($where)->delete()) {
					$this->success(L("Successfully"));
				} else {
					$this->error(L("System error"));
				}

				break;
			default:
				$this->error(L("System error"));
		}
		if (M($mobile)->where($where)->save($data)) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function uploadImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 5 * 1024 * 1024; // 5MB
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/config/';
		$upload->autoSub = false;

		$info = $upload->upload();
		if (!$info) {
			$this->ajaxReturn(array(
				'error' => 1,
				'message' => $upload->getError()
			));
		}

		$imageUrl = __ROOT__ . '/Upload/config/' . $info['imgFile']['savename'];

		$this->ajaxReturn(array(
			'error' => 0,
			'url' => $imageUrl
		));
	}
}
?>