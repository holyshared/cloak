<?php

/**
 * This file is part of cloak.
 *
 * (c) Noritaka Horio <holy.shared.design@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

use \Mockery;

describe('AbstractDriver', function() {

    describe('#isStarted', function() {
        context('when after instantiation', function() {
            before(function() {
                $this->verify = function() {
                    Mockery::close();
                };

                $this->driver = Mockery::mock('cloak\driver\AbstractDriver')->makePartial();
                $this->driver->shouldReceive('start')->never();
                $this->driver->shouldReceive('stop')->never();
                $this->result = $this->driver->isStarted();
            });
            it('should return false', function() {
                expect($this->result)->toBeFalse();
            });
            it('check mock object expectations', function() {
                call_user_func($this->verify);
            });
        });
    });

    describe('#getResult', function() {
        context('when after instantiation', function() {
            before(function() {
                $this->verify = function() {
                    Mockery::close();
                };
                $this->driver = Mockery::mock('cloak\driver\AbstractDriver')->makePartial();
                $this->driver->shouldReceive('start')->never();
                $this->driver->shouldReceive('stop')->never();
                $this->result = $this->driver->getResult();
            });
            it('should return array', function() {
                expect($this->result)->toBeAn('array');
            });
            it('check mock object expectations', function() {
                call_user_func($this->verify);
            });
        });
    });

});
