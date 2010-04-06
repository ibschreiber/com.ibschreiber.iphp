<?php
/**
 * Object Relational Mapper
 * 
 * Inflector to transform words
 * 
 * @category  Orm
 * @package   Inflector
 * @copyright Copyright (c) 2007 Warrant Group Limited
 */

class Orm_Inflector {
	
	/**
	 * Returns given $lower_case_and_underscored_word as a camelCased word.
	 *
	 * @param string $lower_case_and_underscored_word Word to camelize
	 * @return string Camelized word. likeThis.
	*/

	public static function camelize($name) {

		// lowercase all, underscores to spaces, and prefix with underscore.
        // (the prefix is to keep the first letter from getting uppercased
        // in the next statement.)
        $name = '_' . str_replace('_', ' ', strtolower($name));

        // uppercase words, collapse spaces, and drop initial underscore
        return ltrim(str_replace(' ', '', ucwords($name)), '_');
	}

	/**
	 * Returns corresponding table name for given $class_name.
	 *
	 * @param string $class_name Name of class to get database table name for
	 * @return string Name of the database table for given class
	*/

	public static function underscore($camelCasedWord) {
		$replace = strtolower(preg_replace('/(?<=\\w)([A-Z])/', '_\\1', $camelCasedWord));
		return $replace;
	}
	
	/**
	   * Make first letter lowercase
	   *
	   * @param	string
	   * @return string
   	*/
	
	public static function lcfirst($str) {
		return strtolower(substr($str, 0, 1)) . substr($str, 1);
	}
}