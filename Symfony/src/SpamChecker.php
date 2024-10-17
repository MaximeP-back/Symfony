<?php

namespace App;

use App\Entity\Comment;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SpamChecker
{
    private $endpoint;

    private $logger;

    public function __construct(
        private HttpClientInterface $client,
        #[Autowire('%env(AKISMET_KEY)%')] string $akismetKey,
        LoggerInterface $logger
    ) {
        $this->endpoint = sprintf('https://%s.rest.akismet.com/1.1/comment-check', $akismetKey);
        $this->logger = $logger;
    }

    /**
     * @return int : 0: not spam, 1: maybe spam, 2: blatant spam
     */
    public function getSpamScore(Comment $comment, array $context): int
    {
        $response = $this->client->request('POST', $this->endpoint, [
        'body' => array_merge($context, [
        'blog'                 => 'http://localhost:8000',
        'comment_type'         => 'comment',
        'comment_author'       => $comment->getAuthor(),
        'comment_author_email' => $comment->getEmail(),
        'comment_content'      => $comment->getText(),
        'comment_date_gmt'     => $comment->getCreatedAt()->format('c'),
        'blog_lang'            => 'en',
        'blog_charset'         => 'UTF-8',
        'is_test'              => true,
        ]),
        ]);

        $headers = $response->getHeaders();
        $this->logger->info('Akismet response headers', $headers);

        if ('discard' === ($headers['x-akismet-pro-tip'][0] ?? '')) {
            return 2;
        }

        $content = $response->getContent();
        $this->logger->info('Akismet response content', ['content' => $content]);

        if (isset($headers['x-akismet-debug-help'][0])) {
            throw new \RuntimeException(sprintf('Unable to check for spam: %s (%s).', $content, $headers['x-akismet-debug-help'][0]));
        }

        return 'true' === $content ? 1 : 0;
    }
}
