<?php

namespace Jm\JmViewhelpers\ViewHelpers;

/**
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * ViewHelper to create Sharing Links to Social Networks that do not violate privacy protection goals.
 *
 * # Example: Share-Link to Twitter
 * <code>
 * {namespace d=Jm\JmViewhelpers\ViewHelpers}
 * <d:share network="twitter"
 *          get="shareUrl"
 * 			message="Look at this post"
 * 			link="https://www.google.de/"
 * 			title="Fancy Title"
 * 			forceSSL="1" />
 * </code>
 *
 * # Example: Get Share-Count for a specific URL at Twitter
 * <code>
 * {namespace d=Jm\JmViewhelpers\ViewHelpers}
 * <d:share network="twitter"
 *          get="shareCount"
 * 			link="https://www.google.de/" />
 * </code>
 *
 */
class ShareViewHelper extends \TYPO3\CMS\Fluid\Core\ViewHelper\AbstractTagBasedViewHelper {

	/** @var $cObj \TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer */
	protected $cObj;
	
	/**
	 * Arguments Initialization
	 * @return void
	 */
	public function initializeArguments() {
		$this->registerArgument('network', 'string', 'The social network for which to perform the action, either twitter, facebook, google-plus, or linkedin', TRUE);
		$this->registerArgument('get',  'string', 'The information to be returned, either shareUrl or shareCount', TRUE);
		$this->registerArgument('title',  'string', 'The title to be used when sharing the link.', FALSE);
		$this->registerArgument('message',  'string', 'The message (or tweet) to be used when sharing the link.', FALSE);
		$this->registerArgument('link',  'string', 'The link that shall be shared.', TRUE);
		$this->registerArgument('forceSSL',  'boolean', 'Indicates whether the link shall be transformed to https if it is not', FALSE);
	}

	/**
	 * Create the share URL for the given social network
	 * @return string
	 */
	private function getShareUrl($network, $title, $message, $link) {
		switch ($network) {
			case 'twitter': return "https://twitter.com/intent/tweet?text=".rawurlencode($message)."&url=".rawurlencode($link)."&related="; break;
			case 'facebook': return "https://www.facebook.com/sharer/sharer.php?u=".rawurlencode($link); break;
			case 'google-plus': return "https://plus.google.com/share?url=".rawurlencode($link)."&t=".rawurlencode($title)."&gpsrc=gplp0"; break;
			case 'linkedin': return "https://www.linkedin.com/shareArticle?mini=true&url=".rawurlencode($link)."&title=".rawurlencode($title)."&ro=false&summary=&source="; break;
			default: return "";
		}
	}
	
	/**
	 * Query the share count for the given URL and social network
	 * @return string
	 */
	private function getShareCount($network, $link) {
		switch ($network) {
			case 'twitter': 
				$json = @file_get_contents('http://cdn.api.twitter.com/1/urls/count.json?url='.rawurlencode($link));
				$result = json_decode($json);
				return $result->{"count"};
			case 'facebook': 
				$json = @file_get_contents('https://api.facebook.com/method/links.getStats?urls='.rawurlencode($link).'&format=json');
				$result = json_decode($json);
				if (isset($result[0]->{"share_count"}) && isset($result[0]->{"like_count"}))
					return $result[0]->{"share_count"} + $result[0]->{"like_count"};
				else
					return -1;
			case 'google-plus': 
				$contents = @file_get_contents('https://plusone.google.com/_/+1/fastbutton?url='.rawurlencode($link));
				preg_match('/window\.__SSR = {c: ([\d]+)/', $contents, $matches);
				if (isset($matches[0])) 
					return (int) str_replace('window.__SSR = {c: ', '', $matches[0]);
				return 0;
			case 'linkedin': 
				$json = @file_get_contents('http://www.linkedin.com/countserv/count/share?url='.rawurlencode($link).'&format=json');
				$result = json_decode($json);
				return $result->{"count"};
			default: return "";
		}
	}
	
	/**
	 * Render share viewhelper
	 * @return string
	 */
	public function render() {
		if (isset($this->arguments['forceSSL']) && $this->arguments['forceSSL'])
		{
			$this->arguments['link'] = str_replace("http:", "https:", $this->arguments['link']);
		}
		switch ($this->arguments['get']){
			case 'shareUrl': return $this->getShareUrl($this->arguments['network'], $this->arguments['title'], $this->arguments['message'], $this->arguments['link']);
			case 'shareCount': return $this->getShareCount($this->arguments['network'], $this->arguments['link']);
			default: return "";
		}
	}
}
