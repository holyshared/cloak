<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\Analyzer;
use cloak\ConfigurationBuilder;
use cloak\Result;
use cloak\driver\Result as AnalyzeResult;
use cloak\result\Line;
use cloak\result\File;
use \Mockery;

describe('Analyzer', function() {
    before(function() {
        $subject = $this->subject = new \stdClass();
        $subject->called = 0;
        $subject->configuration = null;

        $this->builder = function(ConfigurationBuilder $builder) use ($subject) {
            $subject->called++;
            $subject->builder = $builder;
        };
    });

    describe('#factory', function() {
        before(function() {
            $this->verify = function() {
                Mockery::close();
            };
            $subject = $this->subject = new \stdClass();
            $subject->called = 0;
            $subject->configuration = null;

            $this->builder = function(ConfigurationBuilder $builder) use ($subject) {
                $subject->called++;
                $subject->builder = $builder;
            };
            $this->returnValue = Analyzer::factory($this->builder);
        });

        it('should called once', function() {
            expect($this->subject->called)->toBe(1);
        });
        it('should argument is an instance of cloak\ConfigurationBuilder', function() {
            expect($this->subject->builder)->toBeAnInstanceOf('cloak\ConfigurationBuilder');
        });
        it('should return an instance of cloak\Analyzer', function() {
            expect($this->returnValue)->toBeAnInstanceOf('cloak\Analyzer');
        });
        it('check mock object expectations', function() {
            call_user_func($this->verify);
        });
    });

    describe('#stop', function() {
        before(function() {

            $this->verify = function() {
                Mockery::close();
            };

            $this->analyzer = Analyzer::factory(function(ConfigurationBuilder $builder) {

                $rootDirectory = __DIR__ . '/fixtures/src/';

                $analyzeResult = AnalyzeResult::fromArray([
                    $rootDirectory . 'foo.php' => [
                        1 => Line::EXECUTED
                    ]
                ]);

                $driver = Mockery::mock('cloak\Driver\DriverInterface');
                $driver->shouldReceive('start')->once();
                $driver->shouldReceive('stop')->once();
                $driver->shouldReceive('getAnalyzeResult')
                    ->once()->andReturn($analyzeResult);

                $builder->driver($driver);
            });

            $subject = $this->subject = new \stdClass();

            $this->notifier = Mockery::mock('cloak\AnalyzeLifeCycleNotifierInterface');
            $this->notifier->shouldReceive('notifyStart')->once();
            $this->notifier->shouldReceive('notifyStop')->once()->with(Mockery::on(function($result) use ($subject) {
                $subject->result = $result;
                return true;
            }));

            $this->analyzer->setLifeCycleNotifier($this->notifier);
            $this->analyzer->start();
            $this->analyzer->stop();
        });
        it('should return cloak\Result instance', function() {
            expect($this->subject->result)->toBeAnInstanceOf('cloak\Result');
        });
        it('check mock object expectations', function() {
            call_user_func($this->verify);
        });
    });

    describe('#isStarted', function() {
        context('when started', function() {
            before(function() {
                $this->verify = function() {
                    Mockery::close();
                };
                $this->analyzer = Analyzer::factory(function(ConfigurationBuilder $builder) {
                    $driver = Mockery::mock('cloak\driver\DriverInterface');
                    $driver->shouldReceive('start')->once();
                    $driver->shouldReceive('isStarted')->once()->andReturn(true);
                    $builder->driver($driver);
                });
                $this->analyzer->start();
            });
            it('should return true', function() {
                expect($this->analyzer->isStarted())->toBeTrue();
            });
            it('check mock object expectations', function() {
                call_user_func($this->verify);
            });
        });
        context('when stoped', function() {
            before(function() {
                $this->verify = function() {
                    Mockery::close();
                };

                $this->analyzer = Analyzer::factory(function(ConfigurationBuilder $builder) {
                    $driver = Mockery::mock('cloak\driver\DriverInterface');
                    $driver->shouldReceive('start')->once();
                    $driver->shouldReceive('stop')->once();
                    $driver->shouldReceive('isStarted')->once()->andReturn(false);
                    $builder->driver($driver);
                });

                $this->analyzer->start();
                $this->analyzer->stop();
            });
            it('should return false', function() {
                expect($this->analyzer->isStarted())->toBeFalse();
            });
            it('check mock object expectations', function() {
                call_user_func($this->verify);
            });
        });
    });

    describe('#getResult', function() {
        before(function() {
            $this->verify = function() {
                Mockery::close();
            };

            $this->analyzer = Analyzer::factory(function(ConfigurationBuilder $builder) {
                $rootDirectory = __DIR__ . '/fixtures/src/';

                $coverageResults = [
                    $rootDirectory . 'foo.php' => array( 1 => Line::EXECUTED ),
                    $rootDirectory . 'bar.php' => array( 1 => Line::EXECUTED ),
                    $rootDirectory . 'vendor/foo1.php' => array( 1 => Line::EXECUTED ),
                    $rootDirectory . 'vendor/foo2.php' => array( 1 => Line::EXECUTED )
                ];

                $analyzeResult = AnalyzeResult::fromArray($coverageResults);

                $driver = Mockery::mock('cloak\driver\DriverInterface');
                $driver->shouldReceive('start')->once();
                $driver->shouldReceive('stop')->once();
                $driver->shouldReceive('getAnalyzeResult')->twice()->andReturn($analyzeResult);

                $builder->driver($driver)
                    ->includeFile(function(File $file) {
                        return $file->matchPath('src');
                    })->excludeFile(function(File $file) {
                        return $file->matchPath('vendor');
                    });
            });

            $this->analyzer->start();
            $this->analyzer->stop();

            $this->result = $this->analyzer->getResult();
        });
        it('should return an instance of cloak\Result', function() {
            $files = $this->result->getFiles();
            expect($files->count())->toBe(2);
            expect($this->result)->toBeAnInstanceOf('cloak\Result');
        });
        it('check mock object expectations', function() {
            call_user_func($this->verify);
        });
    });

});
