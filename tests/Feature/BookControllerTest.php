<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGetList(): void
    {
        $author = Author::factory()->create();
        Book::factory()->count(5)->create(['author_id' => $author->id]);

        $response = $this->getJson(route('books.getList'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function testFindByID(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->getJson(route('books.findById', $book->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $book->id,
                    'title' => $book->title,
                    'author_id' => $book->author_id,
                    'annotation' => $book->annotation,
                    'publication_date' => $book->publication_date,
                ],
            ]);
    }

    public function testCreate(): void
    {
        $author = Author::factory()->create();

        $data = [
            'author_id' => $author->id,
            'title' => $this->faker->name,
            'annotation' => $this->faker->text(200),
            'publication_date' => $this->faker->dateTimeBetween('-30 years', 'now')->format('d-m-Y'),
        ];

        $response = $this->postJson(route('books.create'), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'annotation' => $data['annotation'],
                    'publication_date' => Carbon::createFromFormat('d-m-Y', $data['publication_date'])->format('Y-m-d'),
                ],
            ]);

        $this->assertDatabaseHas('books', [
            'title' => $data['title'],
            'annotation' => $data['annotation'],
            'publication_date' => Carbon::createFromFormat('d-m-Y', $data['publication_date'])->format('Y-m-d'),
        ]);
    }

    public function testUpdate(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $data = [
            'author_id' => $author->id,
            'title' => 'Updated Book',
            'annotation' => 'Updated Annotation',
            'publication_date' => $this->faker->dateTimeBetween('-30 years', 'now')->format('d-m-Y'),
        ];

        $response = $this->putJson(route('books.update', $book->id), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'annotation' => $data['annotation'],
                    'publication_date' => Carbon::createFromFormat('d-m-Y', $data['publication_date'])->format('Y-m-d'),
                ],
            ]);

        $this->assertDatabaseHas('books', [
            'title' => $data['title'],
            'annotation' => $data['annotation'],
            'publication_date' => Carbon::createFromFormat('d-m-Y', $data['publication_date'])->format('Y-m-d'),
        ]);
    }

    public function testDelete(): void
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $response = $this->deleteJson(route('books.delete', $book->id));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
    }
}
