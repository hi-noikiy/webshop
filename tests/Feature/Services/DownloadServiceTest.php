<?php

namespace Tests\Feature\Services;

use App\Services\DownloadService;
use \Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * Download Service test
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DownloadServiceTest extends \Tests\Feature\TestCase
{
    /**
     * @test
     * @expectedException \League\Flysystem\FileNotFoundException
     */
    public function throw_exception_if_file_not_found()
    {
        $service = new DownloadService;

        $service->serve();
    }

    /**
     * @test
     */
    public function can_set_file()
    {
        $service = new DownloadService;

        $service->file('test');

        $this->assertEquals('test', $service->file);
    }

    /**
     * @test
     */
    public function can_set_filename()
    {
        $service = new DownloadService;

        $service->as('test');

        $this->assertEquals('test', $service->filename);
    }

    /**
     * @test
     */
    public function can_set_headers()
    {
        $service = new DownloadService;

        $service->headers(['test']);

        $this->assertEquals(['test'], $service->headers);
    }

    /**
     * @test
     */
    public function can_set_disposition()
    {
        $service = new DownloadService;

        $service->disposition('test');

        $this->assertEquals('test', $service->disposition);
    }

    /**
     * @test
     */
    public function returns_download_response()
    {
        $service = new DownloadService;
        $response1 = $service->file(public_path('favicon.ico'))->serve();

        $service = new DownloadService;
        $response2 = $service->file(public_path('favicon.ico'))->as('test')->serve();

        $service = new DownloadService;
        $response3 = $service->file(public_path('favicon.ico'))->disposition('inline')->serve();

        $service = new DownloadService;
        $response4 = $service->file(public_path('favicon.ico'))->as('test')->disposition('inline')->serve();

        $this->assertInstanceOf(BinaryFileResponse::class, $response1);
        $this->assertEquals('attachment; filename="favicon.ico"', $response1->headers->get('content-disposition'));

        $this->assertInstanceOf(BinaryFileResponse::class, $response2);
        $this->assertEquals('attachment; filename="test"', $response2->headers->get('content-disposition'));

        $this->assertInstanceOf(BinaryFileResponse::class, $response3);
        $this->assertEquals('inline; filename="favicon.ico"', $response3->headers->get('content-disposition'));

        $this->assertInstanceOf(BinaryFileResponse::class, $response4);
        $this->assertEquals('inline; filename="test"', $response4->headers->get('content-disposition'));
    }
}
