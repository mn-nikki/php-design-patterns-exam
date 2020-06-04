<?php declare(strict_types=1);
/**
 * 04.06.2020.
 */

namespace Example\Http;

class Response
{
    public const HTTP_OK = 200;
    public const HTTP_BAD_REQUEST = 400;
    public const HTTP_NOT_FOUND = 404;
    public const HTTP_INTERNAL_SERVER_ERROR = 500;

    public static array $statusTexts = [
        200 => 'OK',
        400 => 'Bad Request',
        404 => 'Not Found',
        500 => 'Internal Server Error',
    ];

    private ParameterBag $headers;
    private string $content;
    private int $statusCode;
    private string $statusText;

    public function __construct(?string $content = '', int $status = 200, array $headers = [])
    {
        $this->setContent($content);
        $this->headers = new ParameterBag($headers);
        $this->setStatusCode($status);
    }

    /**
     * @return string|null
     */
    public function getContent(): ?string
    {
        return $this->content;
    }

    /**
     * @param string|null $content
     *
     * @return Response
     */
    public function setContent(?string $content): self
    {
        $this->content = $content ?? '';

        return $this;
    }

    /**
     * @param int  $code
     * @param null $text
     *
     * @return $this
     */
    public function setStatusCode(int $code, $text = null): self
    {
        $this->statusCode = $code;
        if ($text === null) {
            $this->statusText = self::$statusTexts[$code] ?? 'Unknown';
        }

        return $this;
    }

    public function sendHeaders(): self
    {
        if (\headers_sent()) {
            return $this;
        }
        foreach ($this->headers->all() as $header => $value) {
            $replace = (\stripos($header, 'Content-Type') === 0);
            \header(\sprintf('%s: %s', $header, $value), $replace, $this->statusCode);
        }

        \header(\sprintf('HTTP/1.1 %s %s', $this->statusCode, $this->statusText));

        return $this;
    }

    public function sendContent(): self
    {
        echo $this->content;

        return $this;
    }

    public function send(): self
    {
        $this->sendHeaders();
        $this->sendContent();

        return $this;
    }
}
