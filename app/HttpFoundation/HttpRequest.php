<?php
namespace App\HttpFoundation;


use CURLFile;

class HttpRequest
{

    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PUT = 'PUT';
    const METHOD_DELETE = 'DELETE';
    const METHOD_PATCH = 'PATCH';
    const METHOD_HEAD = 'HEAD';

    private $postFields;
    private $postFiles;
    private $queryFields;
    private $rawData;

    private $httpHeaders;
    private $method;
    private $url;

    private $baseAuthUsername;
    private $baseAuthPassword;

    private $lastRequestStatus;

    /**
     * @return mixed
     */
    public function getLastRequestStatus()
    {
        return $this->lastRequestStatus;
    }


    public function __construct ($url = null, $requestMethod = null) {
        $this->url = $url;
        $this->method = $requestMethod;
        $this->init();
    }

    private function init(){
        $this->postFields = array();
        $this->postFiles = array();
        $this->queryFields = array();
        $this->rawData = null;
        $this->httpHeaders = array();
        $this->method = null;
    }

    public function setPostFields (array $postFields) {
        $this->postFields = $postFields;
    }

    public function addPostField ($field, $value ) {
        $this->postFields[$field] = $value;
    }

    public function setPostFiles (array $postFiles ) {
        $this->postFiles = $postFiles;
    }

    public function addPostFile($key, $value){
        $this->postFiles[$key] = $value;
    }

    public function setUrl ($url) {
        $this->url = $url;
    }

    public function setMethod ($requestMethod) {
        $this->method = $requestMethod;
    }

    public function setQueryFields($queryFields)
    {
        $this->queryFields = $queryFields;
    }

    public function addQueryField($key, $value){
        $this->queryFields[$key] = $value;
    }

    public function setHttpHeaders(array $httpHeaders){
        $this->httpHeaders = $httpHeaders;
    }

    public function addHttpHeader($key, $value){
        $this->httpHeaders[$key] = $value;
    }

    public function setBaseAuth($username, $password){
        $this->baseAuthUsername = $username;
        $this->baseAuthPassword = $password;
    }

    /**
     * Notice: if you set any raw data there would be no post fields in the request.
     * @param string $rawData
     */
    public function setRawData($rawData){
        $this->rawData = $rawData;
    }
    
    /**
     * Get rawData
     */
    public function getRawData() {
       return $this->rawData;
    }

    /**
     * @param bool $initialAfterSent clear current request object after the request sent.
     * @return mixed
     */
    public function send($initialAfterSent = true){
        if($this->queryFields){
            $queryFields = array();
            foreach($this->queryFields as $k => $v){
                $queryFields[] = $k . '=' . $v;
            }
            $queryFields = implode('&', $queryFields);
            $this->url .= (strpos($this->url, '?') ? '&' : '?') . $queryFields;
        }

        $ch = curl_init($this->url);

        $this->setRequestMethod($ch);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);


        if($this->rawData)
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->rawData);
        else{
            if($this->postFiles)
                foreach($this->postFiles as $k => $file){
                    if(!($file instanceof CURLFile))
                        $file = new CURLFile($file, mime_content_type($file), basename($file));

                    $this->postFields[$k] = $file;
                }

            if($this->postFields)
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postFields);
        }

        if($this->isSSL()){
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        }

        if($this->httpHeaders){
            $headers = array();
            foreach($this->httpHeaders as $key => $value){
                $headers[] = "$key: $value";
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if($this->baseAuthUsername){
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
            curl_setopt($ch, CURLOPT_USERPWD, "$this->baseAuthUsername:$this->baseAuthPassword");
        }

        $result = curl_exec($ch);
        $this->lastRequestStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);

        if($initialAfterSent)
            $this->init();

        return $result;
    }

    /**
     * @param resource $ch cURL handler
     */
    private function setRequestMethod($ch){
        if(!$this->method || !in_array($this->method, array(
                HttpRequest::METHOD_POST,
                HttpRequest::METHOD_GET,
                HttpRequest::METHOD_PUT,
                HttpRequest::METHOD_DELETE,
                HttpRequest::METHOD_PATCH,
                HttpRequest::METHOD_HEAD)))
            $this->method = HttpRequest::METHOD_POST;

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-HTTP-Method-Override: ' . $this->method));
    }

    private function isSSL(){
        return substr($this->url, 0, 8) == "https://";
    }

}