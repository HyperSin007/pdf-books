@extends('layouts.app')

@section('title', 'Read: ' . $book->title)

@section('content')
<div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('books.show', $book) }}" 
                       class="text-gray-600 hover:text-gray-900 mr-4">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div>
                        <h1 class="text-lg font-semibold text-gray-900 truncate">{{ $book->title }}</h1>
                        <p class="text-sm text-gray-600">by {{ $book->author }}</p>
                    </div>
                </div>
                
                <div class="flex items-center space-x-4">
                    <!-- Zoom Controls -->
                    <div class="flex items-center space-x-2">
                        <button onclick="zoomOut()" class="p-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6"/>
                            </svg>
                        </button>
                        <span id="zoom-level" class="text-sm text-gray-600">100%</span>
                        <button onclick="zoomIn()" class="p-2 text-gray-600 hover:text-gray-900">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Download Button -->
                    <a href="{{ route('books.download', $book) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Viewer -->
    <div class="flex-1">
        <div id="pdf-container" class="max-w-4xl mx-auto p-4">
            <div id="pdf-loading" class="text-center py-12">
                <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                <p class="mt-2 text-gray-600">Loading PDF...</p>
            </div>
            
            <div id="pdf-error" class="hidden text-center py-12">
                <svg class="mx-auto h-12 w-12 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 15.5c-.77.833.192 2.5 1.732 2.5z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">Error loading PDF</h3>
                <p class="mt-1 text-sm text-gray-500">The PDF file could not be loaded. Please try downloading instead.</p>
                <div class="mt-6">
                    <a href="{{ route('books.download', $book) }}" 
                       class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Download PDF
                    </a>
                </div>
            </div>

            <canvas id="pdf-canvas" class="hidden mx-auto shadow-lg rounded-lg"></canvas>
            
            <!-- Page Navigation -->
            <div id="pdf-controls" class="hidden items-center justify-center mt-4 space-x-4">
                <button onclick="previousPage()" id="prev-btn" 
                        class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <span id="page-info" class="text-sm text-gray-600">Page 1 of 1</span>
                <button onclick="nextPage()" id="next-btn" 
                        class="px-3 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.min.js"></script>
<script>
let pdfDoc = null;
let pageNum = 1;
let pageIsRendering = false;
let pageNumIsPending = null;
let scale = 1.0;

const canvas = document.getElementById('pdf-canvas');
const ctx = canvas.getContext('2d');

// PDF.js worker
pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

// Render the page
const renderPage = (num) => {
    pageIsRendering = true;

    pdfDoc.getPage(num).then(page => {
        const viewport = page.getViewport({ scale });
        canvas.height = viewport.height;
        canvas.width = viewport.width;

        const renderCtx = {
            canvasContext: ctx,
            viewport
        };

        page.render(renderCtx).promise.then(() => {
            pageIsRendering = false;

            if (pageNumIsPending !== null) {
                renderPage(pageNumIsPending);
                pageNumIsPending = null;
            }
        });

        // Update page info
        document.getElementById('page-info').textContent = `Page ${num} of ${pdfDoc.numPages}`;
        
        // Update button states
        document.getElementById('prev-btn').disabled = num <= 1;
        document.getElementById('next-btn').disabled = num >= pdfDoc.numPages;
    });
};

// Queue page render
const queueRenderPage = (num) => {
    if (pageIsRendering) {
        pageNumIsPending = num;
    } else {
        renderPage(num);
    }
};

// Previous page
const previousPage = () => {
    if (pageNum <= 1) return;
    pageNum--;
    queueRenderPage(pageNum);
};

// Next page
const nextPage = () => {
    if (pageNum >= pdfDoc.numPages) return;
    pageNum++;
    queueRenderPage(pageNum);
};

// Zoom functions
const zoomIn = () => {
    scale += 0.2;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
};

const zoomOut = () => {
    if (scale <= 0.4) return;
    scale -= 0.2;
    document.getElementById('zoom-level').textContent = Math.round(scale * 100) + '%';
    queueRenderPage(pageNum);
};

// Load PDF
const loadPDF = () => {
    const pdfUrl = '{{ asset(str_replace('public/', 'storage/', $book->file_path)) }}';
    
    pdfjsLib.getDocument(pdfUrl).promise.then(pdf => {
        pdfDoc = pdf;
        
        // Hide loading, show canvas and controls
        document.getElementById('pdf-loading').classList.add('hidden');
        document.getElementById('pdf-canvas').classList.remove('hidden');
        document.getElementById('pdf-controls').classList.remove('hidden');
        document.getElementById('pdf-controls').classList.add('flex');
        
        // Render first page
        renderPage(pageNum);
    }).catch(error => {
        console.error('Error loading PDF:', error);
        
        // Hide loading, show error
        document.getElementById('pdf-loading').classList.add('hidden');
        document.getElementById('pdf-error').classList.remove('hidden');
    });
};

// Keyboard navigation
document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
        previousPage();
    } else if (e.key === 'ArrowRight') {
        nextPage();
    } else if (e.key === '+' || (e.key === '=' && e.ctrlKey)) {
        e.preventDefault();
        zoomIn();
    } else if (e.key === '-' && e.ctrlKey) {
        e.preventDefault();
        zoomOut();
    }
});

// Load PDF when page loads
document.addEventListener('DOMContentLoaded', loadPDF);
</script>
@endsection
