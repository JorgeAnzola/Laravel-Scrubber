<?php

namespace YorCreative\Scrubber\Tests\Unit\Repositories;

use Illuminate\Support\Collection;
use YorCreative\Scrubber\Repositories\RegexRepository;
use YorCreative\Scrubber\Tests\TestCase;

class RegexRepositoryTest extends TestCase
{
    /**
     * @test
     * @group RegexRepository
     * @group Unit
     */
    public function it_can_verify_that_all_regex_patterns_have_testable_counter_parts()
    {
        RegexRepository::getRegexCollection()->each(function ($regexClass) {
            $hits = 0;

            $this->assertStringContainsString(
                config('scrubber.redaction'),
                RegexRepository::checkAndSanitize($regexClass->getPattern(), $regexClass->getTestableString(), $hits)
            );

            $this->assertEquals(1, $hits);
        });
    }

    /**
     * @test
     * @group RegexRepository
     * @group Unit
     */
    public function it_can_sanitize_a_string_with_multiple_sensitive_pieces()
    {
        $hits = 0;

        $content = RegexRepository::getRegexCollection()->get('google_api')->getTestableString()
            .' something something something '
            .RegexRepository::getRegexCollection()->get('google_api')->getTestableString();

        $this->assertStringContainsString(
            config('scrubber.redaction'),
            RegexRepository::checkAndSanitize(
                RegexRepository::getRegexCollection()->get('google_api')->getPattern(),
                $content,
                $hits
            )
        );

        $this->assertEquals(2, $hits);
    }

    /**
     * @test
     * @group RegexRepository
     * @group Unit
     */
    public function it_can_receive_a_collection()
    {
        $this->assertInstanceOf(Collection::class, RegexRepository::getRegexCollection());
    }
}
