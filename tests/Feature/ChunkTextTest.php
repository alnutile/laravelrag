<?php

namespace Tests\Feature;

use App\Domains\Chunking\ChunkText;
use App\Models\Chunk;
use App\Services\LlmServices\Responses\EmbeddingsResponseDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ChunkTextTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_chunking(): void
    {

        $embedding = get_fixture('embedding_response.json');

        $dto = EmbeddingsResponseDto::from([
            'embedding' => data_get($embedding, 'data.0.embedding'),
            'token_count' => 1000,
        ]);

        \App\Services\LlmServices\LlmDriverFacade::shouldReceive('driver->embedData')
            ->times(3)
            ->andReturn($dto);


        $text = get_fixture('smaller_text.txt', false);

        (new ChunkText())->chunk($text);

        $this->assertDatabaseCount('chunks', 3);
        $chunk = Chunk::first();
        $this->assertNotEmpty($chunk->embedding_3072);
    }
}
