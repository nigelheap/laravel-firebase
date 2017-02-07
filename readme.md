# Laravel Firebase

This package is a laravel service provider for [Paragraph1/php-fcm](https://github.com/Paragraph1/php-fcm).

## Installation

```bash
composer require freelyformed/laravel-firebase @dev
```

## Usage

Add the service provider to the providers list in your `config/app.php`

```php
<?php return [

    // ...

    'providers' => [
        // ...
        Freelyformed\LaravelFirebase\FirebaseServiceProvider::class,
    ]

];
```


Publish and edit package configuration

```bash
php artisan vendor:publish --provider="Freelyformed\LaravelFirebase\FirebaseServiceProvider" --tag=config
```

Type hint php-fcm `Client` in your jobs or event listeners, here's a sample job that sends a notification to FCM when something happens

```php
<?php namespace App\Jobs;

use App\Something;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use paragraph1\phpFCM\Client;
use paragraph1\phpFCM\Message;
use paragraph1\phpFCM\Notification;
use paragraph1\phpFCM\Recipient\Device;

class SomethingHappened extends Job implements ShouldQueue {
    use InteractsWithQueue, SerializesModels;

    protected $thing;

    /**
     * Create a new job instance.
     *
     * @param Something $thing A thing instance
     */
    public function __construct(Something $thing) {
        $this->thing = $thing;
    }

    /**
     * Execute the job.
     * @param Client $client
     *
     * @return void
     */
    public function handle(Client $client) {
        $message = new Message();


        $message->addRecipient(new Device('device-token'));

        $notification = new Notification('Something happened', 'Something really did just happen');
        $notification
            ->setIcon('notification_icon_resource_name')
            ->setColor('#ffffff')
            ->setBadge(1);

        $message->setNotification($notification);
        $message->setData(['id' => $this->something->id]);

        $response = $client->send($message);
        // TODO: Handle response
    }
}
```