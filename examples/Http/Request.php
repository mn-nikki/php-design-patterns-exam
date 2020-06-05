<?php declare(strict_types=1);

namespace Example\Http;

/**
 * Http Request object representation.
 */
class Request
{
    private ParameterBag $attributes;
    private ParameterBag $request;
    private ParameterBag $query;
    private ParameterBag $server;
    private ParameterBag $cookies;
    private ParameterBag $headers;
    private ?string $content;

    /**
     * Request constructor.
     *
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null  $content
     */
    public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null)
    {
        $this->init($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * @param array $query
     * @param array $request
     * @param array $attributes
     * @param array $cookies
     * @param array $files
     * @param array $server
     * @param null  $content
     */
    public function init(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null): void
    {
        $this->request = new ParameterBag($request);
        $this->attributes = new ParameterBag($attributes);
        $this->query = new ParameterBag($query);
        $this->server = new ParameterBag($server);
        $this->cookies = new ParameterBag($cookies);
        $this->headers = new ParameterBag($this->server->get('headers', []));
        $this->content = $content;
    }

    /**
     * Factory method.
     *
     * @return static
     */
    public static function create(): self
    {
        $request = new static($_GET, $_POST, [], $_COOKIE, $_FILES, $_SERVER);
        if (0 === \strpos($request->headers->get('CONTENT_TYPE', ''), 'application/x-www-form-urlencoded')
            && \in_array(strtoupper($request->server->get('REQUEST_METHOD', 'GET')), ['PUT', 'DELETE', 'PATCH'])
        ) {
            parse_str($request->getContent(), $data);
            $request->request = new ParameterBag($data);
        }

        return $request;
    }

    /**
     * @return false|string|null
     */
    public function getContent()
    {
        if ($this->content === null || $this->content === false) {
            $this->content = \file_get_contents('php://input');
        }

        return $this->content;
    }

    /**
     * Path without query and fragments.
     *
     * @return string
     */
    public function getPathInfo(): string
    {
        if (($requestUri = $this->getRequestUri()) === null) {
            return '/';
        }

        if (($qPos = \strpos($requestUri, '?')) !== false) {
            $requestUri = \substr($requestUri, 0, $qPos);
        }

        if ($requestUri !== '') {
            $requestUri = \sprintf('/%s', \trim($requestUri, '/'));
        }

        return $requestUri;
    }

    /**
     * Path and query.
     *
     * @return string|null
     */
    public function getRequestUri(): ?string
    {
        $requestUri = $this->server->get('REQUEST_URI');
        if ($requestUri === null) {
            return '/';
        }

        if (!empty($requestUri) && \strpos($requestUri, '/') === 0) {
            if (($pos = strpos($requestUri, '#')) !== false) {
                $requestUri = substr($requestUri, 0, $pos);
            }
        } else {
            $uriComponents = \parse_url($requestUri);
            if (($uriComponents['path'] ?? null) !== null) {
                $requestUri = $uriComponents['path'];
            }

            if (($uriComponents['query'] ?? null) !== null) {
                $requestUri .= sprintf('?%s', $uriComponents['query']);
            }
        }
        $this->server->set('REQUEST_URI', $requestUri);

        return $requestUri;
    }

    /**
     * @return ParameterBag
     */
    public function getAttributes(): ParameterBag
    {
        return $this->attributes;
    }

    /**
     * @return ParameterBag
     */
    public function getQuery(): ParameterBag
    {
        return $this->query;
    }

    /**
     * @return ParameterBag
     */
    public function getCookies(): ParameterBag
    {
        return $this->cookies;
    }
}
