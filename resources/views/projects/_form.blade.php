@csrf

<div class="field mb-6">
    <label class="label text-sm mb-2 block" for="title">Title</label>

    <div class="control">
        <input
            type="text"
            name="title"
            value="{{ $project->title }}"
            class="input bg-transparent border border-muted-light rounded p-2 text-xs w-full"
            placeholder="title">
    </div>
</div>

<div class="field mb-6">
    <label class="label text-sm mb-2 block" for="description">Description</label>

    <div class="control">
        <textarea
            type="text"
            name="description"
            class="textarea bg-transparent border border-muted-light rounded p-2 text-xs w-full"
            placeholder="description">{{ $project->description }}</textarea>
    </div>
</div>

<div class="field">
    <div class="control">
        <button type="submit" class="button is-link mr-2">{{ $buttonText }}</button>

        <a href="{{ $project->path() }}">Cancel</a>
    </div>
</div>

@include('errors')
