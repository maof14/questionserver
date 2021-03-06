<?php 

/**
 *
 * Autoload classes, eliminating the need to require them manually. 
 * @param string $class. The class name. 
 * @return void 
 */

spl_autoload_register(function($class){
	$base_dir = __DIR__.'/src/';
	$file = $base_dir.$class.'/'.$class.'.php';
	if(file_exists($file)) {
		require $file;
	}
});