<?php
set_include_path(
	__DIR__ . DIRECTORY_SEPARATOR
	. '..' . DIRECTORY_SEPARATOR
	. 'library' . PATH_SEPARATOR
	. get_include_path()
);

spl_autoload_register(
	function ($classname)
	{
        $path =
			str_replace('\\', DIRECTORY_SEPARATOR, $classname)
			. '.php';

		require $path;

        return true;
	}
);
