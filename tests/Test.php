<?php
/**
 * User: nvolgas
 * Date: 11/24/14
 * Time: 4:39 PM
 */

namespace Volnix\Monolog\Tests;

use Monolog\Logger;
use Volnix\Monolog\PDO\Handler;
use PHPUnit\Framework\TestCase;

class PDOTest extends TestCase {

	const APP_NAME = 'tests';
	/**
	 * @var null|\PDO $pdo
	 */
	private $pdo = null;

	/**
	 * @var null|\Monolog\Logger $logger
	 */
	private $logger = null;

	protected function setUp()
	{
        // set our dsn and create our pdo instance
        $dsn = sprintf('sqlite:%s/pdo_test.db', __DIR__);
        $this->pdo = new \PDO($dsn);
        $this->handler = new Handler($this->pdo);

		// create our logger and our pdo handler
		$this->logger = new Logger(self::APP_NAME);
        $this->logger->pushHandler($this->handler);

        // drop the table
        $drop_sql = sprintf("
			DROP TABLE IF EXISTS %s;
		", Handler::DEFAULT_TABLE_NAME);

		$this->pdo->exec($drop_sql);
        
        // create the table
		$create_sql = sprintf("
        CREATE TABLE %s (
          id integer(11) PRIMARY KEY,
          time datetime DEFAULT(CURRENT_TIMESTAMP),
          level varchar(100) NOT NULL,
          channel varchar(25) NOT NULL,
          message text
        );
        ", Handler::DEFAULT_TABLE_NAME);

        $this->pdo->exec($create_sql);
	}

	public function testError()
	{
        $this->logger->error('This is a test error');
        
        $row = $this->pdo->query(sprintf("select * from %s where channel = '%s'", Handler::DEFAULT_TABLE_NAME, self::APP_NAME))->fetch($this->pdo::FETCH_ASSOC);

		$this->assertEquals(Logger::getLevelName(Logger::ERROR), $row['level']);
		$this->assertEquals(self::APP_NAME, $row['channel']);
		$this->assertEquals('This is a test error', $row['message']);
    }
}
 