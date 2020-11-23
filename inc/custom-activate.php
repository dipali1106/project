<?php/**
* @package custom-movie-plugin
*/

class CustomActivate{
	public static function activate(){
	flush_rewrite_rules();
}
}