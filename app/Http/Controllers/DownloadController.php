<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Download;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadController extends Controller
{
    public function download(Request $request, Book $book)
    {
        // Check if book is active
        if (!$book->is_active) {
            abort(404, 'Book not found or not available for download.');
        }

        // Check if file exists
        if (!$book->file_path || !Storage::disk('public')->exists($book->file_path)) {
            abort(404, 'File not found.');
        }

        // Track the download
        Download::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Increment download count
        $book->incrementDownloadCount();

        // Return file download
        $filePath = Storage::disk('public')->path($book->file_path);
        $fileName = $book->title . '.pdf';

        return response()->download($filePath, $fileName);
    }

    // Admin method for download analytics
    public function index(Request $request)
    {
        $query = Download::with(['book', 'user']);

        // Date filter
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Book filter
        if ($request->has('book_id') && $request->book_id) {
            $query->where('book_id', $request->book_id);
        }

        $downloads = $query->latest()->paginate(20);
        $books = Book::orderBy('title')->get();

        // Download statistics
        $stats = [
            'total_downloads' => Download::count(),
            'downloads_today' => Download::whereDate('created_at', today())->count(),
            'downloads_this_week' => Download::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count(),
            'downloads_this_month' => Download::whereMonth('created_at', now()->month)->count(),
        ];

        return view('admin.downloads.index', compact('downloads', 'books', 'stats'));
    }

    public function analytics()
    {
        // Total downloads
        $totalDownloads = Download::count();
        
        // Unique books downloaded
        $uniqueBooks = Download::distinct('book_id')->count();
        
        // Active users (users who downloaded in last 30 days)
        $activeUsers = Download::where('created_at', '>=', now()->subDays(30))
            ->whereNotNull('user_id')
            ->distinct('user_id')
            ->count();
        
        // Monthly downloads
        $monthlyDownloads = Download::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        // Top downloaded books
        $topBooks = Book::withCount('downloads')
            ->orderBy('downloads_count', 'desc')
            ->take(10)
            ->get();
        
        // Category statistics
        $categoryStats = Category::withCount(['books'])
            ->get()
            ->map(function ($category) {
                $category->total_downloads = $category->books->sum(function ($book) {
                    return $book->downloads->count();
                });
                return $category;
            })
            ->sortByDesc('total_downloads');
        
        // Recent downloads
        $recentDownloads = Download::with(['book', 'user'])
            ->latest()
            ->take(10)
            ->get();
        
        // Daily downloads for the last 30 days
        $dailyDownloads = collect();
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i)->format('Y-m-d');
            $count = Download::whereDate('created_at', $date)->count();
            $dailyDownloads->put($date, $count);
        }
        
        return view('downloads.analytics', compact(
            'totalDownloads',
            'uniqueBooks', 
            'activeUsers',
            'monthlyDownloads',
            'topBooks',
            'categoryStats',
            'recentDownloads',
            'dailyDownloads'
        ));
    }
}
