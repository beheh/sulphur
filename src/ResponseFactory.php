<?php

namespace Sulphur;

/**
 * @author Benedict Etzel <developer@beheh.de>
 */
class ResponseFactory {

	/**
	 * Creates a response corresponding to the references from url.
	 * @param string $url
	 * @return \Sulphur\Response
	 */
	public static function fromUrl($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, __NAMESPACE__.'/1.0');
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$string = curl_exec($ch);
		curl_close($ch);

		return self::fromString($string);
	}

	/**
	 * Creates a response corresponding to the references passed via string.
	 * @param string $string
	 * @return \Sulphur\Response
	 */
	public static function fromString($string) {
		return new Response($string);
	}

}
