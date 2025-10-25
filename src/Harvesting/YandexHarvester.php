<?php

declare(strict_types=1);

namespace App\Harvesting;

use App\Configuration\Contract\Config;
use App\Dto\Day;
use App\Dto\Location;
use App\Harvesting\Contract\Harvester;
use App\Http\Contract\HttpClient;
use DateInterval;
use DateMalformedStringException;
use DateTimeImmutable;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use RuntimeException;

final class YandexHarvester implements Harvester
{
    private const string BASE_URL = 'https://yandex.ru';
    private const string URL = self::BASE_URL . '/pogoda/ru/%s?lat=%s&lon=%s';
    private const string DAYS_XPATH
        = "//div[contains(@class, 'AppShortForecastDay_container__r4hyT')]"
        . "//*[self::a or self::span[contains(@class, 'AppShortForecastDay_temperature__DV3oM')]]";
    private Location $location;

    public function __construct(
        Config $config,
        private readonly HttpClient $httpClient,
    ) {
        $this->location = $config->getLocation();
    }

    /**
     * @return Day[]
     * @throws DateMalformedStringException
     */
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

    /**
     * @return Day[]
     * @throws DateMalformedStringException
     */
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
        $firstDayOfMonth = null;
        foreach ($nodes as $index => $node) {
            if ($index % 3 === 0) {
                if ($day !== null) {
                    $days[] = $day;
                }
                $day = new Day();
                if ($node->nodeName === 'a') {
                    $dayOfForecast = strtr(
                        preg_replace('/(\d+)/', ',$1', $node->textContent),
                        ['Сегодня' => ',' . date('d')],
                    );
                    [$dayOfWeek, $dayOfMonth] = explode(',', $dayOfForecast);
                    if ($firstDayOfMonth === null) {
                        $firstDayOfMonth = $dayOfMonth;
                    }
                    $date = new DateTimeImmutable(date("Y-m-$dayOfMonth"));

                    // Если дни стали меньше, значит, мы перевалили за границу месяца
                    if ($dayOfMonth < $firstDayOfMonth) {
                        $date = $date->add(DateInterval::createFromDateString('1 month'));
                    }
                    $day->setDate($date)
                        ->setDayOfWeek($dayOfWeek)
                        ->setLink(self::BASE_URL . $node->getAttribute('href'));
                }
            } elseif ($index % 3 === 1) {
                $day->getTemperature()->setMax($this->getTemperature($node->textContent));
            } elseif ($index % 3 === 2) {
                $day->getTemperature()->setMin($this->getTemperature($node->textContent));
            }
        }

        return $days;
    }

    private function getTemperature(string $temperatureString): float
    {
        // Блядский яндекс юзает юникодный минус: − = 0xE2 0x88 0x92 = U+2212
        return (float)preg_replace(
            '/[^0-9.-]/',
            '',
            strtr($temperatureString, ['−' => '-'])
        );
    }
}
