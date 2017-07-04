# Laravel Console Progress Bar
Laravel Console Progress Bar is a **Console based progress bar** library providing an easier and expressive way to show your current progresses.

## Requirements

- PHP >=5.6

## Installation

Composer is the recommended way to install this package.

```
composer require ms48/laravel-console-progress-bar
```

Once composer has installed the package add this line of code to the `providers` array located in your `config/app.php` file:
```php
Ms48\LaravelConsoleProgressBar\ConsoleProgressBarProvider::class,
```
Add this line to the `aliases` array:
```php
'ConsoleProgressBar' => Ms48\LaravelConsoleProgressBar\Facades\ConsoleProgressBar::class,
```

## Code Examples

```php
// calling the Facades
use ConsoleProgressBar;

// calling the progressbar
$limit = 20;
$total = Model::get()->count(); //get total recodes

//looping through the result array and show the progress bar
foreach($resultArr as $row)
{
  ConsoleProgressBar::showProgress($limit, $total);
}
```
Sample output
```
[=======>                       ] 24%  1700/7203 remaining: 45sec elapsed: 14sec
```

You can optionally add the progress bar size (default is 30)
```php
ConsoleProgressBar::showProgress($limit, $total, $size);

```
## License

Laravel Console Progress Bar is licensed under the [MIT License](http://opensource.org/licenses/MIT).

Copyright 2017 **Shanuka Dilshan**
