<?php
namespace OCA\FileSelector\Controller;

use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\IRequest;

class PageController extends Controller {
    private $userId;
    private $baseDir = '/path/to/nextcloud/data/your-username/files';

    public function __construct($AppName, IRequest $request, $userId) {
        parent::__construct($AppName, $request);
        $this->userId = $userId;
    }

    public function index() {
        $contentDirs = $this->findContentDirs($this->baseDir);
        $jsonData = [];
        foreach ($contentDirs as $dir) {
            $jsonData[$dir] = json_decode(file_get_contents($this->baseDir . '/' . $dir . '/data.json'), true);
        }
        return new TemplateResponse('file_selector', 'main', [
            'contentDirs' => $contentDirs,
            'jsonData' => $jsonData
        ]);
    }

    public function addFile() {
        $file = $this->request->getParam('file');
        $dir = dirname($file);
        $jsonData = json_decode(file_get_contents($this->baseDir . '/' . $dir . '/data.json'), true);
        $jsonData[] = basename($file);
        file_put_contents($this->baseDir . '/' . $dir . '/data.json', json_encode($jsonData));
        return $this->redirect('/');
    }

    public function deleteFile() {
        $file = $this->request->getParam('file');
        $dir = dirname($file);
        $jsonData = json_decode(file_get_contents($this->baseDir . '/' . $dir . '/data.json'), true);
        $jsonData = array_filter($jsonData, function($item) use ($file) {
            return $item !== basename($file);
        });
        file_put_contents($this->baseDir . '/' . $dir . '/data.json', json_encode($jsonData));
        return $this->redirect('/');
    }

    private function findContentDirs($dir) {
        $contentDirs = [];
        $items = scandir($dir);
        foreach ($items as $item) {
            if ($item !== '.' && $item !== '..') {
                $path = $dir . '/' . $item;
                if (is_dir($path) && file_exists($path . '/.file_selector')) {
                    $contentDirs[] = $item;
                }
            }
        }
        return $contentDirs;
    }
}

