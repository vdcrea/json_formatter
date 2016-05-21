<?php
Class FormatterJson extends TextFormatter{
	public function about(){
		return array('name' => 'Json Encode');
	}
	public function run($string){
		$string = str_replace('&quot;', '"', $string);
		$string = json_encode($string, JSON_HEX_QUOT);
		return $string;
	}
}
