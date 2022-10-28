<?php

namespace Erykai\Upload;


/**
 * Class resource upload
 */
abstract class Resource
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
     * @var array|object
     */
    private array|object $data;
    /**
     * @var object
     */
    private object $response;
    /**
     * @var string|null
     */
    protected ?string $url;
    /**
     * @var string|null
     */
    protected ?string $key;

    /**
     *
     */
    public function __construct(?string $url = null, ?string $key = null)
    {
        $this->url = $url;
        $this->key = $key;
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
        $files = [];
        if (!empty($_FILES)) {
            $this->setMimeType();
            $upload = $_FILES;
            foreach ($upload as $key => $file) {
                if($this->url)
                {
                    $url_array = explode("/", $this->url);
                    $file['name'] = end($url_array);
                    $finfo = new \finfo(FILEINFO_MIME_TYPE);
                    $mime_type = $finfo->buffer(file_get_contents($this->url));
                    $file['type'] = $mime_type;
                    $file['key'] = $this->key;
                }
                else{
                    $file['key'] = $key;
                }
                [$type] = explode("/", $file['type']);
                $this->setPath($type);
                $file['ext'] = pathinfo($file['name'], PATHINFO_EXTENSION);
                $file['name'] = $this->slug(pathinfo($file['name'], PATHINFO_FILENAME));
                $file['path'] = $this->getPath();
                $file['directory'] = dirname(__DIR__, 4) . "/" . $this->getPath();
                $files[] = (object)$file;
                if (!in_array($file['type'], $this->getMimeType(), true)) {
                    $this->files = null;
                    $this->setResponse(400, "error","invalid file format ".$file['type'], "upload", dynamic: $file['type'] );
                    return false;
                }
            }
            $this->files = (object)$files;
        } else {
            $this->files = null;
        }
        $this->setResponse(200, "success","defined attribute", "upload");
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
    protected function getData(): object
    {
        return (object) $this->data;
    }

    /**
     * @param string $key
     * @param string $value
     */
    protected function setData(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }

    /**
     * @return object
     */
    protected function getResponse(): object
    {
        return $this->response;
    }

    /**
     * @param int $code
     * @param string $type
     * @param string $text
     * @param string $model
     * @param object|null $data
     * @param string|null $dynamic
     */
    protected function setResponse(int $code, string $type, string $text, string $model, ?object $data = null, ?string $dynamic = null): void
    {
        $this->response = (object)[
            "code" => $code,
            "type" => $type,
            "text" => $text,
            "model" => $model,
            "data" => $data,
            "dynamic" => $dynamic
        ];
    }
}