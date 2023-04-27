<?php
namespace Modules\News\Services;

use Modules\File\Services\ImageService;
use Modules\News\Models\News;

class NewsService
{
    private $newsModel;
    private $imageService;

    public function __construct()
    {
        $this->newsModel = new News();
        $this->imageService = new ImageService();
    }

    public function add($request)
    {
        $image = $this->imageService->upload($request['image']);
        $request['image'] = $image;
        $this->newsModel->insert($request);
    }

    public function getAll()
    {
        $news = $this->newsModel->paginate(10);
        $pager = $this->newsModel->pager->getDetails();
        $data = [
            'data' => $this->getMappedNews($news),
            'pager' => $pager
        ];
        return $data;
    }

    public function getBySlug($slug)
    {
        $news = $this->newsModel->where('slug', $slug)->first();
        $news['image'] = base_url() . $news['image'];
        return $news;
    }

    public function updateBySlug($slug, $request, $oldImage)
    {
        if ($oldImage && $request['image']) {
            $oldImagePath = ROOTPATH . 'public/' . $oldImage;
            $this->imageService->delete($oldImagePath);
            $image = $this->imageService->upload($request['image']);
            $request['image'] = $image;
            $this->newsModel->where('slug', $slug)->set([
                'title' => $request['title'],
                'body' => $request['body'],
                'image' => $request['image']
            ])->update();
        } else {
            $this->newsModel->where('slug', $slug)->set([
                'title' => $request['title'],
                'body' => $request['body']
            ])->update();
        }
    }

    public function deleteBySlug($slug, $imagePath)
    {
        $this->imageService->delete($imagePath);
        $this->newsModel->where('slug', $slug)->delete();
    }

    private function getMappedNews($newsArray)
    {
        $mappedNews = array_map(function ($news) {
            return [
                'id' => $news['id'],
                'title' => $news['title'],
                'slug' => $news['slug'],
                'body' => $news['body'],
                'author' => $news['author'],
                'image' => base_url() . $news['image'],
                'created_at' => $news['created_at'],
                'updated_at' => $news['updated_at']
            ];
        }, $newsArray);
        return $mappedNews;
    }

}