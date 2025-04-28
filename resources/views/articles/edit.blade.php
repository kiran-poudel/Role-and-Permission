<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Articles / Edit
            </h2>
            <a class="bg-slate-700 text-sm rounded-md text-white px-3 py-2" href="{{route('articles.list')}}">Back</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{route('articles.update', $article->id)}}" method="post">
                        @csrf
                        <div>
                            <label for="" class="text-lg font-medium">Title</label>
                            <div class="my-3">
                                <input value="{{old('title', $article->title)}}" type="text"
                                    class="border-gray-300 shadow-sm w-50% rounded-lg" placeholder="Title" name="title">
                                @error('title')
                                    <p class="text-red-400 font-medium">{{$message}} </p>
                                @enderror
                            </div>

                            <label for="" class="text-lg font-medium">Content</label>
                            <div class="my-3">
                                <textarea name="text" id="text" cols="30" rows="10"
                                    class="border-gray-300 shadow-sm w-50% rounded-lg"
                                    placeholder="Content">{{old('text', $article->text)}}</textarea>

                            </div>

                            <label for="" class="text-lg font-medium">Author</label>
                            <div class="my-3">
                                <input value="{{old('author', $article->author)}}" type="text"
                                    class="border-gray-300 shadow-sm w-50% rounded-lg" placeholder="Author"
                                    name="author">
                                @error('author')
                                    <p class="text-red-400 font-medium">{{$message}} </p>
                                @enderror
                            </div>
                            <button class="bg-slate-700 text-sm rounded-md text-white px-5 py-3">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>