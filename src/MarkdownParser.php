<?php
declare(strict_types=1);
class MarkdownParser {

    public function __construct(private readonly Parsedown $parsedown = new Parsedown())
    {
    }

    /**
     * @param string $file The file to parse
     * @return array returns an array with the content. `mainText` holds the raw text and `htmlContent` displays the
     * formatted html content. Any content in the frontmatter is the specific `<tag>`.
     */
    public function parseFile(string $file): array
    {
        $raw = file_get_contents($file);

        if ($raw === false) {
            throw new RuntimeException("Datei konnte nicht gelesen werden: $file");
        }

        $data = [];
        $content = $raw;

        if (preg_match('/^---\s*(.*?)\s*---/s', $raw, $matches)) {
            $content = str_replace($matches[0], '', $raw);
            $lines = explode("\n", trim($matches[1]));

            foreach ($lines as $line) {
                if (str_contains($line, ':')) {
                    [$key, $value] = explode(':', $line, 2);
                    $data[trim($key)] = trim($value);
                }
            }
        }

        $data['mainText'] = trim($content);
        $data['htmlContent'] = $this->parsedown->text($data['mainText']);

        return $data;
    }
}