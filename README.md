## What is it?
**A Telegram bot that alerts you when the temperature drops too low**.  
For example, it can notify you when it’s time to change your tires.

## What weather sources does it use?
Currently, there are two sources: **Yandex Weather** (parsed from their website) and **Open-Meteo** (via API).  
If you live in Russia, Yandex Weather is usually the best choice.

## How to add other sources?
Just implement another harvester and register it in `config/container.php` for the **Harvester** interface.  
Alternatively, you can add another binary following the example of `alertOpenMeteo.php`.
