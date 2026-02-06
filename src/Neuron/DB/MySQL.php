<?php
/**
 *  Dolumar engine, php/html MMORTS engine
 *  Copyright (C) 2009 Thijs Van der Schaeghe
 *  CatLab Interactive bvba, Gent, Belgium
 *  http://www.catlab.eu/
 *  http://www.dolumar.com/
 *
 *  This program is free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License along
 *  with this program; if not, write to the Free Software Foundation, Inc.,
 *  51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
 */

// MySQL implementation using PDO instead of MySQLi
class Neuron_DB_MySQL extends Neuron_DB_Database
{
	/**
	 * @var PDO
	 */
	private $connection;

	/*
		define ('DB_USERNAME', 'myuser');
		define ('DB_PASSWORD', 'myuser');
		define ('DB_SERVER', 'localhost');
		define ('DB_DATABASE', 'dolumar');
	*/
	private function connect ()
	{
		if (!isset ($this->connection))
		{
			try
			{
				$dsn = 'mysql:host=' . DB_SERVER . ';dbname=' . DB_DATABASE . ';charset=utf8mb4';
				$this->connection = new PDO($dsn, DB_USERNAME, DB_PASSWORD, [
					PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
					PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
					PDO::ATTR_EMULATE_PREPARES => false,
				]);
			}
			catch (PDOException $e)
			{
				printf("Connect failed: %s\n", $e->getMessage());
				exit();
			}
		}
	}

	public function getConnection ()
	{
		return $this->connection;
	}

	/**
	 * Executes multiple SQL statements separated by semicolons.
	 * 
	 * WARNING: This is a simplified implementation that splits on semicolons.
	 * It WILL FAIL with semicolons in string literals, comments, or stored procedures.
	 * Example of BROKEN query: INSERT INTO table VALUES ('text;with;semicolons')
	 * 
	 * This method is provided for backward compatibility only.
	 * For reliable execution, use individual query() calls instead.
	 * 
	 * @param string $sSQL Multiple SQL statements separated by semicolons
	 * @throws PDOException If any query fails
	 */
	public function multiQuery($sSQL)
	{
		$this->connect();
		// PDO doesn't support multi_query in the same way as MySQLi
		// Split queries by semicolon and execute them one by one
		// WARNING: This simple split WILL fail with semicolons in string literals!
		$queries = array_filter(array_map('trim', explode(';', $sSQL)));
		foreach ($queries as $query) {
			if (!empty($query)) {
				$this->connection->exec($query);
			}
		}
	}

	/*
		Execute a query and return a result
	*/
	public function query ($sSQL)
	{
		$this->addQueryLog ($sSQL);

		$this->connect ();

		// Increase the counter
		$this->query_counter ++;

		try {
			$statement = $this->connection->query($sSQL);

			// Check if this is a SELECT query that returns results
			if ($statement instanceof PDOStatement && $statement->columnCount() > 0)
			{
				return new Neuron_DB_Result ($statement);
			}

			// Insert ID will return zero if this query was not insert or update.
			$this->insert_id = intval ($this->connection->lastInsertId());

			// Affected rows
			if ($statement instanceof PDOStatement) {
				$this->affected_rows = intval ($statement->rowCount());
			} else {
				$this->affected_rows = 0;
			}

			if ($this->insert_id > 0)
				return $this->insert_id;

			if ($this->affected_rows > 0)
				return $this->affected_rows;

			return $statement;
		}
		catch (PDOException $e)
		{
			throw new Exception('MySQL Error: ' . $e->getMessage());
		}
	}

	/**
	 * Escapes a string for SQL queries.
	 * 
	 * @deprecated This method is provided for backward compatibility only.
	 * New code should use prepared statements with parameter binding instead.
	 * 
	 * @param string $txt The text to escape
	 * @return string The escaped text
	 * @throws Neuron_Core_Error If an array is passed
	 */
	public function escape ($txt)
	{
		if (is_array ($txt))
		{
			throw new Neuron_Core_Error ('Invalid parameter: escape cannot handle arrays.');
		}
		// PDO doesn't have a direct escape function like mysqli_real_escape_string
		// The proper way is to use prepared statements, but for backward compatibility
		// we'll use quote and remove the surrounding quotes
		$this->connect();
		$quoted = $this->connection->quote($txt);
		// Remove surrounding quotes added by quote()
		return substr($quoted, 1, -1);
	}

	public function fromUnixtime ($timestamp)
	{
		// Use prepared statement for consistency and best practices
		$this->connect();
		$stmt = $this->connection->prepare("SELECT FROM_UNIXTIME(?) AS datum");
		$stmt->execute([intval($timestamp)]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $result['datum'];
	}

	public function toUnixtime ($date)
	{
		// Use prepared statement to prevent SQL injection
		$this->connect();
		$stmt = $this->connection->prepare("SELECT UNIX_TIMESTAMP(?) AS datum");
		$stmt->execute([$date]);
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		$stmt->closeCursor();
		return $result['datum'];
	}
}
?>
