<?php

namespace Tests\Unit;

use App\Services\UserAgentParser;
use PHPUnit\Framework\TestCase;

final class UserAgentParserTest extends TestCase
{
    private UserAgentParser $parser;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parser = new UserAgentParser();
    }

    public function test_it_parses_chrome_on_windows(): void
    {
        $result = $this->parser->parse('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36');

        $this->assertSame('Chrome 120.0.0.0', $result['browser']);
        $this->assertSame('Windows 10/11', $result['os']);
    }

    public function test_it_parses_firefox_on_linux(): void
    {
        $result = $this->parser->parse('Mozilla/5.0 (X11; Linux x86_64; rv:125.0) Gecko/20100101 Firefox/125.0');

        $this->assertSame('Firefox 125.0', $result['browser']);
        $this->assertSame('Linux', $result['os']);
    }

    public function test_it_returns_nulls_for_empty_user_agent(): void
    {
        $result = $this->parser->parse(null);

        $this->assertNull($result['browser']);
        $this->assertNull($result['os']);
    }
}
