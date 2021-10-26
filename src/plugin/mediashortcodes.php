<?php
/*
 * @copyright  Copyright (C) 2021 Marco Beierer. All rights reserved.
 * @license    http://www.gnu.org/licenses/agpl-3.0.html GNU/AGPL
 */

defined ('_JEXEC') or die ('Restricted access');

class plgContentMediashortcodes extends JPlugin {
	protected $autoloadLanguage = true;

	public function onContentPrepare($context, &$article, &$params, $page = 0) {
		$pattern = '/{youtube}(.*){\/youtube}/';
		$matches = array();

		$ok = preg_match_all($pattern, $article->text, $matches, PREG_SET_ORDER);
		if (!$ok) {
			return;
		}

		foreach($matches as $match) {
			// TODO could be stricter because we probably know all valid chars for IDs...
			$youtubeID = htmlspecialchars($match[1]); // prevents XSS 

			// 56.25% is for 16:9
			$replacement = sprintf('<div style="position: relative; padding-bottom: 56.25%%; height: 0;"><iframe style="position: absolute; top: 0; left: 0; width: 100%%; height: 100%%" src="https://www.youtube-nocookie.com/embed/%s?rel=0" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>', $youtubeID);
			$article->text = str_replace($match[0], $replacement, $article->text);
		}
	}
}
