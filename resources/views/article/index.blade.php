<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Article Page') }}
        </h2>
    </x-slot>

    {{-- {{dd($articles)}} --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="mb-5">
                    <a href="{{ route('article.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded shadow-lg">
                        + Create Article
                    </a>
                </div>
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6 ">
                        <div class="flex justify-end mb-3 ">
                            <form class="flex items-center w-96" action="{{ route('article.index') }}">
                                @csrf
                                <label for="simple-search" class="sr-only">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                        <svg aria-hidden="true" class="w-5 h-5 text-gray-500 " fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                                    </div>
                                    <input type="text" name="search" value="{{ old('search', request('search')) }}" id="simple-search" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5  " placeholder="Search" required>
                                </div>
                                <button type="submit" class="p-2.5 ml-2 text-sm font-medium text-white bg-blue-700 rounded-lg border border-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                    <span class="sr-only">Search</span>
                                </button>
                            </form>

                        </div>
                        {{-- Start Tabel Article --}}
                        <div class="relative overflow-x-auto sm:rounded-lg mb-5">
                            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400" id="crudTable">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
                                    <tr>
                                        <th scope="col" class="px-6 py-3">
                                            No.
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Title
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Category
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Author
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Body
                                        </th>
                                        <th scope="col" class="px-6 py-3">
                                            Status Publish
                                        </th>
                                        {{-- <th scope="col" class="px-6 py-3">
                                            Action
                                        </th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($articles as $item)
                                    {{-- {{ dd($item['category']['name']) }} --}}
                                    <tr class=" border-b @if ($loop->iteration % 2 == 0) 'bg-white' @else 'bg-gray-50' @endif ">
                                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap ">
                                            {{ ($articles->currentPage()-1) * $articles->perPage() + $loop->index + 1 }}
                                        </th>
                                        <td class="px-6 py-4">
                                            <a href="{{ route('article.show', $item['slug']) }}" class="inline-flex items-center font-medium text-blue-600 dark:text-blue-500 hover:underline">{{ $item['title'] }}</a>
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item['category']['name'] }}
                                        </td>
                                        <td class="px-6 py-4">
                                            {{ $item['author']['name'] }}
                                        </td>
                                        <td class="px-4 py-4">
                                            <p>{{ $item['excerpt'] }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center {{ $item['is_publish'] ? "bg-green-100 text-green-800" : "bg-red-100 text-red-800" }} text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">
                                                <span class="flex w-2.5 h-2.5 {{ $item['is_publish'] ? "bg-green-500" : "bg-red-700" }} rounded-full mr-1.5 flex-shrink-0"></span>
                                                {{ $item['is_publish'] ? "Publish" : "Not Publish" }}
                                            </span>
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>

                        <div class="flex justify-end">
                            {{ $articles->links() }}
                        </div>
                    </div>
                </div>

                {{-- <x-welcome /> --}}

            </div>
        </div>
    </div>
</x-app-layout>
