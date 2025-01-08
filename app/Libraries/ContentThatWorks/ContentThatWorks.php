<?php

/**
* ContentThatWorks
* Manages import process for content that works
* Copyright: Aaron Connelly - Moonbase3
* VER: 0.1
**/

namespace App\Libraries\ContentThatWorks;

Class ContentThatWorks {

  public $feed_url = 'http://content.contentthatworks.com/rss/fdcsmhqx/homedaily.xml?limit=10';

  public function __construct($feed = null){
    if( !is_null($feed) ){
      $this->feed_url = $feed;
    }
  }

  /**
   * get
   * grabs the feed
   *
   * @return array result
   */
  public function get(){

    if ( !function_exists('curl_init') ){
      die('Sorry cURL is not installed!');
    }

    $output = file_get_contents($this->feed_url);
    if( !$output ){ return false; }

    $doc = new \DOMDocument;
    $doc->loadXML($output);

    # grab what we want out of this
    $items = $doc->getElementsByTagName('item');
    $articles = [];
    foreach( $items as $key => $item ){
      $articles[$key]['title'] = $this->getVal($item, 'title');
      $articles[$key]['summary'] = $this->getVal($item, 'description');
      $articles[$key]['pubDate'] = $this->getVal($item, 'pubDate');
      $articles[$key]['guid'] = $this->getVal($item, 'guid');
      $articles[$key]['link'] = $this->getVal($item, 'link');
      # lets grab the article content.
      $content = $this->getContent($articles[$key]['link']);
      $articles[$key]['content'] = $content['content'];
      $articles[$key]['images'] = $content['images'];
    }

    return $articles;
  }

  /**
   * getContent
   * helper function to grab the actual content of the article
   */
  function getContent($url){

    # need to grab HTMl and not the XML version
    $url_html = str_replace('.xml', '.html', $url);
    $output = file_get_contents($url_html);
    if( empty($output) ) return '';

    $output_xml = file_get_contents($url);
    $doc_thumb = new \DOMDocument;
    $doc_thumb->loadXML($output_xml);
    $images = [];

    # try to grab all images from XML doc they are not in a proper item collection so doing this funky
    for( $i = 0; $i < 5; $i++ ){
      $check = 'ArticleImage';
      if( $i > 0 ) $check = $check.$i;
      $image_url = $this->getVal($doc_thumb, $check);
      if( !empty($image_url ) ){
        $images[] = $image_url;
      }
    }

    $doc = new \DOMDocument;
    @$doc->loadHTML($output, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    if($title = $doc->getElementsByTagName('title')->item(0)) {
      $title->parentNode->removeChild($title);
    }

    # remove H1's
    foreach ($doc->getElementsByTagName('h1') as $node) {
      $node->parentNode->removeChild($node);
    }

    # remove H2's
    foreach ($doc->getElementsByTagName('h2') as $node) {
      $node->parentNode->removeChild($node);
    }

    $return = ['images' => $images, 'content' => $doc->saveHTML()];

    # little clean up
    $return['content'] = strip_tags($return['content'], '<p><a><br><img><strong><i><em><span>');

    # remove image width and heights
    $interm = preg_replace('/(<*[^>]*width=)"[^>]+"([^>]*>)/', '\1"" class="img-responsive"\2', $return['content']);
    $return['content'] = preg_replace('/(<*[^>]*height=)"[^>]+"([^>]*>)/', '\1"300"\2', $interm);

    return $return;
  }

  /**
   * getVal
   * helper function that pulls values out of the DOM
   *
   * @param object doc=m object
   * @param string field
   * @return html result
   */
  function getVal($doc, $field, $html = false){
    $field = $doc->getElementsByTagName($field);
    $return = '';
    foreach( $field as $val ){
      if( $html ){
        $return .= $this->outerHTML($val);
      }else{
        $return .= $val->nodeValue;
      }
    }
    return $return;
  }

  public function outerHTML($e) {
     $doc = new \DOMDocument;
     $doc->appendChild($doc->importNode($e, true));
     return $doc->saveHTML();
  }

}