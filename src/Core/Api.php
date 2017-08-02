<?php namespace RescueCore\Core;

abstract class Api
{
//    protected $storageDir = DOCUMENT_ROOT . '/storage';
//    protected $storageFile;

    protected function success($data)
    {
        return ApiFactory::success($data);
    }

    protected function error($message)
    {
        return ApiFactory::error($message);
    }

    protected function digest($procedure)
    {
        return ApiFactory::call($procedure, true);
    }

//    private function ensureDirectory($path)
//    {
//        if (!file_exists($path)) {
//            mkdir($path, 0777, true);
//        }
//    }

//    private function filePath($file)
//    {
//        return $this->storageDir . '/' . $file;
//    }

//    protected function read()
//    {
//        $path = $this->filePath($this->storageFile);
//        if (file_exists($path)) {
//            $contents = file_get_contents($path);
//            if (! empty($contents)) {
//                return json_decode($contents, true);
//            }
//        }
//
//        return null;
//    }
//
//    protected function write($content)
//    {
//        $this->ensureDirectory($this->storageDir);
//
//        return file_put_contents($this->filePath($this->storageFile), $content);
//    }

}
