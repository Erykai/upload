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
        foreach ($this->getFiles() as $file) {
            $this->createDir($file->path);
            $archive = $file->name . "." . $file->ext;
            $directory = $file->directory . "/";
            $path = $file->path . "/";
            if(file_exists($directory .$archive)){
                $archive = $file->name . "-" . time(). mt_rand() . "." . $file->ext;
            }
            if(!empty($file->tmp_name)){
                move_uploaded_file($file->tmp_name, $directory . $archive);
            }else{
                file_put_contents($directory . $archive, file_get_contents($this->url));
            }

            $this->setData($file->key, $path . $archive);
        }
        $this->setResponse(200,"success","upload performed successfully","upload",$this->getData());
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