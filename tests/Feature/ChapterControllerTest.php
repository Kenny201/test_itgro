<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class ChapterControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testGetList(): void
    {
        $book = Book::factory()->create();
        Chapter::factory()->count(5)->create(['book_id' => $book->id]);

        $response = $this->getJson(route('chapters.getList', $book->id));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(5, 'data');
    }

    public function testFindByID(): void
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $response = $this->getJson(route('chapters.findById', [$book->id, $chapter->id]));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'id' => $chapter->id,
                    'title' => $chapter->title,
                    'content' => $chapter->content,
                ],
            ]);
    }

    public function testCreate(): void
    {
        $book = Book::factory()->create();

        $data = [
            'title' => $this->faker->sentence,
            'content' => $this->faker->paragraph,
        ];

        $response = $this->postJson(route('chapters.create', $book->id), $data);

        $response->assertStatus(Response::HTTP_CREATED)
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'content' => $data['content'],
                ],
            ]);

        $this->assertDatabaseHas('chapters', [
            'title' => $data['title'],
            'content' => $data['content'],
            'book_id' => $book->id,
        ]);
    }

    public function testUpdate(): void
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $data = [
            'title' => 'Updated Chapter',
            'content' => 'Updated Content',
        ];

        $response = $this->putJson(route('chapters.update', [$book->id, $chapter->id]), $data);

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    'title' => $data['title'],
                    'content' => $data['content'],
                ],
            ]);

        $this->assertDatabaseHas('chapters', [
            'title' => $data['title'],
            'content' => $data['content'],
            'book_id' => $book->id,
        ]);
    }

    public function testDelete(): void
    {
        $book = Book::factory()->create();
        $chapter = Chapter::factory()->create(['book_id' => $book->id]);

        $response = $this->deleteJson(route('chapters.delete', [$book->id, $chapter->id]));

        $response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertDatabaseMissing('chapters', ['id' => $chapter->id]);
    }
}
