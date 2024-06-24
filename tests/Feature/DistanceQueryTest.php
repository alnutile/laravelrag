<?php

namespace Tests\Feature;

use App\Models\Chunk;
use Facades\App\Services\LlmServices\DistanceQuery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use LlmLaraHub\LlmDriver\DistanceQuery\DistanceQueryFacade;
use Pgvector\Laravel\Vector;
use Tests\TestCase;

class DistanceQueryTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_query(): void
    {
        $files = File::files(base_path('tests/fixtures/document_chunks'));

        foreach ($files as $file) {
            $data = json_decode(File::get($file), true);
            Chunk::factory()->create($data);
        }

        $question = get_fixture('test_query.json');

        $vector = new Vector($question);

        $results = DistanceQuery::cosineDistance(
            'embedding_1024',
            $vector);

        $this->assertCount(1, $results);
    }
}
