<?php
namespace Admin\Controller;

class TradeController extends AdminController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array(
			"index",
			"sethy",
			"hylog",
			"market",
			"marketEdit",
			"marketStatus",
			"tradeclear",
			"orderinfo",
			"orderinfo_ty",
			"setwinloss_ty",
			"setwinloss",
			"bbsetting",
			"bbxjlist",
			"bbsjlist",
			"gethyorder",
			"settzstatus",
			"getRecharges",
			"settzstatus_recharge",
			"getWithdraw",
			"settzstatus_withdraw",
			"tyorder",
			"manualApprove",
			"resultQueuePanel",
			"resultQueueAction"
		);
		$manager_action = array(
			"index",
			"orderinfo",
			"setwinloss",
			"bbxjlist",
			"bbsjlist",
			"hylog",
			"getRecharges",
			"settzstatus_recharge",
			"getWithdraw",
			"settzstatus_withdraw",
			"gethyorder",
			"settzstatus",
		);
		if (!in_array(ACTION_NAME, $allow_action)) {
			$this->error(L("Page not existed"));
		} else {
			if (session('admin_level') == 1) {
				if (!in_array(ACTION_NAME, $manager_action)) {
					$this->error(L("No access to this action"));
				}
			}
		}
	}

	/* Check lệnh nạp tiền, rút tiền, trade mới */
	public function settzstatus_withdraw()
	{
		$where['status'] = 1;
		$where['admin_view'] = 0;
		$list = M("myzc")->where($where)->field('id')->select();
		if (!empty($list)) {
			foreach ($list as $key => $vo) {
				$id = $vo['id'];
				M("myzc")->where(array('id' => $id))->save(array('admin_view' => 1));
			}
			$this->ajaxReturn(['code' => 1]);
		}
	}

	public function getWithdraw()
	{
		$where['status'] = 1;
		$where['admin_view'] = 0;
		$count = M("myzc")->where($where)->count();
		if ($count > 0) {
			$this->ajaxReturn(['code' => 1]);
		}
	}

	public function settzstatus_recharge()
	{
		$where['status'] = 1;
		$where['admin_view'] = 0;
		$list = M("recharge")->where($where)->field('id')->select();
		if (!empty($list)) {
			foreach ($list as $key => $vo) {
				$id = $vo['id'];
				M("recharge")->where(array('id' => $id))->save(array('admin_view' => 1));
			}
			$this->ajaxReturn(['code' => 1]);
		}
	}

	public function getRecharges()
	{
		$where['status'] = 1;
		$where['admin_view'] = 0;
		$count = M("recharge")->where($where)->count();
		if ($count > 0) {
			$this->ajaxReturn(['code' => 1]);
		}
	}

	public function settzstatus()
	{
		$where['status'] = 1;
		$where['tznum'] = 0;
		$list = M("hyorder")->where($where)->field('id')->select();
		if (!empty($list)) {
			foreach ($list as $key => $vo) {
				$id = $vo['id'];
				M("hyorder")->where(array('id' => $id))->save(array('tznum' => 1));
			}
			$this->ajaxReturn(['code' => 1]);
		}
	}

	public function gethyorder()
	{
		$where['status'] = 1;
		$where['tznum'] = 0;
		$count = M("hyorder")->where($where)->count();
		if ($count > 0) {
			$this->ajaxReturn(['code' => 1]);
		}
	}
	/* Check lệnh nạp tiền, rút tiền, trade mới */

	public function bbsjlist()
	{
		if (I('get.type') > 0) {
			$hyzd = trim(I('get.type'));
			$where['type'] = $hyzd;
		}
		if (I('get.status') > 0) {
			$status = trim(I('get.status'));
			$where['status'] = $status;
		}
		if (I('get.username') > 0) {
			$username = trim(I('get.username'));
			$where['account'] = $username;
		}
		$where['ordertype'] = 2;
		$count = M('bborder')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('bborder')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function bbxjlist()
	{
		if (I('get.type') > 0) {
			$hyzd = trim(I('get.type'));
			$where['type'] = $hyzd;
		}
		if (I('get.status') > 0) {
			$status = trim(I('get.status'));
			$where['status'] = $status;
		}
		if (I('get.username') > 0) {
			$username = trim(I('get.username'));
			$where['account'] = $username;
		}
		$where['ordertype'] = 1;
		$count = M('bborder')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('bborder')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function bbsetting()
	{
		if ($_POST) {
			$id = trim($_POST['bbid']);
			if ($id <= 0) {
				$result = M("bbsetting")->add($_POST);
			} else {
				unset($_POST['bbid']);
				$result = M("bbsetting")->where(array('id' => $id))->save($_POST);
			}
			if ($result) {
				$this->success(L("Success"), U('Trade/bbsetting'));
			} else {
				$this->error(L("System error"), U('Trade/bbsetting'));
			}
		} else {
			$info = M("bbsetting")->where(array('id' => 1))->find();
			$this->assign("info", $info);
			$this->display();
		}
	}

	public function setwinloss()
	{
		if ($_POST) {
			$id = trim(I('post.id'));
			$kongyk = trim(I('post.kongyk'));
			$info = M("hyorder")->where(array('id' => $id))->find();
			if (empty($info)) {
				$this->ajaxReturn(['code' => 0, 'info' => L("Less important parameters")]);
			}
			$result = M("hyorder")->where(array('id' => $id))->save(array('kongyk' => $kongyk));
			if ($result) {
				$this->ajaxReturn(['code' => 1, 'info' => L("Successfully")]);
			} else {
				$this->ajaxReturn(['code' => 0, 'info' => L("System error")]);
			}
		} else {
			$this->ajaxReturn(['code' => 0, 'info' => L("Network error")]);
		}
	}

	public function setwinloss_ty()
	{
		if ($_POST) {
			$id = trim(I('post.id'));
			$kongyk = trim(I('post.kongyk'));
			$info = M("tyhyorder")->where(array('id' => $id))->find();
			if (empty($info)) {
				$this->ajaxReturn(['code' => 0, 'info' => L("Less important parameters")]);
			}
			$result = M("tyhyorder")->where(array('id' => $id))->save(array('kongyk' => $kongyk));
			if ($result) {
				$this->ajaxReturn(['code' => 1, 'info' => L("Successfully")]);
			} else {
				$this->ajaxReturn(['code' => 0, 'info' => L("System error")]);
			}
		} else {
			$this->ajaxReturn(['code' => 0, 'info' => L("Network error")]);
		}
	}

	public function orderinfo()
	{
		$id = trim(I('get.id'));
		$info = M("hyorder")->where(array("id" => $id))->find();
		$this->assign('info', $info);
		$this->display();
	}

	public function orderinfo_ty()
	{
		$id = trim(I('get.id'));
		$info = M("tyhyorder")->where(array("id" => $id))->find();
		$this->assign('info', $info);
		$this->display();
	}

	public function resultQueuePanel()
	{
		$list = M('HyResultQueue')->order('round_no asc,id asc')->select();
		if (!empty($list)) {
			foreach ($list as $k => $v) {
				$list[$k]['addtime_text'] = addtime($v['addtime']);
			}
		}
		$this->ajaxReturn(array('code' => 1, 'info' => L("Successfully"), 'data' => $list));
	}

	public function resultQueueAction()
	{
		if (!IS_POST) {
			$this->ajaxReturn(array('code' => 0, 'info' => L("Network error")));
		}
		$action = trim(I('post.action'));
		$model = M('HyResultQueue');
		$now = time();
		if ($action == 'next_win' || $action == 'next_loss') {
			$model->where(array('id' => array('gt', 0)))->delete();
			$result = ($action == 'next_win') ? 'WIN' : 'LOSS';
			$add = $model->add(array(
				'round_no' => 1,
				'result' => $result,
				'addtime' => $now
			));
			if ($add) {
				$this->ajaxReturn(array('code' => 1, 'info' => L("Successfully")));
			} else {
				$this->ajaxReturn(array('code' => 0, 'info' => L("System error")));
			}
		}
		if ($action == 'add_win' || $action == 'add_loss') {
			$lastRound = $model->max('round_no');
			$nextRound = intval($lastRound) + 1;
			if ($nextRound <= 0) {
				$nextRound = 1;
			}
			$result = ($action == 'add_win') ? 'WIN' : 'LOSS';
			$add = $model->add(array(
				'round_no' => $nextRound,
				'result' => $result,
				'addtime' => $now
			));
			if ($add) {
				$this->ajaxReturn(array('code' => 1, 'info' => L("Successfully")));
			} else {
				$this->ajaxReturn(array('code' => 0, 'info' => L("System error")));
			}
		}
		$this->ajaxReturn(array('code' => 0, 'info' => L("Parameter error")));
	}

	public function sethy()
	{
		if ($_POST) {
			$id = trim($_POST['hy_id']);
			if ($id <= 0) {
				$result = M("hysetting")->add($_POST);
			} else {
				unset($_POST['hy_id']);
				$result = M("hysetting")->where(array('id' => $id))->save($_POST);
			}
			if ($result) {
				$this->success(L("Success"), U('Trade/sethy'));
			} else {
				$this->error(L("System error"), U('Trade/sethy'));
			}
		} else {
			$info = M("hysetting")->where(array('id' => 1))->find();
			$this->assign("info", $info);
			$this->display();
		}
	}

	public function tyorder()
	{
		$where = array();
		if (I('get.username') != '' || I('get.username') != null) {
			$username = trim(I('get.username'));
			$where['username'] = $username;
		}
		if (I('get.hyzd') > 0) {
			$hyzd = trim(I('get.hyzd'));
			$where['hyzd'] = $hyzd;
		}
		$count = M('tyhyorder')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('tyhyorder')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function index()
	{
		$where = array();
		if (I('get.username') != '' || I('get.username') != null) {
			$username = trim(I('get.username'));
			$where['username'] = $username;
		}
		if (I('get.hyzd') > 0) {
			$hyzd = trim(I('get.hyzd'));
			$where['hyzd'] = $hyzd;
		}
		$where['status'] = 1;
		$count = M('hyorder')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('hyorder')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function hylog($invit = null, $username = null)
	{
		if ($invit != '') {
			$where['invit'] = $invit;
		}
		if ($username != '') {
			$where['username'] = $username;
		}
		$where['status'] = 2;
		$count = M('hyorder')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('hyorder')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function market($field = NULL, $name = NULL)
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
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('Market')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function marketEdit($id = NULL)
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
				$this->success(L("Successfully"));
			} else {
				$this->error(L("System error"));
			}
		}
	}

	public function marketStatus($id = NULL, $type = NULL, $mobile = 'Market')
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

	public function tradeclear($type = NULL, $id = NULL)
	{
		if (!$id) {
			$this->error(L('Select trade market'));
		}
		if (!$type) {
			$this->error(L('Select market type'));
		}
		$market = M('Market')->where(array('id' => $id))->find();
		if ($type == 1) {
			$allclear = M('Trade')->where(array('market' => $market['name'], 'userid' => 0))->delete();
		}
		if ($type == 2) {
			if (!$market['sdhigh'] or !$market['sdlow']) {
				$this->error(L('No max min price'));
			}
			$map['market'] = $market['name'];
			$map['userid'] = 0;
			$map['price'] = array('notbetween', array($market['sdhigh'], $market['sdlow']));
			$allclear = M('Trade')->where($map)->delete();
		}
		if ($allclear) {
			$this->success(str_replace('{count}', $allclear, L('Clear successful')));
		} else {
			$this->error(L('Clear failed'));
		}
	}

	public function manualApprove()
	{
		if ($_POST) {
			$id = trim(I('post.id'));
			$info = M("hyorder")->where(array('id' => $id))->find();
			if (empty($info)) {
				$this->ajaxReturn(['code' => 0, 'info' => "Không tìm thấy hợp đồng"]);
			}
			$result = M("hyorder")->where(array('id' => $id))->find();

			if ($result) {
				$uid = $result['uid'];
				$coinname = $result['coinname'];
				$symbol = strtoupper($coinname);
				$coinApi = "https://www.okx.com/api/v5/market/history-index-candles?instId={$symbol}";
				$resultCoin = get_maket_api($coinApi);
				$marketPrices = $resultCoin['data'][0]['4'];
				$sellPrice = $marketPrices ?? 0;
				$tmoney = $result['ploss'];
				$updateData = [
					'status' => 2,
					'sellprice' => $sellPrice,
				];
				if ($result['kongyk'] == 1) {
					// Thắng do kiểm soát
					$updateData['is_win'] = 1;
					tangtien($uid, $tmoney);
				} elseif ($result['kongyk'] == 2) {
					// Thua do kiểm soát
					$updateData['is_win'] = 2;
					$updateData['ploss'] = $result['num'];
				} else {
					if ($sellPrice > 0) {
						if ($result['hyzd'] == 1) { // Mua tăng
							if ($sellPrice > $result['buyprice']) {
								$updateData['is_win'] = 1;
								tangtien($uid, $tmoney);
							} else {
								$updateData['is_win'] = 2;
								$updateData['ploss'] = $result['num'];
							}
						} elseif ($result['hyzd'] == 2) { // Mua giảm
							if ($sellPrice < $result['buyprice']) {
								$updateData['is_win'] = 1;
								tangtien($uid, $tmoney);
							} else {
								$updateData['is_win'] = 2;
								$updateData['ploss'] = $result['num'];
							}
						}
					} else {
						// Nếu không lấy được giá, mặc định thua
						$updateData['is_win'] = 2;
						$updateData['ploss'] = $result['num'];
					}
				}

				M("hyorder")->where(array('id' => $id))->save($updateData);
				$this->ajaxReturn(['code' => 1, 'info' => "Thành công"]);
			} else {
				$this->ajaxReturn(['code' => 0, 'info' => "Lỗi hệ thống"]);
			}
		} else {
			$this->ajaxReturn(['code' => 0, 'info' => "Lỗi mạng"]);
		}
	}
}
?>