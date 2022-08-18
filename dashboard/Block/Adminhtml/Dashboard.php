<?php
/**
 * Copyright (c) 2019 Magenerds
 * All rights reserved
 *
 * This product includes proprietary software developed at Magenerds, Germany
 * For more information see http://www.magenerds.com/
 *
 * To obtain a valid license for using this software please contact us at
 * info@magenerds.com
 */

namespace Magenerds\Dashboard\Block\Adminhtml;

use Exception;
use Magento\Backend\Block\Template;

/**
 * @copyright  Copyright (c) 2019 Magenerds (http://www.magenerds.com)
 * @link       http://www.magenerds.com/
 * @author     Florian Sydekum <info@magenerds.com>
 */
class Dashboard extends Template
{
    /**
     * Holds the blog feed url
     */
    const BLOG_FEED_URL = null; # http://www.magenerds.com/category/magento-2/feed/

    /**
     * Holds the news feed url
     */
    const NEWS_FEED_URL = null; # http://www.magenerds.com/category/dashboardnews/feed/

    /**
     * Holds the number of blog feeds
     */
    const NUMBER_BLOG_FEEDS = 5;

    /**
     * Holds the number of news feeds
     */
    const NUMBER_NEWS_FEEDS = 5;

    /**
     * @return object
     */
    protected function getReader()
    {
        if (class_exists('\Zend_Feed_Reader')) {
            return new \Zend_Feed_Reader();
        }
        /** @noinspection PhpFullyQualifiedNameUsageInspection PhpUndefinedNamespaceInspection */
        return new \Zend\Feed\Reader\Reader();
    }

    /**
     * Returns the blog feeds
     *
     * @return array
     */
    public function getBlogFeeds()
    {
        $feeds = [];
        if (!static::BLOG_FEED_URL) {
            return $feeds;
        }
        $reader = $this->getReader();

        $ctr = 1;
        try {
            foreach ($reader->import(static::BLOG_FEED_URL) as $feed) {
                $feeds[] = [
                    'link' => $feed->getLink(),
                    'title' => $feed->getTitle(),
                    'date' => substr($feed->getDateCreated(), 0, 10),
                    'description' => $feed->getDescription(),
                ];
                if ($ctr == static::NUMBER_BLOG_FEEDS) {
                    break;
                }
                $ctr++;
            }
        } catch (Exception $e) {
            // do nothing
        }

        return $feeds;
    }

    /**
     * Returns the news feeds
     *
     * @return array
     */
    public function getNewsFeeds()
    {
        $feeds = [];
        if (!static::NEWS_FEED_URL) {
            return $feeds;
        }
        $reader = $this->getReader();

        $ctr = 1;
        try {
            foreach ($reader->import(static::NEWS_FEED_URL) as $feed) {
                $feeds[] = [
                    'link' => $feed->getLink(),
                    'title' => $feed->getTitle(),
                    'date' => substr($feed->getDateCreated(), 0, 10),
                    'content' => $feed->getContent(),
                ];
                if ($ctr == static::NUMBER_NEWS_FEEDS) {
                    break;
                }
                $ctr++;
            }
        } catch (Exception $e) {
            // do nothing
        }

        return $feeds;
    }
}
