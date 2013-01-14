<?php
namespace Aura\Framework\Bootstrap;

/**
 * Generated by PHPUnit_SkeletonGenerator 1.2.0 on 2012-11-20 at 08:18:01.
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Factory
     */
    protected $factory;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // use the real system root
        $root = null;
        $map = ['mock' => 'Aura\Framework\Mock\Bootstrap'];
        $this->factory = new Factory($root, $map);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Aura\Framework\Bootstrap::exec
     * @todo   Implement testExec().
     */
    public function testExec()
    {
        $bootstrap = $this->factory->newInstance('mock');
        $this->assertInstanceOf('Aura\Framework\Mock\Bootstrap', $bootstrap);
    }

    /**
     * This will test to ensure that config files are loaded in the 
     * proper order (ie default.php >> test.php >> local.php)
     * 
     * @covers Aura\Framework\Bootstrap::readConfigSet
     */
    public function testReadConfigSet()
    {
        // Mode to load
        $mode = 'test';

        // Actual loaded files
        $loaded_files = [];

        // Expected loaded files
        $expected_files = [
            'default',
            $mode,
            'local',
        ];

        // Method to read the config file
        $read = function($file) use (&$loaded_files) {
            require $file;
        };

        // Setup filesystem
        $path = VfsSystem::create('root');

        $config_path = $path . DIRECTORY_SEPARATOR . 'config';

        // Create config files to be read
        foreach($expected_files as $file) {
            file_put_contents(
                $config_path . DIRECTORY_SEPARATOR . "{$file}.php",
                "<?php \$loaded_files[]='{$file}';"
            );
        }
        
        // Read the config files
        $this->factory->readConfigSet($read, $mode, $config_path);

        $this->assertEquals($expected_files, $loaded_files);
    }
}
