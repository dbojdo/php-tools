<?php
namespace Webit\Tools\FileSystem;

class FileReader {
	static public function readLastLine($file, $nonEmpty = true) {
		$line = '';
		
		$f = fopen($file, 'r');
		$cursor = -1;
		
		fseek($f, $cursor, SEEK_END);
		$char = fgetc($f);
		
		if($nonEmpty) {
			/**
			 * Trim trailing newline chars of the file
			 */
			while ($char === "\n" || $char === "\r") {
				fseek($f, $cursor--, SEEK_END);
				$char = fgetc($f);
			}
		}
		
		/**
		 * Read until the start of file or first newline char
		 */
		while ($char !== false && $char !== "\n" && $char !== "\r") {
			/**
			 * Prepend the new char
			 */
			$line = $char . $line;
			fseek($f, $cursor--, SEEK_END);
			$char = fgetc($f);
		}
		
		return $line;
	}
}
?>