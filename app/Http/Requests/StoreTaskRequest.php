<?php

namespace App\Http\Requests;

use App\Models\Task;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class StoreTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:3', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
            'deadline' => ['nullable', 'date'],
            'status' => ['required', 'in:'.implode(',', Task::STATUSES)],
            
            'attachments' => ['nullable', 'array', 'max:5'],
            'attachments.*' => ['bail', File::types(['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'png', 'jpg', 'jpeg', 'webp', 'txt', 'zip'])->max('10mb')],
        ];
    }

    public function messages(): array
    {
        return [
            'attachments.*.max' => 'Each attachment must be 10 MB or smaller.',
            'attachments.max' => 'You can upload up to five attachments at once.',
            'attachments.*.extensions' => 'This attachment file type is not supported.',
        ];
    }
}
