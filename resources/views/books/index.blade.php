@extends('layouts.app')

@section('title', 'Browse Books')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Hero Section -->
        @if(request()->routeIs('home'))
        <div class="bg-gradient-to-r from-primary-600 to-primary-800 rounded-xl text-white mb-12 p-8">
            <div class="max-w-4xl mx-auto text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">
                    Discover Amazing PDF Books
                </h1>
                <p class="text-xl mb-8 text-gray-200">
                    Browse, read, and download thousands of books across various categories. 
                    Start your reading journey today!
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('books.index') }}" 
                       class="bg-white text-primary-600 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition">
                        Browse Books
                    </a>
                    <a href="{{ route('categories.index') }}" 
                       class="border border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary-600 transition">
                        View Categories
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Featured Books Section -->
        @if(isset($featured_books) && $featured_books->count() > 0)
        <div class="mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-8">Featured Books</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
                @foreach($featured_books as $book)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-[3/4] bg-gray-200 relative">
                        <img src="{{ $book->getCoverUrl() }}" 
                             alt="{{ $book->title }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute top-2 right-2">
                            <span class="bg-primary-500 text-white text-xs px-2 py-1 rounded-full">
                                Featured
                            </span>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-sm mb-1 line-clamp-2">{{ $book->title }}</h3>
                        <p class="text-gray-600 text-xs mb-2">{{ $book->author }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $book->download_count }} downloads</span>
                            <a href="{{ route('books.show', $book) }}" 
                               class="text-primary-600 hover:text-primary-800">
                                View
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <form action="{{ route('books.index') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Search Books</label>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}"
                               placeholder="Search by title, author, or description..." 
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                        <select name="category" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Sort By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sort By</label>
                        <select name="sort" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="title" {{ request('sort') == 'title' ? 'selected' : '' }}>Title A-Z</option>
                            <option value="author" {{ request('sort') == 'author' ? 'selected' : '' }}>Author A-Z</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end">
                    <button type="submit" 
                            class="bg-primary-600 text-white px-6 py-2 rounded-lg hover:bg-primary-700 transition">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Books Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse($books as $book)
            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                <div class="aspect-[3/4] bg-gray-200 relative">
                    <img src="{{ $book->getCoverUrl() }}" 
                         alt="{{ $book->title }}" 
                         class="w-full h-full object-cover">
                    @if($book->is_featured)
                    <div class="absolute top-2 right-2">
                        <span class="bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                            Featured
                        </span>
                    </div>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ $book->title }}</h3>
                    <p class="text-gray-600 text-sm mb-2">by {{ $book->author }}</p>
                    <div class="flex items-center mb-3">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                              style="background-color: {{ $book->category->color }}20; color: {{ $book->category->color }};">
                            {{ $book->category->name }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                        <span>{{ $book->download_count }} downloads</span>
                        @if($book->file_size)
                        <span>{{ $book->getFileSizeFormatted() }}</span>
                        @endif
                    </div>
                    <div class="flex space-x-2">
                        <a href="{{ route('books.show', $book) }}" 
                           class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-700 transition">
                            View Details
                        </a>
                        <a href="{{ route('books.read', $book) }}" 
                           class="bg-indigo-600 text-white py-2 px-3 rounded-lg hover:bg-indigo-700 transition flex items-center" 
                           title="Read Online">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                        <a href="{{ route('books.download', $book) }}" 
                           class="bg-green-600 text-white py-2 px-3 rounded-lg hover:bg-green-700 transition flex items-center" 
                           title="Download PDF">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No books found</h3>
                <p class="mt-1 text-sm text-gray-500">Try adjusting your search criteria or browse all categories.</p>
                <div class="mt-6">
                    <a href="{{ route('books.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700">
                        View All Books
                    </a>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($books->hasPages())
        <div class="mt-12">
            {{ $books->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
