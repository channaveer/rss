<?php
namespace Channaveer\RSS;

use DateTime;
use Exception;

class RSS
{
    /**
     * RSS Generation Variables
     *
     * @var $siteName [string] - Hold SiteName
     * @var $siteUrl [string] - Hold SiteURL
     * @var $description [text] - Description Of Website
     * @var $language [string] - Site Developed Language
     * @var $lastUpdated [DateTime] - LastUpdated DateTime
     */
    protected $siteName;
    protected $siteUrl;
    protected $description;
    protected $language 		= 'en-US';
    protected $lastUpdated;
    protected $filename 		= 'rss.xml';
    protected $path 			= '';
    protected $validateFields 	= ['siteName', 'siteUrl', 'description', 'lastUpdated'];
    

    public function rssDateTime($datetime)
    {
        return (new DateTime($datetime))->format(DateTime::RSS);
    }

    public function siteName($siteName)
    {
        $this->siteName = $siteName;
        return $this;
    }

    public function siteUrl($siteUrl)
    {
        $this->siteUrl = $siteUrl;
        return $this;
    }

    public function description($description)
    {
        $this->description = $description;
        return $this;
    }
    
    public function language($language)
    {
        $this->language = empty($language) ? $this->language : $language;
        return $this;
    }
    
    public function lastUpdated($lastUpdated)
    {
        $this->lastUpdated = $this->rssDateTime($lastUpdated);
        return $this;
    }

    public function setFileName($filename)
    {
        $this->filename = empty($filename) ? $this->filename : $filename;
    }

    public function validateFields()
    {
        foreach ($this->validateFields as $field) {
            if (empty($this->$field)) {
                throw new Exception($field . ' Needs To Be Set');
            }
        }
    }
    
    public function validateAndSetPath($path)
    {
        if (empty($path)) {
            throw new Exception('Path Not Found Exception');
        }
        if (!is_dir($path)) {
            throw new Exception('Given Path Is Not A Directory');
        }
        if (!is_writable($path)) {
            throw new Exception('The Directory Path Is Not Writable');
        }
        $this->path = $path;
    }

    public function generate($path, $rssItems, $filename = null)
    {
        /** Check if the basic fields are set or not */
        $this->validateFields();
        $this->validateAndSetPath($path);
        $this->setFileName($filename);
        $xml = '<?xml version="1.0" encoding="UTF-8" ?>'. PHP_EOL;
        $xml .= '<rss version="2.0" xmlns:content="http://purl.org/rss/1.0/modules/content/" xmlns:wfw="http://wellformedweb.org/CommentAPI/" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:sy="http://purl.org/rss/1.0/modules/syndication/" xmlns:slash="http://purl.org/rss/1.0/modules/slash/">'. PHP_EOL;
        $xml .= '	<channel>'. PHP_EOL;
        $xml .= '		<title>'. $this->siteName .'</title>'. PHP_EOL;
        $xml .= '		<link>'. $this->siteUrl .'</link>'. PHP_EOL;
        $xml .= '		<description><![CDATA['. $this->description .']]></description>'. PHP_EOL;
        $xml .= '		<language>'. $this->language. '</language>'. PHP_EOL;
        $xml .= '		<copyright>Copyright '. date('Y'). ' '. $this->siteName .'</copyright>'. PHP_EOL;
        $xml .= '		<lastBuildDate>'. $this->lastUpdated .'</lastBuildDate>'. PHP_EOL;
        $xml .= '		<atom:link href="'. $this->siteUrl .'" rel="self" type="application/rss+xml" />'. PHP_EOL;
        foreach ($rssItems as $item) {
            $xml .= '		<item>'. PHP_EOL;
            $xml .= '			<title>'. PHP_EOL;
            $xml .= '			<![CDATA['. $item["title"] .']]>'. PHP_EOL;
            $xml .= '			</title>'. PHP_EOL;
            $xml .= '			<link>'. $item["url"] .'</link>'. PHP_EOL;
            $xml .= '			<description>'. PHP_EOL;
            $xml .= '				<![CDATA['. $item["description"] .']]>'. PHP_EOL;
            $xml .= '			</description>'. PHP_EOL;
            $xml .= '			<pubDate>'. $this->rssDateTime($item["updatedAt"]) .'</pubDate>'. PHP_EOL;
            $xml .= '			<guid>'. $item["url"] .'</guid>'. PHP_EOL;
            $xml .= '		</item>'. PHP_EOL;
        }
        $xml .= '	</channel>'. PHP_EOL;
        $xml .= '</rss>'. PHP_EOL;
        file_put_contents($this->path.'/'. $this->filename, $xml);
        echo 'RSS Generated Successfully!';
    }
}
