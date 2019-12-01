<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\PostResource;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Model\Post;
use App\Model\Topic;


class PostController extends Controller
{

    public function show(Request $request , Topic $topic , Post $post){

return new PostResource($post);

    }


    public function store(StorePostRequest $request , Topic $topic){
        $post = new Post();

        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->posts()->save($post);

        return new PostResource($post);
    }

    public function update(UpdatePostRequest $request , Topic $topic , Post $post){
        $this->authorize('update' , $post);
        $post->body = $request->get('body' , $post->title);
        $post->save();
        return new PostResource($post);
    }

    public function destroy(Topic $topic , Post $post)
    {
        $this->authorize('destroy' , $post);
        $post->delete();
        return response(null , 204);

    }

}
