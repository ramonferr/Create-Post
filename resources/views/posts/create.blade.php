<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Post') }}
        </h2>
    </x-slot>

    <div class="p-6">
        <form action="{{ route('posts.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
                <input type="text" id="title" name="title" required class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border">
            </div>
            <div>
                <label for="body" class="block text-sm font-medium text-gray-700">Body:</label>
                <textarea id="body" name="body" required class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border"></textarea>
            </div>
            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create Post</button>
        </form>
    </div>
</x-app-layout>
