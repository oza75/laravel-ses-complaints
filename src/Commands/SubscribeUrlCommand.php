<?php


namespace Oza75\LaravelSesComplaints\Commands;


use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Oza75\LaravelSesComplaints\LaravelSesComplaints;
use Oza75\LaravelSesComplaints\LaravelSesComplaintsServiceProvider;
use Oza75\LaravelSesComplaints\Models\Subscription;

class SubscribeUrlCommand extends Command
{
    protected $signature = 'aws:sns:subscribe-url';

    protected $description = "Show aws sns SubscribeURL that can be used to confirm subscription";


    public function handle(): int
    {
        $model = LaravelSesComplaints::subscriptionModel();

        if (!Schema::hasTable($model->getTable())) {
            $this->error("You need run migration first !");
            $this->comment("Please publish migrations by using artisan vendor:publish --provider=" . LaravelSesComplaintsServiceProvider::class . " --tags='migrations' first and run artisan migrate");
            return 1;
        }

        $topics = $model->newQuery()->select('topic_arn')->pluck('topic_arn')->values()->all();

        if (empty($topics)) {
            $this->error("There's no subscription confirmation request !");
            $this->comment("You need to request a confirmation before using artisan aws:sns:subscribe-url. More details on https://docs.aws.amazon.com/sns/latest/dg/SendMessageToHttp.confirm.html");
            return 1;
        }

        $topic = $this->choice("Select a topic", $topics, 0);

        $subscription = $model->newQuery()->where('topic_arn', $topic)->latest()->first();

        if (!$subscription) {
           throw new \Error("no subscription confirmation request found for this topic !");
        }

        $this->info("SubscribeURL: " . $subscription->subscribe_url);

        return 0;
    }
}
