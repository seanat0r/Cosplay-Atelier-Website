<?php
    require_once __DIR__ . "/../lib/Parsedown.php";
    function markdownParser($file): array
    {
        $raw = file_get_contents($file);

        $data = [];
        $content = $raw;

        if (preg_match('/^---\s*(.*?)\s*---/s', $raw, $matches)) {

            $frontmatter = $matches[1];
            $content = str_replace($matches[0], '', $raw);
            $lines = explode("\n", trim($frontmatter));

            foreach ($lines as $line) {
                if (str_contains($line, ':')) {
                    list($key, $value) = explode(':', $line, 2);
                    $data[trim($key)] = trim($value);
                }
            }
        }
        $cleanContent = trim($content);
        $data['mainText'] = $cleanContent;

        $Parsedown = new Parsedown();
        $data['htmlContent'] = $Parsedown->text($cleanContent);

        return $data;
    }