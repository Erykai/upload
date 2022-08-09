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
        if($this->getError())
        {
            return false;
        }
        if(!$this->getFiles())
        {
            return false;
        }
        foreach ($this->getFiles() as $file) {
            $this->createDir($file->path);
            $archive = $file->name . "." . $file->ext;
            $directory = $file->directory . "/";
            $path = $file->path . "/";
            if(file_exists($directory .$archive)){
                $archive = $file->name . "-" . time(). mt_rand() . "." . $file->ext;
            }
            move_uploaded_file($file->tmp_name, $directory . $archive);
            $this->setResponse($file->key,$path . $archive);
        }
        return true;
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
                $this->setError('file not exist');
                return false;
            }
        }
        foreach ($this->getResponse() as $value) {
            unlink($dir . $value);
        }
        return true;
    }
}