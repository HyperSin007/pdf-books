@extends('layouts.app')

@section('title', $book->title)

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-8" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-700 hover:text-primary-600">
                        Home
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <a href="{{ route('books.index') }}" class="text-gray-700 hover:text-primary-600">Books</a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <a href="{{ route('categories.show', $book->category) }}" class="text-gray-700 hover:text-primary-600">{{ $book->category->name }}</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="mx-2 text-gray-400">/</span>
                        <span class="text-gray-500">{{ $book->title }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Book Cover and Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg p-6 sticky top-8">
                    <!-- Book Cover -->
                    <div class="aspect-[3/4] bg-gray-200 rounded-lg overflow-hidden mb-6">
                        <img src="{{ $book->getCoverUrl() }}" 
                             alt="{{ $book->title }}" 
                             class="w-full h-full object-cover">
                    </div>

                    <!-- Download Actions -->
                    <div class="space-y-3">
                        <a href="{{ route('books.read', $book) }}" 
                           class="w-full bg-indigo-600 text-white py-3 px-4 rounded-lg hover:bg-indigo-700 transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Read Online
                        </a>
                        
                        <a href="{{ route('books.download', $book) }}" 
                           class="w-full bg-primary-600 text-white py-3 px-4 rounded-lg hover:bg-primary-700 transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Download PDF
                        </a>

                        @auth
                        <button class="w-full bg-gray-100 text-gray-700 py-3 px-4 rounded-lg hover:bg-gray-200 transition flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            Add to Favorites
                        </button>
                        @endauth
                    </div>

                    <!-- Book Stats -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-4">Book Statistics</h3>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Downloads:</span>
                                <span class="font-medium">{{ number_format($book->download_count) }}</span>
                            </div>
                            @if($book->rating > 0)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Rating:</span>
                                <div class="flex items-center">
                                    <span class="font-medium mr-1">{{ number_format($book->rating, 1) }}</span>
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $book->rating ? 'fill-current' : 'text-gray-300' }}" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        @endfor
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($book->file_size)
                            <div class="flex justify-between">
                                <span class="text-gray-600">File Size:</span>
                                <span class="font-medium">{{ $book->getFileSizeFormatted() }}</span>
                            </div>
                            @endif
                            @if($book->pages)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Pages:</span>
                                <span class="font-medium">{{ number_format($book->pages) }}</span>
                            </div>
                            @endif
                            @if($book->language)
                            <div class="flex justify-between">
                                <span class="text-gray-600">Language:</span>
                                <span class="font-medium">{{ strtoupper($book->language) }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Details -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <!-- Book Header -->
                    <div class="mb-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium" 
                                  style="background-color: {{ $book->category->color }}20; color: {{ $book->category->color }};">
                                {{ $book->category->name }}
                            </span>
                            @if($book->is_featured)
                            <span class="ml-2 inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                Featured
                            </span>
                            @endif
                        </div>
                        
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $book->title }}</h1>
                        <p class="text-xl text-gray-600 mb-4">by {{ $book->author }}</p>

                        @if($book->published_date)
                        <p class="text-gray-500 mb-2">Published: {{ $book->published_date->format('F d, Y') }}</p>
                        @endif

                        @if($book->isbn)
                        <p class="text-gray-500">ISBN: {{ $book->isbn }}</p>
                        @endif
                    </div>

                    <!-- Book Description -->
                    @if($book->description)
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold text-gray-900 mb-4">Description</h2>
                        <div class="prose max-w-none text-gray-700">
                            {!! nl2br(e($book->description)) !!}
                        </div>
                    </div>
                    @endif

                    <!-- Additional Info -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">Book Details</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Category:</dt>
                                    <dd>
                                        <a href="{{ route('categories.show', $book->category) }}" 
                                           class="text-primary-600 hover:text-primary-800">
                                            {{ $book->category->name }}
                                        </a>
                                    </dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Author:</dt>
                                    <dd class="font-medium">{{ $book->author }}</dd>
                                </div>
                                @if($book->published_date)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Published:</dt>
                                    <dd>{{ $book->published_date->format('Y') }}</dd>
                                </div>
                                @endif
                                @if($book->language)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Language:</dt>
                                    <dd>{{ strtoupper($book->language) }}</dd>
                                </div>
                                @endif
                            </dl>
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-900 mb-3">File Information</h3>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Format:</dt>
                                    <dd class="font-medium">PDF</dd>
                                </div>
                                @if($book->file_size)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">File Size:</dt>
                                    <dd>{{ $book->getFileSizeFormatted() }}</dd>
                                </div>
                                @endif
                                @if($book->pages)
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Pages:</dt>
                                    <dd>{{ number_format($book->pages) }}</dd>
                                </div>
                                @endif
                                <div class="flex justify-between">
                                    <dt class="text-gray-600">Downloads:</dt>
                                    <dd>{{ number_format($book->download_count) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <!-- Share -->
                    <div class="border-t border-gray-200 pt-6">
                        <h3 class="font-semibold text-gray-900 mb-3">Share this book</h3>
                        <div class="flex space-x-3">
                            <button class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                                Facebook
                            </button>
                            <button class="bg-blue-400 text-white px-4 py-2 rounded-lg hover:bg-blue-500 transition">
                                Twitter
                            </button>
                            <button class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition">
                                Email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Books -->
        @if($related_books->count() > 0)
        <div class="mt-12">
            <h2 class="text-2xl font-bold text-gray-900 mb-8">Related Books</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($related_books as $relatedBook)
                <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-lg transition-shadow duration-300">
                    <div class="aspect-[3/4] bg-gray-200 relative">
                        <img src="{{ $relatedBook->getCoverUrl() }}" 
                             alt="{{ $relatedBook->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-lg mb-2 line-clamp-2">{{ $relatedBook->title }}</h3>
                        <p class="text-gray-600 text-sm mb-2">by {{ $relatedBook->author }}</p>
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <span>{{ $relatedBook->download_count }} downloads</span>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('books.show', $relatedBook) }}" 
                               class="flex-1 bg-primary-600 text-white text-center py-2 px-4 rounded-lg hover:bg-primary-700 transition">
                                View
                            </a>
                            <a href="{{ route('books.download', $relatedBook) }}" 
                               class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition">
                                📥
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
