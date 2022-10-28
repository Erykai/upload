<?php

namespace Erykai\Upload;

use finfo;
use RuntimeException;
use stdClass;

/**
 * Class Trait Upload
 */
trait TraitUpload
{
    /**
     * @param string $path
     */
    protected function createDir(string $path): void
    {
        $folders = explode("/", $path);
        $dir = dirname(__DIR__, 4);
        foreach ($folders as $folder) {
            $dir .= "/" . $folder;
            if (!file_exists($dir) && !mkdir($dir) && !is_dir($dir)) {
                throw new RuntimeException(sprintf('Directory "%s" was not created', $dir));
            }
        }
    }

    /**
     * @param string $name
     * @return string
     */
    protected function slug(string $name): string
    {
        $characters = array(
            'Š' => 'S', 'š' => 's', 'Đ' => 'Dj', 'đ' => 'dj', 'Ž' => 'Z', 'ž' => 'z', 'Č' => 'C', 'č' => 'c', 'Ć' => 'C', 'ć' => 'c',
            'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'A', 'Ç' => 'C', 'È' => 'E', 'É' => 'E',
            'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O',
            'Õ' => 'O', 'Ö' => 'O', 'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ý' => 'Y', 'Þ' => 'B', 'ß' => 'Ss',
            'à' => 'a', 'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'a', 'ç' => 'c', 'è' => 'e', 'é' => 'e',
            'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i', 'ð' => 'o', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o',
            'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ý' => 'y', 'þ' => 'b',
            'ÿ' => 'y', 'Ŕ' => 'R', 'ŕ' => 'r', '/' => '-', ' ' => '-'
        );
        $stripped = preg_replace(array('/\s{2,}/', '/[\t\n]/'), ' ', $name);
        return strtolower(strtr($stripped, $characters));
    }

    protected function upload(): bool
    {
        foreach ($this->getFiles() as $file) {
            $this->createDir($file->path);
            $archive = $file->name . "." . $file->ext;
            $directory = $file->directory . "/";
            $path = $file->path . "/";
            if (file_exists($directory . $archive)) {
                $archive = $file->name . "-" . time() . mt_rand() . "." . $file->ext;
            }
            $this->setData($file->key, $path . $archive);
            if (!empty($file->tmp_name)) {
                move_uploaded_file($file->tmp_name, $directory . $archive);
            }else{
                file_put_contents($directory . $archive, file_get_contents($this->url));
            }
        }
        return true;
    }

    /**
     * @return bool
     */
    protected function uploadFiles(): bool
    {
        $upload = $_FILES;
        foreach ($upload as $key => $file) {
            $this->file = new stdClass();
            if (!$this->mimetype($file)) {
                return false;
            }
            $this->mountFile($file, $key);
            $this->file->tmp_name = $file['tmp_name'];
            $files[$key] = (object)$this->file;
        }
        if (!empty($files)) {
            $this->files['upload_file'] = (object)$files;
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    protected function uploadUrl(): bool
    {
        $this->file = new stdClass();
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->buffer(file_get_contents($this->url));
        $file['type'] = $mime_type;

        if (!$this->mimetype($file)) {
            return false;
        }

        $url_array = explode("/", $this->url);
        $file['name'] = end($url_array);
        $this->mountFile($file, $this->key);
        $files[$this->key] = (object)$this->file;
        $this->files['upload_url'] = (object)$files;
        return true;
    }

    /**
     * @param array $file
     */
    private function mountFile(array $file, string $key)
    {
        [$type] = explode("/", $file['type']);
        $this->setPath($type);

        $this->file->key = $key;
        $this->file->ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $this->file->name = $this->slug(pathinfo($file['name'], PATHINFO_FILENAME));
        $this->file->path = $this->getPath();
        $this->file->directory = dirname(__DIR__, 4) . "/" . $this->getPath();
    }

    /**
     * @param array $type
     * @return bool
     */
    private function mimetype(array $type): bool
    {
        if (!in_array($type['type'], $this->getMimeType(), true)) {
            $this->files = null;
            $this->setResponse(400, "error", "invalid file format " . $type['type'], "upload", dynamic: $type['type']);
            return false;
        }
        return true;
    }
}