## What's it?
**The telegram bot to alert you about low environment temperature**.
For example, you may be alerted if you have to change your tires.

## What weather sources do it use?
For now there are two sources: Yandex Weather (by parsing their website) and OpenMeteo (by API).
If you're living in Russia, Yendex weather is your best choise.

## How to add other sources?
Just implement another harvester and put it into the `config/container` for ther Harverster interface.
Also you may just add another binary following the example of `alertOpenMeteo.php`
