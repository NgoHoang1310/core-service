<?php
// app/Http/Requests/FilmFilterRequest.php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FilmFilterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'user_uuid' => 'required|string|max:255',
            'type' => 'nullable|in:movie,series',
            'genre' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'release' => 'nullable|integer|min:1900|max:' . now()->year,
            'director' => 'nullable|string|max:255',
            'actor' => 'nullable|string|max:255',
            'search' => 'nullable|string|max:255',
            'sort_by' => 'nullable|string|in:title,created_at,release_year',
            'sort_direction' => 'nullable|in:asc,desc',
            'per_page' => 'nullable|integer|min:1|max:100'
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
