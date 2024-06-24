<?php

namespace App\Domains\Chunking;

use App\Models\Chunk;
use App\Services\LlmServices\LlmDriverFacade;
use App\Services\LlmServices\Responses\EmbeddingsResponseDto;
use Illuminate\Support\Facades\Log;

class ChunkText
{

    public function chunk(string $text) {
        $page_number = 1;
        $chunked_chunks = TextChunker::handle($text);
        foreach ($chunked_chunks as $chunkSection => $chunkContent) {

            try {
                $guid = md5($chunkContent);
                $chunk = Chunk::updateOrCreate(
                    [
                        'guid' => $guid,
                    ],
                    [
                        'section_number' => $chunkSection,
                        'content' => $chunkContent,
                        'sort_order' => $page_number,
                    ]
                );

                /** @var EmbeddingsResponseDto $results */
                $results = LlmDriverFacade::driver(config('llmdriver.driver'))
                    ->embedData($chunkContent);

                $embedding_column = get_embedding_size(config('llmdriver.driver'));

                $chunk->update([
                    $embedding_column => $results->embedding,
                ]);

            } catch (\Exception $e) {
                Log::error('Error parsing Text', ['error' => $e->getMessage()]);
            }

        }
    }
}
