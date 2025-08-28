@extends('layouts.app')

@section('title', 'Download Analytics')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Download Analytics</h1>
        <p class="text-gray-600">Track book downloads and popular content across the platform</p>
    </div>

    <!-- Overview Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Downloads</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $totalDownloads }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Unique Books</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $uniqueBooks }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Users</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $activeUsers }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">This Month</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $monthlyDownloads }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Most Downloaded Books -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Most Downloaded Books</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($topBooks as $index => $book)
                        <div class="flex items-center">
                            <div class="flex-shrink-0 w-8 h-8 bg-indigo-100 rounded-full flex items-center justify-center">
                                <span class="text-sm font-medium text-indigo-600">{{ $index + 1 }}</span>
                            </div>
                            <div class="ml-4 flex-1">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">{{ $book->title }}</p>
                                        <p class="text-sm text-gray-500">by {{ $book->author }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium text-gray-900">{{ $book->downloads_count }} downloads</p>
                                        <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                            <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $book->downloads_count > 0 ? ($book->downloads_count / $topBooks->first()->downloads_count) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No downloads yet</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Categories Performance -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Downloads by Category</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @forelse($categoryStats as $category)
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-xs font-medium text-purple-600">{{ strtoupper(substr($category->name, 0, 2)) }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $category->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $category->books_count }} books</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium text-gray-900">{{ $category->total_downloads }}</p>
                                <div class="w-20 bg-gray-200 rounded-full h-2 mt-1">
                                    <div class="bg-purple-600 h-2 rounded-full" style="width: {{ $category->total_downloads > 0 ? ($category->total_downloads / $categoryStats->max('total_downloads')) * 100 : 0 }}%"></div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 text-center py-4">No data available</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Downloads -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Recent Downloads</h3>
        </div>
        <div class="overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Book</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Size</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($recentDownloads as $download)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <img class="h-10 w-8 object-cover rounded" src="{{ $download->book->cover_image_url }}" alt="{{ $download->book->title }}">
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $download->book->title }}</div>
                                        <div class="text-sm text-gray-500">{{ $download->book->author }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($download->user)
                                        {{ $download->user->name }}
                                        <div class="text-xs text-gray-500">{{ $download->user->email }}</div>
                                    @else
                                        <span class="text-gray-500">Guest</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $download->created_at->format('M d, Y H:i') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($download->book->file_size, 1) }} MB
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">No downloads yet</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($recentDownloads->count() >= 10)
            <div class="px-6 py-3 bg-gray-50 text-center">
                <p class="text-sm text-gray-500">Showing latest 10 downloads</p>
            </div>
        @endif
    </div>

    <!-- Download Trends -->
    <div class="mt-8 bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Download Trends (Last 30 Days)</h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-7 gap-2">
                @for($i = 29; $i >= 0; $i--)
                    @php
                        $date = now()->subDays($i);
                        $count = $dailyDownloads->get($date->format('Y-m-d'), 0);
                        $maxCount = $dailyDownloads->max() ?: 1;
                        $height = $count > 0 ? max(20, ($count / $maxCount) * 100) : 8;
                    @endphp
                    <div class="text-center">
                        <div class="bg-indigo-200 rounded-sm mb-1 mx-auto" 
                             style="height: {{ $height }}px; width: 20px;"
                             title="{{ $date->format('M d') }}: {{ $count }} downloads">
                        </div>
                        <div class="text-xs text-gray-500">{{ $date->format('j') }}</div>
                    </div>
                @endfor
            </div>
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">Daily download activity over the past month</p>
            </div>
        </div>
    </div>
</div>
@endsection
