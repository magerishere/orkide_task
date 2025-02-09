<?php

namespace Modules\Base\Repository\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;

interface BaseRepositoryInterface
{
    public function freshQuery(): self;

    public function getQuery(): Builder;

    public function getModel(bool $asResource = false): Model|JsonResource;

    public function getCollection(bool $asResource = false): Collection|LengthAwarePaginator|LazyCollection|ResourceCollection;

    public function create(array $data = []): self;

    public function mergeCreateData(array $data, bool $removeIfNull = false): array;

    public function randomly(): self;

    public function first(): self;
}
