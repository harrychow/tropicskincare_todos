<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Arr;

class TodoController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $todos_list = $this->getHttpJson('https://jsonplaceholder.typicode.com/todos');
        $users_list = $this->getHttpJson('https://jsonplaceholder.typicode.com/users');

        // Combine todos with user data
        $users_keyed_by_id = collect($users_list)->keyBy('id');
        $todos_with_users = collect($todos_list)->map(function($todo) use($users_keyed_by_id) {
            $todo['user'] = ($users_keyed_by_id[$todo['userId']]) ?? null;
            return $todo;
        });

        $filters = $this->getFilters($request);
        $flatten_filters = Arr::dot($filters);
        $filtered_todos = $todos_with_users->filter(function ($value, $key) use ($flatten_filters) {
            $flatten_todo = Arr::dot($value);
            foreach ($flatten_filters as $filter => $filter_value) {
                if (!isset($flatten_todo[$filter]) || $flatten_todo[$filter] !== $filter_value) {
                    return false;
                }
            }
            return true;
        });

        return response()->json($filtered_todos);;
    }

    /**
     * Helper function to parse out json request
     *
     * @param String $url
     * @return array
     */
    public function getHttpJson(String $url): array {
        $request = Http::get($url);
        return $request->json();
    }

    /**
     * Accepted filters
     *
     * @param Request $request
     * @return array
     */
    private function getFilters(Request $request): array {
        return $request->only([
            'name',
            'title',
            'completed',
            'user.name',
            'user.username',
            'user.email',
            'user.phone',
            'user.address.street',
            'user.address.suite',
            'user.address.city',
            'user.address.zipcode',
            'user.address.geo.lat',
            'user.address.geo.lng',
            'user.website',
            'user.company.catchPhrase',
            'user.company.name',
            'user.company.bs',
        ]);
    }
}
