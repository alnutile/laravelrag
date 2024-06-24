<?php

namespace App\Services\LlmServices\Responses;

use Pgvector\Laravel\Vector;
use Spatie\LaravelData\Attributes\WithCastable;

class EmbeddingsResponseDto extends \Spatie\LaravelData\Data
{
    public function __construct(
        #[WithCastable(VectorCaster::class)]
        public Vector $embedding,
        public int $token_count
    ) {
    }
}
