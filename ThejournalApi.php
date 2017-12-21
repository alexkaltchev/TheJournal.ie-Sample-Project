<?php

/**
 * ThejournalApi class
 */
class ThejournalApi
{
	private $username = 'sample';
	private $password = 'eferw5wr335£65';
	private $endpoint_thejournal = 'http://api.thejournal.ie/v3/sample/thejournal';
	private $endpoint_tag = 'http://api.thejournal.ie/v3/sample/tag';

	/**
	 * Executes the API request
	 *
	 * @param string $request The request
	 *
	 * @return array
	 */
	public function Execute($request) {
		$result=[];
		$response = $this->PerformRequest($request);
		$response = json_decode($response);
		if ($response) {
			foreach ($response->response->articles as $article) {
				$result[] = [
					'title' => $article->title,
					'excerpt' => $article->excerpt,
					'images' => $article->images,
					'type' => $article->type
				];
			}
		}
		return $result;
	}

	/**
	 * Initializes a cURL session
	 *
	 * @param string $request The request
	 *
	 * @return resource $ch cURL handle
	 */
	private function Init($request)
	{
		if (strlen($request)>1) {
			$url = $this->endpoint_tag . $request;
		} else {
			$url = $this->endpoint_thejournal;
		}
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_USERPWD, $this->username . ":" . $this->password);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
		curl_setopt($ch, CURLOPT_TIMEOUT, 15);
		curl_setopt($ch, CURLINFO_HEADER_OUT, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		return $ch;
	}

	/**
	 * Performs requests using cURL session
	 *
	 * @param string $request The request
	 * @param boolean $canRetry Specifies whether the request can be retried or not
	 *
	 * @return array|FALSE
	 */
	public function PerformRequest($request, $canRetry=TRUE)
	{
		$response = FALSE;
		$ch = $this->Init($request);
		if ($ch) {
			$responseBody = curl_exec($ch);
			$curlErrorNo = curl_errno($ch);
			$info = curl_getinfo($ch);
			curl_close($ch);
			if ( ($curlErrorNo === 0) && ($info['http_code'] === 200)) {
				$response = $responseBody;
			} else {
				if (($info['http_code'] === 429) && ($canRetry)) {
					sleep(5);
					return $this->PerformRequest($request, FALSE);
				}
			}
		}
		return $response;
	}
}
