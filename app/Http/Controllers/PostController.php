<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
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
}
