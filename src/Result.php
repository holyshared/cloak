<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace cloak;

use cloak\value\Coverage;
use cloak\result\FileResult;
use cloak\result\LineSet;
use cloak\result\collection\NamedResultCollection;
use cloak\driver\Result as AnalyzeResult;
use PhpCollection\Sequence;
use PhpCollection\AbstractSequence;
use \UnexpectedValueException;


/**
 * Class Result
 * @package cloak
 */
class Result implements CoverageResultInterface
{

    /**
     * @var AbstractSequence
     */
    private $files;


    /**
     * @param File[] $files
     */
    public function __construct($files = [])
    {
        $this->files = new Sequence();

        foreach ($files as $file) {
            $this->addFile($file);
        }
    }


    /**
     * @param driver\Result $result
     * @return Result
     */
    public static function fromAnalyzeResult(AnalyzeResult $result)
    {
        $files = static::parseResult($result);
        return new self($files);
    }

    /**
     * @param driver\Result $result
     * @return array
     */
    public static function parseResult(AnalyzeResult $result)
    {
        $files = [];
        $fileResults = $result->getFiles();

        foreach ($fileResults as $fileResult) {
            $path = $fileResult->getPath();
            $lineResults = LineSet::from( $fileResult->getLineResults() );

            $file = new FileResult($path, $lineResults);
            $files[] = $file;
        }

        return $files;
    }

    /**
     * @param File[] $files
     * @return $this
     */
    public function setFiles(array $files)
    {
        $this->files = new Sequence($files);
        return $this;
    }

    /**
     * @return NamedResultCollection
     */
    public function getFiles()
    {
        $files = $this->files->all();
        return new NamedResultCollection($files);
    }

    /**
     * @param FileResult $file
     * @return $this
     */
    public function addFile(FileResult $file)
    {
        $this->files->add($file);
        return $this;
    }

    /**
     * @param FileResult $file
     * @return $this
     */
    public function removeFile(FileResult $file)
    {
        $indexAt = $this->files->indexOf($file);

        if ($indexAt === -1) {
            throw new UnexpectedValueException("File that does not exist {$file->getPath()}");
        }
        $this->files->remove($indexAt);

        return $this;
    }

    /**
     * @return int
     */
    public function getLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getLineCount();
        }

        return $totalLineCount;
    }

    /**
     * @return int
     */
    public function getDeadLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getDeadLineCount();
        }

        return $totalLineCount;
    }

    /**
     * @return int
     */
    public function getExecutedLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getExecutedLineCount();
        }

        return $totalLineCount;
    }

    /**
     * @return int
     */
    public function getUnusedLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getUnusedLineCount();
        }

        return $totalLineCount;
    }

    /**
     * @return int
     */
    public function getExecutableLineCount()
    {
        $totalLineCount = 0;
        $files = $this->files->getIterator();

        foreach ($files as $file) {
            $totalLineCount += $file->getExecutableLineCount();
        }

        return $totalLineCount;
    }

    /**
     * @return Coverage
     */
    public function getCodeCoverage()
    {
        $executedLineCount = $this->getExecutedLineCount();
        $executableLineCount = $this->getExecutableLineCount();
        $realCoverage = ($executedLineCount / $executableLineCount) * 100;

        $coverage = (float) round($realCoverage, 2);

        return new Coverage($coverage);
    }

    /**
     * @param Coverage $coverage
     * @return bool
     */
    public function isCoverageLessThan(Coverage $coverage)
    {
        return $this->getCodeCoverage()->lessThan($coverage);
    }

    /**
     * @param Coverage $coverage
     * @return bool
     */
    public function isCoverageGreaterEqual(Coverage $coverage)
    {
        return $this->getCodeCoverage()->greaterEqual($coverage);
    }

}
