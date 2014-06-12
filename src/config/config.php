<?php 

return array(

	/*
	|--------------------------------------------------------------------------
	| Encoders
	|--------------------------------------------------------------------------
	|
	| You always respond in a certain format, or syntax. These are all the
	| registered encoders that can convert result into the format.
	| 
	| Note, the first encoder will be used as default.
	|
	*/

	'encoders' => array(
		'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder',
		'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
		'yaml' => '\Peakfijn\GetSomeRest\Encoders\YamlEncoder',
	),

);