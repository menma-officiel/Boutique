<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCommentsBulkTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_bulk_approve_and_delete_comments()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create();
        $c1 = Comment::factory()->create(['product_id' => $product->id, 'approved' => false]);
        $c2 = Comment::factory()->create(['product_id' => $product->id, 'approved' => false]);

        // approve both
        $response = $this->actingAs($admin)->post(route('admin.comments.bulk'), [
            'action' => 'approve',
            'ids' => [$c1->id, $c2->id],
        ]);

        $response->assertRedirect(route('admin.comments.index'));
        $this->assertDatabaseHas('comments', ['id' => $c1->id, 'approved' => 1]);
        $this->assertDatabaseHas('comments', ['id' => $c2->id, 'approved' => 1]);

        // delete
        $response = $this->actingAs($admin)->post(route('admin.comments.bulk'), [
            'action' => 'delete',
            'ids' => [$c1->id, $c2->id],
        ]);

        $response->assertRedirect(route('admin.comments.index'));
        $this->assertDatabaseMissing('comments', ['id' => $c1->id]);
        $this->assertDatabaseMissing('comments', ['id' => $c2->id]);
    }

    public function test_filters_show_expected_comments()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $prodA = Product::factory()->create(['name' => 'Alpha']);
        $prodB = Product::factory()->create(['name' => 'Beta']);

        $cA = Comment::factory()->create(['product_id' => $prodA->id, 'author_name' => 'Alice', 'content' => 'Super', 'approved' => 0]);
        $cB = Comment::factory()->create(['product_id' => $prodB->id, 'author_name' => 'Bob', 'content' => 'Cool', 'approved' => 1]);

        // filter by product_id
        $res = $this->actingAs($admin)->get(route('admin.comments.index', ['product_id' => $prodA->id]));
        $res->assertStatus(200);
        $res->assertSeeText('Alice');
        $res->assertDontSeeText('Bob');

        // filter by approved=1
        $res = $this->actingAs($admin)->get(route('admin.comments.index', ['approved' => 1]));
        $res->assertStatus(200);
        $res->assertSeeText('Bob');
        $res->assertDontSeeText('Alice');

        // search
        $res = $this->actingAs($admin)->get(route('admin.comments.index', ['search' => 'Super']));
        $res->assertStatus(200);
        $res->assertSeeText('Alice');
    }
}
