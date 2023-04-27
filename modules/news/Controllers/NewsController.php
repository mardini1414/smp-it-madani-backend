<?php

namespace Modules\News\Controllers;

use App\Controllers\BaseController;
use Modules\News\Services\NewsService;
use Codeigniter\API\ResponseTrait;

class NewsController extends BaseController
{

    use ResponseTrait;

    private $newsService;

    public function __construct()
    {
        $this->newsService = new NewsService();
    }

    public function add()
    {
        $request = [
            'title' => $this->request->getVar('title'),
            'body' => $this->request->getVar('body'),
            'image' => $this->request->getFile('image')
        ];
        if (!$this->isValidatedAddNews()) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->newsService->add($request);
        return $this->respondCreated(['message' => 'berita berhasil dibuat']);
    }

    public function getAll()
    {
        $data = $this->newsService->getAll();
        return $this->respond($data);
    }

    public function getBySlug($slug)
    {
        $news = $this->newsService->getBySlug($slug);
        if ($news) {
            $data = [
                'data' => $news
            ];
            return $this->respond($data);
        } else {
            return $this->respond(['message' => 'berita tidak di temukan'], 404);
        }
    }

    public function updateBySlug($slug)
    {
        $request = [
            'title' => $this->request->getVar('title'),
            'body' => $this->request->getVar('body'),
            'image' => $this->request->getFile('image')
        ];
        $news = $this->newsService->getBySlug($slug);
        $isValidatedUpdateNews = $this->isValidatedUpdateNews($request['image'], $slug);
        if (!$news) {
            return $this->respond(['message' => 'berita tidak di temukan'], 404);
        }
        if (!$isValidatedUpdateNews) {
            $data = [
                'errors' => $this->validator->getErrors()
            ];
            return $this->respond($data, 400);
        }
        $this->newsService->updateBySlug($slug, $request, $news['image']);
        return $this->respond(['message' => 'berita berhasil dirubah']);
    }

    public function deleteBySlug($slug)
    {
        $news = $this->newsService->getBySlug($slug);
        if (!$news) {
            return $this->respond(['message' => 'berita tidak di temukan'], 404);
        }
        $this->newsService->deleteBySlug($slug, $news['image']);
        return $this->respond(['message' => 'berita berhasil dihapus']);
    }

    private function isValidatedAddNews()
    {
        $rules = [
            'title' => 'required|max_length[100]|min_length[10]|is_unique[news.title]',
            'body' => 'required|min_length[100]',
            'image' => 'uploaded[image]|max_size[image,1024]|is_image[image]'
        ];
        $isValidated = $this->validate($rules);
        return $isValidated;
    }

    private function isValidatedUpdateNews($imageRequest, $slug)
    {
        $rules = [
            'title' => "required|max_length[100]|min_length[10]|is_unique[news.title,slug,{$slug}]",
            'body' => 'required|min_length[100]',
        ];
        if ($imageRequest) {
            $rules['image'] = 'uploaded[image]|max_size[image,1024]|is_image[image]';
        }
        $isValidated = $this->validate($rules);
        return $isValidated;
    }
}