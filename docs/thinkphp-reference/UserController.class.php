<?php
namespace Admin\Controller;

class UserController extends AdminController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array(
			"index",
			"edit",
			"status",
			"admin",
			"adminEdit",
			"adminStatus",
			"updateRules",
			"log",
			"logEdit",
			"logStatus",
			"qianbao",
			"qianbaoEdit",
			"qianbaoStatus",
			"coin",
			"coinEdit",
			"coinFreeze",
			"coinLog",
			"setpwd",
			"amountlog",
			"loginadmin",
			"billdel",
			"sendnotice",
			"upsendnotice",
			"noticelist",
			"noticedel",
			"authrz",
			"upanthrz",
			"online",
			"onlinelist",
			"sendonline",
			"uponline",
			"setagent",
			"agent",
			"cancelagent",
			"setmanager",
			"addfunds",
		);
		if (!in_array(ACTION_NAME, $allow_action)) {
			$this->error(L("Page not existed"));
		}
	}

	public function cancelagent($id)
	{
		$uid = $id;
		if ($uid <= 0 || $uid == '') {
			$this->error(L("Parameter error"));
		}
		$uinfo = M("user")->where(array('id' => $uid))->field("is_agent")->find();
		if (empty($uinfo)) {
			$this->error(L("Parameter error"));
		}
		if ($uinfo['is_agent'] == 0) {
			$this->error(L("Not an agent"));
		}
		$result = M("user")->where(array('id' => $uid))->save(array('is_agent' => 0));
		if ($result) {
			$this->success(L("Setup successful"));
		} else {
			$this->error(L("Setup failed"));
		}
	}

	public function agent()
	{
		$where['is_agent'] = 1;
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('User')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$uid = $v['id'];
			$one = M('User')->where(array('invit_1' => $uid))->count();
			if ($one <= 0) {
				$one = 0;
			}
			$two = M('User')->where(array('invit_2' => $uid))->count();
			if ($two <= 0) {
				$two = 0;
			}
			$three = M('User')->where(array('invit_3' => $uid))->count();
			if ($three <= 0) {
				$three = 0;
			}

			$all = $one + $two + $three;
			if ($all <= 0) {
				$all = 0;
			}
			$list[$k]['all'] = $all;
			$list[$k]['one'] = $one;
			$list[$k]['two'] = $two;
			$list[$k]['three'] = $three;
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function setagent($id)
	{
		$uid = $id;
		if ($uid <= 0 || $uid == '') {
			$this->error(L("Parameter error"));
		}
		$uinfo = M("user")->where(array('id' => $uid))->field("is_agent")->find();
		if (empty($uinfo)) {
			$this->error(L("Parameter error"));
		}
		if ($uinfo['is_agent'] == 1) {
			$this->error(L("Already an agent"));
		}
		$result = M("user")->where(array('id' => $uid))->save(array('is_agent' => 1));
		if ($result) {
			$this->success(L("Setup successful"));
		} else {
			$this->error(L("Setup failed"));
		}
	}

	public function setmanager($id)
	{
		if (session('admin_level') == 1) {
			$this->error(L("No permission to setup manager"));
		}
		$uid = $id;
		if ($uid <= 0 || $uid == '') {
			$this->error(L("Parameter error"));
		}
		$uinfo = M("user")->where(array('id' => $uid))->find();
		if (empty($uinfo)) {
			$this->error(L("Parameter error"));
		}
		$admin_list = M('Admin')->field('username')->select();
		$admin_usernames = array_column($admin_list, 'username');
		if (in_array($uinfo['username'], $admin_usernames)) {
			$this->error(L("Already a manager"));
		}
		$resultUser = M("user")->where(array('id' => $uid))->save(array('status' => 2));
		$resultAdmin = M("Admin")->add([
			'email' => $uinfo['username'],
			'username' => $uinfo['username'],
			'nickname' => isset($uinfo['username']) ? explode('@', $uinfo['username'])[0] : '',
			'moble' => $uinfo['cccd'],
			'password' => $uinfo['password'],
			'sort' => 0,
			'addtime' => time(),
			'last_login_time' => time(),
			'last_login_ip' => 0,
			'endtime' => 0,
			'status' => 1,
			'level' => 1,
		]);
		if ($resultUser && $resultAdmin) {
			$this->success(L("Setup successful"));
		} else {
			$this->error(L("Setup failed"));
		}
	}

	public function addfunds($id = null, $num = null)
	{
		$uid = intval($id);
		$amount = trim($num);
		if ($uid <= 0) {
			$this->error(L("Parameter error"));
		}
		if (!is_numeric($amount) || $amount <= 0) {
			$this->error(L("Parameter error"));
		}
		if (!preg_match('/^\d+(\.\d{1,8})?$/', $amount)) {
			$this->error(L("Parameter error"));
		}
		$user = M('User')->where(array('id' => $uid))->field('id,username')->find();
		if (empty($user)) {
			$this->error(L("Parameter error"));
		}
		$userCoinModel = M('UserCoin');
		$userCoin = $userCoinModel->where(array('userid' => $uid))->find();
		if (empty($userCoin)) {
			$userCoinModel->add(array('userid' => $uid));
			$userCoin = $userCoinModel->where(array('userid' => $uid))->find();
		}
		if (empty($userCoin)) {
			$this->error(L("System error"));
		}
		$before = floatval($userCoin['usdt']);
		$amountFloat = floatval($amount);
		$incre = $userCoinModel->where(array('userid' => $uid))->setInc('usdt', $amountFloat);
		$bill = array();
		$bill['uid'] = $uid;
		$bill['username'] = $user['username'];
		$bill['num'] = $amountFloat;
		$bill['coinname'] = 'usdt';
		$bill['afternum'] = $before + $amountFloat;
		$bill['type'] = 17;
		$bill['addtime'] = date("Y-m-d H:i:s", time());
		$bill['st'] = 1;
		$bill['remark'] = 'Admin cộng USDT thủ công';
		$addBill = M('bill')->add($bill);
		if ($incre && $addBill) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function upanthrz()
	{
		$rzstatus = $_POST['rzstatus'];
		$uid = $_POST['uid'];
		if ($uid <= 0 || $uid == '') {
			$this->error(L("Parameter error"));
		}
		if ($rzstatus == 2) {
			$result = M("user")->where(array('id' => $uid))->save(array('rzstatus' => 2, 'rzuptime' => time()));
			if ($result) {
				$kjid = $_POST['kjid'];
				$minfo = M("kuangji")->where(array('id' => $kjid))->find();
				$odate['kid'] = $minfo['id'];
				$odate['type'] = 1;
				$odate['sharebl'] = '';
				$odate['uid'] = $uid;
				$odate['username'] = $_POST['username'];
				$odate['kjtitle'] = $minfo['title'];
				$odate['imgs'] = $minfo['imgs'];
				$odate['status'] = 1;
				$odate['cycle'] = $minfo['cycle'];
				$odate['synum'] = $minfo['cycle'];
				$odate['outtype'] = $minfo['outtype'];
				$odate['outcoin'] = $minfo['outcoin'];
				if ($minfo['outtype'] == 1) {
					$odate['outnum'] = '';
					$odate['outusdt'] = $minfo['dayoutnum'];
				} elseif ($minfo['outtype'] == 2) {
					$odate['outnum'] = $minfo['dayoutnum'];
					$odate['outusdt'] = '';
				}
				$odate['djout'] = $minfo['djout'];
				if ($minfo['djout'] == 2) {
					$odate['djnum'] = $minfo['djday'];
				} else {
					$odate['djnum'] = $minfo['djday'];
				}
				$odate['addtime'] = date("Y-m-d H:i:s", time());
				$odate['endtime'] = date("Y-m-d H:i:s", (time() + 86400 * $minfo['cycle']));
				$odate['intaddtime'] = time();
				$odate['intendtime'] = time() + 86400 * $minfo['cycle'];
				$adre = M("kjorder")->add($odate);
				$notice['uid'] = $uid;
				$notice['account'] = $_POST['username'];
				$notice['title'] = 'Kiểm tra chứng nhận thành công';
				$notice['content'] = 'Yêu cầu xin chứng nhận của bạn đã được xem xét thành công';
				$notice['addtime'] = date("Y-m-d H:i:s", time());
				$notice['status'] = 1;
				M("notice")->add($notice);
				$this->success(L("Authentication successful"));
			} else {
				$this->error(L("System error"));
			}
		} elseif ($rzstatus == 3) {
			$result = M("user")->where(array('id' => $uid))->save(array('rzstatus' => 3, 'rzuptime' => time()));
			if ($result) {
				$notice['uid'] = $uid;
				$notice['account'] = $_POST['username'];
				$notice['title'] = 'Xác thực bị từ chối';
				$notice['content'] = 'Ứng dụng xác thực của bạn đã bị quản trị viên từ chối, vui lòng liên hệ với quản trị viên';
				$notice['addtime'] = date("Y-m-d H:i:s", time());
				$notice['status'] = 1;
				M("notice")->add($notice);
				$this->success(L("Successfully"));
			} else {
				$this->error(L("System error"));
			}
		}
	}

	public function authrz($id)
	{
		$klist = M("kuangji")->where(array('rtype' => 2))->field("id,title")->select();
		$this->assign("klist", $klist);
		$info = M("user")->where(array('id' => $id))->field("id,username,cccd,cardzm,cardfm,rztime")->find();
		$this->assign('info', $info);
		$this->display();
	}

	public function noticedel($id = null, $type = null)
	{
		if (empty($id)) {
			$this->error(L("Missing params"));
			exit();
		}
		$where['id'] = array('in', $id);
		$result = M("notice")->where($where)->delete();
		if ($result) {
			$this->success(L("Delete successfully"));
		} else {
			$this->error(L("Cannot delete"));
		}
	}

	public function noticelist($username = NULL)
	{
		$where = array();
		if ($username != '') {
			$where['account'] = $username;
		}
		$count = M('notice')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('notice')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function sendnotice($id = null, $type = null)
	{
		$this->assign('id', $id);
		$this->assign('type', $type);
		$this->display();
	}

	public function upsendnotice($id = null, $type = null, $title = null, $content = null, $imgs = null)
	{
		//if(checkstr($title) || checkstr($content)){
		//$this->error("不能输入特殊字符！");exit();
		//}
		if ($type == 1) {
			if ($id <= 0) {
				$this->error(L("Missing params"));
				exit();
			}
			$uinfo = M("user")->where(array('id' => $id))->field("id,username")->find();
			if (empty($uinfo)) {
				$this->error(L("Missing params"));
				exit();
			}
			$data['uid'] = $uinfo['id'];
			$data['account'] = $uinfo['username'];
			$data['title'] = $title;
			$data['content'] = $content;
			$data['imgs'] = $imgs;
			$data['addtime'] = date("Y-m-d H:i:s", time());
			$data['status'] = 1;
			$result = M("notice")->add($data);
			if ($result) {
				$this->success(L("Notice sent successfully"));
			} else {
				$this->success(L("Notice send failed"));
			}
		} elseif ($type == 2) {
			$ulist = M("user")->field("id,username")->select();
			if (!empty($ulist)) {
				foreach ($ulist as $key => $vo) {
					$data['uid'] = $vo['id'];
					$data['account'] = $vo['username'];
					$data['title'] = $title;
					$data['content'] = $content;
					$data['imgs'] = $imgs;
					$data['addtime'] = date("Y-m-d H:i:s", time());
					$data['status'] = 1;
					M("notice")->add($data);
				}
				$this->success(L("Bulk notice sent successfully"));
			}
		} else {
			$this->error(L("Access denied"));
			exit();
		}
	}

	public function status($id = NULL, $type = null)
	{
		if (empty($id)) {
			$this->error(L("Select a member"));
			exit();
		}
		$where['id'] = array('in', $id);
		switch (strtolower($type)) {
			case 1:
				//冻结
				$result = M("user")->where($where)->save(array('status' => 1));
				break;
			case 2:
				//解封
				$result = M("user")->where($where)->save(array('status' => 2));
				break;
			case 3:
				//启动提币
				$result = M("user")->where($where)->save(array('txstate' => 1));
				break;
			case 4:
				//禁止提币
				$result = M("user")->where($where)->save(array('txstate' => 2));
				break;
			case 5:
				//删除会员
				if (session('admin_level') == 1) {
					$this->error(L("No permission to delete member"));
				}
				$result = M("user")->where($where)->delete();
				break;
			default:
				$this->error(L("System error"));
		}
		if ($result) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function onlinelist($id = null)
	{
		$where['uid'] = $id;
		$where['type'] = 2;
		$where['state'] = 0;
		$count = M('online')->where($where)->count();
		$Page = new \Think\Page($count, 50);
		$show = $Page->show();
		$list = M('online')->where($where)->order('state desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function sendonline($id = null)
	{
		$info = M("online")->where(array('id' => $id))->find();
		$this->assign("info", $info);
		$this->display();
	}

	public function uponline($oid = null, $content = null)
	{
		if (checkstr($content)) {
			$this->error(L("Invalid input information"));
		}
		if ($oid <= 0 || $oid == '') {
			$this->error(L("Missing params"));
		}
		$info = M("online")->where(array('id' => $oid))->find();
		$uid = $info['uid'];
		$data['uid'] = $uid;
		$data['username'] = $info['username'];
		$data['content'] = $content;
		$data['type'] = 1;
		$data['addtime'] = date("Y-m-d H:i:s", time());
		$data['state'] = 1;
		$result = M("online")->add($data);
		if ($result) {
			M("online")->where(array('id' => $oid))->save(array('state' => 1));
			$this->success(L("Reply successful"));
		} else {
			$this->error(L("Reply failed"));
		}
	}

	public function online($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();
		if ($field && $name) {
			$where[$field] = $name;
		}
		if ($status) {
			$where['status'] = $status;
		}
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 50);
		$show = $Page->show();
		$list = M('User')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->field("id,username")->select();
		foreach ($list as $key => $vo) {
			$map['uid'] = $vo['id'];
			$map['state'] = array('eq', 0);
			$list[$key]['nor'] = M("online")->where($map)->count();
		}
		$new_arr = array_column($list, 'nor');
		array_multisort($new_arr, SORT_DESC, $list);
		foreach ($list as $k => $v) {
			if ($v['nor'] == 0) {
				unset($list[$k]);
			}
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function index($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();
		if ($field && $name) {
			$where[$field] = $name;
		}
		if ($status) {
			$where['status'] = $status;
		}
		if ($field !== 'username') {
			$where['username'] = array('neq', session('admin_username'));
		}
		$count = M('User')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('User')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$admin_list = M('Admin')->field('username')->select();
		$admin_usernames = array_column($admin_list, 'username');
		foreach ($list as $k => $v) {
			$list[$k]['invit_1'] = M('User')->where(array('id' => $v['invit_1']))->getField('username');
			$list[$k]['invit_2'] = M('User')->where(array('id' => $v['invit_2']))->getField('username');
			$list[$k]['invit_3'] = M('User')->where(array('id' => $v['invit_3']))->getField('username');
			$user_login_state = M('user_log')->where(array('userid' => $v['id'], 'type' => 'login'))->order('id desc')->find();
			$list[$k]['state'] = $user_login_state['state'];
			$list[$k]['is_manager'] = in_array($v['username'], $admin_usernames) ? 1 : 0;
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = '';
			} else {
				$this->data = M('User')->where(array('id' => trim($id)))->find();
			}
			$this->display();
		} else {
			//新增会员
			if ($id <= 0 || $id == null) {
				$username = trim($_POST['username']);
				if ($username == '') {
					$this->error(L("Enter member account"));
					exit();
				} else {
					$add['username'] = $username;
				}
				if ($_POST['password'] == "") {
					$this->error(L("Enter login password"));
					exit();
				} else {
					$add['password'] = md5($_POST['password']);
				}
				if ($_POST['paypassword'] != "") {
					$add['paypassword'] = md5($_POST['paypassword']);
				}
				if ($_POST['invit'] != 0 || $_POST['invit'] != '') {
					$inv_user = M('User')->where(array('invit' => $_POST['invit']))->field("id,username,invit_1,invit_2,path")->find();
					if (empty($inv_user)) {
						$this->error(L("Referrer not exist"));
						exit();
					}
					$add['invit_1'] = $inv_user['id'];
					$add['invit_2'] = $inv_user['invit_1'];
					$add['invit_3'] = $inv_user['invit_2'];
					$path = $inv_user['path'] . ',' . $inv_user['id'];
				} else {
					$add['invit_1'] = 0;
					$add['invit_2'] = 0;
					$add['invit_3'] = 0;
					$path = '';
				}
				$add['bank_name'] = $_POST['bank_name'];
				$add['bank_acc_no'] = $_POST['bank_acc_no'];
				$add['bank_acc_name'] = $_POST['bank_acc_name'];
				$add['wallet'] = $_POST['wallet'];
				$add['status'] = $_POST['status'];
				$add['txstate'] = $_POST['txstate'];
				$add['addtime'] = time();
				$add['addip'] = get_client_ip();
				$add['addr'] = get_city_ip();
				$add['invit'] = tradenoa();
				$re = M("user")->add($add);
				if ($re) {
					M('user_coin')->add(array('userid' => $re));
					$this->success(L("Add successfully"));
					exit();
				} else {
					$this->error(L("Cannot add"));
					exit();
				}
			} else {
				if ($_POST['password']) {
					$_POST['password'] = md5($_POST['password']);
				} else {
					unset($_POST['password']);
				}
				if ($_POST['paypassword']) {
					$_POST['paypassword'] = md5($_POST['paypassword']);
				} else {
					unset($_POST['paypassword']);
				}
				if ($_POST['status'] == 1) {
					// Status active thì vô hiệu hoá record quản lý
					M("Admin")->where(array('username' => $_POST['username']))->save(array('status' => 2));
				}
				if (isset($_POST['cccd'] ) && !empty($_POST['cccd'])) {
					$cccd_exists = M('User')->where(array('cccd' => $_POST['cccd'], 'id' => array('neq', $id)))->field("id, cccd")->find();
					if ($cccd_exists) {
						$this->error(L("CCCD existed"));
					}
				}
				$result = M("user")->where(array('id' => $id))->save($_POST);
				if ($result) {
					$this->success(L("Edit successfully"));
					exit();
				} else {
					$this->error(L("Edit unsuccessful"));
					exit();
				}
			}
		}
	}

	public function admin($name = NULL, $field = NULL, $status = NULL)
	{
		$DbFields = M('Admin')->getDbFields();
		if (!in_array('email', $DbFields)) {
			M()->execute('ALTER TABLE `tw_admin` ADD COLUMN `email` VARCHAR(200) NOT NULL COMMENT \'\' AFTER `id`;');
		}
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
		$where['username'] = array('neq', session('admin_username'));
		$count = M('Admin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Admin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$aga = 0;
			$aga = M('AuthGroupAccess')->where(array('uid' => $v['id']))->find();
			$ag = M('AuthGroup')->where(array('id' => $aga['group_id']))->find();
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function adminEdit()
	{
		if (empty($_POST)) {
			if (empty($_GET['id'])) {
				$this->data = null;
			} else {
				$where = array();
				$where['id'] = trim($_GET['id']);
				$where['username'] = array('neq', session('admin_username'));
				$this->data = M('Admin')->where($where)->find();
			}
			$this->display();
		} else {
			$input = I('post.');
			if (!check($input['username'], 'username')) {
				//$this->error('Tên đăng nhập格式错误！');
			}
			if ($input['nickname'] && !check($input['nickname'], 'A')) {
				$this->error(L("Invalid name format"));
			}
			if ($input['password'] && !check($input['password'], 'password')) {
				$this->error(L("Invalid password format"));
			}
			if ($input['mobile'] && !check($input['mobile'], 'mobile')) {
				$this->error(L("Invalid phone format"));
			}
			if ($input['email'] && !check($input['email'], 'email')) {
				$this->error(L("Invalid email format"));
			}
			if ($input['password']) {
				$input['password'] = md5($input['password']);
			} else {
				unset($input['password']);
			}
			if ($_POST['id']) {
				$rs = M('Admin')->save($input);
			} else {
				$_POST['addtime'] = time();
				$rs = M('Admin')->add($input);
			}
			if ($rs) {
				$this->success(L("Edit successfully"));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function adminStatus($id = NULL, $type = NULL, $mobile = 'Admin')
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

	protected function updateRules()
	{
		$nodes = $this->returnNodes(false);
		$AuthRule = M('AuthRule');
		$map = array(
			'module' => 'admin',
			'type' => array('in', '1,2')
		);
		$rules = $AuthRule->where($map)->order('name')->select();
		$data = array();
		foreach ($nodes as $value) {
			$temp['name'] = $value['url'];
			$temp['title'] = $value['title'];
			$temp['module'] = 'admin';
			if (0 < $value['pid']) {
				$temp['type'] = \Common\Model\AuthRuleModel::RULE_URL;
			} else {
				$temp['type'] = \Common\Model\AuthRuleModel::RULE_MAIN;
			}
			$temp['status'] = 1;
			$data[strtolower($temp['name'] . $temp['module'] . $temp['type'])] = $temp;
		}
		$update = array();
		$ids = array();
		foreach ($rules as $index => $rule) {
			$key = strtolower($rule['name'] . $rule['module'] . $rule['type']);
			if (isset($data[$key])) {
				$data[$key]['id'] = $rule['id'];
				$update[] = $data[$key];
				unset($data[$key]);
				unset($rules[$index]);
				unset($rule['condition']);
				$diff[$rule['id']] = $rule;
			} else if ($rule['status'] == 1) {
				$ids[] = $rule['id'];
			}
		}
		if (count($update)) {
			foreach ($update as $k => $row) {
				if ($row != $diff[$row['id']]) {
					$AuthRule->where(array('id' => $row['id']))->save($row);
				}
			}
		}
		if (count($ids)) {
			$AuthRule->where(array(
				'id' => array('IN', implode(',', $ids))
			))->save(array('status' => -1));
		}
		if (count($data)) {
			$AuthRule->addAll(array_values($data));
		}
		if ($AuthRule->getDbError()) {
			trace('[' . 'Admin\\Controller\\UserController::updateRules' . ']:' . $AuthRule->getDbError());
			return false;
		} else {
			return true;
		}
	}

	public function log($name = NULL, $field = NULL, $status = NULL)
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
		$count = M('UserLog')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserLog')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function logEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			} else {
				$this->data = M('UserLog')->where(array('id' => trim($id)))->find();
			}
			$this->display();
		} else {
			$_POST['addtime'] = strtotime($_POST['addtime']);
			if (M('UserLog')->save($_POST)) {
				$this->success(L("Edit successfully"));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function logStatus($id = NULL, $type = NULL, $mobile = 'UserLog')
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

	public function qianbao($name = NULL, $field = NULL, $coinname = NULL, $status = NULL)
	{
		$where = array();
		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else {
				$where[$field] = $name;
			}
		}
		if ($coinname) {
			$where['name'] = trim($coinname);
		}
		$coinlist = M("coin")->where("type = 1")->order("id desc")->field("name,title")->select();
		$this->assign("coinlist", $coinlist);
		$count = M('UserQianbao')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserQianbao')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function qianbaoEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			} else {
				$this->data = M('UserQianbao')->where(array('id' => trim($id)))->find();
			}
			$coinlist = M("coin")->where("type = 1")->order("id desc")->field("name,title")->select();
			$this->assign("coinlist", $coinlist);
			$this->display();
		} else {
			$_POST['addtime'] = strtotime($_POST['addtime']);
			if (M('UserQianbao')->save($_POST)) {
				$this->success(L("Edit successfully"));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function coin($name = NULL, $field = NULL)
	{
		$where = array();
		if ($field && $name) {
			if ($field == 'username') {
				$where['userid'] = M('User')->where(array('username' => $name))->getField('id');
			} else {
				$where[$field] = $name;
			}
		}
		$admin_list = M('Admin')->field('username')->select();
		$admin_usernames = array_column($admin_list, 'username');
		$count = M('UserCoin')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('UserCoin')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k => $v) {
			$list[$k]['username'] = M('User')->where(array('id' => $v['userid']))->getField('username');
			if (!$list[$k]['username'] || in_array($list[$k]['username'], $admin_usernames)) {
				unset($list[$k]);
			}
		}
		$this->assign('list', $list);
		$this->assign('page', $show);
		$coinlist = M("coin")->order("id desc")->field("name,title")->select();
		$this->assign("coinlist", $coinlist);
		$this->display();
	}

	public function coinEdit($id = NULL)
	{
		if (empty($_POST)) {
			if (empty($id)) {
				$this->data = null;
			} else {
				$this->data = M('UserCoin')->where(array('id' => trim($id)))->find();
			}
			$coinlist = M("coin")->order("id desc")->field("name,title")->select();
			$userid = $this->data['userid'];
			$user = M('User')->where(array('id' => $userid))->getField('username');
			$this->assign("user", $user);
			$this->assign("coinlist", $coinlist);
			$this->display();
		} else {
			$_POST['addtime'] = strtotime($_POST['addtime']);
			if (M('UserCoin')->save($_POST)) {
				$this->success(L("Edit successfully"));
			} else {
				$this->error(L("Edit unsuccessful"));
			}
		}
	}

	public function amountlog($st = null, $coinname = null, $username = null)
	{
		$coinlist = M("coin")->order("id desc")->field("name,title")->select();
		$this->assign("coinlist", $coinlist);
		if ($st > 0) {
			$where['st'] = $st;
		}
		if ($coinname != '') {
			$where['coinname'] = $coinname;
		}
		if ($username != '') {
			$where['username'] = $username;
		}
		$count = M('bill')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('bill')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function billdel($type = null, $id = null)
	{
		if (empty($id)) {
			$this->error(L("Missing params"));
			exit();
		}
		$where['id'] = array('in', $id);
		$re = M('bill')->where($where)->delete();
		if ($re) {
			$this->success(L("Delete successfully"));
			exit();
		} else {
			$this->error(L("Cannot delete"));
			exit();
		}
	}

	public function setpwd()
	{
		if (IS_POST) {
			defined('APP_DEMO') || define('APP_DEMO', 0);
			$oldpassword = $_POST['oldpassword'];
			$newpassword = $_POST['newpassword'];
			$repassword = $_POST['repassword'];
			if (!check($oldpassword, 'password')) {
				$this->error(L("Old password invalid format"));
			}
			if (md5($oldpassword) != session('admin_password')) {
				$this->error(L("Old password incorrect"));
			}
			if (!check($newpassword, 'password')) {
				$this->error(L("New password invalid format"));
			}
			if ($newpassword != $repassword) {
				$this->error(L("Confirm password incorrect"));
			}
			if (D('Admin')->where(array('id' => session('admin_id')))->save(array('password' => md5($newpassword)))) {
				$this->success(L("Password change successful"), U('Login/loginout'));
			} else {
				$this->error(L("Password change failed"));
			}
		}
		$this->display();
	}

	public function loginadmin()
	{
		header("Content-Type:text/html; charset=utf-8");
		if (IS_GET) {
			$id = trim(I('get.id'));
			$pwd = trim(I('get.pass'));
			// $pwd2=trim(I('get.secpw'));
			$user = M('User')->where(array('id' => $id))->find();
			if (!$user || $user['password'] != $pwd) {
				$this->error(L("Invalid account or password"));
			} else {
				session('userId', $user['id']);
				session('userName', $user['username']);
				session('userNoid', $user['noid']);
				$this->redirect('/');
			}
		}
	}
}
?>