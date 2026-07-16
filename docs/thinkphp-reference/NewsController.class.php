<?php
namespace Admin\Controller;

class NewsController extends AdminController
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
		$list = M("news")->where($where)->field("id,title")->select();
		if (!empty($list)) {
			foreach ($list as $key => $vo) {
				$oid = $vo['id'];
				M("news")->where(array('id' => $oid))->delete();
			}
			$this->success(L("Delete successfully"), U('News/index'), 1);
			exit();
		} else {
			$this->error(L("No data selected"));
			exit();
		}

	}

	public function index()
	{
		$where = array();
		$count = M('news')->where($where)->count();
		$Page = new \Think\Page($count, 15);
		$show = $Page->show();
		$list = M('news')->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		$this->assign('list', $list);
		$this->assign('page', $show);
		$this->display();
	}

	public function edit($id = NULL, $type = NULL)
	{

		if ($id > 0) {
			$info = M("news")->where(array('id' => $id))->find();
			$this->assign('data', $info);
		}

		$this->display();
	}

	public function ggeditup()
	{
		$id = I('post.id', 0, 'int');
		$img = I('post.coverImage', '', 'trim');

		if (!empty($img)) {
			if (strpos($img, 'http') === false) {
				$protocol = is_ssl() ? 'https://' : 'http://';
				$domain   = $_SERVER['HTTP_HOST'];
				$img      = $protocol . $domain . '/Upload/news/' . $img;
			}
		}
		$data = [
			'title'      => I('post.title', '', 'trim'),
			'coverImage'        => $img,
			'content'    => $_POST['content'] ?? '',
			'status'     => I('post.status', 1, 'int'),
			'updated_at' => date('Y-m-d H:i:s'),
		];
		$data['content'] = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $data['content']);
		if (empty($data['title'])) {
			$this->error(L("Title cannot be empty"));
		}
		if ($id <= 0) {
			$data['created_at'] = date("Y-m-d H:i:s", time());
			$re = M("news")->add($data);
			if ($re) {
				$this->success(L("News added successfully"), U('News/index'), 1);
			} else {
				$this->error(L("Cannot add news"));
			}
		} else {
			$re = M("news")->where(array('id' => $id))->save($data);
			if ($re) {
				$this->success(L("Edit news successfully"), U('News/index'), 1);
			} else {
				$this->error(L("Edit news failed"));
			}
		}

	}

	public function uploadImage()
	{
		$upload = new \Think\Upload();
		$upload->maxSize = 5 * 1024 * 1024; // 5MB
		$upload->exts = array('jpg', 'gif', 'png', 'jpeg', 'webp');
		$upload->rootPath = './Upload/news/';
		$upload->autoSub = false;

		$info = $upload->upload();
		if (!$info) {
			$this->ajaxReturn(array(
				'error' => 1,
				'message' => $upload->getError()
			));
		}

		$imageUrl = __ROOT__ . '/Upload/news/' . $info['imgFile']['savename'];

		$this->ajaxReturn(array(
			'error' => 0,
			'url' => $imageUrl
		));
	}

    // Upload ảnh bìa (thumbnail)
	public function wenzhangimg()
	{
		// Tự động tạo thư mục
		$rootPath = './Upload/news/';
		if (!is_dir($rootPath)) {
			mkdir($rootPath, 0777, true);
		}

		$upload = new \Think\Upload();
		$upload->maxSize   = 5 * 1024 * 1024;
		$upload->exts      = array('jpg', 'gif', 'png', 'jpeg', 'webp');
		$upload->rootPath  = $rootPath;
		$upload->autoSub   = false;
		$upload->saveName  = array('uniqid', '');

		// Lấy file
		$file = $_FILES['upload_file0'] ?? current($_FILES) ?? null;

		if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
			echo "0";
			exit();
		}

		$info = $upload->uploadOne($file);

		if (!$info) {
			echo "0";
			exit();
		}

		echo $info['savename'];
		exit();
	}
}