<?php 
final class Transaction 
{
	private static $conn; // Conexão Ativa

	private function __construct() {}

	public static function open($database)
	{
		if (empty(self::$conn))
		{
			self::$conn = Connection::open($database);
			self::$conn->beginTransaction(); // Inicia a transção
		}
	}

	public static function get()
	{
		return self::$conn; // Retorna a conexão ativa
	}

	public static function rollback() 
	{
		if (self::$conn)
		{
			self::$conn->rollback(); // Desfaz as operações realizadas
			self::$conn = NULL;
		}
	}

	public static function close()
	{
		if (self::$conn)
		{
			self::$conn->commit(); // Aplica as operações realizadas
			self::$conn = NULL;
		}
	}

}