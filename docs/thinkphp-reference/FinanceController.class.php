<?php
namespace Admin\Controller;

class FinanceController extends AdminController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array(
			"index",
			"myzr",
			"myzc",
			"adopttb",
			"reject",
			"adoptzr",
			"rejectzr",
			"del",
			"delT",
			"fund"
		);
		$manager_action = array(
			"index",
			"myzr",
			"myzc",
			"adoptzr",
			"rejectzr",
			"reject",
			"adopttb"
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

	public function rejectzr($id = null)
	{
		if ($id <= 0) {
			$this->error(L("Missing params"));
			exit();
		}
		$info = M("recharge")->where(array('id' => $id))->find();
		if (empty($info)) {
			$this->error(L("Deposit order does not exist"));
			exit();
		}
		if ($info['status'] != 1) {
			$this->error(L("The order has been processed"));
			exit();
		}
		//修改订单状态
		$save['updatetime'] = date("Y-m-d H:i:s", time());
		$save['status'] = 3;
		$upre = M("recharge")->where(array('id' => $id))->save($save);
		if ($upre) {
			$data['uid'] = $info['uid'];
			$data['account'] = $info['username'];
			$data['title'] = 'Xem xét tiền gửi';
			$data['content'] = 'Yêu cầu nạp tiền của bạn đã bị hệ thống từ chối, vui lòng liên hệ với chăm sóc khách hàng';
			$data['addtime'] = date("Y-m-d H:i:s", time());
			$data['status'] = 1;
			M("notice")->add($data);

			$this->success(L("Deposit was declined"));
		} else {
			$this->error(L("Cannot refuse"));
		}
	}

	public function adoptzr($id = null)
	{
		try {
			if ($id <= 0) {
				$this->error(L("Missing params"));
				exit();
			}
			$info = M("recharge")->where(array('id' => $id))->find();
			if (empty($info)) {
				$this->error(L("Deposit order does not exist"));
				exit();
			}
			if ($info['status'] != 1) {
				$this->error(L("The order has been processed"));
				exit();
			}
			$uid = $info['uid'];
			$num = $info['num'];
			$coinname = strtolower(trim($info['coin']));
			$minfo = M("user_coin")->where(array('userid' => $uid))->find();
			//修改订单状态
			$save['updatetime'] = date("Y-m-d H:i:s", time());
			$save['status'] = 2;
			$upre = M("recharge")->where(array('id' => $id))->save($save);
			//增加会员资产
			$incre = M("user_coin")->where(array('userid' => $uid))->setInc($coinname, $num);
			//增加充值日志
			$data['uid'] = $info['uid'];
			$data['username'] = $info['username'];
			$data['num'] = $num;
			$data['coinname'] = $coinname;
			$data['afternum'] = $minfo[$coinname] + $num;
			$data['type'] = 17;
			$data['addtime'] = date("Y-m-d H:i:s", time());
			$data['st'] = 1;
			$data['remark'] = 'Nạp tiền vào tài khoản';
			$addre = M("bill")->add($data);
			if ($upre && $incre && $addre) {
				$notice['uid'] = $info['uid'];
				$notice['account'] = $info['username'];
				$notice['title'] = 'Xem xét tiền gửi';
				$notice['content'] = 'Số tiền nạp của bạn đã được nhận, hãy chú ý kiểm tra';
				$notice['addtime'] = date("Y-m-d H:i:s", time());
				$notice['status'] = 1;
				M("notice")->add($notice);

				$this->success(L("Successfully"));
			} else {
				$this->error(L("System error"));
			}
		} catch (\Throwable $th) {
			var_dump($th->getMessage());
		}
	}

	public function del($id = null)
	{
		$info = M("recharge")->where(array('id' => $id))->find();
		if (empty($info)) {
			$this->error(L("Deposit order does not exist"));
			exit();
		}
		$result = M("recharge")->where(array('id' => $id))->delete();
		if ($result) {
			$this->success(L("Delete successfully"), U('Finance/myzr'));
		} else {
			$this->error(L("Cannot delete"));
			exit();
		}
	}

	public function delT($id = null)
	{
		$info = M("myzc")->where(array('id' => $id))->find();
		if (empty($info)) {
			$this->error(L("Withdrawal order does not exist"));
			exit();
		}
		$result = M("myzc")->where(array('id' => $id))->delete();
		if ($result) {
			$this->success(L("Delete successfully"), U('Finance/myzc'));
		} else {
			$this->error(L("Cannot delete"));
			exit();
		}
	}

	public function reject($id = null)
	{
		if ($id <= 0) {
			$this->error(L("Missing params"));
			exit();
		}
		$info = M("myzc")->where(array('id' => $id))->find();
		if (empty($info)) {
			$this->error(L("Withdrawal order does not exist"));
			exit();
		}
		if ($info['status'] != 1) {
			$this->error(L("The order has been processed"));
			exit();
		}

		$uid = $info['userid'];
		$num = $info['num'];
		$coinname = strtolower(trim($info['coinname']));
		//修改记录状态
		$save['endtime'] = date("Y-m-d H:i:s", time());
		$save['status'] = 3;
		$upre = M("myzc")->where(array('id' => $id))->save($save);
		//把提币的数量返回给账号户，并写入日志
		$minfo = M("user_coin")->where(array('userid' => $uid))->find();
		$incre = M("user_coin")->where(array('userid' => $uid))->setInc($coinname, $num);
		$bill['uid'] = $uid;
		$bill['username'] = $info['username'];
		$bill['num'] = $num;
		$bill['coinname'] = $info['coinname'];
		$bill['afternum'] = $minfo[$coinname] + $num;
		$bill['type'] = 16;
		$bill['addtime'] = date("Y-m-d H:i:s", time());
		$bill['st'] = 1;
		$bill['remark'] = 'Rút tiền bị từ chối, tiền được trả lại';
		$billre = M("bill")->add($bill);
		if ($upre && $incre && $billre) {
			$notice['uid'] = $uid;
			$notice['account'] = $info['username'];
			$notice['title'] = 'Xem xét rút tiền';
			$notice['content'] = 'Yêu cầu rút tiền của bạn đã bị từ chối, vui lòng liên hệ với quản trị viên';
			$notice['addtime'] = date("Y-m-d H:i:s", time());
			$notice['status'] = 1;
			M("notice")->add($notice);

			$this->success(L("Successfully"));
			exit();
		} else {
			$this->error(L("System error"));
			exit();
		}

	}

	public function adopttb($id = null)
	{
		if ($id <= 0) {
			$this->error(L("Missing params"));
			exit();
		}
		$info = M("myzc")->where(array('id' => $id))->find();
		if (empty($info)) {
			$this->error(L("Withdrawal order does not exist"));
			exit();
		}
		if ($info['status'] != 1) {
			$this->error(L("The order has been processed"));
			exit();
		}
		$save['endtime'] = date("Y-m-d H:i:s", time());
		$save['status'] = 2;
		$result = M("myzc")->where(array('id' => $id))->save($save);
		if ($result) {

			$notice['uid'] = $info['userid'];
			$notice['account'] = $info['username'];
			$notice['title'] = 'Xem xét rút tiền';
			$notice['content'] = 'Yêu cầu rút tiền của bạn đã được phê duyệt, vui lòng kiểm tra';
			$notice['addtime'] = date("Y-m-d H:i:s", time());
			$notice['status'] = 1;
			M("notice")->add($notice);

			$this->success(L("Successfully"), U('Finance/myzc'));
		} else {
			$this->error(L("System error"));
			exit();
		}
	}

	public function index($name = null)
	{
		if ($name != '') {
			$where['username'] = $name;
		}
		$count = M('bill')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('bill')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function myzr($name = null)
	{
		if ($name != '') {
			$where['username'] = $name;
		}
		$count = M('recharge')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('recharge')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display();
	}

	public function myzc($name = null)
	{
		if ($name != '') {
			$where['username'] = $name;
		}
		$count = M('myzc')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('myzc')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display();
	}

	public function fund($name = null)
	{
		$where = array();
		if ($name != '') {
			$where['username'] = $name;
		}
		$count = M('CoinExchangeHistory')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('CoinExchangeHistory')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);

		$this->display();
	}
}
?>