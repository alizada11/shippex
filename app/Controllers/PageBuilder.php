<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PagesModel;

class PageBuilder extends Controller
{
    public function index($id = null)
    {
        $pageModel = new PagesModel();
        $page = $pageModel->find($id);

        if (!$page) {
            $page = [
                'id' => $id,
                'title' => 'New Page',
                'content' => '<h1>Start editing...</h1>'
            ];
        }

        return view('pagebuilder/editor', [
            'page' => $page
        ]);
    }

    // New method to handle save from Studio SDK
    public function save()
    {
        $data = $this->request->getJSON(true); // Studio SDK sends JSON
        $id = $data['project']['id'] ?? null;
        $html = $data['components'] ?? '';

        if (!$id) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing project ID']);
        }

        $pageModel = new PagesModel();
        // If page exists, update; otherwise create
        if ($pageModel->find($id)) {
            $pageModel->update($id, ['content' => $html]);
        } else {
            $pageModel->insert(['id' => $id, 'title' => 'New Page', 'content' => $html]);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    // Optional: load endpoint for editor initial load
    public function load($id)
    {
        $pageModel = new PagesModel();
        $page = $pageModel->find($id);

        if (!$page) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Page not found']);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'components' => $page['content']
        ]);
    }
}
