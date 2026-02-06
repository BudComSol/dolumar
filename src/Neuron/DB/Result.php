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

// Result wrapper for PDOStatement instead of MySQLi_Result
class Neuron_DB_Result implements Iterator, ArrayAccess, Countable
{
	private $result;
	
	// iterator
	private $rowId;
	private $current;
	
	private $allRows;

	/**
	 * Creates a new Result wrapper around a PDOStatement.
	 * 
	 * Note: This implementation fetches all rows into memory immediately.
	 * For large result sets, this may cause high memory consumption.
	 * This is done to maintain compatibility with the original MySQLi-based
	 * implementation that supported random access via data_seek().
	 * 
	 * @param PDOStatement $result The PDOStatement to wrap
	 */
	public function __construct ($result)
	{
		$this->result = $result;
		
		// Fetch all rows into memory for PDO (since PDO doesn't support data_seek as easily)
		$this->allRows = $result->fetchAll(PDO::FETCH_ASSOC);
		
		// Close cursor immediately since we've fetched all rows
		$result->closeCursor();
		
		// Move to the first row
		$this->rowId = 0;
		$this->current = isset($this->allRows[0]) ? $this->allRows[0] : null;
	}
	
	public function getNumRows ()
	{
		return count($this->allRows);
	}
	
	/**************************
		ARRAY ACCESS
	***************************/
	public function offsetExists($offset): bool
	{
		return $offset >= 0 && $offset < $this->getNumRows ();
	}
	
	public function offsetGet($offset): mixed
	{
		// Only numeric values
		$offset = intval ($offset);
	
		// Direct access to allRows array (already in memory, O(1) access)
		return isset($this->allRows[$offset]) ? $this->allRows[$offset] : null;
	}
	
	public function offsetUnset($offset): void
	{
		// Doesn't do anything here.
	}
	
	public function offsetSet($offset, $value): void
	{
		// Doesn't do anything here.
	}

	
	/**************************
		ITERATOR
	***************************/
	public function current(): mixed
	{
		return $this->current;
	}
	
	public function key(): mixed
	{
		return $this->rowId;
	}
	
	public function next(): void
	{
		$this->rowId ++;
		$this->current = isset($this->allRows[$this->rowId]) ? $this->allRows[$this->rowId] : null;
	}
	
	public function rewind(): void
	{
		$this->rowId = 0;
		$this->current = isset($this->allRows[0]) ? $this->allRows[0] : null;
	}
	
	public function valid(): bool
	{
		return is_array ($this->current);
	}
	
	/**************************
		COUNTABLE
	***************************/
	public function count(): int
	{
		return $this->getNumRows ();
	}
	
	// Destruct
	public function __destruct ()
	{
		// Cursor was already closed in constructor after fetching all rows
		// Nothing to do here
	}
}
?>
