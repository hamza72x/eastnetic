<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\UserTrait;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class UserController extends Controller
{
  use UserTrait;

  public function getUsers(Request $request)
  {
    $page = $request->page ? intval($request->page) : 1;
    $per_page = $request->per_page ? intval($request->per_page) : 20;

    // check cache first
    $cache = $this->usersFromCache($request);

    if ($cache) {
      $total_items = count($cache);
      $total_pages = intval(ceil($total_items / $per_page));
      $offset = $page > 1 ? ($page - 1) * $per_page : 0;

      return [
        'users' => array_slice($cache, $offset, $per_page),

        'current_page' => $page,
        'per_page' => $per_page,

        'total_items' => $total_items,
        'total_pages' => $total_pages,
      ];
    }

    // otherwise get from database
    $query = User::where('id', '!=', 0);
    $paginated = $this->usersFromDB($query, $request);

    return [
      'users' => $paginated->items(),

      'current_page' => $paginated->currentPage(),
      'per_page' => $paginated->perPage(),

      'total_items' => $paginated->total(),
      'total_pages' => $paginated->lastPage(),
    ];
  }
}
