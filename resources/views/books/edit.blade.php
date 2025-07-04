@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('books.show', $book) }}"
                   class="text-amber-600 hover:text-amber-700 transition duration-150">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div>
                    <h1 class="text-3xl font-bold text-amber-900 font-serif">Editar Libro</h1>
                    <p class="text-amber-700 mt-2">{{ $book->title }}</p>
                </div>
            </div>
        </div>

        <!-- Form -->
        <div class="bg-white/70 backdrop-blur-sm rounded-lg shadow-lg border border-amber-200 p-8">
            <form method="POST" action="{{ route('books.update', $book) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Title -->
                <div>
                    <label for="title" class="block text-sm font-medium text-amber-800 mb-2">
                        Título <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="title" name="title" value="{{ old('title', $book->title) }}" required
                           class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                           placeholder="Ingresa el título del libro">
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Author -->
                <div>
                    <label for="author" class="block text-sm font-medium text-amber-800 mb-2">
                        Autor <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="author" name="author" value="{{ old('author', $book->author) }}" required
                           class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                           placeholder="Ingresa el nombre del autor">
                    @error('author')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- ISBN and Category Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="isbn" class="block text-sm font-medium text-amber-800 mb-2">ISBN</label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn', $book->isbn) }}"
                               class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="978-0-123456-78-9">
                        @error('isbn')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category_id" class="block text-sm font-medium text-amber-800 mb-2">Categoría</label>
                        <select id="category_id" name="category_id"
                                class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                            <option value="">Selecciona una categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Publication Year and Copies Row -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-amber-800 mb-2">Año de Publicación</label>
                        <input type="number" id="publication_year" name="publication_year" value="{{ old('publication_year', $book->publication_year) }}"
                               min="1000" max="{{ date('Y') + 1 }}"
                               class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="{{ date('Y') }}">
                        @error('publication_year')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="copies_total" class="block text-sm font-medium text-amber-800 mb-2">
                            Número de Copias <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="copies_total" name="copies_total" value="{{ old('copies_total', $book->copies_total) }}"
                               min="1" max="100" required
                               class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                               placeholder="1">
                        @error('copies_total')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-amber-600">
                            Actualmente prestados: {{ $book->copies_total - $book->copies_available }} copias
                        </p>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium text-amber-800 mb-2">Descripción</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full border border-amber-300 rounded-lg px-3 py-3 bg-white/50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                              placeholder="Descripción del libro, sinopsis, etc...">{{ old('description', $book->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Current Cover Image -->
                @if($book->cover_image)
                    <div>
                        <label class="block text-sm font-medium text-amber-800 mb-2">Imagen Actual</label>
                        <div class="flex items-center space-x-4">
                            <img src="{{ $book->getCoverImageUrl() }}" alt="{{ $book->title }}"
                                 class="h-20 w-16 object-cover rounded border border-amber-200">
                            <div>
                                <p class="text-sm text-gray-600">Imagen actual de la portada</p>
                                <p class="text-xs text-gray-500">Sube una nueva imagen para reemplazarla</p>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Cover Image -->
                <div>
                    <label for="cover_image" class="block text-sm font-medium text-amber-800 mb-2">
                        {{ $book->cover_image ? 'Nueva Imagen de Portada' : 'Imagen de Portada' }}
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-amber-300 border-dashed rounded-lg bg-white/30">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-amber-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-amber-600 hover:text-amber-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-amber-500">
                                    <span>Subir archivo</span>
                                    <input id="cover_image" name="cover_image" type="file" class="sr-only" accept="image/*">
                                </label>
                                <p class="pl-1">o arrastra y suelta</p>
                            </div>
                            <p class="text-xs text-gray-500">PNG, JPG, GIF hasta 2MB</p>
                        </div>
                    </div>
                    @error('cover_image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Buttons -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-amber-200">
                    <a href="{{ route('books.show', $book) }}"
                       class="px-4 py-2 border border-amber-300 rounded-lg text-amber-700 hover:bg-amber-50 transition duration-150">
                        Cancelar
                    </a>
                    <button type="submit"
                            class="px-6 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white font-medium rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                        <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Actualizar Libro
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
