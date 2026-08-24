<?php

namespace App\Traits;

trait HasCleanContent
{
    /**
     * Clean and unescape a string that contains HTML entities, backslashes, or raw HTML markup.
     *
     * @param string|null $value
     * @param bool $asHtml If true, returns clean formatted HTML. If false, returns clean plain text.
     * @return string
     */
    public static function sanitizeContent(?string $value, bool $asHtml = false): string
    {
        if (empty($value)) {
            return '';
        }

        $str = $value;

        // 1. Unescape JSON / backslashes e.g. \" or \\&quot; or \'
        $str = str_replace(['\"', "\\'", '\\\\', '\\&quot;', '\\&amp;quot;'], ['"', "'", '\\', '"', '"'], $str);

        // 2. Multi-pass HTML entity decoding for double/triple encoded strings
        for ($i = 0; $i < 4; $i++) {
            if (str_contains($str, '&lt;') || str_contains($str, '&gt;') || str_contains($str, '&amp;') || str_contains($str, '&quot;') || str_contains($str, '&#039;')) {
                $decoded = html_entity_decode($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                if ($decoded === $str) {
                    break;
                }
                $str = $decoded;
            } else {
                break;
            }
        }

        // 3. Strip noisy data attributes e.g. data-start="177", data-end="190", style="..."
        $str = preg_replace('/\s*data-[a-z0-9-]+=\\\?["\'][^"\']*\\\?["\']/i', '', $str);
        $str = preg_replace('/\s*style=\\\?["\'][^"\']*\\\?["\']/i', '', $str);
        $str = str_replace('&nbsp;', ' ', $str);

        // If we want clean plain text (e.g. for <textarea> fields or text snippets)
        if (!$asHtml) {
            $str = preg_replace('/<\/(p|div|h[1-6]|li|tr)>/i', "\n", $str);
            $str = preg_replace('/<br\s*\/?>/i', "\n", $str);
            $str = strip_tags($str);
            // Replace multiple consecutive newlines with maximum 2 newlines
            $str = preg_replace("/\n[ \t]*\n[ \t]*/", "\n\n", $str);
            return trim($str);
        }

        // If we want formatted HTML for rich display:
        $hasTags = ($str !== strip_tags($str));

        if ($hasTags) {
            // Clean up empty tags like <p><br></p> or <p></p>
            $str = preg_replace('/<p>\s*(<br\s*\/?>)?\s*<\/p>/i', '', $str);
            $str = preg_replace('/<br\s*\/?>\s*<br\s*\/?>+/i', '<br>', $str);
            // Allow safe tags only
            $str = strip_tags($str, '<p><br><b><strong><i><em><ul><ol><li><a><span>');
            return trim($str);
        }

        // If original input was plain text, convert newlines to <br> or clean paragraphs
        return nl2br(e(trim($str)));
    }

    public function getCleanDescriptionAttribute(): string
    {
        $desc = $this->attributes['description'] ?? $this->attributes['message'] ?? '';
        return static::sanitizeContent($desc, true);
    }

    public function getPlainDescriptionAttribute(): string
    {
        $desc = $this->attributes['description'] ?? $this->attributes['message'] ?? '';
        return static::sanitizeContent($desc, false);
    }

    public function getCleanRemarksAttribute(): string
    {
        $rem = $this->attributes['remarks'] ?? $this->attributes['ticket_remarks'] ?? $this->attributes['ticket_note'] ?? '';
        return static::sanitizeContent($rem, true);
    }

    public function getPlainRemarksAttribute(): string
    {
        $rem = $this->attributes['remarks'] ?? $this->attributes['ticket_remarks'] ?? $this->attributes['ticket_note'] ?? '';
        return static::sanitizeContent($rem, false);
    }

    public function getCleanSubjectAttribute(): string
    {
        $subj = $this->attributes['subject'] ?? $this->attributes['title'] ?? '';
        return static::sanitizeContent($subj, false);
    }

    public function getCleanCommentAttribute(): string
    {
        $cmt = $this->attributes['ticket_comments'] ?? $this->attributes['comment'] ?? $this->attributes['message'] ?? '';
        return static::sanitizeContent($cmt, true);
    }

    public function getPlainCommentAttribute(): string
    {
        $cmt = $this->attributes['ticket_comments'] ?? $this->attributes['comment'] ?? $this->attributes['message'] ?? '';
        return static::sanitizeContent($cmt, false);
    }
}
