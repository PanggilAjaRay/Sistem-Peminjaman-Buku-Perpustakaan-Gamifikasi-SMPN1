<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class BookForm extends Component
{
    use WithPagination, WithFileUploads;

    public $title, $isbn, $author, $publisher, $year, $stock, $category_id, $cover_image;
    public $old_cover_image; // Store old image path for deletion
    public $bookId;
    public $isOpen = false;
    public $search = '';
    public $uploadProgress = false;
    
    protected function rules()
    {
        $rules = [
            'title' => 'required|string|max:255',
            'isbn' => 'nullable|string|max:20|unique:books,isbn',
            'author' => 'required|string|max:255',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
        ];

        // If editing, exclude current book from ISBN unique validation
        if ($this->bookId) {
            $rules['isbn'] = 'nullable|string|max:20|unique:books,isbn,' . $this->bookId;
        }

        return $rules;
    }

    public function updatedCoverImage()
    {
        // This method is called when cover_image is updated
        // Validate the file immediately
        $this->validate([
            'cover_image' => 'nullable|image|max:2048|mimes:jpg,jpeg,png,gif',
        ]);
    }

    public function render()
    {
        $books = Book::with('category')
            ->where('title', 'like', '%' . $this->search . '%')
            ->orWhere('author', 'like', '%' . $this->search . '%')
            ->paginate(10);

        $categories = Category::orderBy('name', 'asc')->get();

        return view('livewire.book-form', [
            'books' => $books,
            'categories' => $categories
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->resetInputFields();
    }

    private function resetInputFields()
    {
        $this->title = '';
        $this->isbn = '';
        $this->author = '';
        $this->publisher = '';
        $this->year = '';
        $this->stock = '';
        $this->category_id = '';
        $this->cover_image = '';
        $this->old_cover_image = '';
        $this->bookId = null;
    }

    public function store()
    {
        $this->validate();

        $coverImagePath = null;

        // Handle file upload
        if ($this->cover_image) {
            // Delete old image if exists
            if ($this->old_cover_image && Storage::disk('public')->exists($this->old_cover_image)) {
                Storage::disk('public')->delete($this->old_cover_image);
            }

            // Store new image
            $coverImagePath = $this->cover_image->store('book-covers', 'public');
        } else {
            // Keep old image if no new image uploaded
            $coverImagePath = $this->old_cover_image;
        }

        Book::updateOrCreate(['id' => $this->bookId], [
            'title' => $this->title,
            'isbn' => $this->isbn,
            'author' => $this->author,
            'publisher' => $this->publisher,
            'year' => $this->year,
            'stock' => $this->stock,
            'category_id' => $this->category_id,
            'cover_image' => $coverImagePath,
        ]);

        session()->flash('message', 
            $this->bookId ? 'Buku berhasil diperbarui.' : 'Buku berhasil ditambahkan.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $book = Book::findOrFail($id);
        $this->bookId = $id;
        $this->title = $book->title;
        $this->isbn = $book->isbn;
        $this->author = $book->author;
        $this->publisher = $book->publisher;
        $this->year = $book->year;
        $this->stock = $book->stock;
        $this->category_id = $book->category_id;
        $this->old_cover_image = $book->cover_image; // Store old image path
        $this->cover_image = null; // Reset file input

        $this->openModal();
    }

    public function delete($id)
    {
        $book = Book::find($id);
        
        // Delete cover image if exists
        if ($book->cover_image && Storage::disk('public')->exists($book->cover_image)) {
            Storage::disk('public')->delete($book->cover_image);
        }
        
        $book->delete();
        session()->flash('message', 'Buku berhasil dihapus.');
    }
}
