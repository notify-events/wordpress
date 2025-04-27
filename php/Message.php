<?php

namespace notify_events\php;

use ErrorException;
use InvalidArgumentException;

/**
 * Class Message
 * @package notify_events\php_send
 */
class Message
{
    const CRLF = "\r\n";

    const PRIORITY_LOWEST  = 'lowest';
    const PRIORITY_LOW     = 'low';
    const PRIORITY_NORMAL  = 'normal';
    const PRIORITY_HIGH    = 'high';
    const PRIORITY_HIGHEST = 'highest';

    const LEVEL_VERBOSE = 'verbose';
    const LEVEL_INFO    = 'info';
    const LEVEL_NOTICE  = 'notice';
    const LEVEL_WARNING = 'warning';
    const LEVEL_ERROR   = 'error';
    const LEVEL_SUCCESS = 'success';

    const CALLBACK_METHOD_HEAD   = 'head';
    const CALLBACK_METHOD_GET    = 'get';
    const CALLBACK_METHOD_POST   = 'post';
    const CALLBACK_METHOD_PUT    = 'put';
    const CALLBACK_METHOD_PATCH  = 'patch';
    const CALLBACK_METHOD_DELETE = 'delete';

    /** @var string */
    protected static $_baseUrl = 'https://notify.events/api/v1/channel/source/%s/execute';

    /** @var string */
    protected $_title;
    /** @var string */
    protected $_content;
    /** @var string */
    protected $_priority;
    /** @var string */
    protected $_level;

    CONST FILE_TYPE_FILE    = 'file';
    CONST FILE_TYPE_CONTENT = 'content';
    CONST FILE_TYPE_URL     = 'url';

    /** @var array */
    protected $_files = [];
    /** @var array */
    protected $_images = [];
    /** @var array */
    protected $_actions = [];

    /**
     * Message constructor.
     *
     * @param string $content  Message text
     * @param string $title    Message title
     * @param string $priority Priority
     * @param string $level    Level
     */
    public function __construct($content = '', $title = '', $priority = self::PRIORITY_NORMAL, $level = self::LEVEL_INFO)
    {
        $this
            ->setTitle($title)
            ->setContent($content)
            ->setPriority($priority)
            ->setLevel($level);
    }

    /**
     * Prepares a param to sending.
     *
     * @param string $boundary
     * @param string $name
     * @param string $content
     * @return string
     */
    protected static function prepareParam($boundary, $name, $content)
    {
        return self::prepareBoundaryPart($boundary, $content, [
            'Content-Disposition' => 'form-data; name="' . $name . '"',
            'Content-Type'        => 'text/plain; charset=utf-8',
        ]);
    }

    /**
     * Prepares a boundary param part for file.
     *
     * @param string $boundary
     * @param string $name
     * @param array  $files
     * @return string
     * @throws ErrorException
     */
    protected static function boundaryFiles($boundary, $name, $files)
    {
        $result = '';

        foreach ($files as $idx => $file) {
            switch ($file['type']) {
                case self::FILE_TYPE_FILE: {
                    $content  = file_get_contents($file['fileName']);
                    $fileName = basename($file['fileName']);
                    $mimeType = !empty($file['mimeType']) ? $file['mimeType'] : (extension_loaded('fileinfo') ? mime_content_type($file['fileName']) : 'application/octet-stream');
                } break;
                case self::FILE_TYPE_CONTENT: {
                    $content  = $file['content'];
                    $fileName = !empty($file['fileName']) ? $file['fileName'] : 'file.dat';
                    $mimeType = !empty($file['mimeType']) ? $file['mimeType'] : 'application/octet-stream';
                } break;
                case self::FILE_TYPE_URL: {
                    $content  = file_get_contents($file['url']);
                    $fileName = !empty($file['fileName']) ? $file['fileName'] : basename($file['url']);
                    $mimeType = !empty($file['mimeType']) ? $file['mimeType'] : 'application/octet-stream';
                } break;
                default: {
                    throw new ErrorException('Unknown file type');
                }
            }

            $result .= self::prepareBoundaryPart($boundary, $content, [
                'Content-Disposition' => 'form-data; name="' . $name . '[' . $idx . ']"; filename="' . $fileName . '"',
                'Content-Type'        => $mimeType,
            ]);
        }

        return $result;
    }

