<?php

namespace Erykai\Upload;


use stdClass;

/**
 * Class resource upload
 */
abstract class Resource
{
    use TraitUpload;

    /**
     * @var object|array|null
     */
    private $files;
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
     * @var stdClass
     */
    protected stdClass $file;
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
        $this->key = $key ?? 'file';
        $this->setFiles();
    }

    /**
     * @return object|null
     */
    protected function getFiles(): ?object
    {
        $upload = (object) $this->files;
        if(isset($upload->upload_file) && isset($upload->upload_url)){
            $key = $this->key;
            $upload->upload_file->$key = $upload->upload_url->$key;
            $this->files = $upload->upload_file;
            unset($upload->upload_file, $upload->upload_url);
        }
        if(isset($upload->upload_file)){
            $this->files = $upload->upload_file;
            unset($upload->upload_file);
        }
        if(isset($upload->upload_url)){
            $this->files = $upload->upload_url;
            unset($upload->upload_url);
        }
        return (object) $this->files;
    }

    /**
     * convert in object and count files
     */
    private function setFiles(): bool
    {
        $this->setMimeType();
        if (!empty($_FILES)) {
            $this->uploadFiles();
        }
        if ($this->url) {
            $this->uploadUrl();
        }
        if (isset($this->files)) {
            $this->setResponse(200, "success", "defined attribute", "upload");
            return true;
        }
        return false;
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
        return (object)$this->data;
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