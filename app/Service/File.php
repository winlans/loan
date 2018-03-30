<?php
/**
 * Created by PhpStorm.
 * User: raye
 * Date: 2018/3/21
 * Time: 11:48
 */
namespace App\Service;
use App\Traits\ErrorInfo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class File
{
    use ErrorInfo;
    private $root = 'assert/folder/';
    private $uploads = [];

    const ALLOW_TYPE = array(
        'png', 'jpeg', 'jpg', 'gif', 'txt'
    );

    public function upload(\ArrayIterator $files){
        while ($files->valid()){
            /** @var UploadedFile $file */
            $file = $files->current();
            if (!in_array($file->guessExtension(), self::ALLOW_TYPE)){
                return $this->ensure(false, 1, 'Unsupported file type.');
            }
            $files->next();
        }
        $this->moveTo($files, $this->root);

        return $this->uploads;
    }
    
    private function moveTo(\ArrayIterator $files, string $dest) {
        $files->rewind();

        while ($files->valid()){
            /** @var UploadedFile $file */
            $file = $files->current();
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move($dest, $filename);
            $this->uploads[] = $dest . $filename;
            $files->next();
        }
    }

    public function deleteFile(string $filename) :void{
        @unlink($this->root . '.' . $filename);
    }
}