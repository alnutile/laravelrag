<?php

namespace App\Services\LlmServices;

use App\Models\Chunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Pgvector\Laravel\Distance;
use Pgvector\Laravel\Vector;

class DistanceQuery
{
    public function cosineDistance(
        string $embeddingSize,
        Vector $embedding
    ): Collection {

        $query = Chunk::query()
            ->orderBy('sort_order')
            ->orderBy('section_number')
            ->nearestNeighbors($embeddingSize, $embedding, Distance::Cosine)
            ->get();

        $results = collect($query)
            ->unique('id')
            ->take(8);

        $siblingsIncluded = $this->getSiblings($results);

        return $siblingsIncluded;
    }

    protected function getSiblings(Collection $results): Collection
    {
        $siblingsIncluded = collect();

        foreach ($results as $result) {
            if ($result->section_number === 0) {
                $siblingsIncluded->push($result);
            } else {
                if ($sibling = $this->getSiblingOrNot($result, $result->section_number - 1)) {

                }     $siblingsIncluded->push($sibling);

                $siblingsIncluded->push($result);
            }

            if ($sibling = $this->getSiblingOrNot($result, $result->section_number + 1)) {
                $siblingsIncluded->push($sibling);
            }
        }

        return $siblingsIncluded;
    }

    protected function getSiblingOrNot(Chunk $result, int $sectionNumber): false|Chunk
    {
        $sibling = Chunk::query()
            ->where('sort_order', $result->sort_order)
            ->where('section_number', $sectionNumber)
            ->first();

        if ($sibling?->id) {
            return $sibling;
        }

        return false;
    }
}
