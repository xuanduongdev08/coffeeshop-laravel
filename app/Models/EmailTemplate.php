<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailTemplate extends Model
{
    protected $fillable = [
        'template_key',
        'subject',
        'content',
        'description',
    ];

    /**
     * Biên dịch nội dung template: thay thế các placeholder bằng giá trị thực.
     */
    public function render(array $placeholders = []): string
    {
        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $this->content
        );
    }

    /**
     * Biên dịch tiêu đề email: thay thế placeholder trong subject.
     */
    public function renderSubject(array $placeholders = []): string
    {
        return str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            $this->subject
        );
    }
}
