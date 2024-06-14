<?php
namespace BazaarVoice;

use Exception;
use BazaarVoice\Elements\FeedElement;
use BazaarVoice\Elements\FeedElementInterface;
use phpseclib\Net\SFTP;
use SimpleXMLElement;

abstract class AbstractFeed implements FeedInterface
{
    /**
     * @var bool
     */
    protected $useStage = false;

    /**
     * @var bool
     */
    protected $shouldCompressFile = true;

    /**
     * @var string
     */
    protected $baseHost = 'sftp';

    /**
     * @var string
     */
    private $fileType = '.xml.gz';

    public function __construct()
    {
        return $this;
    }

    public function useStage(): self
    {
        $this->useStage = true;
        return $this;
    }

    public function useProduction(): self
    {
        $this->useStage = false;
        return $this;
    }

    public function newFeed(string $name, bool $incremental = false): FeedElementInterface
    {
        return new FeedElement($name, $incremental);
    }

    public function printFeed(FeedElementInterface $feed): string
    {
        if ($xml = $this->generateFeedXML($feed)) {
            $xmlString = $xml->asXML();
            $xmlString = str_replace(['<![CDATA[', ']]>'], '', $xmlString);

            return $xmlString;
        }

        throw new Exception('Unable to generate XML Feed');
    }

    public function saveFeed(FeedElementInterface $feed, string $directory, string $filename)
    {
        $feedXml = $this->printFeed($feed);
        $file = $this->shouldCompressFile ? gzencode($feedXml) : $feedXml;

        if (!$file) {
            throw new Exception('Unable to gzip XML file.');
        }

        $directory = rtrim($directory, '/\\');
        if (!is_dir($directory) || !is_writable($directory)) {
            throw new Exception('Directory isn\'t writable or is not a valid.');
        }

        $filePath = $directory.'/'.$filename.$this->fileType;

        if (file_put_contents($filePath, $file)) {
            return $filePath;
        }

        throw new Exception('Unable to save XML file on directory.');
    }

    public function sendFeed(string $filePath, string $sftpUsername, string $sftpPassword, string $sftpDirectory = 'import-inbox', string $sftpPort = '22'): bool
    {
        if (filesize($filePath) === 0) {
            throw new Exception('The file is empty.');
        }

        $filename = basename($filePath);

        $sftp = new SFTP($this->getHost(), $sftpPort);

        $sftpDirectory = rtrim($sftpDirectory, '/');
        $sftpDirectory = ltrim($sftpDirectory, '/');

        if ($sftp->login($sftpUsername, $sftpPassword)) {
            $rootDirectory = rtrim('/', $sftp->realpath('.'));
            $fullDirectoryPath = $rootDirectory.'/'.$sftpDirectory;
            $sftp->chdir($fullDirectoryPath);
            return $sftp->put($filename, file_get_contents($filePath, false));
        }

        throw new Exception('Failed to login to sFTP');
    }

    public function getHost()
    {
        $sftpHost = $this->baseHost;

        if ($this->useStage) {
            $sftpHost .= '-stg';
        }

        return $sftpHost.'.bazaarvoice.com';
    }

    public function setBaseHost(string $baseHost)
    {
        $this->baseHost = $baseHost;

        return $this;
    }

    public function withoutCompression()
    {
        $this->shouldCompressFile = false;
        $this->fileType = '.xml';

        return $this;
    }

    public function withCompression()
    {
        $this->shouldCompressFile = true;
        $this->fileType = '.xml.gz';

        return $this;
    }

    private function generateFeedXML(FeedElementInterface $feed)
    {
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><Feed></Feed>');
        $feedXml = $feed->generateXMLArray();
        $this->buildXML($xml, $feedXml);
        return $xml;
    }

    private function buildXML(SimpleXMLElement &$xml, array $element = []): void
    {
        if (empty($element)) {
            return;
        }

        $elementXml = $xml;
        if (isset($element['#name']) && !empty($element['#name'])) {
            $elementXml = $elementXml->addChild($element['#name'], ($element['#value'] ?? null));
        }

        if (isset($element['#attributes']) && !empty($element['#attributes'])) {
            foreach ($element['#attributes'] as $attribute => $value) {
                $elementXml->addAttribute($attribute, $value);
            }
        }

        if (isset($element['#children']) && !empty($element['#children'])) {
            foreach ($element['#children'] as $child) {
                $this->buildXML($elementXml, $child);
            }
        }
    }
}
