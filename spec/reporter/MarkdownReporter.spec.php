<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\Result;
use cloak\Configuration;
use cloak\value\CoverageBounds;
use cloak\result\LineResult;
use cloak\event\InitEvent;
use cloak\event\StartEvent;
use cloak\event\StopEvent;
use cloak\reporter\MarkdownReporter;
use cloak\driver\Result as AnalyzeResult;
use \DateTime;


describe(MarkdownReporter::class, function() {
    beforeEach(function() {
        $fixturePath = realpath(__DIR__ . '/../fixtures/');

        $this->source1 = $fixturePath . '/Example1.php';
        $this->source2 = $fixturePath . '/Example2.php';
        $this->markdownReport = $fixturePath . '/report.md';

        $this->startDateTime = DateTime::createFromFormat('Y-m-d H:i:s', '2014-07-10 00:00:00');

        $coverageResults = [
            $this->source1 => [
                10 => LineResult::EXECUTED,
                11 => LineResult::EXECUTED
            ],
            $this->source2 => [
                10 => LineResult::UNUSED,
                15 => LineResult::EXECUTED
            ]
        ];
        $analyzeResult = AnalyzeResult::fromArray($coverageResults);

        $this->result = Result::fromAnalyzeResult($analyzeResult);
    });

    describe('onStop', function() {
        beforeEach(function() {
            $this->reportDirectory = $this->makeDirectory();

            $this->fileName = 'report.md';
            $this->filePath = $this->reportDirectory->getPath() . '/' . $this->fileName;

            $this->initEvent = new InitEvent(new Configuration([
                'reportDirectory' => $this->reportDirectory->getPath(),
                'coverageBounds' => new CoverageBounds(35.0, 70.0)
            ]));
            $this->startEvent = new StartEvent($this->startDateTime);
            $this->stopEvent = new StopEvent($this->result);

            $this->reporter = new MarkdownReporter($this->fileName);
            $this->reporter->onInit($this->initEvent);
            $this->reporter->onStart($this->startEvent);
            $this->reporter->onStop($this->stopEvent);

            $this->outputReport = file_get_contents($this->markdownReport);
        });

        it('output the markdown report', function() {
            expect(file_get_contents($this->filePath))->toEqual($this->outputReport);
        });
    });

});
