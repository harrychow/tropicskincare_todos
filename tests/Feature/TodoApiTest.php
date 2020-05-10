<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TodoApiTest extends TestCase
{

    private function mockJsonplaceholder($todos = [], $users = []) {
        Http::fake([
            'https://jsonplaceholder.typicode.com/todos' => Http::response($todos, 200, ['Headers']),
            'https://jsonplaceholder.typicode.com/users' => Http::response($users, 200, ['Headers']),
        ]);
    }

    private function mockValidUserAndTodo() {
        $valid_todos = [
            [
                "userId" => 1,
                "id" => 1,
                "title" => "delectus aut autem",
                "completed" => false
            ],
            [
                "userId" => 2,
                "id" => 2,
                "title" => "quis ut nam facilis et officia qui",
                "completed" => false
            ]
        ];

        $valid_user = [
            [
                "id" => 1,
                "username" => "Bret",
                "email" => "Sincere@april.biz",
                "address" => [
                    "zipcode" => "92998-3874",
                    "geo" => [
                        "lat" => "-37.3159",
                        "lng" => "81.1496"
                    ]
                ],
                "phone" => "1-770-736-8031 x56442",
                "company" => [
                    "name" => "Romaguera-Crona",
                ]
            ]
        ];
        $this->mockJsonplaceholder($valid_todos, $valid_user);
    }

    /**
     * Test Valid api request if jsonplaceholder returns empty
     *
     * @return void
     *
     */
    public function testValidApiRequest() {
        $this->mockJsonplaceholder();

        $response = $this->get('/api/todos');
        $response->assertStatus(200);
    }


    /**
     * Test valid api request if jsonplaceholder returns valid json
     */
    public function testValidTodos() {
        $valid_todos = [
             [
                 "userId" => 1,
                 "id" => 1,
                 "title" => "delectus aut autem",
                 "completed" => false
             ],
             [
                 "userId" => 1,
                 "id" => 2,
                 "title" => "quis ut nam facilis et officia qui",
                 "completed" => false
             ],
         ];
        // Mock json data is valid
        $this->mockJsonplaceholder($valid_todos);
        $response = $this->getJson('/api/todos');
        $response->assertJson($valid_todos);
    }

    /**
     * Test error if jsonplaceholder returns invalid json
     */
    public function testInvalidTodos() {
        // Mock json data is invalid
        $this->mockJsonplaceholder('String passed back');
        $response = $this->getJson('/api/todos');
        $response->assertStatus(500);
    }


    /**
     * Test valid data returned if jsonplaceholder provides correct todo and user data
     */
    public function testCombineTodosWithUsers() {
        // Mock json data with users
        $valid_todos = [
            [
                "userId" => 1,
                "id" => 1,
                "title" => "delectus aut autem",
                "completed" => false
            ],
            [
                "userId" => 1,
                "id" => 2,
                "title" => "quis ut nam facilis et officia qui",
                "completed" => false
            ],
        ];

        $valid_user = [
            [
                "id" => 1,
                "name" => "Leanne Graham",
                "username" => "Bret",
                "email" => "Sincere@april.biz",
                "address" => [
                    "street" => "Kulas Light",
                    "suite" => "Apt. 556",
                    "city" => "Gwenborough",
                    "zipcode" => "92998-3874",
                    "geo" => [
                        "lat" => "-37.3159",
                        "lng" => "81.1496"
                    ]
                ],
                "phone" => "1-770-736-8031 x56442",
                "website" => "hildegard.org",
                "company" => [
                    "name" => "Romaguera-Crona",
                    "catchPhrase" => "Multi-layered client-server neural-net",
                    "bs" => "harness real-time e-markets"
                ]
            ]
        ];
        $this->mockJsonplaceholder($valid_todos, $valid_user);
        $response = $this->getJson('/api/todos');

        $valid_todos[0]['user'] = $valid_user[0];
        $response->assertJsonFragment($valid_todos[0]);
    }


    /**
     * Test filters if acceptable filter is provided
     */
    public function testInvalidFilters()
    {
        $this->mockValidUserAndTodo();
        $response = $this->get('/api/todos?userId=1');
        $response->assertJsonCount(2);
    }

    /**
     * Test filters if unaccepted filter is provided
     */
    public function testValidFilters()
    {

        $this->mockValidUserAndTodo();
        $response = $this->get('/api/todos?user[email]=Sincere@april.biz');
        $response->assertJsonCount(1);
    }

}
