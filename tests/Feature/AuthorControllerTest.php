<?php

namespace Tests\Feature;

use App\Models\Author;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AuthorControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testCreateValidationFails()
    {
        $data = [
            // Без имени, что вызовет ошибку валидации
            'info' => 'Test Info',
            'birthdate' => '1990-01-01',
        ];

        $response = $this->postJson('/api/authors', $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    public function testUpdateValidationFails()
    {
        $author = Author::factory()->create();

        $data = [
            // Обновление без имени, что вызовет ошибку валидации
            'info' => 'Updated Info',
            'birthdate' => '1990-01-01',
        ];

        $response = $this->putJson("/api/authors/{$author->id}", $data);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
            ->assertJsonValidationErrors(['name']);
    }

    /**
     * Тест получения списка авторов.
     *
     * @return void
     */
    public function testGetList(): void
    {
        Author::factory()->count(3)->create();

        $response = $this->getJson(route('authors.getList'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'info', 'birthdate', 'books_count']
                ]
            ]);
    }

    /**
     * Тест получения автора по ID.
     *
     * @return void
     */
    public function testFindById(): void
    {
        $author = Author::factory()->create();

        $response = $this->getJson(route('authors.findById', $author->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $author->id,
                    'name' => $author->name,
                    'info' => $author->info,
                    'birthdate' => $author->birthdate,
                ]
            ]);
    }

    /**
     * Тест создания нового автора.
     *
     * @return void
     */
    public function testCreateAuthor(): void
    {
        $data = [
            'name' => $this->faker->name,
            'info' => $this->faker->text(200),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', 'now')->format('d-m-Y'),
        ];

        $response = $this->postJson(route('authors.create'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'info' => $data['info'],
                    'birthdate' => Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
                ]
            ]);


        $this->assertDatabaseHas('authors', [
            'name' => $data['name'],
            'info' => $data['info'],
            'birthdate' => Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
        ]);
    }

    /**
     * Тест обновления данных автора.
     *
     * @return void
     */
    public function testUpdateAuthor(): void
    {
        $author = Author::factory()->create();

        $data = [
            'name' => $this->faker->name,
            'info' => $this->faker->text(200),
            'birthdate' => $this->faker->dateTimeBetween('-30 years', 'now')->format('d-m-Y'),
        ];

        $response = $this->putJson(route('authors.update', $author->id), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $author->id,
                    'name' => $data['name'],
                    'info' => $data['info'],
                    'birthdate' => Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
                ]
            ]);


        $this->assertDatabaseHas('authors', [
            'id' => $author->id,
            'name' => $data['name'],
            'info' => $data['info'],
            'birthdate' => Carbon::createFromFormat('d-m-Y', $data['birthdate'])->format('Y-m-d'),
        ]);
    }

    /**
     * Тест удаления автора.
     *
     * @return void
     */
    public function testDeleteAuthor(): void
    {
        $author = Author::factory()->create();

        $response = $this->deleteJson(route('authors.delete', $author->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('authors', ['id' => $author->id]);
    }
}
