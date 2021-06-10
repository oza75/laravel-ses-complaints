<?php


namespace Oza75\LaravelSesComplaints\Listeners;


use Illuminate\Contracts\Pipeline\Pipeline;
use Illuminate\Mail\Events\MessageSending;
use Oza75\LaravelSesComplaints\Contracts\LaravelSesComplaints as Contract;
use Oza75\LaravelSesComplaints\Utilities\CheckPipeline;

class CheckIsMessageShouldBeSend
{
    /** @var Contract $repository */
    protected $repository;

    /**
     * CheckIsMessageShouldBeSend constructor.
     * @param Contract $repository
     */
    public function __construct(Contract $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param MessageSending $event
     * @return mixed
     */
    public function handle(MessageSending $event)
    {
        if (!$this->repository->enabled()) {
            return true;
        }

        /** @var Pipeline $pipeline */
        $pipeline = app(CheckPipeline::class);

        return $pipeline
            ->send($event->message)
            ->through($this->repository->checkMiddlewares())
            ->thenReturn();
    }
}
