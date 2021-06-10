<?php


namespace Oza75\LaravelSesComplaints\Tests;


use Illuminate\Database\Schema\Blueprint;
use Oza75\LaravelSesComplaints\LaravelSesComplaintsServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase($this->app);
    }

    protected function getPackageProviders($app): array
    {
        return [
            LaravelSesComplaintsServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // perform environment setup
        config()->set('database.default', 'sqlite');
        config()->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        config()->set('mail.default', 'array');

    }

    protected function setUpDatabase($app)
    {
        $app['db']->connection()->getSchemaBuilder()->create('test_users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('email');
            $table->timestamps();
        });

        $files =  glob(__DIR__ . '/../database/migrations/*.php');
        foreach ($files as $file) {
            include_once $file;
        }

        (new \CreateSesNotificationsTable())->up();
        (new \CreateSnsSubscriptionsTable())->up();
    }
}
