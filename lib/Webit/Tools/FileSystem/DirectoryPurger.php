<?php
namespace Webit\Tools\FileSystem;
class DirectoryPurger {
	static public function purgeDirectory($directory, $removeOnPurge = false) {
		$empty = !$removeOnPurge;
		// if the path has a slash at the end we remove it here
		if (substr($directory, -1) == '/') {
			$directory = substr($directory, 0, -1);
		}

		// if the path is not valid or is not a directory ...
		if (!file_exists($directory) || !is_dir($directory)) {
			// ... we return false and exit the function
			return false;

			// ... if the path is not readable
		} elseif (!is_readable($directory)) {
			// ... we return false and exit the function
			return false;

			// ... else if the path is readable
		} else {

			// we open the directory
			$handle = opendir($directory);

			// and scan through the items inside
			while (false !== ($item = readdir($handle))) {
				// if the filepointer is not the current directory
				// or the parent directory
				if ($item != '.' && $item != '..') {
					// we build the new path to delete
					$path = $directory . '/' . $item;

					// if the new path is a directory
					if (is_dir($path)) {
						// we call this function with the new path
						self::purgeDirectory($path);

						// if the new path is a file
					} else {
						// we remove the file
						unlink($path);
					}
				}
			}
			// close the directory
			closedir($handle);

			// if the option to empty is not set to true
			if ($empty == false) {
				// try to delete the now empty directory
				if (!rmdir($directory)) {
					// return false if not possible
					return false;
				}
			}
			// return success
			return true;
		}
	}
}
?>