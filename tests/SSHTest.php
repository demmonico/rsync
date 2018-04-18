<?php

use xobotyi\rsync\SSH;

/**
 * @Author : a.zinovyev
 * @Package: rsync
 * @License: http://www.opensource.org/licenses/mit-license.php
 */
class SSHTest extends \PHPUnit\Framework\TestCase
{
    public function testSSH() {
        $ssh = new SSH();

        $this->assertFalse($ssh->isRawOutput());
        $this->assertEquals(' ', $ssh->getOptionValueAssigner());

        $ssh->setParameters(['123', '321']);
        $this->assertEquals(['123', '321'], $ssh->getParameters());
        $ssh->clearParameters();
        $this->assertEquals([], $ssh->getParameters());

        $this->assertEquals([], $ssh->getOptions());
        $this->assertEquals('ssh', (string)$ssh);

        $identPath = __DIR__ . '\..\tests\ident.txt';
        touch($identPath);

        $ssh->setOptions([
                             SSH::OPT_IDENTIFICATION_FILE => $identPath,
                             SSH::OPT_OPTION              => ['BatchMode=yes', 'StrictHostKeyChecking=no'],
                             SSH::OPT_IPV4                => true,
                         ]);
        $this->assertNotEmpty((string)$ssh);
        $this->assertNull($ssh->getCode());
        $this->assertEquals('ssh', $ssh->getExecutable());

        unlink($identPath);
    }

    public function testSSHException_noArgumentAllowed() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        SSH::OPT_IPV4 => ['BatchMode=yes', 'StrictHostKeyChecking=no'],
                    ],
                ]);
    }

    public function testSSHException_notExecutable() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);

        $execPath = __DIR__ . '\..\tests\executable';

        new SSH([
                    SSH::CONF_EXECUTABLE => $execPath,
                ]);
    }

    public function testSSHException_notReadable() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        SSH::OPT_IDENTIFICATION_FILE => __DIR__ . '\..\tests\ident1.txt',
                    ],
                ]);
    }

    public function testSSHException_notRepeatable() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        SSH::OPT_CIPHER => ['BatchMode=yes', 'StrictHostKeyChecking=no'],
                    ],
                ]);
    }

    public function testSSHException_notStringable1() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        SSH::OPT_CIPHER => null,
                    ],
                ]);
    }

    public function testSSHException_notStringable2() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        SSH::OPT_OPTION => ['BatchMode=yes', 'StrictHostKeyChecking=no', null],
                    ],
                ]);
    }

    public function testSSHException_notValuableExecutable() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_EXECUTABLE => '   ',
                ]);
    }

    public function testSSHException_optionNotSupported() {
        $this->expectException(\xobotyi\rsync\Exception\Command::class);
        new SSH([
                    SSH::CONF_OPTIONS => [
                        'sdfjhgsdjfhgsdf' => ['BatchMode=yes', 'StrictHostKeyChecking=no'],
                    ],
                ]);
    }
}