<?php

if (!empty($_ENV['SENTRY_URL'])) {
    $scrubSentryRequest = static function (\Sentry\Event $event): \Sentry\Event {
        $request = $event->getRequest();
        $safeRequest = [];
        if (isset($request['method']) && is_string($request['method'])) {
            $safeRequest['method'] = $request['method'];
        }
        $event->setRequest($safeRequest);

        $transaction = $event->getTransaction();
        if (is_string($transaction)) {
            if (preg_match('/\A[A-Za-z0-9]{5}\z/D', trim($transaction, '/')) === 1) {
                $transaction = '/[clip]';
            } else {
                $transaction = preg_replace(
                    '#/[A-Za-z0-9]{5}/?\z#D',
                    '/[clip]',
                    $transaction
                );
                $transaction = preg_replace('/([?&]code=)[^&]*/i', '$1[Filtered]', (string) $transaction);
            }
            $event->setTransaction($transaction);
        }

        return $event;
    };

    $sentryOptions = [
        'dsn' => $_ENV['SENTRY_URL'],
        'traces_sample_rate' => min(1.0, max(0.0, (float) ($_ENV['TRACES_SAMPLE_RATE'] ?? 0.0))),
        'send_default_pii' => false,
        'max_request_body_size' => 'none',
        'before_send' => $scrubSentryRequest,
        'before_send_transaction' => $scrubSentryRequest,
    ];
    $sentryRelease = trim((string) ($_ENV['APP_COMMIT'] ?? ''));
    if ($sentryRelease !== '') {
        $sentryOptions['release'] = $sentryRelease;
    }

    \Sentry\init($sentryOptions);
}
