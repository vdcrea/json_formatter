<?php
Class FormatterJsonString extends TextFormatter{
	public function about(){
		return array('name' => 'Json String');
	}
	public function run($string){
		$string = str_replace('&quot;', '"', $string);
		$string = json_encode($string, JSON_HEX_QUOT);
		return $string;
	}
}
