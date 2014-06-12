<?php namespace Peakfijn\GetSomeRest\Encoders;

use SimpleXMLElement;
use Illuminate\Http\Request;
use Peakfijn\GetSomeRest\Contracts\Encoder;

class XmlEncoder extends Encoder {

	/**
	 * Get the encoded content type.
	 * 
	 * @return string
	 */
	public function getContentType()
	{
		return 'application/xml';
	}

	/**
	 * Get the encoded content.
	 *
	 * @param  mixed  $data
	 * @param  \Illuminate\Http\Request $request
	 * @return string
	 */
	public function getContent( $data, Request $request )
	{
		$data = $this->toArray($data);

		return $this->toXml($data);
	}

	/**
	 * The main function for converting to an XML document.
	 * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
	 *
	 * @see http://snipplr.com/view/3491/convert-php-array-to-xml-or-simple-xml-object-if-you-wish/
	 * @param  array $data
	 * @param  string $rootNodeName What you want the root node to be - defaultsto data.
	 * @param  SimpleXMLElement $xml Should only be used recursively
	 * @return string XML
	 */
	protected function toXml( array $data, $rootNodeName = 'root', SimpleXMLElement $xml = null )
	{
		// turn off compatibility mode as simple xml throws a wobbly if you don't.
		if( ini_get('zend.ze1_compatibility_mode') == 1 )
		{
			ini_set('zend.ze1_compatibility_mode', 0);
		}

		if( $xml == null )
		{
			$xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");
		}

		// loop through the data passed in.
		foreach( $data as $key => $value )
		{
			// no numeric keys in our xml please!
			if( is_numeric($key) )
			{
				// make string key...
				$key = "unknownNode_". (string) $key;
			}

			// replace anything not alpha numeric
			$key = preg_replace('/[^a-z]/i', '', $key);

			// if there is another array found recrusively call this function
			if( is_array($value) )
			{
				$node = $xml->addChild($key);
				// recrusive call.
				$this->toXml($value, $rootNodeName, $node);
			}
			else 
			{
				// add single node.
				$value = htmlentities($value);
				$xml->addChild($key,$value);
			}
		}

		// pass back as string. or simple xml object if you want!
		return $xml->asXML();
	}

}