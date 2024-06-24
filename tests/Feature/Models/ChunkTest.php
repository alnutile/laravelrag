<?php

namespace Tests\Feature\Models;

use App\Domains\UnStructured\StructuredTypeEnum;
use App\Models\Chunk;
use App\Models\DocumentChunk;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Pgvector\Vector;
use Tests\TestCase;

class ChunkTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_factory()
    {
        $model = Chunk::factory()->create();
        $this->assertNotNull($model->content);
        $this->assertNotNull($model->section_number);
        $this->assertNotNull($model->sort_order);

        $this->assertInstanceOf(Vector::class, $model->embedding_3072);
    }
}
