<?php

class Quack {
    public function interactive_quack ($quack) {
		$pattern = '/#(\w+)/';
		preg_match_all($pattern, $quack, $matches, PREG_OFFSET_CAPTURE);

		$hashtags = array();
		foreach ($matches[0] as $match) {
			$hashtag = $match[0];
			$startIndex = $match[1];
			$hashtags[] = array('hashtag' => $hashtag, 'startIndex' => $startIndex);
		}

		$n = sizeof($hashtags);
		$better_quack = '';
		$pocetak = 0;
		foreach ($hashtags as $hashtag) {
			$kraj = $hashtag['startIndex'];
			$better_quack .= substr($quack, $pocetak, $kraj - $pocetak);
			
			$link = '<a href="quack.php?hashtag=' . substr($hashtag['hashtag'], 1) . '">' . $hashtag['hashtag'] . '</a>';
			$better_quack .= $link;
			$pocetak = $kraj + strlen($hashtag['hashtag']);
		}
		$better_quack .= substr($quack, $pocetak, strlen($quack) - $pocetak);
		return $better_quack;
	}
}

?>