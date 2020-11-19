<?php/**
* @package custom-movie-plugin
*/

class CustomDeactivate{
	public static function deactivate(){
	flush_rewrite_rules();
}
}