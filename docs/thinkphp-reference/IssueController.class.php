<?php
namespace Admin\Controller;

class IssueController extends AdminController
{

	public function index()
	{
		$count = M('issue')->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('issue')->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function issueimage()
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

	public function edit($id = null)
	{
		$clist = M("coin")->where("type = 3 or type = 2")->order("id desc")->field("name,title")->select();
		$this->assign("clist", $clist);

		$paylist = M("coin")->where("type = 1 or type = 2")->order("id desc")->field("name,title")->select();
		$this->assign("paylist", $paylist);

		$alllist = M("coin")->order("id desc")->field("name,title")->select();
		$this->assign("alllist", $alllist);
		if ($id > 0) {
			$data = M("issue")->where(array('id' => $id))->find();
			$this->assign('data', $data);
		}

		$this->display();
	}

	public function save()
	{
		$_POST['addtime'] = date("Y-m-d H:i:s", time());

		if ($_POST['id']) {
			$rs = M('Issue')->save($_POST);
		} else {
			$rs = M('Issue')->add($_POST);
		}

		if ($rs) {
			$this->success(L("Successfully"));
		} else {
			$this->error(L("System error"));
		}
	}

	public function log($name = null)
	{
		if ($name != null) {
			$where['account'] = trim($name);
		}
		$count = M('issue_log')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('issue_log')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();

	}
}
?>