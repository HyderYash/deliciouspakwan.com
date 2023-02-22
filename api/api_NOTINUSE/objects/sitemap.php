<?php
include_once '../config/database.php';
define('XML_VERSION','<?xml version="1.0" encoding="UTF-8"?>');
define('SITEMAP_PROTOCOL_VERSION','https://www.sitemaps.org/schemas/sitemap/0.9');
define('SITEMAP_XML_FILE_LOCATION', HTDOCS_PATH . '/sitemap.xml');
class Sitemap{
 
    // database connection and table name
    private $db;
    // constructor with $db as database connection
    public function __construct(){
        // instantiate database and settings object
		$this->db = new DB();
    }
	// read products with pagination
	public function updateSitemapXML(){
		$sitemapContentArr = $this->getSitemapContent();
		return $this->displaySitemap($sitemapContentArr);
	}	
	function getSitemapContent (){
		$sql = "SELECT * FROM `dp_yt_sitemap_urls`"; //die;
		$result = $this->db->get_multiple_tables_records($sql);
		return $result;	
	}
    function displaySitemap($sitemapContentArr) {
		// Everything for owner, read and execute for everybody else
		if (file_exists(SITEMAP_XML_FILE_LOCATION)) {
			chmod(SITEMAP_XML_FILE_LOCATION,0755);
			unlink(SITEMAP_XML_FILE_LOCATION);
		}		
        $this->writeSitemapXmlFile($sitemapContentArr);
        if (file_exists(SITEMAP_XML_FILE_LOCATION)) {
           $sitemapCreated = file_get_contents(SITEMAP_XML_FILE_LOCATION);
		   return 'Sitemap.xml file created successfully.';
        }
		return 'Failed';
    }

	function writeSitemapXmlFile ($sitemapContentArr){
		if (!file_exists(SITEMAP_XML_FILE_LOCATION)) {
			$xmlstr = $this->_sitemapIndexXML();
			$xml = new SimpleXMLElement($xmlstr);
		} else {
			$xml = simplexml_load_file(SITEMAP_XML_FILE_LOCATION);
		}
		
		foreach ($sitemapContentArr as $sitemapUrl) {
			$url = $xml->addChild('url');
			$url->addChild('loc', BASEURL . $sitemapUrl['SITE_URL']);
			$url->addChild('lastmod',$sitemapUrl['LAST_UPDATED']);
			$url->addChild('changefreq', 'monthly');
			$url->addChild('priority', '0.5');
		}
		
		// Adding updated content in xml file
		$fp = fopen(SITEMAP_XML_FILE_LOCATION, 'w');
		fwrite($fp, $xml->asXml());	
		fclose($fp);		
	}
    /**
     * Returns an XML string for the sitemap index
     */
    function _sitemapIndexXML() {
        $xmlstr = XML_VERSION;
        $xmlstr .= "\n" . '<urlset xmlns="' . SITEMAP_PROTOCOL_VERSION . '">';
        $xmlstr .= "\n" . '</urlset>';
        return $xmlstr;
    }	
}