@extends('layouts.app')

@section('title', $category->name . ' Books')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Category Header -->
    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-8 text-white mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold mb-2">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="text-indigo-100 text-lg mb-4">{{ $category->description }}</p>
                @endif
                <div class="flex items-center space-x-6 text-indigo-100">
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ $books->total() }} books
                    </span>
                    <span class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"/>
                        </svg>
                        {{ $books->sum(function($book) { return $book->downloads->count(); }) }} downloads
                    </span>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="h-20 w-20 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                    <span class="text-2xl font-bold">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <div class="flex-1">
            <form method="GET" class="relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Search books in {{ $category->name }}..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </form>
        </div>
        <div class="flex gap-2">
            <select name="sort" onchange="window.location.href = updateUrlParameter(window.location.href, 'sort', this.value)" 
                    class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author A-Z</option>
                <option value="downloads" {{ request('sort') == 'downloads' ? 'selected' : '' }}>Most Downloaded</option>
            </select>
        </div>
    </div>

    <!-- Books Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
        @forelse($books as $book)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                <!-- Book Cover -->
                <div class="aspect-w-3 aspect-h-4">
                    <img src="{{ $book->cover_image_url }}" 
                         alt="{{ $book->title }}" 
                         class="w-full h-48 object-cover">
                </div>
                
                <!-- Book Info -->
                <div class="p-4">
                    <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2">{{ $book->title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">by {{ $book->author }}</p>
                    
                    @if($book->description)
                        <p class="text-gray-600 text-sm mb-3 line-clamp-2">{{ $book->description }}</p>
                    @endif
                    
                    <!-- Book Stats -->
                    <div class="flex items-center justify-between text-xs text-gray-500 mb-3">
                        <span>{{ number_format($book->file_size, 1) }} MB</span>
                        @if($book->page_count)
                            <span>{{ $book->page_count }} pages</span>
                        @endif
                        <span>{{ $book->downloads->count() }} downloads</span>
                    </div>

                    <!-- Actions -->
                    <div class="flex gap-2">
                        <a href="{{ route('books.show', $book) }}" 
                           class="flex-1 bg-indigo-600 text-white text-center py-2 px-3 rounded-md text-sm font-medium hover:bg-indigo-700 transition-colors duration-200">
                            View
                        </a>
                        <a href="{{ route('books.download', $book) }}" 
                           class="flex-1 bg-green-600 text-white text-center py-2 px-3 rounded-md text-sm font-medium hover:bg-green-700 transition-colors duration-200">
                            Download
                        </a>
                    </div>
                </div>

                <!-- Featured Badge -->
                @if($book->is_featured)
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-400 text-yellow-900 px-2 py-1 rounded-full text-xs font-medium">Featured</span>
                    </div>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No books found</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request('search'))
                        No books match your search in this category.
                    @else
                        No books are available in this category yet.
                    @endif
                </p>
                @if(request('search'))
                    <div class="mt-6">
                        <a href="{{ route('categories.show', $category) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                            Clear Search
                        </a>
                    </div>
                @endif
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($books->hasPages())
        <div class="flex justify-center">
            {{ $books->appends(request()->query())->links() }}
        </div>
    @endif
</div>

<script>
function updateUrlParameter(url, param, paramVal) {
    var newAdditionalURL = "";
    var tempArray = url.split("?");
    var baseURL = tempArray[0];
    var additionalURL = tempArray[1];
    var temp = "";
    if (additionalURL) {
        tempArray = additionalURL.split("&");
        for (var i = 0; i < tempArray.length; i++) {
            if (tempArray[i].split('=')[0] != param) {
                newAdditionalURL += temp + tempArray[i];
                temp = "&";
            }
        }
    }
    var rows_txt = temp + "" + param + "=" + paramVal;
    return baseURL + "?" + newAdditionalURL + rows_txt;
}
</script>
@endsection
