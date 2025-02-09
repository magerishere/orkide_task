<?php

namespace Modules\Base\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\LazyCollection;
use Modules\Base\Repository\Contracts\BaseRepositoryInterface;

class BaseRepository implements BaseRepositoryInterface
{
    protected Model $model;
    protected readonly Collection|\Illuminate\Support\Collection|LengthAwarePaginator|LazyCollection $collection;

    public function __construct(
        protected Builder          $query,
        protected readonly ?string $apiResource = null,
        protected readonly ?string $apiResourceCollection = null,
        protected readonly array   $defaultCreateData = [],
    )
    {
        $this->model = $this->query->getModel();
    }

    public function freshQuery(): BaseRepositoryInterface
    {
        $this->query = $this->query->freshQuery();

        return $this;
    }

    public function freshModel(): BaseRepositoryInterface
    {
        $this->model = $this->model->refresh();

        return $this;
    }

    public function getQuery(): Builder
    {
        return $this->query;
    }

    public function getModel(bool $asResource = false): Model|JsonResource
    {
        return $asResource
            ? new $this->apiResource($this->model)
            : $this->model;
    }

    public function getCollection(bool $asResource = false): Collection|LengthAwarePaginator|LazyCollection|ResourceCollection
    {
        return $asResource
            ? new $this->apiResourceCollection($this->collection)
            : $this->collection;
    }

    public function create(array $data = []): BaseRepositoryInterface
    {
        $data = $this->mergeCreateData(data: $data);
        $this->model = $this->query->create(attributes: $data);
        return $this;
    }


    public function mergeCreateData(array $data, bool $removeIfNull = false): array
    {
        $data = array_merge($this->defaultCreateData, $data);

        return $removeIfNull
            ? array_filter($data, fn($item) => !is_null($item))
            : $data;
    }

    public function update(array $data): BaseRepositoryInterface
    {
        $this->model->update(attributes: $data);

        $this->freshModel();

        return $this;
    }

    public function randomly(): BaseRepositoryInterface
    {
        $this->query->inRandomOrder();
        return $this;
    }

    public function where(...$args): BaseRepositoryInterface
    {
        $this->query->where(...$args);

        return $this;
    }

    public function exists(): bool
    {
        return $this->query->exists();
    }

    public function findBy(...$args): BaseRepositoryInterface
    {
        $this->where(...$args)->first();

        return $this;
    }

    public function findById(int|string $id, ?string $column = null): BaseRepositoryInterface
    {
        $column ??= $this->model->getKeyName();

        return $this->findBy($column, $id);
    }


    public function first(): BaseRepositoryInterface
    {
        return $this->setModel(model: $this->query->first());
    }

    private function setModel(?Model $model = null): BaseRepositoryInterface
    {
        if ($model instanceof Model) {
            $this->model = $model;
        }

        return $this;
    }
}
