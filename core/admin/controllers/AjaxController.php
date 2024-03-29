<?php

namespace core\admin\controllers;

use core\base\controllers\BaseAjax;
use libraries\FilesEdit;

class AjaxController extends BaseAdmin
{
    public function ajax() {
        if (isset($this->ajaxData['ajax'])) {
            $this->execBase();
            foreach ($this->ajaxData as $key => $item) {
                $this->ajaxData[$key] = $this->clearStr($item);
            }
            switch ($this->ajaxData['ajax']) {
                case 'sitemap':
                    return (new CreatesitemapController())->inputData($this->ajaxData['links_counter'], false);
                    break;
                case 'editData':
                    $_POST['return_id'] = true;
                    $this->checkPost();
                    return json_encode(['success' => 1]);
                    break;
                case 'change_parent':
                    return $this->changeParent();
                    break;
                case 'search':
                    return $this->search();
                    break;
                case 'wyswyg_file':
                    $dir = $this->clearStr($this->ajaxData['table']) . '/content_files/';
                    $fileEdit = new FilesEdit();
                    $fileEdit->setUniqueFile(false);
                    $file = $fileEdit->addFile($dir);
                    return ['success' => true, 'location' => PATH . UPLOAD_DIR . $file[key($file)]];
                    break;
            }
        }
        return json_encode(['success' => 0, 'message' => 'No Ajax variable']);
    }
    protected function search() {
        $data = $this->clearStr($this->ajaxData['data']);
        $table = $this->clearStr($this->ajaxData['table']);
        return $this->model->search($data, $table, 20);
    }
    protected function changeParent() {

        return $this->model->get($this->ajaxData['table'], [
            'fields' => ['COUNT(*) as count'],
            'where' => [ 'parent_id' => $this->ajaxData['parent_id'] ],
            'no_concat' => true
        ])[0]['count'] + $this->ajaxData['iteration'];
    }
}