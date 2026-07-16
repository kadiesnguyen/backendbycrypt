<?php
namespace Admin\Controller;

class ArticleController extends AdminController
{
	protected function _initialize()
	{
		parent::_initialize();
		$allow_action = array(
			"index",
			"edit",
			"wenzhangimg",
			"ggeditup",
			"setstatus",
			"uploadImage"
		);
		if (!in_array(ACTION_NAME, $allow_action)) {
			$this->error(L("Page not existed"));
		}
	}

	public function setstatus($id = null)
	{
		$where = array();
		if (empty($id)) {
			$this->error(L("Missing params"));
			exit();
		}
		$where['id'] = array('in', $id);
		$list = M("content")->where($where)->field("id,title")->select();
		if (!empty($list)) {
			foreach ($list as $key => $vo) {
				$oid = $vo['id'];
				M("content")->where(array('id' => $oid))->delete();
			}
			$this->success(L("Delete successfully"));
			exit();
		} else {
			$this->error(L("No data selected"));
			exit();
		}

	}

	public function index($name = NULL, $field = NULL, $status = NULL)
	{
		$where = array();
		$count = M('content')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('content')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL, $type = NULL)
	{

		if ($id > 0) {
			$info = M("content")->where(array('id' => $id))->find();
			$this->assign('data', $info);
		}

		$this->display();
	}

	public function ggeditup($title = null, $img = null, $content = null, $status = null, $id = null)
	{
		if ($id <= 0) {
			$_POST['addtime'] = date("Y-m-d H:i:s", time());
			$re = M("content")->add($_POST);
			if ($re) {
				$this->success(L("Notification added successfully"));
			} else {
				$this->error(L("Cannot add notification"));
			}
		} else {

			$re = M("content")->where(array('id' => $id))->save($_POST);
			if ($re) {
				$this->success(L("Edit notification successfully"));
			} else {
				$this->error(L("Edit notification failed"));
			}
		}

	}

	public function uploadImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 5 * 1024 * 1024; // 5MB
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/article/';
		$upload->autoSub = false;

		$info = $upload->upload();
		if (!$info) {
			$this->ajaxReturn(array(
				'error' => 1,
				'message' => $upload->getError()
			));
		}

		$imageUrl = __ROOT__ . '/Upload/article/' . $info['imgFile']['savename'];

		$this->ajaxReturn(array(
			'error' => 0,
			'url' => $imageUrl
		));
	}

	public function wenzhangimg()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 3145728;
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg');
		$upload->rootPath = './Upload/article/';
		$upload->autoSub = false;
		$info = $upload->upload();
		foreach ($info as $k => $v) {
			$path = $v['savepath'] . $v['savename'];
			echo $path;
			exit();
		}
	}

}

?>