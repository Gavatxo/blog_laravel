<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Database\Eloquent\Builder;

class PostController extends Controller
{
    public function index(Request $request): View
    {
       return $this->postsView($request->search ? ['search' => $request->search] : []);
    }

    public function postsByCategory(Category $category): View
    {
       return $this->postsView(['category' => $category]);
    }

    public function postsByTag(Tag $tag): View
    {
        return $this->postsView(['tag' => $tag]);
    }

    protected function postsView(array $filters): View
    {
        // La méthode filters peut être utilisé car dans le model post on a fait un ScopeFilters
        return view('posts.index', [
            'posts' => Post::filters($filters)->latest()->paginate(10),
        ]);
    }

    public function show(Post $post): View
    {
        return view('posts.show', [
            'post' => $post,
        ]);
    }

    public function comment (Post $post, Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'comment' => ['required', 'string', 'between:2,255'],
        ]);

        // Manière de faire pour l'enregistrer en bdd, mais possibilité de passer directement par le modèle avec la méthode fillable/create (Masasigment)

        $comment = new Comment();

        $comment->content = $validated['comment'];
        $comment->post_id = $post->id;
        $comment->user_id = Auth::id();

        $comment->save();

        return back()->withStatus('commentaire publié');
    }
}
