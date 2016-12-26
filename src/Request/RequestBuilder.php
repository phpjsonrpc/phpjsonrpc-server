<?php

namespace PhpJsonRpc\Server\Request;

use PhpJsonRpc\Server\Error\ParseError;
use PhpJsonRpc\Server\Error\InvalidRequest;
use JsonSchema\Validator as JsonSchemaValidator;

class RequestBuilder
{
    const JSON_SCHEMA_PATH = '/../../jsonschemas/request.json';

    /**
     * @var mixed should be object or array
     */
    protected $decodedJson;

    /**
     * @param string $jsonRpcMessage
     *
     * @throws InvalidRequest
     * @throws ParseError
     */
    public function __construct($jsonRpcMessage)
    {
        $this->decodedJson  = json_decode($jsonRpcMessage);
        $jsonLastError      = json_last_error();

        if ($jsonLastError !== JSON_ERROR_NONE) {
            throw new ParseError($this->buildJsonErrorMessage($jsonLastError));
        }

        if ($this->isBatchRequest() && !$this->decodedJson()) {
            throw new InvalidRequest;
        }
    }

    /**
     * @return bool
     */
    public function isBatchRequest()
    {
        return is_array($this->decodedJson);
    }

    /**
     * @return mixed should be object or array
     */
    public function decodedJson()
    {
        return $this->decodedJson;
    }

    /**
     * @param object $jsonObject
     *
     * @return AbstractRequest NotificationRequest|Request
     *
     * @throws InvalidRequest
     */
    public function buildRequest($jsonObject)
    {
        $jsonSchemaValidator = $this->assembleJsonSchemaValidator($jsonObject);

        if ($jsonSchemaValidator->isValid() === false) {
            throw new InvalidRequest($jsonSchemaValidator->getErrors());
        }

        if (property_exists($jsonObject, 'id')) {
            return new Request($jsonObject);
        }

        return new NotificationRequest($jsonObject);
    }

    /**
     * @param mixed $jsonDecodedMessage
     *
     * @return JsonSchemaValidator
     */
    protected function assembleJsonSchemaValidator($jsonDecodedMessage)
    {
        $validator = new JsonSchemaValidator;

        $validator->check($jsonDecodedMessage, (object)['$ref' => 'file://' . __DIR__ . self::JSON_SCHEMA_PATH]);

        return $validator;
    }

    /**
     * @param int $jsonLastError
     *
     * @return string
     */
    protected function buildJsonErrorMessage($jsonLastError)
    {
        $message = 'Unknown error';

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                $message = 'No errors';
                break;
            case JSON_ERROR_DEPTH:
                $message = 'Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $message = 'Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $message = 'Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $message = 'Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
        }

        return $message;
    }
}
