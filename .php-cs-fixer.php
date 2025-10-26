<?php

declare(strict_types=1);

$finder = (new PhpCsFixer\Finder())
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/config',
        __DIR__ . '/bin',
//        __DIR__ . '/tests',
    ])
    ->name('*.php'); // Искать только .php файлы

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true) // Разрешаем "рискованные" правила. Необходимо для strict_types и др.
    ->setUsingCache(true)   // Включаем кеширование для ускорения последующих запусков.
    ->setRules([
        // --- БАЗОВЫЕ НАБОРЫ ПРАВИЛ ---
        '@PSR12' => true,                 // Строгое следование стандарту PSR-12.
//        '@PHP82Migration' => true,        // Правила для миграции на синтаксис PHP 8.2.


        // --- МОИ ОБЯЗАТЕЛЬНЫЕ ПРАВИЛА (сверх стандарта) ---

        // Массивы
        'array_syntax' => ['syntax' => 'short'], // Использовать короткий синтаксис [] для массивов.
        'no_multiline_whitespace_around_double_arrow' => true, // Нет пробелов вокруг => в массивах.
        'normalize_index_brace' => true, // $array[0] вместо $array [0].
        'trim_array_spaces' => true,    // Убирает пробелы в начале и конце массивов.

        // Импорты (use)
        'ordered_imports' => ['sort_algorithm' => 'alpha', 'imports_order' => ['const', 'class', 'function']], // Сортировать `use` по алфавиту.
        'no_unused_imports' => true,                       // Автоматически удалять неиспользуемые `use`.
        'single_line_after_imports' => true,               // Одна пустая строка после блока `use`.

        // Строгая типизация
        'declare_strict_types' => true, // Автоматически добавлять declare(strict_types=1);

        // Пробелы и отступы
        'method_argument_space' => [
            'on_multiline' => 'ensure_fully_multiline',
            'after_heredoc' => true,
        ],
        'no_extra_blank_lines' => true, // Убирает лишние пустые строки.
        'no_whitespace_in_blank_line' => true, // Убирает пробелы в пустых строках.
        'blank_line_after_opening_tag' => true, // Пустая строка после <?php при отсутствии declare.

        // Приведение типов и операторы
        'concat_space' => ['spacing' => 'one'], // Один пробел вокруг оператора конкатенации '.'.
        'operator_linebreak' => ['only_booleans' => true], // Перенос строк в логических операциях.

        // PHPDoc
        'phpdoc_align' => ['align' => 'vertical'], // Выравнивать параметры в PHPDoc.
        'phpdoc_scalar' => true,                  // Использовать `int` вместо `integer`, `bool` вместо `boolean`.
        'phpdoc_to_comment' => false,             // Не превращать PHPDoc в обычные комментарии.

        // Возвращаемые значения
        'void_return' => true, // Добавлять : void для функций, которые ничего не возвращают.

        // Прочее
        'ternary_operator_spaces' => true,      // Стандартные пробелы вокруг тернарного оператора.
    ])
    ->setFinder($finder);