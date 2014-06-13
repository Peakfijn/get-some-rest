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
		'json' => '\Peakfijn\GetSomeRest\Encoders\JsonEncoder',
		'yaml' => '\Peakfijn\GetSomeRest\Encoders\YamlEncoder',
		'xml'  => '\Peakfijn\GetSomeRest\Encoders\XmlEncoder',
	),

	/*
	|--------------------------------------------------------------------------
	| Extension Regex
	|--------------------------------------------------------------------------
	|
	| All API routes has an optional suffixed to define the response format.
	| This is the regex to match the pattern.
	|
	*/

	'extension' => '\.[A-Za-z]+',

	/*
	|--------------------------------------------------------------------------
	| Fail on Unknown Extension
	|--------------------------------------------------------------------------
	|
	| When the user defines an unsupported format, we can throw with errors.
	| Also we can just use the default response format, your choise...
	|
	*/

	'fail_on_unknown_extension' => true,

	/*
	|--------------------------------------------------------------------------
	| Extensions (Aliases)
	|--------------------------------------------------------------------------
	|
	| When using the extension for response formats, that extension must match
	| the name of the encoder. But sometimes there are multiple extensions 
	| allowed for a format. Here you can specify "aliases" for these formats.
	|
	*/

	'extensions' => array(
	 	'yml' => 'yaml',
	),

);