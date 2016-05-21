<?php
Class FormatterJsonTitle extends TextFormatter{
	public function about(){
		return array('name' => 'Json Encode Title');
	}
	public function run($string){

		//original Title Case script © John Gruber <daringfireball.net>
		//javascript port © David Gouch <individed.com>
		//PHP port of the above by Kroc Camen <camendesign.com>

		//remove HTML, storing it for later
		//       HTML elements to ignore    | tags  | entities
		$regx = '/<(code|var)[^>]*>.*?<\/\1>|<[^>]+>|&\S+;/';
		preg_match_all ($regx, $string, $html, PREG_OFFSET_CAPTURE);
		$string = preg_replace ($regx, '', $string);

		//find each word (including punctuation attached)
		preg_match_all ('/[\w\p{L}&`\'‘’"“\.@:\/\{\(\[<>_]+-? */u', $string, $m1, PREG_OFFSET_CAPTURE);
		foreach ($m1[0] as &$m2) {
			//shorthand these- "match" and "index"
			list ($m, $i) = $m2;

			//correct offsets for multi-byte characters (`PREG_OFFSET_CAPTURE` returns *byte*-offset)
			//we fix this by recounting the text before the offset using multi-byte aware `strlen`
			$i = mb_strlen (substr ($string, 0, $i), 'UTF-8');

			//find words that should always be lowercase…
			//(never on the first word, and never if preceded by a colon)
			$m = $i>0 && mb_substr ($string, max (0, $i-2), 1, 'UTF-8') !== ':' &&
				!preg_match ('/[\x{2014}\x{2013}] ?/u', mb_substr ($string, max (0, $i-2), 2, 'UTF-8')) &&
				 preg_match ('/^(a(nd?|s|t)?|b(ut|y)|en|for|i[fn]|o[fnr]|t(he|o)|vs?\.?|via)[ \-]/i', $m)
			?	//…and convert them to lowercase
				mb_strtolower ($m, 'UTF-8')

			//else:	brackets and other wrappers
			: (	preg_match ('/[\'"_{(\[‘“]/u', mb_substr ($string, max (0, $i-1), 3, 'UTF-8'))
			?	//convert first letter within wrapper to uppercase
				mb_substr ($m, 0, 1, 'UTF-8').
				mb_strtoupper (mb_substr ($m, 1, 1, 'UTF-8'), 'UTF-8').
				mb_substr ($m, 2, mb_strlen ($m, 'UTF-8')-2, 'UTF-8')

			//else:	do not uppercase these cases
			: (	preg_match ('/[\])}]/', mb_substr ($string, max (0, $i-1), 3, 'UTF-8')) ||
				preg_match ('/[A-Z]+|&|\w+[._]\w+/u', mb_substr ($m, 1, mb_strlen ($m, 'UTF-8')-1, 'UTF-8'))
			?	$m
				//if all else fails, then no more fringe-cases; uppercase the word
			:	mb_strtoupper (mb_substr ($m, 0, 1, 'UTF-8'), 'UTF-8').
				mb_substr ($m, 1, mb_strlen ($m, 'UTF-8'), 'UTF-8')
			));

			//resplice the title with the change (`substr_replace` is not multi-byte aware)
			$string = mb_substr ($string, 0, $i, 'UTF-8').$m.
				 mb_substr ($string, $i+mb_strlen ($m, 'UTF-8'), mb_strlen ($string, 'UTF-8'), 'UTF-8')
			;
		}

		//restore the HTML
		foreach ($html[0] as &$tag) $string = substr_replace ($string, $tag[0], $tag[1], 0);

		// json encode
		$string = str_replace('&quot;', '"', $string);
		$string = json_encode($string, JSON_HEX_QUOT);
		return $string;
	}
}
