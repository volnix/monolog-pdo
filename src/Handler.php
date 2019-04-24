<?php

namespace Volnix\Monolog\PDO;

use Monolog\Handler\AbstractProcessingHandler;
use PDO;

class Handler extends AbstractProcessingHandler
{
	/**
	 * @var \PDO
	 */
	private $pdo;

	/**
	 * @var string
	 */
	private $logs_table;

	/**
	 * @var string $default_logs_table_name - 'logs'
	 */
	const DEFAULT_TABLE_NAME = 'logs';

	/**
	 * SQL constructor.
	 * @param \PDO $pdo
	 * @param string $logs_table
	 */
	public function __construct(\PDO $pdo, string $logs_table = self::DEFAULT_TABLE_NAME)
	{
		$this->pdo = $pdo;
		$this->logs_table = $logs_table;
	}

	/**
	 * @param array $data
	 * @return void
	 * @throws \Exception
	 */
	protected function write(array $data) : void
	{
		$fields = [
			'level'     => $data['level_name'],
			'channel'  => $data['channel'],
			'message'   => $data['message']
		];

		$statement = $this->pdo->prepare(sprintf('INSERT INTO %s (level, channel, message) VALUES (:level, :channel, :message)', $this->logs_table));
		$statement->execute($fields);
	}
}