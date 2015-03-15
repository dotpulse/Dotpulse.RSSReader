<?php
namespace Dotpulse\RSSReader\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Dotpulse.RSSReader".    *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

class RSSReaderController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @return void
	 */
	public function indexAction() {

		$rss = array();

		$xmlURL = $this->request->getInternalArgument('__url');
		$xml = simplexml_load_file($xmlURL);
		
		$rss['info'] = array();
		if ( isset($xml->channel->title) ) {
			$rss['info']['title'] = (string)$xml->channel->title;
		}
		if ( isset($xml->channel->link) ) {
			$rss['info']['link'] = (string)$xml->channel->link;
		}
		if ( isset($xml->channel->description) ) {
			$rss['info']['description'] = (string)$xml->channel->description;
		}

		$rss['items'] = array();
		foreach ($xml->channel->item as $item) {
			$rss['items'][] = array(
				'title' => (string)$item->title,
				'link' => (string)$item->link,
				'pubDate' => (string)$item->pubDate,
				'date' => new \DateTime((string)$item->pubDate)
			);
		}

		$this->view->assign('rss', $rss);
		
		$maxItems = $this->request->getInternalArgument('__maxItems');
		$this->view->assign('maxItems', $maxItems);
		
		$linkTarget = $this->request->getInternalArgument('__linkTarget');
		$this->view->assign('linkTarget', $linkTarget);

	}

}