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
use cloak\result\LineResult;
use cloak\reporter\TextReporter;
use cloak\driver\Result as AnalyzeResult;
use cloak\value\CoverageBounds;
use cloak\event\InitEvent;
use cloak\event\StopEvent;


describe(TextReporter::class, function() {
    describe('onStop', function() {
        beforeEach(function() {
            $expectResultFile = __DIR__ . '/../fixtures/report/text_report.log';
            $this->expectResult = file_get_contents($expectResultFile);

            $sourceFile1 = realpath(__DIR__ . '/../fixtures/report/src/Example1.php');
            $sourceFile2 = realpath(__DIR__ . '/../fixtures/report/src/Example2.php');
            $sourceFile3 = realpath(__DIR__ . '/../fixtures/report/src/Example3.php');

            $coverages = [
                $sourceFile1 => [
                    13 => LineResult::EXECUTED,
                    18 => LineResult::EXECUTED
                ],
                $sourceFile2 => [
                    13 => LineResult::EXECUTED,
                    18 => LineResult::UNUSED
                ],
                $sourceFile3 => [
                    13 => LineResult::UNUSED,
                    18 => LineResult::UNUSED
                ]
            ];

            $analyzeResult = AnalyzeResult::fromArray($coverages);
            $this->stopEvent = new StopEvent(Result::fromAnalyzeResult($analyzeResult));

            $config = new Configuration([
                'coverageBounds' => new CoverageBounds(35.0, 70.0)
            ]);
            $initEvent = new InitEvent($config);

            $this->reporter = new TextReporter();
            $this->reporter->onInit($initEvent);
        });
        it('output text report', function() {
            expect(function() {
                $this->reporter->onStop($this->stopEvent);
            })->toPrint($this->expectResult);
        });
    });
});
