<?php

declare(strict_types=1);

namespace Parc\UpdateUrlKeys\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\File\Csv;
use Magento\Framework\Filesystem\Io\File;

class CsvExporter
{

    /**
     * @var Csv
     */
    private Csv $_csvProcessor;

    /**
     * @var DirectoryList
     */
    private DirectoryList $_directoryList;

    /**
     * @var File
     */
    private File $_file;

    public function __construct(
        Csv $_csvProcessor,
        DirectoryList $_directoryList,
        File $_file
    ) {
        $this->_csvProcessor = $_csvProcessor;
        $this->_directoryList = $_directoryList;
        $this->_file = $_file;
    }

    /**
     * @param array  $data
     * @param string $fileName
     *
     * @return int
     * @throws FileSystemException
     */
    public function exportData(array $data, string $fileName, string $dir = '/export'): int
    {
        $varDir = $this->_directoryList->getPath(DirectoryList::VAR_DIR);

        $fileDirectoryPath = $varDir . $dir;

        if (!$this->_file->fileExists($fileDirectoryPath)) {

            $this->_file->mkdir($fileDirectoryPath, 0777, true);
        }

        $filePath = $fileDirectoryPath . '/' . $fileName;

        $this->_csvProcessor
            ->setEnclosure('"')
            ->setDelimiter(';');

        if ($this->_csvProcessor->appendData($filePath, $data)) {
            // File was written successfully
            return 0;
        } else {
            // File was not written
            return 1;
        }
    }
}
