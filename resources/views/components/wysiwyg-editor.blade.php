@props([
    'id' => null,
    'name' => 'content',
    'value' => '',
    'placeholder' => 'Compose rich content...',
    'height' => '280px',
    'maxHeight' => '450px',
    'tokens' => [],
    'showHtmlToggle' => true,
])

@php
    $editorId = $id ?? 'wysiwyg_' . str_replace(['[', ']', '.'], '_', $name) . '_' . uniqid();
    $rawVal = old($name, $value);
    
    // Cleanly decode entities and escaped slashes so content displays visually
    $decodedVal = (string)$rawVal;
    if (!empty($decodedVal)) {
        $decodedVal = stripslashes($decodedVal);
        if (strpos($decodedVal, '&lt;') !== false || strpos($decodedVal, '&gt;') !== false || strpos($decodedVal, '&amp;') !== false) {
            $decodedVal = html_entity_decode($decodedVal, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            if (strpos($decodedVal, '&lt;') !== false && strpos($decodedVal, '&gt;') !== false) {
                $decodedVal = html_entity_decode($decodedVal, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }
        }
    }
@endphp

<div class="wysiwyg-editor-wrapper" id="{{ $editorId }}_wrapper" data-editor-id="{{ $editorId }}">
    <!-- Toolbar -->
    <div class="wysiwyg-toolbar border border-bottom-0 rounded-top bg-body-tertiary p-2 d-flex flex-wrap align-items-center gap-1">
        <!-- History -->
        <div class="btn-group btn-group-sm me-1" role="group">
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Undo" onclick="execWysiwygAction('{{ $editorId }}', 'undo')">
                <i class="fa-solid fa-rotate-left"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Redo" onclick="execWysiwygAction('{{ $editorId }}', 'redo')">
                <i class="fa-solid fa-rotate-right"></i>
            </button>
        </div>

        <!-- Format dropdown / Heading -->
        <div class="me-1">
            <select class="form-select form-select-sm py-1 px-2 fs-8 border bg-body" style="width: 120px;" onchange="execWysiwygBlock('{{ $editorId }}', this.value)">
                <option value="p">Paragraph</option>
                <option value="h2">Heading 2</option>
                <option value="h3">Heading 3</option>
                <option value="h4">Heading 4</option>
                <option value="blockquote">Quote</option>
            </select>
        </div>

        <!-- Basic Styling -->
        <div class="btn-group btn-group-sm me-1" role="group">
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Bold (Ctrl+B)" onclick="execWysiwygAction('{{ $editorId }}', 'bold')">
                <i class="fa-solid fa-bold"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Italic (Ctrl+I)" onclick="execWysiwygAction('{{ $editorId }}', 'italic')">
                <i class="fa-solid fa-italic"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Underline (Ctrl+U)" onclick="execWysiwygAction('{{ $editorId }}', 'underline')">
                <i class="fa-solid fa-underline"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Strikethrough" onclick="execWysiwygAction('{{ $editorId }}', 'strikeThrough')">
                <i class="fa-solid fa-strikethrough"></i>
            </button>
        </div>

        <!-- Lists -->
        <div class="btn-group btn-group-sm me-1" role="group">
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Bullet List" onclick="execWysiwygAction('{{ $editorId }}', 'insertUnorderedList')">
                <i class="fa-solid fa-list-ul"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Numbered List" onclick="execWysiwygAction('{{ $editorId }}', 'insertOrderedList')">
                <i class="fa-solid fa-list-ol"></i>
            </button>
        </div>

        <!-- Alignment -->
        <div class="btn-group btn-group-sm me-1" role="group">
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Align Left" onclick="execWysiwygAction('{{ $editorId }}', 'justifyLeft')">
                <i class="fa-solid fa-align-left"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Align Center" onclick="execWysiwygAction('{{ $editorId }}', 'justifyCenter')">
                <i class="fa-solid fa-align-center"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Align Right" onclick="execWysiwygAction('{{ $editorId }}', 'justifyRight')">
                <i class="fa-solid fa-align-right"></i>
            </button>
        </div>

        <!-- Inserts -->
        <div class="btn-group btn-group-sm me-1" role="group">
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Insert Link" onclick="insertWysiwygLinkModal('{{ $editorId }}')">
                <i class="fa-solid fa-link"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Horizontal Line" onclick="execWysiwygAction('{{ $editorId }}', 'insertHorizontalRule')">
                <i class="fa-solid fa-minus"></i>
            </button>
            <button type="button" class="btn btn-sm btn-light border py-1 px-2" title="Clear Formatting" onclick="execWysiwygAction('{{ $editorId }}', 'removeFormat')">
                <i class="fa-solid fa-eraser text-muted"></i>
            </button>
        </div>

        @if($showHtmlToggle)
        <!-- HTML Code View Toggle -->
        <button type="button" class="btn btn-sm btn-outline-secondary border py-1 px-2 ms-auto" title="Toggle HTML Source" onclick="toggleWysiwygSource('{{ $editorId }}', this)">
            <i class="fa-solid fa-code me-1"></i> <span class="fs-9 fw-semibold">HTML</span>
        </button>
        @endif
    </div>

    @if(!empty($tokens))
    <!-- Token Quick-Insert Bar -->
    <div class="bg-body-secondary border-start border-end p-2 d-flex flex-wrap align-items-center gap-1 fs-9">
        <span class="text-body-secondary fw-semibold me-1"><i class="fa-solid fa-tags me-1"></i> Insert Token:</span>
        @foreach($tokens as $tok)
            <button type="button" class="badge bg-body border text-body-emphasis font-monospace py-1 px-2 hover-primary" style="cursor: pointer;" title="Insert {{ $tok }}" onclick="insertWysiwygToken('{{ $editorId }}', '{{ $tok }}')">
                + {{ $tok }}
            </button>
        @endforeach
    </div>
    @endif

    <!-- Visual Canvas: Pre-populated directly from decoded HTML -->
    <div class="wysiwyg-canvas border rounded-bottom p-3 bg-body text-body fs-8" 
         id="{{ $editorId }}_canvas" 
         contenteditable="true" 
         spellcheck="true"
         style="min-height: {{ $height }}; max-height: {{ $maxHeight }}; overflow-y: auto; line-height: 1.65;"
         placeholder="{{ $placeholder }}">{!! $decodedVal !!}</div>

    <!-- Raw HTML Textarea (Source Mode & Form Submission Target) -->
    <textarea name="{{ $name }}" 
              id="{{ $editorId }}_source" 
              class="form-control form-control-sm font-monospace border rounded-bottom d-none bg-body-tertiary text-body fs-8" 
              rows="10" 
              style="min-height: {{ $height }}; max-height: {{ $maxHeight }};"
              placeholder="Raw HTML content...">{{ $decodedVal }}</textarea>
</div>

@pushOnce('css')
<style>
    .wysiwyg-canvas:focus {
        outline: none;
        border-color: #3b82f6 !important;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.2);
    }
    .wysiwyg-canvas[placeholder]:empty:before {
        content: attr(placeholder);
        color: #94a3b8;
        pointer-events: none;
        display: block;
    }
    .wysiwyg-canvas blockquote {
        border-left: 3px solid #3b82f6;
        padding-left: 12px;
        margin: 12px 0;
        color: #64748b;
        font-style: italic;
    }
    .wysiwyg-canvas table {
        border-collapse: collapse;
        width: 100%;
        margin: 12px 0;
    }
    .wysiwyg-canvas th, .wysiwyg-canvas td {
        border: 1px solid #cbd5e1;
        padding: 6px 10px;
    }
    [data-bs-theme="dark"] .wysiwyg-canvas th,
    [data-bs-theme="dark"] .wysiwyg-canvas td {
        border-color: #475569;
    }
    .hover-primary:hover {
        background-color: #3b82f6 !important;
        color: #ffffff !important;
        border-color: #3b82f6 !important;
    }
</style>
@endPushOnce

@pushOnce('js')
<script>
    // Global WYSIWYG Engine
    function initWysiwygEditor(editorId) {
        const wrapper = document.getElementById(editorId + '_wrapper');
        if (!wrapper) return;

        const canvas = document.getElementById(editorId + '_canvas');
        const source = document.getElementById(editorId + '_source');
        if (!canvas || !source) return;

        // If canvas is empty but source has content, decode and populate
        if (!canvas.innerHTML.trim() && source.value.trim()) {
            let rawContent = source.value;
            let decoder = document.createElement('textarea');
            decoder.innerHTML = rawContent;
            let decoded = decoder.value;
            if (decoded.includes('&lt;') && decoded.includes('&gt;')) {
                decoder.innerHTML = decoded;
                decoded = decoder.value;
            }
            canvas.innerHTML = decoded;
        } else if (canvas.innerHTML.trim() && !source.value.trim()) {
            source.value = canvas.innerHTML;
        }

        // Avoid attaching duplicate event listeners
        if (wrapper.dataset.wysiwygBound === 'true') return;
        wrapper.dataset.wysiwygBound = 'true';

        // Continuous sync to source
        const syncToSource = () => {
            if (!canvas.classList.contains('d-none')) {
                source.value = canvas.innerHTML;
            }
        };

        canvas.addEventListener('input', syncToSource);
        canvas.addEventListener('blur', syncToSource);
        canvas.addEventListener('keyup', syncToSource);
        canvas.addEventListener('paste', () => setTimeout(syncToSource, 50));

        // Form submission sync
        const form = wrapper.closest('form');
        if (form) {
            form.addEventListener('submit', () => {
                if (canvas.classList.contains('d-none')) {
                    canvas.innerHTML = source.value;
                } else {
                    source.value = canvas.innerHTML;
                }
            });
        }
    }

    function execWysiwygAction(editorId, command, value = null) {
        const canvas = document.getElementById(editorId + '_canvas');
        if (!canvas) return;
        canvas.focus();
        document.execCommand(command, false, value);
        const source = document.getElementById(editorId + '_source');
        if (source) source.value = canvas.innerHTML;
    }

    function execWysiwygBlock(editorId, tag) {
        execWysiwygAction(editorId, 'formatBlock', tag);
    }

    function insertWysiwygLinkModal(editorId) {
        const canvas = document.getElementById(editorId + '_canvas');
        if (!canvas) return;
        const url = prompt('Enter website link (URL):', 'https://');
        if (url && url !== 'https://') {
            execWysiwygAction(editorId, 'createLink', url);
        }
    }

    function insertWysiwygToken(editorId, token) {
        const canvas = document.getElementById(editorId + '_canvas');
        if (!canvas) return;
        canvas.focus();
        document.execCommand('insertText', false, token);
        const source = document.getElementById(editorId + '_source');
        if (source) source.value = canvas.innerHTML;
    }

    function toggleWysiwygSource(editorId, btn) {
        const canvas = document.getElementById(editorId + '_canvas');
        const source = document.getElementById(editorId + '_source');
        if (!canvas || !source) return;

        if (source.classList.contains('d-none')) {
            // Switch to Source View
            source.value = canvas.innerHTML;
            canvas.classList.add('d-none');
            source.classList.remove('d-none');
            source.focus();
            if (btn) {
                btn.classList.add('btn-secondary');
                btn.classList.remove('btn-outline-secondary');
                btn.innerHTML = '<i class="fa-solid fa-eye me-1"></i> <span class="fs-9 fw-semibold">Visual</span>';
            }
        } else {
            // Switch to Visual View
            canvas.innerHTML = source.value;
            source.classList.add('d-none');
            canvas.classList.remove('d-none');
            canvas.focus();
            if (btn) {
                btn.classList.remove('btn-secondary');
                btn.classList.add('btn-outline-secondary');
                btn.innerHTML = '<i class="fa-solid fa-code me-1"></i> <span class="fs-9 fw-semibold">HTML</span>';
            }
        }
    }

    // Auto initialize editors on page load and modal open
    function initAllWysiwygEditors() {
        document.querySelectorAll('.wysiwyg-editor-wrapper').forEach(function(wrapper) {
            const editorId = wrapper.getAttribute('data-editor-id');
            if (editorId) initWysiwygEditor(editorId);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initAllWysiwygEditors);
    } else {
        initAllWysiwygEditors();
    }

    // Handle Bootstrap modals
    document.addEventListener('shown.bs.modal', function(e) {
        if (e.target) {
            e.target.querySelectorAll('.wysiwyg-editor-wrapper').forEach(function(wrapper) {
                const editorId = wrapper.getAttribute('data-editor-id');
                if (editorId) initWysiwygEditor(editorId);
            });
        }
    });
</script>
@endPushOnce
