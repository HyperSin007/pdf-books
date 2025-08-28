@extends('layouts.app')

@section('title', 'Categories')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Browse Categories</h1>
        <p class="text-xl text-gray-600">Discover books organized by topic and genre</p>
    </div>

    <!-- Categories Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($categories as $category)
            <div class="bg-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300">
                <div class="p-6">
                    <!-- Category Icon/Initial -->
                    <div class="flex items-center mb-4">
                        <div class="flex-shrink-0 h-12 w-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                            <span class="text-xl font-bold text-white">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $category->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $category->books->count() }} books</p>
                        </div>
                    </div>

                    <!-- Description -->
                    @if($category->description)
                        <p class="text-gray-600 mb-4 line-clamp-3">{{ $category->description }}</p>
                    @endif

                    <!-- Sample Books -->
                    @if($category->books->count() > 0)
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Featured Books:</h4>
                            <div class="space-y-1">
                                @foreach($category->books->take(3) as $book)
                                    <div class="text-sm text-gray-600 truncate">
                                        "{{ $book->title }}" by {{ $book->author }}
                                    </div>
                                @endforeach
                                @if($category->books->count() > 3)
                                    <div class="text-sm text-gray-500">
                                        and {{ $category->books->count() - 3 }} more...
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- View Category Button -->
                    <div class="flex justify-between items-center">
                        <a href="{{ route('categories.show', $category) }}" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-200">
                            Browse Books
                            <svg class="ml-2 -mr-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        
                        <!-- Download Count for Category -->
                        @php
                            $totalDownloads = $category->books->sum(function($book) {
                                return $book->downloads->count();
                            });
                        @endphp
                        @if($totalDownloads > 0)
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                {{ $totalDownloads }} downloads
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
                <p class="mt-1 text-sm text-gray-500">Categories will appear here once they are added.</p>
            </div>
        @endforelse
    </div>

    <!-- Popular Categories Section -->
    @if($categories->count() > 0)
        <div class="mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Popular Categories</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                @foreach($categories->sortByDesc(function($category) { return $category->books->count(); })->take(4) as $category)
                    <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg p-6 text-white">
                        <h3 class="text-lg font-semibold mb-2">{{ $category->name }}</h3>
                        <p class="text-indigo-100 mb-4">{{ $category->books->count() }} books available</p>
                        <a href="{{ route('categories.show', $category) }}" 
                           class="inline-flex items-center text-sm font-medium text-white hover:text-indigo-200">
                            Explore →
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
