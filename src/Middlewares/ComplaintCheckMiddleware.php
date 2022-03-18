<?php


namespace Oza75\LaravelSesComplaints\Middlewares;


use Closure;
use Oza75\LaravelSesComplaints\Contracts\CheckMiddleware;
use Oza75\LaravelSesComplaints\Contracts\LaravelSesComplaints as Repository;
use Symfony\Component\Mime\Email;

class ComplaintCheckMiddleware implements CheckMiddleware
{
    private Repository $repository;

    /**
     * ComplaintCheckMiddleware constructor.
     * @param Repository $repository
     */
    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param Email   $message
     * @param Closure $next
     * @param array   $options
     *
     * @return mixed|bool
     */
    public function handle(Email $message, Closure $next, array $options = [])
    {
        $recipients = $this->shouldSendTo($message, $options);

        if (empty(array_keys($recipients))) {
            return false;
        }

        $message->to(...$recipients);

        return $next($message);
    }

    /**
     * @param Email $message
     * @param array $options
     *
     * @return array
     */
    protected function shouldSendTo(Email $message, array $options): array
    {
        $emails = collect($message->getTo())->map(fn ($to) => $to->getAddress())->all();
        $model = $this->repository->notificationModel();

        $query = $model->newQuery()
            ->selectRaw('destination_email, count(id) as n_entry')
            ->where('type', 'complaint')
            ->whereIn('destination_email', $emails);

        if ($options['check_by_subject'] ?? false) {
            $query->where('subject', $message->getSubject());
        }

        $entries = $query
            ->groupBy('destination_email')
            ->toBase()
            ->pluck('n_entry', 'destination_email');

        return collect($message->getTo())->filter(function ($to) use ($options, &$entries) {
            return (int)($entries[$to->getAddress()] ?? 0) < (int)($options['max_entries'] ?? 1);
        })->toArray();
    }
}
