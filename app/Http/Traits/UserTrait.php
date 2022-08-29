<?php

namespace App\Http\Traits;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

const USERS_BIRTH_YEAR = 'users_birth_year.';
const USERS_BIRTH_MONTH = 'users_birth_month.';
const BIRTH_BASED_CACHE = 'birth_based_cache';

trait UserTrait
{
  public function usersFromCache(Request $request): array | null
  {
    $birth_year = $request->birth_year ? intval($request->birth_year) : null;
    $birth_month = $request->birth_month ? intval($request->birth_month) : null;

    $cacheKey = '';

    if ($birth_year) {
      $cacheKey .= USERS_BIRTH_YEAR . $birth_year . '_';
    }

    if ($birth_month) {
      $cacheKey .= USERS_BIRTH_MONTH . $birth_month;
    }

    if ($cacheKey) {
      $cache = Redis::get($cacheKey);
      if ($cache) {
        return json_decode($cache);
      }
      return null;
    }

    return null;
  }

  public function usersFromDB(Builder $query, Request $request): LengthAwarePaginator
  {
    $page = $request->page ? intval($request->page) : 1;
    $per_page = $request->per_page ? intval($request->per_page) : 20;

    $birth_year = $request->birth_year ? intval($request->birth_year) : null;
    $birth_month = $request->birth_month ? intval($request->birth_month) : null;
    $cacheKey = '';

    if ($birth_year) {
      $query = $query->where('birth_year', '=', $birth_year);
      $cacheKey .= USERS_BIRTH_YEAR . $birth_year . '_';
    }

    if ($birth_month) {
      $query = $query->where('birth_month', '=', $birth_month);
      $cacheKey .= USERS_BIRTH_MONTH . $birth_month;
    }

    if ($cacheKey) {
      // remove existing cache (as per instruction)
      Redis::del(BIRTH_BASED_CACHE);
      // remember the cache key
      Redis::set(BIRTH_BASED_CACHE, $cacheKey);
      // set the cache
      Redis::set($cacheKey, $query->get());
    }

    return $query->paginate($per_page, ['*'], 'page', $page);
  }
}
