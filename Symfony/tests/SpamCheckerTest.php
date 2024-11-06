<?php

namespace App\Tests;

use App\SpamChecker;
use App\Entity\Comment;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Psr\Log\LoggerInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SpamCheckerTest extends TestCase
{
    public function testSpamScoreWithInvalidRequest(): void
    {
        $this->assertTrue(true);
        $comment = new Comment();
        $comment->setCreatedAt();
        $context = [];
        $client = new MockHttpClient([new MockResponse('invalid', ['response_headers' => ['x-akismet-debug-help: Invalid key']])]);
        $checker = new SpamChecker($client, getenv('AKISMET_KEY'), $this->createMock(LoggerInterface::class));
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unable to check for spam: invalid (Invalid key).');
        $checker->getSpamScore($comment, $context);
    }

    /**
     * @dataProvider provideComment
     */
    public function testSpamScore(int $expectedScore, ResponseInterface $response, Comment $comment, array $context): void
    {
        $client = new MockHttpClient([$response]);
        $checker = new SpamChecker($client, getenv('AKISMET_KEY'), $this->createMock(LoggerInterface::class));

        $score = $checker->getSpamScore($comment, $context);

        $this->assertSame($expectedScore, $score);

        echo "score : $score\n";
    }

    public function provideComment(): iterable
    {
        $comment = new Comment();
        $comment->setCreatedAt();
        $context = [];

        $response = new MockResponse('', ['response_headers' => ['x-akismet-pro-tip' => 'discard']]);
        yield 'blatant spam' => [2, $response, $comment, $context];

        $response = new MockResponse('true');
        yield 'spam' => [1, $response, $comment, $context];

        $response = new MockResponse('false');
        yield 'ham' => [0, $response, $comment, $context];
    }
}