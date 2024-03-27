<?php

declare(strict_types=1);

namespace App\Controller;

use League\Flysystem\Filesystem;
use Nyholm\Psr7\Response;
use Nyholm\Psr7\ServerRequest as Request;
use Nyholm\Psr7\UploadedFile;

final readonly class FileUploadController
{
    public function __construct(private Filesystem $filesystem)
    {
    }

    public function __invoke(Request $request): Response
    {
        $uploadedFiles = $request->getUploadedFiles();
        $firstFile = array_pop($uploadedFiles);

        if (!$firstFile instanceof UploadedFile) {
            return new Response(400);
        }

        $this->filesystem->write(
            location: sprintf('%s', $firstFile->getClientFilename()),
            contents: $firstFile->getStream()->getContents()
        );

        return new Response(
            status: 200,
            headers: ['Content-Type' => 'application/json'],
            body: json_encode(['status' => true], flags: JSON_THROW_ON_ERROR)
        );
    }
}
