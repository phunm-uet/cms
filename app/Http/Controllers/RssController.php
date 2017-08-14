<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\SimpleXMLExtended;
use DB;
class RssController extends Controller
{
	const URL_HOME = "http://test.dev/";


  public function addChildWithCDATA($name, $value = NULL) {
    $new_child = $this->addChild($name);

    if ($new_child !== NULL) {
      $node = dom_import_simplexml($new_child);
      $no   = $node->ownerDocument;
      $node->appendChild($no->createCDATASection($value));
    }

    return $new_child;
  }

    public function show_rss(){
    	$rss = new SimpleXMLExtended('<rss xmlns:content="http://purl.org/rss/1.0/modules/content/"></rss>');
		$rss->addAttribute('version', '2.0');
		$channel = $rss->addChild('channel'); //add channel node

		$title = $channel->addChild('title','Sanwebe'); 
		$description = $channel->addChild('description','description line goes here'); //feed description
		$link = $channel->addChild('link','http://www.sanwebe.com'); //feed site
		$language = $channel->addChild('language','en-us'); //language
		$date_f = date("Y-m-d\TH:i:s\Z", time());
		$build_date = gmdate(DATE_ISO8601, strtotime($date_f)); 
		$lastBuildDate = $channel->addChild('lastBuildDate',$date_f); //feed last build date
		$posts = DB::table('posts')->get();
		foreach ($posts as $post) {
			$item = $channel->addChild('item');
			$item->addChild('title',$post->name);
			$item->addChild('link',self::URL_HOME.$post->slug);
			$item->addChild('guid',$post->slug);
			$item->addChild('guid',$post->slug);
			$publish = $post->updated_at;
			$contentEncode = $item->addChild('content:content:encoded');
			$publish_time = str_replace(" ","T",$publish).'Z';
			$item->addChild('pubDate',$publish_time);
			$item->addChild('author','Admin');
			$contentEncode->addChildWithCDATA("",$post->content);
		}
		$rss = $rss->asXML();  	
    	return response($rss)->header('Content-Type', 'text/xml');
    }
}
