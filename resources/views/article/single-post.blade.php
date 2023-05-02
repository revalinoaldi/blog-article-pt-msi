<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __($article->title) }}
        </h2>
    </x-slot>

    {{-- {{dd($article->toArray())}} --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <article class="bg-white rounded-lg shadow-md overflow-hidden">
                    @php
                    $validUrl = filter_var($article->image, FILTER_VALIDATE_URL) ? $article->image : Storage::url(''.$article->image);
                    @endphp
                    <img
                    src="{{ $validUrl }}"
                    alt="{{ $article->slug }}"
                    class="w-full h-48 object-cover"

                    />
                    <div class="px-6 py-4">
                        <h1 class="text-5xl font-bold mb-2">{{ $article->title }}</h1>
                        <div class="text-gray-600 text-sm mb-4">
                            Created on <span class="font-bold">{{ \Carbon\Carbon::parse($article->created_at)->format('F d, Y')}}</span> by
                            <span class="font-bold">{{ $article->author->name }}</span>
                        </div>
                        <div class="text-gray-600 text-sm mb-4">
                            Publish on <span class="font-bold">{{ @$article->is_publish ? \Carbon\Carbon::parse($article->publish_at)->format('F d, Y') : "- (Not published)" }}</span>
                        </div>
                        @auth

                        @if (auth()->user()->id == $article->id || auth()->user()->username == 'administrator')
                        <div class="text-gray-600 text-sm mb-4">
                            <span class="font-bold">Action :</span>
                            <form class="inline-block" action="{{ route('article.update', @$article->slug) }}" method="POST">
                                @method('PUT')
                                @csrf
                                <input type="text" name="is_publish" value="{{ $article->is_publish ? "0" : "1"}}" hidden>
                                <input type="text" name="publish" value="only_publish" hidden>
                                @if ($article->is_publish)
                                <button type="submit" onclick="return confirm('Are you sure want to draf article?')" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-blue-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 ">Draf</button>
                                @else
                                <button type="submit" onclick="return confirm('Are you sure want to publish article?')" class="py-2.5 px-5 mr-2 mb-2 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-blue-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-200 ">Published</button>
                                @endif
                            </form>

                            <a href="{{ route('article.edit', $article->slug) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">Edit</a>
                            <form class="inline-block" action="{{ route('article.destroy', $article->slug) }}" method="POST">
                                @method('delete')
                                @csrf
                                <button type="submit" onclick="return confirm('Are you sure?')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                    Delete
                                </button>
                            </form>

                        </div>
                        @endif
                        @endauth

                        <div class="body-article">
                            {!! $article->body !!}
                        </div>

                        <div class="flex flex-col space-y-4 mt-10 ">
                            <h2 class="text-xl font-semibold mb-4">Komentar ({{ $article->comment->count()}})</h2>

                            @php
                            function defaultProfilePhotoUrl($fullname)
                            {
                                $name = trim(collect(explode(' ', $fullname))->map(function ($segment) {
                                    return mb_substr($segment, 0, 1);
                                })->join(' '));

                                return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
                            }
                            @endphp
                            @foreach ($article->comment as $comment)
                            <div class="flex space-x-4">
                                <img src="{{ defaultProfilePhotoUrl($comment->name) }}" alt="Foto profil" class="w-10 h-10 rounded-full">
                                <div class="flex flex-col">
                                    <span class="font-medium">
                                        {{$comment->name}} - {{$comment->email}}
                                        @auth
                                        <form class="inline-block" action="{{ route('article-comment.destroy', $comment->id) }}" method="POST">
                                            @method('delete')
                                            @csrf

                                            <button type="submit" onclick="return confirm('Are you sure delete this comment?')" class="focus:outline-none text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-900">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="inline-block w-4 h-4 mr-2">
                                                    <path fill-rule="evenodd" d="M2 5a1 1 0 011-1h14a1 1 0 011 1v1H2V5zm15 3a1 1 0 011 1v8a1 1 0 01-1 1H3a1 1 0 01-1-1V9a1 1 0 011-1h14zm-5-2a1 1 0 00-1 1v4a1 1 0 002 0V7a1 1 0 00-1-1zM8 6a1 1 0 00-1 1v4a1 1 0 002 0V7a1 1 0 00-1-1z" clip-rule="evenodd" />
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                        @endauth

                                    </span>

                                    <span class="text-gray-500 text-sm">{{ Carbon\Carbon::parse($comment->created_at)->diffForHumans(); }}</span>
                                </div>
                            </div>
                            <div class="ml-14">
                                <p>{{ $comment->body }}</p>
                            </div>
                            @endforeach
                        </div>


                        <form action="{{ route('article-comment.store') }}" class="p-4 bg-gray-100 mt-10" method="POST">
                            @csrf
                            <div class="grid gap-6 mb-6 md:grid-cols-2">
                                <div >
                                    <label class="block text-gray-700 font-medium mb-2" for="name">Nama</label>
                                    <input type="text" name="article_id" id="article_id" hidden value="{{ $article->id }}">
                                    <input type="text" name="slug" id="slug" hidden value="{{ $article->slug }}">
                                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md" type="text" id="name" name="name" placeholder="Masukkan nama Anda" required>
                                </div>
                                <div >
                                    <label class="block text-gray-700 font-medium mb-2" for="email">Email</label>
                                    <input class="w-full px-3 py-2 border border-gray-300 rounded-md" type="email" id="email" name="email" placeholder="Masukkan alamat email Anda" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-gray-700 font-medium mb-2" for="comment">Komentar</label>
                                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md" id="comment" name="comment" rows="4" placeholder="Tulis komentar Anda" required></textarea>
                            </div>
                            <div class="flex justify-end">
                                <button class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 transition-colors duration-300" type="submit">Kirim</button>
                            </div>
                        </form>
                    </div>
                </article>
            </div>
        </div>
    </div>
</x-app-layout>
