<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class UploadService
{

    private SluggerInterface $slugger;

    private string $uploadsDirection;

    /**
     * UploadService constructor.
     * @param string $uploadsDirection
     * @param SluggerInterface $slugger
     */
    public function __construct(string $uploadsDirection, SluggerInterface $slugger)
    {
        $this->uploadsDirection = $uploadsDirection;
        $this->slugger = $slugger;
    }


    /**
     * @param UploadedFile $file
     * @return array{fileName: string, filePath: string}
     */
    public function upload(UploadedFile $file): array
    {
        $filename = $this->generateUniqFileName($file);

        try {
            $file->move($this->uploadsDirection, $filename);
        } catch (FileException $fileException) {
            throw new FileException();
        }

        return [
            "fileName" => $filename,
            "filePath" => $this->uploadsDirection . $filename
        ];
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    private function generateUniqFileName(UploadedFile $file): string
    {
        return sprintf("%s_%s.%s",
            $this->slugger->slug(strtolower($file->getClientOriginalName())),
            uniqid(),
            $file->getClientOriginalExtension());
    }

}