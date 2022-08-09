<?php

namespace Erykai\Upload;

/**
 * Class resource upload
 */
class Resource
{
    use TraitUpload;
    /**
     * @var object|null
     */
    private ?object $files;
    /**
     * @var string
     */
    private string $path;
    /**
     * @var array|null
     */
    private ?array $mimeType;
    /**
     * @var array
     */
    private array $response;
    /**
     * @var string|null
     */
    private ?string $error = null;
    /**
     *
     */
    public function __construct()
    {
        $this->setFiles();
    }
    /**
     * @return object|null
     */
    protected function getFiles(): ?object
    {
        return $this->files;
    }

    /**
     * convert in object and count files
     */
    private function setFiles(): bool
    {
        if (!empty($_FILES)) {
            $this->setMimeType();
            $upload = $_FILES;
            $files = [];
            foreach ($upload as $key => $file) {
                if (!in_array($file['type'], $this->getMimeType(), true)) {
                    $this->setError("invalid file format " . $file['type']);
                    return false;
                }
                [$type] = explode("/", $file['type']);
                $this->setPath($type);
                $file['key'] = $key;
                $file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file['name'] = $this->slug(pathinfo($file['name'], PATHINFO_FILENAME));
                $file['path'] = $this->getPath();
                $file['directory'] = dirname(__DIR__, 4) . "/" . $this->getPath();
                $files[] = (object)$file;
            }
            $this->files = (object)$files;
        }else{
            $this->files = null;
        }
        return true;
    }

    /**
     * @return string|null
     */
    private function getPath(): ?string
    {
        return $this->path;
    }

    /**
     * @param string $type
     */
    private function setPath(string $type): void
    {
        $this->path = UPLOAD_DIR . "/$type/" . date('Y') . "/" . date('m') . "/" . date('d');
    }

    /**
     * @return array|null
     */
    private function getMimeType(): ?array
    {
        return $this->mimeType;
    }

    /**
     * valide mimetype accept
     */
    private function setMimeType(): void
    {
        $this->mimeType = UPLOAD_MIMETYPE;
    }

    /**
     * @return object
     */
    protected function getResponse(): object
    {
        return (object) $this->response;
    }

    /**
     * @param string $key
     * @param string $response
     */
    protected function setResponse(string $key, string $response): void
    {
        $this->response[$key] = $response;
    }

    /**
     * @return string|null
     */
    protected function getError(): ?string
    {
        return $this->error;
    }

    /**
     * @param string|null $error
     */
    protected function setError(?string $error): void
    {
        $this->error = $error;
    }

}