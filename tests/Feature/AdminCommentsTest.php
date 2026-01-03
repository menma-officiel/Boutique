<?php

namespace Tests\Feature;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCommentsTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_comments_index()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->get(route('admin.comments.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_approve_and_delete_comment()
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $product = Product::factory()->create();
        $comment = Comment::factory()->create(["product_id" => $product->id, 'approved' => false]);

        // approve
        $response = $this->actingAs($admin)->put(route('admin.comments.update', $comment), [
            'approved' => 1,
        ]);

        $response->assertRedirect(route('admin.comments.index'));
        $this->assertDatabaseHas('comments', ['id' => $comment->id, 'approved' => 1]);

        // delete
        $response = $this->actingAs($admin)->delete(route('admin.comments.destroy', $comment));
        $response->assertRedirect(route('admin.comments.index'));
        $this->assertDatabaseMissing('comments', ['id' => $comment->id]);
    }
}
