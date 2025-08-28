<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Category;
use App\Models\Download;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $stats = [
            'total_books' => Book::count(),
            'total_categories' => Category::count(),
            'total_downloads' => Download::count(),
            'total_users' => User::count(),
            'featured_books' => Book::where('is_featured', true)->count(),
        ];

        $recent_downloads = Download::with(['book', 'user'])
            ->latest()
            ->take(10)
            ->get();

        $popular_books = Book::with('category')
            ->orderBy('download_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'recent_downloads', 'popular_books'));
    }
}