    /**
     * Prepares a boundary param part.
     *
     * @param string   $boundary
     * @param string   $content
     * @param string[] $headers
     * @return string
     */
    protected static function prepareBoundaryPart($boundary, $content, $headers)
    {
        $headers['Content-Length'] = strlen($content);

        $result = '--' . $boundary . self::CRLF;

        foreach ($headers as $key => $value) {
            $result .= $key . ': ' . $value . self::CRLF;
        }

        $result .= self::CRLF;
        $result .= $content . self::CRLF;

        return $result;
    }

    /**
     * @return bool
     */
    protected static function capabilityCheckFOpen()
    {
        return ini_get('allow_url_fopen') == '1';
    }

    /**
     * @return bool
     */
    protected static function capabilityCheckCurl()
    {
        return function_exists('curl_init');
    }

    /**
     * Capability check.
     *
     * @return bool
     */
    public static function capabilityCheck()
    {
        return static::capabilityCheckFOpen() || static::capabilityCheckCurl();
    }

    /**
     * Sends the message to the specified channel.
     * You can get the source token when connecting the PHP source
     * to your channel on the Notify.Events service side.
     *
     * @param string $token Source token
     * @return void
     * @throws ErrorException
     */
    public function send($token)
    {
        $url = sprintf(self::$_baseUrl, $token);

        $boundary = uniqid();

        $content = '';

        if (!empty($this->_title)) {
            $content .= self::prepareParam($boundary, 'title', $this->_title);
        }

        $content .= self::prepareParam($boundary, 'content', $this->_content);
        $content .= self::prepareParam($boundary, 'priority', $this->_priority);
        $content .= self::prepareParam($boundary, 'level', $this->_level);

        $content .= self::boundaryFiles($boundary, 'files', $this->_files);
        $content .= self::boundaryFiles($boundary, 'images', $this->_images);

        $content .= '--' . $boundary . '--' . self::CRLF;

        if (static::capabilityCheckFOpen()) {

            $context = stream_context_create([
                'http' => [
                    'method' => 'POST',
                    'header' =>
                        'Content-Type: multipart/form-data; boundary="' . $boundary . '"' . self::CRLF .
                        'Content-Length: ' . strlen($content) . self::CRLF,
                    'content' => $content,
                ],
            ]);

            file_get_contents($url, false, $context);

        } elseif (static::capabilityCheckCurl()) {

            static $curl;

            if ($curl === null) {
                $curl = curl_init();
            }

            curl_setopt_array($curl, [
                CURLOPT_URL => $url,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: multipart/form-data; boundary="' . $boundary . '"',
                    'Content-Length: ' . strlen($content),
                ],
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $content,
            ]);

            curl_exec($curl);

        } else {
            throw new ErrorException('Can\'t detect capability method for sending!');
        }
    }

    /**
     * Sets the value of the Title property.
     *
     * @param string $title Message title
     * @return $this
     */
    public function setTitle($title)
    {
        $this->_title = $title;

        return $this;
    }

    /**
     * Returns the value of the Title property.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the value of the Content property.
     *
     * @param string $content Message content
     * @return $this
     */
    public function setContent($content)
    {
        $this->_content = $content;

        return $this;
    }

    /**
     * Returns the value of the Content property.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->_content;
    }

    /**
     * Sets the value of the Priority property.
     * For recipients which supports priority, the message will be highlighted accordingly.
     * This method checks that $priority is in the list of available message priorities.
     *
     * @param string $priority Message priority
     * @return $this
     */
    public function setPriority($priority)
    {
        if (!in_array($priority, [
            self::PRIORITY_LOWEST,
            self::PRIORITY_LOW,
            self::PRIORITY_NORMAL,
            self::PRIORITY_HIGH,
            self::PRIORITY_HIGHEST,
        ])) {
            throw new InvalidArgumentException('Invalid priority value');
        }

        $this->_priority = $priority;

        return $this;
    }

    /**
     * Returns the value of the Priority property.
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->_priority;
    }

    /**
     * Sets the value of the Level property.
     * This method checks that $level is in the list of available message levels.
     * For recipients which have differences in the display of messages at different levels, this level will be applied.
     *
     * @param string $level Message Level
     * @return $this
     */
    public function setLevel($level)
    {
        if (!in_array($level, [
            self::LEVEL_VERBOSE,
            self::LEVEL_INFO,
            self::LEVEL_NOTICE,
            self::LEVEL_WARNING,
            self::LEVEL_ERROR,
            self::LEVEL_SUCCESS,
        ])) {
            throw new InvalidArgumentException('Invalid level value');
        }

        $this->_level = $level;

        return $this;
    }

    /**
     * Returns the value of the Level property.
     *
     * @return string
     */
    public function getLevel()
    {
        return $this->_level;
    }

    /**
     * Adds a new File by local file path
     * to the message attached files list.
     *
     * @param string      $filePath Local file path
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addFile($filePath, $fileName = null, $mimeType = null)
    {
        $this->_files[] = [
            'type'     => self::FILE_TYPE_FILE,
            'fileName' => $fileName ?: basename($filePath),
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * Adds a new File by content
     * to the message attached files list.
     *
     * @param string      $content  File content
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addFileFromContent($content, $fileName = null, $mimeType = null)
    {
        $this->_files[] = [
            'type'     => self::FILE_TYPE_CONTENT,
            'content'  => $content,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * Adds a new File by URL
     * to the message attached files list.
     *
     * @param string      $url      File remote URL
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addFileFromUrl($url, $fileName = null, $mimeType = null)
    {
        $this->_files[] = [
            'type'     => self::FILE_TYPE_URL,
            'url'      => $url,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * Adds a new Image by filename
     * to the message attached images list.
     *
     * @param string      $filePath Local file path
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addImage($filePath, $fileName = null, $mimeType = null)
    {
        $this->_images[] = [
            'type'     => self::FILE_TYPE_FILE,
            'fileName' => $fileName ?: basename($filePath),
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * Adds a new Image by content
     * to the message attached images list.
     *
     * @param string      $content  File content
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addImageFromContent($content, $fileName = null, $mimeType = null)
    {
        $this->_images[] = [
            'type'     => self::FILE_TYPE_CONTENT,
            'content'  => $content,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * Adds a new Image by URL
     * to the message attached images list.
     *
     * @param string      $url      File remote URL
     * @param string|null $fileName Attachment file name
     * @param string|null $mimeType Attachment file MimeType
     * @return $this
     */
    public function addImageFromUrl($url, $fileName = null, $mimeType = null)
    {
        $this->_images[] = [
            'type'     => self::FILE_TYPE_URL,
            'url'      => $url,
            'fileName' => $fileName,
            'mimeType' => $mimeType,
        ];

        return $this;
    }

    /**
     * @param string $name
     * @param string $title
     * @param string|null $callback_url
     * @param string $callback_method
     * @param array $callback_headers
     * @param string $callback_content
     * @return $this
     */
    public function addAction($name, $title, $callback_url = null, $callback_method = 'get', $callback_headers = [], $callback_content = '')
    {
        if (!in_array($callback_method, [
            self::CALLBACK_METHOD_HEAD,
            self::CALLBACK_METHOD_GET,
            self::CALLBACK_METHOD_POST,
            self::CALLBACK_METHOD_PUT,
            self::CALLBACK_METHOD_PATCH,
            self::CALLBACK_METHOD_DELETE,
        ])) {
            throw new InvalidArgumentException('Invalid callback_method value');
        }

        $this->_actions[] = [
            'name'             => $name,
            'title'            => $title,
            'callback_url'     => $callback_url,
            'callback_method'  => $callback_method,
            'callback_headers' => $callback_headers,
            'callback_content' => $callback_content,
        ];

        return $this;
    }
}
