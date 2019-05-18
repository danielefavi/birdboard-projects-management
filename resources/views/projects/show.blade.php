@extends('layouts.app')

@section('content')
    <header class="flex items-center mb-6 py-4">
        <div class="flex justify-between items-end w-full">
            <p class="text-muted font-light">
                <a href="/projects" class="text-muted no-underline hover:underline">
                    My Projects
                </a> / {{ $project->title }}
            </p>

            <div class="flex items-center">
                @foreach($project->members as $member)
                    <img
                        class="rounded-full w-8 mr-2"
                        src="{{ gravatar_url($member->email) }}"
                        alt="{{ $member->name }}"
                    >
                @endforeach

                <img
                    class="rounded-full w-8 mr-2"
                    src="https://gravatar.com/avatar/{{ md5($project->owner->email) }}?s=60"
                    alt="{{ $project->owner->name }}"
                >

                <a href="{{ $project->path('edit') }}" class="button ml-4">Edit Project</a>
            </div>
        </div>
    </header>

    <main>
        <div class="lg:flex -mx-3">
            <div class="lg:w-3/4 px-3 mb-6">
                <div class="mb-8">
                    <div class="text-lg text-muted font-light mb-3">Tasks</div>

                    @foreach($project->tasks as $task)
                        <div class="card mb-3">
                            <form action="{{ $task->path() }}" method="POST">
                                @method('PATCH')
                                @csrf

                                <div class="flex items-center">
                                    <input name="body" value="{{ $task->body }}" class="text-default bg-card w-full {{ $task->completed ? 'line-through text-muted' : '' }}">
                                    <input name="completed" type="checkbox" onChange="this.form.submit()" {{ $task->completed ? 'checked' : '' }}>
                                </div>
                            </form>
                        </div>
                    @endforeach

                    <div class="card mb-3">
                        <form class="" action="{{ $project->path('tasks') }}" method="post">
                            @csrf

                            <input placeholder="Add a new task..." class="text-default bg-card w-full" name="body">
                        </form>
                    </div>
                </div>
                <div>
                    <h2 class="text-lg text-muted font-light mb-3">General Notes</h2>

                    <form  action="{{ $project->path() }}" method="post">
                        @csrf
                        @method('PATCH')

                        <textarea
                            name="notes"
                            class="card text-default w-full mb-4"
                            style="min-height:200px"
                        >{{ $project->notes }}</textarea>

                        <button type="submit" class="button">Save</button>

                        @include('errors')
                    </form>
                </div>
            </div>

            <div class="lg:w-1/4 px-3 mt-8">
                @include('projects.card')

                @include('projects.activity.card')

                @can('manage', $project)
                    @include('projects.invite_user')
                @endcan
            </div>
        </div>
    </main>

@endsection
