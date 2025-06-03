<?php
// app/Services/FilmService.php
namespace App\Services;

use App\Http\Resources\MovieResource;
use App\Http\Resources\SeriesResource;
use App\Models\Movie;
use App\Models\Series;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

class FilmService
{
    public static function filterMovies(array $filters)
    {
        $query = Movie::query()->with(['genres', 'categories', 'watchHistory' => fn($q) => $q->where('user_uuid', $filters['user_uuid'])])->where('status', \App\Models\Movie::STATUS_ACTIVE);

        if (!empty($filters['genre'])) {
            $query->whereHas('genres', function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['genre'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%');
            });
        }

        if (!empty($filters['release'])) {
            $start = Carbon::createFromDate($filters['release'], 1, 1)->startOfDay();
            $end = Carbon::createFromDate($filters['release'], 12, 31)->endOfDay();

            $query->whereBetween('release', [$start, $end]);
        }

        if (!empty($filters['country'])) {
            $query->where('country', 'like', '%' . $filters['country'] . '%');
        }

        if (!empty($filters['directors'])) {
            $query->where('directors', 'like', '%' . $filters['director'] . '%');
        }

        if (!empty($filters['actors'])) {
            $query->where('actors', 'like', '%' . $filters['actors'] . '%');
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%')
                ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                ->orWhere('country', 'like', '%' . $filters['search'] . '%');
        }

        // Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_direction'] ?? 'desc';
        $per_page = $filters['per_page'] ?? 10;
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($per_page);
    }


    public static function filterSeries(array $filters)
    {
        $query = Series::query()->with(['genres', 'categories', 'seasons', 'watchHistory' => fn($q) => $q->where('user_uuid', $filters['user_uuid'])])->where('status', \App\Models\Series::STATUS_ACTIVE);

        if (!empty($filters['genre'])) {
            $query->whereHas('genres', function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['genre'] . '%');
            });
        }

        if (!empty($filters['category'])) {
            $query->whereHas('categories', function (Builder $q) use ($filters) {
                $q->where('name', 'like', '%' . $filters['category'] . '%');
            });
        }

        if (!empty($filters['release'])) {
            $start = Carbon::createFromDate($filters['release'], 1, 1)->startOfDay();
            $end = Carbon::createFromDate($filters['release'], 12, 31)->endOfDay();

            $query->whereBetween('release', [$start, $end]);
        }

        if (!empty($filters['country'])) {
            $query->where('country', 'like', '%' . $filters['country'] . '%');
        }

        if (!empty($filters['directors'])) {
            $query->where('directors', 'like', '%' . $filters['director'] . '%');
        }

        if (!empty($filters['actors'])) {
            $query->where('actors', 'like', '%' . $filters['actors'] . '%');
        }

        if (!empty($filters['search'])) {
            $query->where('title', 'like', '%' . $filters['search'] . '%')
                ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                ->orWhere('country', 'like', '%' . $filters['search'] . '%');
        }

        // Sắp xếp
        $sortBy = $filters['sort_by'] ?? 'created_at';
        $sortDir = $filters['sort_direction'] ?? 'desc';
        $per_page = $filters['per_page'] ?? 10;
        $query->orderBy($sortBy, $sortDir);

        return $query->paginate($per_page);
    }

    public static function filterBoth(array $filters)
    {
        $movieQuery = Movie::query()
            ->with(['genres', 'categories', 'watchHistory' => fn($q) => $q->where('user_uuid', $filters['user_uuid'])])
            ->where('status', Movie::STATUS_ACTIVE);

        $seriesQuery = Series::query()
            ->with(['genres', 'categories', 'seasons'])
            ->where('status', Series::STATUS_ACTIVE);

        // Lọc chung
        foreach ([$movieQuery, $seriesQuery] as $query) {
            // Search theo nhiều cột
            if (!empty($filters['search'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('title', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('slug', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('country', 'like', '%' . $filters['search'] . '%');
                });
            }

            // Lọc theo category
            if (!empty($filters['category'])) {
                $query->whereHas('categories', function (Builder $q) use ($filters) {
                    $q->where('name', $filters['category']);
                });
            }

            // Lọc theo genre
            if (!empty($filters['genre'])) {
                $query->whereHas('genres', function (Builder $q) use ($filters) {
                    $q->where('name', $filters['genre']);
                });
            }

            // Lọc theo năm (timestamp -> human date)
            if (!empty($filters['release'])) {
                $start = Carbon::createFromDate($filters['release'], 1, 1)->startOfDay();
                $end = Carbon::createFromDate($filters['release'], 12, 31)->endOfDay();
                $query->whereBetween('release', [$start, $end]);
            }
        }

        // Lấy dữ liệu
        $movies = MovieResource::collection($movieQuery->get());
        $series = SeriesResource::collection($seriesQuery->get());

        // Gộp lại
        $merged = $movies->merge($series);
        $shuffled = $merged->shuffle();
        // Sắp xếp
        $sortDirection = $filters['sort_direction'] ?? 'desc';
        $sorted = $shuffled;
        if (!empty($filters['sort_by'])) {
            $sorted = $shuffled->sortBy([
                [$filters['sort_by'], $sortDirection === 'desc' ? SORT_DESC : SORT_ASC],
            ]);
        }

        // Phân trang thủ công
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? 10;
        $total = $sorted->count();
        $paged = $sorted->slice(($page - 1) * $perPage, $perPage)->values();

        return new LengthAwarePaginator(
            $paged,
            $total,
            $perPage,
            $page,
        );
    }
}
