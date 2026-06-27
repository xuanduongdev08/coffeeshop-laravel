<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{
    /**
     * Danh sách các email template.
     */
    public function index()
    {
        $templates = EmailTemplate::all();
        return view('admin.email-templates.index', compact('templates'));
    }

    /**
     * Form chỉnh sửa template.
     */
    public function edit(EmailTemplate $emailTemplate)
    {
        // Phân tích các placeholders khả dụng dựa trên template_key
        $placeholders = [];
        if ($emailTemplate->template_key === 'register_success') {
            $placeholders = ['{customer_name}', '{customer_email}', '{website_link}'];
        } elseif ($emailTemplate->template_key === 'order_status_updated') {
            $placeholders = ['{customer_name}', '{order_code}', '{order_status}', '{shipping_address}', '{total_price}', '{order_link}'];
        } elseif ($emailTemplate->template_key === 'drink_status_updated') {
            $placeholders = ['{customer_name}', '{order_code}', '{drink_status_label}', '{extra_note}', '{order_link}'];
        }

        return view('admin.email-templates.edit', compact('emailTemplate', 'placeholders'));
    }

    /**
     * Cập nhật template.
     */
    public function update(Request $request, EmailTemplate $emailTemplate)
    {
        $data = $request->validate([
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $emailTemplate->update($data);

        return redirect()->route('admin.email-templates.index')
            ->with('success', 'Đã cập nhật mẫu email thành công.');
    }
}
