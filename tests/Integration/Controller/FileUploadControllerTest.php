<?php

namespace Tests\Integration\Controller;

use League\Flysystem\FilesystemAdapter;
use League\Flysystem\InMemory\InMemoryFilesystemAdapter;
use Nyholm\Psr7\Stream;
use Nyholm\Psr7\UploadedFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Tests\Traits\AppTestTrait;

class FileUploadControllerTest extends TestCase
{
    use AppTestTrait;

    #[Test]
    public function itCanHandleFileUploads(): void
    {
        // mock the file system
        $fileSystemAdapter = new InMemoryFilesystemAdapter();
        $this->container->set(FilesystemAdapter::class, fn () => $fileSystemAdapter);

        $file = new Stream(fopen(__DIR__ . '/../../Fixtures/text_file.txt', 'r+'));
        $request = $this
            ->createFormRequest('POST', '/upload', [])
            ->withHeader('Content-Type', 'multipart/form-data')
            ->withUploadedFiles([
                'file' => new UploadedFile(
                    streamOrFile: $file,
                    size: $file->getSize(),
                    errorStatus: 0,
                    clientFilename: 'text_file.txt',
                    clientMediaType: 'plain/text',
                ),
            ]);

        $response = $this->app->handle($request);
        $responseData = $this->getJsonData($response);

        $this->assertSame(200, $response->getStatusCode());
        $this->assertTrue($responseData['status']);
        $this->assertTrue($fileSystemAdapter->fileExists('text_file.txt'));
    }
}
