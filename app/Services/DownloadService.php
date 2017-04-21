<?php

namespace App\Services;

use File;
use League\Flysystem\FileNotFoundException;

/**
 * Download Service
 *
 * @author  Thomas Wiringa <thomas.wiringa@gmail.com>
 */
class DownloadService extends Service
{
    /**
     * Full path to the file.
     *
     * @var string
     */
    public $file;

    /**
     * Name of the file to be served.
     *
     * @var string
     */
    public $filename;

    /**
     * Headers for the response.
     *
     * @var array
     */
    public $headers = [];

    /**
     * Content-Disposition attachment|inline
     *
     * @var string
     */
    public $disposition = 'attachment';

    /**
     * @param  string  $file
     * @return $this
     */
    public function file(string $file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Set the content disposition.
     *
     * @param  string  $disposition
     * @return $this
     */
    public function disposition(string $disposition)
    {
        $this->disposition = $disposition;

        return $this;
    }

    /**
     * Set the filename the client will see.
     *
     * @param  string  $name
     * @return $this
     */
    public function as(string $name)
    {
        $this->filename = $name;

        return $this;
    }

    /**
     * Set the headers.
     *
     * @param  array  $headers
     * @return $this
     */
    public function headers(array $headers)
    {
        $this->headers = $headers;

        return $this;
    }

    /**
     * Return the download response
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     * @throws FileNotFoundException
     */
    public function serve()
    {
        if (!$this->checkIfFileExists()) {
            throw new FileNotFoundException($this->file);
        }

        return response()->download($this->file, $this->filename, $this->headers, $this->disposition);
    }

    /**
     * Check if the file exists
     *
     * @return bool
     */
    protected function checkIfFileExists(): bool
    {
        return File::exists($this->file);
    }
}