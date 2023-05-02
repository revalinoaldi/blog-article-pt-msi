<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Form Category Article') }}
        </h2>
    </x-slot>

    <script>
        tinymce.init({
            selector:'textarea.body',
            // width: 900,
            height: 300
        });
        let loadFile = function(event,target) {
            let avatar = document.getElementById(target);
            avatar.src = URL.createObjectURL(event.target.files[0]);
            avatar.onload = function() {
                URL.revokeObjectURL(avatar.src) // free memory
            }
        };
    </script>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-7">
                @if ($errors->any())
                <div class="mb-5" role="alert">
                    <div class="bg-red-500 text-white font-bold rounded-t px-4 py-2">
                        There's something wrong!
                    </div>
                    <div class="border border-t-0 border-red-400 rounded-b bg-red-100 px-4 py-3 text-red-700">
                        <p>
                            <ul>
                                @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </p>
                    </div>
                </div>
                @endif
                <form class="w-full" action="{{ @$article->slug ? route('article.update', @$article->slug) : route('article.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    @if (@$article->slug)
                    @method('PUT')
                    @endif
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="title">
                                Title
                            </label>
                            <input value="{{ old('title', @$article->title) }}" name="title" class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="title-article" type="text" placeholder="Title Article" required>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="body">
                                Body Article
                            </label>
                            <textarea class="body" name="body" required>
                                {!! old('body', @$article->body) !!}
                            </textarea>
                            {{-- <input value="{{ old('title', @$article->title) }}" name="title" class="appearance-none block w-full text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="title-article" type="text" placeholder="Title Article"> --}}
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Article Cateogry
                            </label>
                            <select id="categories" name="categories" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">-- Select One --</option>
                                @foreach ($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categories', @$article->category_id) == $cat->id ? "selected" : "" }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                Image Upload
                            </label>
                            <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" accept="image/*" aria-describedby="file_input_help" onchange="loadFile(event,`avatarPreview`)" id="file_input" name="file_input" type="file">
                            <p class="mt-1 text-sm text-gray-500" id="file_input_help">Only : SVG, PNG, JPG (MAX. 2Mb).</p>
                            <div class="flex justify-start mt-2">
                                <span class="mt-1 text-sm text-gray-500"> Output : &nbsp;</span>
                                @php
                                    if(@$article->image){
                                        $validUrl = filter_var($article->image, FILTER_VALIDATE_URL) ? $article->image : Storage::url($article->image);
                                    }else{
                                        $validUrl = "https://via.placeholder.com/800x400";
                                    }

                                @endphp
                                <img class="h-auto max-w-lg rounded-lg" src="{{ $validUrl }}" id="avatarPreview" alt="image description">
                            </div>
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3">
                            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                                On Publish
                            </label>
                            <select id="is_publish" name="is_publish" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 ">
                                <option value="">-- Select One --</option>
                                {{-- <option selected>Choose a country</option> --}}
                                <option value="1" {{ old('is_publish', @$article->is_publish) == "1" ? "selected" : "" }}>Publish</option>
                                <option value="0" {{ old('is_publish', @$article->is_publish) == "0" ? "selected" : "" }}>Draft</option>

                            </select>
                            {{-- <input value="{{ old('name', @$article->slug) }}" name="name" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500" id="grid-last-name" type="text" placeholder="Category Name"> --}}
                        </div>
                    </div>
                    <div class="flex flex-wrap -mx-3 mb-6">
                        <div class="w-full px-3 text-right">
                            <button type="submit" class=" shadow-lg bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Save Category
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
