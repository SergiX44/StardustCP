<?php

namespace Core\AuraX;

use Exception;
use OutOfBoundsException;

class StringBuilder {
	/**
	 * @var string
	 */
	private $thestring;

	/**
	 * StringBuilder constructor
	 * @param string $string Base string
	 * @throws Exception Argument passed is not a string
	 */
	public function __construct($string = '') {
		if (!is_string($string)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring = $string;
	}

	/**
	 * StringBuilder destructor
	 */
	public function __destruct() {
		unset($this->thestring);
	}
	//<editor-fold desc="MANIPULATE CONTENT">

	/**
	 * Append a string
	 * @param string $string String to append
	 * @throws Exception Argument passed is not a string
	 */
	public function append($string) {
		if (!is_string($string)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring .= $string;
	}

	/**
	 * Append a string with return carriage
	 * @param string $string String to append
	 * @throws Exception Argument passed is not a string
	 */
	public function appendLine($string = '') {
		if (!is_string($string)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring .= $string . PHP_EOL;
	}

	/**
	 * Append a string with format
	 * Example: appendFormat('Hi {0}!','Luke') => Hi Luke!
	 * @param string $format String to append
	 * @param string $args,... Arguments to replace in {number}
	 * @throws Exception Argument passed is not a string
	 */
	public function appendFormat($format) {
		if (!is_string($format)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring .= $this->format($format, func_get_args());
	}

	/**
	 * Append a string with format and return carriage
	 * Example: appendLineFormat('Hi {0}!','Luke') => Hi Luke!
	 * @param string $format String to append
	 * @param string $args,... Arguments to replace in {number}
	 * @throws Exception Argument passed is not a string
	 */
	public function appendLineFormat($format) {
		if (!is_string($format)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring .= $this->format($format, func_get_args()) . PHP_EOL;
	}

	/**
	 * Append a StringBuilder object
	 * @param StringBuilder $sb StringBuilder object to append
	 * @throws Exception Argument passed is not a StringBuilder object
	 */
	public function appendStringBuilder($sb) {
		if (!($sb instanceof StringBuilder)) {
			throw new Exception('Argument passed is not a StringBuilder object');
		}
		$this->thestring .= $sb->toString();
	}

	/**
	 * Append a StringBuilder object with return carriage
	 * @param StringBuilder $sb StringBuilder object to append
	 * @throws Exception Argument passed is not a StringBuilder object
	 */
	public function appendLineStringBuilder($sb) {
		if (!($sb instanceof StringBuilder)) {
			throw new Exception('Argument passed is not a StringBuilder object');
		}
		$this->thestring .= $sb->toString() . PHP_EOL;
	}

	/**
	 * Prepend a string
	 * @param string $string String to prepend
	 * @throws Exception Argument passed is not a string
	 */
	public function prepend($string) {
		if (!is_string($string)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring = $string . $this->thestring;
	}

	/**
	 * Prepend a string with return carriage
	 * @param string|null $string String to prepend
	 * @throws Exception Argument passed is not a string
	 */
	public function prependLine($string = null) {
		if (!is_string($string)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring = $string . PHP_EOL . $this->thestring;
	}

	/**
	 * Prepend a string with format
	 * Example: prependFormat('Hi {0}!','Luke') => Hi Luke!
	 * @param string $format String to append
	 * @param string $args,... Arguments to replace in {number}
	 * @throws Exception Argument passed is not a string
	 */
	public function prependFormat($format) {
		if (!is_string($format)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring = $this->format($format, func_get_args()) . $this->thestring;
	}

	/**
	 * Prepend a string with format and return carriage
	 * Example: prependLineFormat('Hi {0}!','Luke') => Hi Luke!
	 * @param string $format String to append
	 * @param string $args,... Arguments to replace in {number}
	 * @throws Exception Argument passed is not a string
	 */
	public function prependLineFormat($format) {
		if (!is_string($format)) {
			throw new Exception('Argument passed is not a string');
		}
		$this->thestring = $this->format($format, func_get_args()) . PHP_EOL . $this->thestring;
	}

	/**
	 * Prepend a StringBuilder object
	 * @param StringBuilder $sb StringBuilder object to prepend
	 * @throws Exception Argument passed is not a StringBuilder object
	 */
	public function prependStringBuilder($sb) {
		if (!($sb instanceof StringBuilder)) {
			throw new Exception('Argument passed is not a StringBuilder object');
		}
		$this->thestring = $sb->toString() . $this->thestring;
	}

	/**
	 * Prepend a StringBuilder object with return carriage
	 * @param StringBuilder $sb StringBuilder object to prepend
	 * @throws Exception Argument passed is not a StringBuilder object
	 */
	public function prependLineStringBuilder($sb) {
		if (!($sb instanceof StringBuilder)) {
			throw new Exception('Argument passed is not a StringBuilder object');
		}
		$this->thestring = $sb->toString() . PHP_EOL . $this->thestring;
	}

	/**
	 * Clear the string
	 */
	public function clear() {
		$this->thestring = '';
	}

	/**
	 * Insert a string to entered position
	 * @param int $index String position
	 * @param string $string String to insert
	 * @throws Exception Index parameter must be integer
	 * @throws OutOfBoundsException Index parameter must respect the limits of the string
	 */
	public function insert($index, $string) {
		if (!is_int($index)) {
			throw new Exception('Index parameter must be integer');
		} else if ($this->isValidIndex($index)) {
			throw new OutOfBoundsException('Index parameter must respect the limits of the string');
		}
		$this->thestring = substr_replace($this->thestring, $string, $index, 0);
	}

	/**
	 * Remove a portion of string
	 * @param int $start Start index
	 * @param int $end End index
	 * @throws Exception Index parameters must be integers
	 * @throws OutOfBoundsException Index parameters must respect the limits of the string
	 */
	public function remove($start, $end) {
		if (!is_int($start) and !is_int($end)) {
			throw new Exception('Index parameters must be integers');
		} else if ($start < 0 or $end >= $this->Length() or $end - $start <= 0) {
			throw new OutOfBoundsException('Index parameters must respect the limits of the string');
		}
		$length = strlen($this->thestring);
		$str_start = substr($this->thestring, 0, $start);
		$str_end = substr($this->thestring, $end + 1, ($length - $end));
		$this->thestring = $str_start . $str_end;
	}

	/**
	 * Remove a character on specific position
	 * @param int $index Character position
	 * @throws Exception Index parameter must be integer
	 * @throws OutOfBoundsException Index parameter must respect the limits of the string
	 */
	public function removeAt($index) {
		if (!is_int($index)) {
			throw new Exception('Index parameter must be integer');
		} else if ($this->isValidIndex($index)) {
			throw new OutOfBoundsException('Index parameter must respect the limits of the string');
		}
		$this->thestring[$index] = '';
	}

	/**
	 * Replace string
	 * @param string $old String to search
	 * @param string $new New string
	 */
	public function replace($old, $new) {
		$this->thestring = str_replace($old, $new, $this->thestring);
	}

	/**
	 * Reverse the string
	 */
	public function reverse() {
		$this->thestring = strrev($this->thestring);
	}

	/**
	 * Set string length
	 * @param int $length Length
	 * @param string $char Char to insert if entered length is greater than string length
	 * @throws Exception Length parameter must be integer.
	 * @throws OutOfBoundsException Length parameter must be >= 0.
	 */
	public function setLength($length, $char = ' ') {
		if (!is_int($length)) {
			throw new Exception('Length parameter must be integer.');
		} else if ($length < 0) {
			throw new OutOfBoundsException('Length parameter must be >= 0.');
		}
		if ($length < $this->Length()) {
			$this->remove($length, $this->Length() - 1);
		} else if ($length > $this->Length()) {
			if (strlen($char) <= 0) {
				throw new Exception('char parameter cannot be empty.');
			}
			$oldlength = $this->Length();
			for ($i = 0; $i < ($length - $oldlength); $i++) {
				$this->append($char);
			}
		}
	}

	/**
	 * Remove special chars or a list of chars from the beginning and end of the supplied string
	 * @param string|null $charlist Chars to remove
	 */
	public function trim($charlist = null) {
		if ($charlist == null) {
			$this->thestring = trim($this->thestring);
		} else {
			$this->thestring = trim($this->thestring, $charlist);
		}
	}

	/**
	 * Remove special chars or a list of chars at the beginning of the supplied string
	 * @param string|null $charlist Chars to remove
	 */
	public function trimLeft($charlist = null) {
		if ($charlist == null) {
			$this->thestring = ltrim($this->thestring);
		} else {
			$this->thestring = ltrim($this->thestring, $charlist);
		}
	}

	/**
	 * Remove special chars or a list of chars at the end of the supplied string
	 * @param string|null $charlist Chars to remove
	 */
	public function trimRight($charlist = null) {
		if ($charlist == null) {
			$this->thestring = rtrim($this->thestring);
		} else {
			$this->thestring = rtrim($this->thestring, $charlist);
		}
	}

	/**
	 * Set a char at the entered position
	 * @param int $index Char position starting from 0
	 * @param string $char Char to set
	 * @throws Exception Index parameter must be integer.
	 * @throws OutOfBoundsException Index parameter must respect the limits of the string
	 * @throws Exception Char parameter must be a char.
	 */
	public function setCharAt($index, $char) {
		if (!is_int($index)) {
			throw new Exception('Index parameter must be integer.');
		} else if ($this->isValidIndex($index)) {
			throw new OutOfBoundsException('Index parameter must respect the limits of the string');
		} else if (strlen($char) != 1) {
			throw new Exception('Char parameter must be a char.');
		}
		$this->thestring[$index] = $char;
	}

	/**
	 * Remove last char or a number of chars at the end of the supplied string
	 * @param int $count Number of chars to remove
	 * @throws Exception Count parameter must be integer.
	 * @throws OutOfBoundsException Count parameter must be >=1 and <=string length
	 */
	public function removeLast($count = 1) {
		if (!is_int($count)) {
			throw new Exception('Count parameter must be integer.');
		}
		if ($count < 1 || $count > $this->length()) {
			throw new OutOfBoundsException('Count parameter must be >=1 and <=string length');
		}
		$this->thestring = substr($this->thestring, 0, -$count);
	}

	/**
	 * Remove first char or a number of chars at the beginning of the supplied string
	 * @param int $count Number of chars to remove
	 * @throws Exception Count parameter must be integer.
	 * @throws OutOfBoundsException Count parameter must be >=1 and <=string length
	 */
	public function removeFirst($count = 1) {
		if (!is_int($count)) {
			throw new Exception('Count parameter must be integer.');
		}
		if ($count < 1 || $count > $this->length()) {
			throw new OutOfBoundsException('Count parameter must be >=1 and <=string length');
		}
		$this->thestring = substr($this->thestring, $count);
	}
	//</editor-fold>
	//<editor-fold desc="CHECK CONTENT">
	/**
	 * Check if the string start with a specific string
	 * @param string $string String to find
	 * @return bool
	 */
	public function startWith($string) {
		$length = strlen($string);
		return (substr($this->thestring, 0, $length) === $string);
	}

	/**
	 * Check if the string end with a specific string
	 * @param string $string String to find
	 * @return bool
	 */
	public function endsWith($string) {
		$length = strlen($string);
		if ($length == 0) {
			return true;
		}
		return (substr($this->thestring, -$length) === $string);
	}

	/**
	 * Check if string containe another string
	 * @param string $string String to find
	 * @return bool
	 */
	public function contains($string) {
		return strpos($this->thestring, $string) !== false;
	}

	/**
	 * Returns the first position of the string to find otherwise false
	 * @param string $string String to find
	 * @return bool|int
	 */
	public function indexOf($string) {
		return strpos($this->thestring, $string);
	}

	/**
	 * Returns the last position of the string to find otherwise false
	 * @param string $string String to find
	 * @return bool|int
	 */
	public function lastIndexOf($string) {
		return strrpos($this->thestring, $string);
	}

	/**
	 * Returns the char at entered position
	 * @param $index int Char position
	 * @return string
	 * @throws Exception Index parameter must be integer.
	 * @throws OutOfBoundsException Index parameter must respect the limits of the string.
	 */
	public function getCharAt($index) {
		if (!is_int($index)) {
			throw new Exception('Index parameter must be integer.');
		} else if ($this->isValidIndex($index)) {
			throw new OutOfBoundsException('Index parameter must respect the limits of the string.');
		}
		return $this->thestring[$index];
	}

	/**
	 * Return part of a string
	 * @param int $start If start is non-negative, the returned string will start at the start'th position in string,
	 *                    counting from zero. For instance, in the string 'abcdef', the character at position 0 is 'a',
	 *                    the character at position 2 is 'c', and so forth.
	 *                    If start is negative, the returned string will start at the start'th character from the end
	 *                    of string. If string is less than or equal to start characters long, false will be returned.
	 * @param int $length [optional] If length is given and is positive, the string returned will contain at most
	 *                    length characters beginning from start (depending on the length of string).
	 *                    If length is given and is negative, then that many characters will be omitted from
	 *                    the end of string (after the start position has been calculated when a start is negative).
	 *                    If start denotes a position beyond this truncation, an empty string will be returned.
	 *                    If length is given and is 0, false or null; an empty string will be returned.
	 * @return string
	 * @throws Exception Start/length parameters must be integers.
	 * @throws OutOfBoundsException Index parameter must respect the limits of the string.
	 */
	public function substring($start, $length = null) {
		if (!is_int($start)) {
			throw new Exception('Start parameter must be integer.');
		} else if ($start < 0 or $length >= $this->Length() or $length - $start <= 0) {
			throw new OutOfBoundsException('Index parameter must respect the limits of the string.');
		}
		if ($length == null) {
			return substr($this->thestring, $start);
		} else {
			return substr($this->thestring, $start, $length);
		}
	}

	/**
	 * Returns string length
	 * @return int
	 */
	public function length() {
		return strlen($this->thestring);
	}
	//</editor-fold>
	//<editor-fold desc="UTILS">
	/**
	 * Convert StringBuilder object to string
	 * @return string
	 */
	public function __toString() {
		return $this->thestring;
	}

	/**
	 * Convert StringBuilder object to string
	 * @return string
	 */
	public function toString() {
		return $this->thestring;
	}

	/**
	 * Replace all EOL to html tag <br>
	 */
	public function toHtml() {
		return str_replace(PHP_EOL, '<br>', $this->thestring);
	}

	/**
	 * Compare StringBuilder object with another StringBuilder object
	 * @param StringBuilder $stringbuilder StringBuilder to compare
	 * @return bool
	 */
	public function compare($stringbuilder) {
		return $this->__toString() === $stringbuilder->__toString();
	}

	/**
	 * Returns md5 hash of object
	 * @return string
	 */
	public function hashCode() {
		return md5($this);
	}

	private function format($format, $args) {
		//$args = func_get_args();
		$format = array_shift($args);
		preg_match_all('/(?=\{)\{(\d+)\}(?!\})/', $format, $matches, PREG_OFFSET_CAPTURE);
		$offset = 0;
		foreach ($matches[1] as $data) {
			$i = $data[0];
			$format = substr_replace($format, @$args[$i], $offset + $data[1] - 1, 2 + strlen($i));
			$offset += strlen(@$args[$i]) - 2 - strlen($i);
		}
		return $format;
	}

	private function isValidIndex($index) {
		return $index < 0 or $index >= $this->Length();
	}
	//</editor-fold>
}