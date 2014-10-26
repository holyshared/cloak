<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use cloak\ConfigurationBuilder;
use cloak\configuration\Reporter;


describe('Reporter', function() {

    describe('#applyTo', function() {
        before(function() {
            $this->builder = new ConfigurationBuilder();
            $this->config = new Reporter([
                'reporters' => [
                    'uses' => ['lcov', 'processingTime'],
                    'configs' => [
                        'lcov' => [
                            'outputFilePath' => 'report.lcov'
                        ]
                    ]
                ]
            ]);
            $this->config->applyTo($this->builder);
        });
        it('return cloak\reporter\CompositeReporter instance', function() {
            expect($this->builder->reporter)->toBeAnInstanceOf('cloak\reporter\CompositeReporter');
        });
    });

});
