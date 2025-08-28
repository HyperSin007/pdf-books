<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::with('category')->where('is_active', true);

        // Search functionality
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        // Category filter
        if ($request->has('category') && $request->category) {
            $query->whereHas('category', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Sort options
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'popular':
                $query->orderBy('download_count', 'desc');
                break;
            case 'rating':
                $query->orderBy('rating', 'desc');
                break;
            case 'title':
                $query->orderBy('title', 'asc');
                break;
            default:
                $query->latest();
        }

        $books = $query->paginate(12);
        $categories = Category::where('is_active', true)->get();
        $featured_books = Book::where('is_featured', true)->where('is_active', true)->take(6)->get();

        return view('books.index', compact('books', 'categories', 'featured_books'));
    }

    public function show(Book $book)
    {
        $book->load('category');
        $related_books = Book::where('category_id', $book->category_id)
            ->where('id', '!=', $book->id)
            ->where('is_active', true)
            ->take(4)
            ->get();

        return view('books.show', compact('book', 'related_books'));
    }

    public function read(Book $book)
    {
        // Increment view count
        $book->increment('view_count');
        
        return view('books.read', compact('book'));
    }

    // Admin methods
    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'pdf_file' => 'required|file|mimes:pdf|max:50000', // 50MB max
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isbn' => 'nullable|max:20',
            'published_date' => 'nullable|date',
            'language' => 'nullable|max:5',
            'pages' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $book = new Book($request->all());
        $book->slug = Str::slug($book->title);

        // Handle PDF file upload
        if ($request->hasFile('pdf_file')) {
            $pdfPath = $request->file('pdf_file')->store('books/pdfs', 'public');
            $book->file_path = $pdfPath;
            $book->file_size = $request->file('pdf_file')->getSize();
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            $imagePath = $request->file('cover_image')->store('books/covers', 'public');
            $book->cover_image = $imagePath;
        }

        $book->save();

        return redirect()->route('admin.books.index')->with('success', 'Book created successfully!');
    }

    public function edit(Book $book)
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|max:255',
            'author' => 'required|max:255',
            'description' => 'nullable',
            'category_id' => 'required|exists:categories,id',
            'pdf_file' => 'nullable|file|mimes:pdf|max:50000',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'isbn' => 'nullable|max:20',
            'published_date' => 'nullable|date',
            'language' => 'nullable|max:5',
            'pages' => 'nullable|integer|min:1',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ]);

        $book->fill($request->all());
        $book->slug = Str::slug($book->title);

        // Handle PDF file upload
        if ($request->hasFile('pdf_file')) {
            // Delete old file
            if ($book->file_path) {
                Storage::disk('public')->delete($book->file_path);
            }
            $pdfPath = $request->file('pdf_file')->store('books/pdfs', 'public');
            $book->file_path = $pdfPath;
            $book->file_size = $request->file('pdf_file')->getSize();
        }

        // Handle cover image upload
        if ($request->hasFile('cover_image')) {
            // Delete old image
            if ($book->cover_image) {
                Storage::disk('public')->delete($book->cover_image);
            }
            $imagePath = $request->file('cover_image')->store('books/covers', 'public');
            $book->cover_image = $imagePath;
        }

        $book->save();

        return redirect()->route('admin.books.index')->with('success', 'Book updated successfully!');
    }

    public function destroy(Book $book)
    {
        // Delete associated files
        if ($book->file_path) {
            Storage::disk('public')->delete($book->file_path);
        }
        if ($book->cover_image) {
            Storage::disk('public')->delete($book->cover_image);
        }

        $book->delete();

        return redirect()->route('admin.books.index')->with('success', 'Book deleted successfully!');
    }

    public function indexAdmin()
    {
        $query = Book::with('category');
        
        if (request()->has('search') && request('search')) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . request('search') . '%')
                  ->orWhere('author', 'like', '%' . request('search') . '%');
            });
        }
        
        if (request()->has('category') && request('category')) {
            $query->where('category_id', request('category'));
        }
        
        $books = $query->latest()->paginate(20);
        $categories = Category::all();
        
        return view('admin.books.index', compact('books', 'categories'));
    }
}
