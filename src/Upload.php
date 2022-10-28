<?php

namespace Erykai\Upload;

/**
 * execute upload
 */
class Upload extends Resource
{
    /**
     * @return bool
     */
    public function save(): bool
    {
        if($this->response()->type === 'error')
        {
            return false;
        }
        if(!$this->getFiles())
        {
            return false;
        }
        if($this->upload()) {
            $this->setResponse(200, "success", "upload performed successfully", "upload", $this->getData());
            return true;
        }
        return false;
    }

    /**
     * @param string|null $path
     * @return bool
     */
    public function delete(?string $path = null): bool
    {
        $dir = dirname(__DIR__, 4) . "/";
        if($path){
            if(!unlink($dir . $path))
            {
                $this->setResponse(404,"error","$path file not exist","upload",dynamic: $path);
                return false;
            }
        }
        foreach ($this->response()->data as $value) {
            unlink($dir . $value);
        }
        $this->setResponse(200,"success","file deleted successfully","upload");
        return true;
    }

    /**
     * @return object
     */
    public function response(): object
    {
        return $this->getResponse();
    }
}