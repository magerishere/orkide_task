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

    public function freshModel(): self;

    public function getQuery(): Builder;

    public function getModel(bool $asResource = false): Model|JsonResource;

    public function getCollection(bool $asResource = false, ?\Closure $closure = null): Collection|LengthAwarePaginator|LazyCollection|ResourceCollection;

    public function create(array $data = []): self;

    public function mergeCreateData(array $data, bool $removeIfNull = false): array;

    public function update(array $data): self;
    public function randomly(): self;

    public function where(...$args): self;

    public function whereIn(...$args): self;

    public function whereHas(...$args): self;

    public function whereRelation(...$args): self;

    public function with(array $relations): self;

    public function exists(): bool;

    public function findBy(...$args): self;

    public function findById(int|string $id, ?string $column = null): self;
    public function first(): self;

    public function all(array $columns = ['*'], array $relations = []): self;
}
