<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Post;
use App\Model\Topic;
use App\Http\Resources\TopicResource;
use App\Http\Requests\TopicCreateRequest;
use App\Http\Requests\UpdateTopicRequest;
use Illuminate\Support\Facades\Gate;


class TopicController extends Controller
{

    public function index()
    {
//        $topics = Topic::paginate(5);
        $topics = Topic::orderBy('created_at', 'desc')->paginate(6);

        return TopicResource::collection($topics);
    }


    public function create()
    {
        //
    }


    public function store(TopicCreateRequest $request)
    {
        $topic = new Topic();
        $topic->title = $request->title;
        $topic->user()->associate($request->user());

        $post = new Post();
        $post->body = $request->body;
        $post->user()->associate($request->user());

        $topic->save();
        $topic->posts()->save($post);

        return new TopicResource($topic);
    }


    public function show(Topic $topic)
    {
        return new TopicResource($topic);
    }


    public function edit($id)
    {
        //
    }


    public function update(UpdateTopicRequest $request , Topic $topic)
    {
        $this->authorize('update' , $topic);
        $topic->title = $request->get('title' , $topic->title);
        $topic->save();
        return new TopicResource($topic);
    }


    public function destroy(Topic $topic)
    {
        $this->authorize('destroy' , $topic);
        $topic->delete();
        return response(null , 204);

    }
}
