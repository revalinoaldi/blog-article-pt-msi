<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Page') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                @foreach ($articles as $article)
                <div>
                    <div class="max-w-sm bg-white border border-gray-200 rounded-lg shadow ">
                        @php
                            $routeUrl = Auth::check() ? "article.show" : "article-detail";
                        @endphp
                        <a href="{{ route($routeUrl, $article['slug']) }}">
                            @php
                            $validUrl = filter_var($article['image'], FILTER_VALIDATE_URL) ? $article['image'] : Storage::url(''.$article['image']);
                            @endphp
                            <img class="rounded-t-lg" src="{{ $validUrl }}" alt="" />
                        </a>
                        <div class="p-5">
                            <a href="{{ route($routeUrl, $article['slug']) }}">
                                <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 ">{{$article['title']}}</h5>
                            </a>
                            <div class="text-gray-600 text-sm mb-4">
                                Created on <span class="font-bold">{{ \Carbon\Carbon::parse($article['created_at'])->format('F d, Y')}}</span> <br>
                                Publish on <span class="font-bold">{{ @$article['is_publish'] ? \Carbon\Carbon::parse($article['publish_at'])->format('F d, Y') : "- (Not published)" }}</span><br> by
                                <span class="font-bold">{{ $article['author']['name'] }}</span>
                            </div>
                            <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">
                                {{$article['excerpt']}}
                            </p>
                            <a href="{{ route($routeUrl, $article['slug']) }}" class="inline-flex items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                Read more
                                <svg aria-hidden="true" class="w-4 h-4 ml-2 -mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
            <div class="flex justify-end mr-3 mt-3">
                {{ $articles->links() }}
            </div>

        </div>
    </div>
</x-app-layout>
