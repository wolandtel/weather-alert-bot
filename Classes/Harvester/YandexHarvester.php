<?php

declare(strict_types=1);

namespace Classes\Harvester;

use Classes\Dto\Day;
use Classes\Dto\Location;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Interfaces\Harvester;
use Interfaces\HttpClient;
use RuntimeException;

final class YandexHarvester implements Harvester
{
    private const string BASE_URL = 'https://yandex.ru';
    private const string URL = self::BASE_URL . '/pogoda/ru/%s?lat=%s&lon=%s';
    private const string DAYS_XPATH
        = "//div[contains(@class, 'AppShortForecastDay_container__r4hyT')]"
            . "//*[self::a or self::span[contains(@class, 'AppShortForecastDay_temperature__DV3oM')]]";
    private ?Location $location = null;

    public function __construct(private readonly HttpClient $httpClient)
    {
    }

    public function setLocation(Location $location): self
    {
        $this->location = $location;
        return $this;
    }

    /** @return Day[] */
    public function getTemperatureData(): array
    {
        return $this->parseHtmlPage($this->getHtmlPage());
    }

    private function getHtmlPage(): string
    {
        return $this->httpClient->get(sprintf(
            self::URL,
            $this->location->getName(),
            $this->location->getLatitude(),
            $this->location->getLongitude(),
        ));
    }

    /** @return Day[] */
    private function parseHtmlPage(string $html): array
    {
        $doc = new DOMDocument();
        libxml_use_internal_errors(true); // подавляем ворнинги HTML5
        $doc->loadHTML($html);
        libxml_clear_errors();

        // XPath-запрос
        $xpath = new DOMXPath($doc);

        $nodes = $xpath->query(self::DAYS_XPATH);

        if (!($nodes instanceof DOMNodeList) || $nodes->length === 0) {
            throw new RuntimeException('Error obtaining temperature data.');
        }

        $days = [];
        $day = null;
        foreach ($nodes as $index => $node)
        {
            if ($index % 3 === 0) {
                if ($day !== null) {
                    $days[] = $day;
                }
                $day = new Day();
                if ($node->nodeName === 'a') {
                    $date = strtr(
                        preg_replace('/(\d+)/', ', $1', $node->textContent),
                        ['Сегодня' => ', ' . date('d')],
                    );
                    $day->setDate($date . date('.m.Y'));
                    $day->setLink(self::BASE_URL . $node->getAttribute('href'));
                }
            } elseif ($index % 3 === 2) {
                $day->setTemperature((float)preg_replace(
                    '/[^0-9.-]/',
                    '',
                    $node->textContent
                ));
            }
        }

        return $days;
    }
}

