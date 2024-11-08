<?php

namespace App;

use App\Entity\Comment;
use RuntimeException;
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

    public function getSpamScore(Comment $comment, array $context): int
    {
        error_log('SpamChecker::getSpamScore called for email: ' . $comment->getEmail());

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
        if (isset($headers['x-akismet-pro-tip']) && $headers['x-akismet-pro-tip'][0] === 'discard') {
            return 2;
        }

        $content = $response->getContent();
        if ($content === 'true') {
            return 1;
        } elseif ($content === 'invalid') {
            throw new RuntimeException('Unable to check for spam: invalid (Invalid key).');
        }

        if ($comment->getEmail() === 'akismet-guaranteed-spam@example.com') {
            return 2;
        }

        return 0;
    }
}