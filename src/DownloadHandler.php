<?php
declare(strict_types=1);

use Slim\Exception\HttpInternalServerErrorException;
use Slim\Exception\HttpNotFoundException;
use Slim\Psr7\Factory\StreamFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DownloadHandler
{
    /**
     * All valid file to download
     * @var array|string[]
     */
    private array $allowed_name = ["statuten.pdf"];

    /**
     * Checks the file and make it safe
     * @param ?string $rawFile the raw file from user request
     * @return array{success: bool, message: string, code: int, path?: string, exception?: string} All output has `success` (bool), `message`(string) and `code`(int) if
     *                                          the check successfull you get a `path` (string), if it fails you get
     *                                          `exception` (string) <- this has the exption message from server!
     */
    private function checkFile(?string $rawFile): array
    {
        if ($rawFile === null || trim($rawFile) === '') {
            return [
                'success' => false,
                'message' => 'File not found',
                'code'    => 404,
            ];
        }
        try {
            $file = basename($rawFile);
            $filepath = BASE_PATH . "/downloads/" . $file;

            // whitelist
            if (!in_array($file, $this->allowed_name, true)) {
                return [
                    "success" => false,
                    "message" => "File not allowed to download",
                    "code" => 404
                ];
            }

            // exist
            if (!is_file($filepath)) {
                return [
                    "success" => false,
                    "message" => "File not found",
                    "code" => 404
                ];
            }

            // readable
            if (!is_readable($filepath)) {
                return [
                    "success" => false,
                    "message" => "File not readable",
                    "code" => 500,
                    "exception" => "File not readable for {$filepath}"
                ];
            }
            return [
                "success" => true,
                "message" => "File validate",
                "code" => 200,
                "path" => $filepath
            ];
        } catch (Exception $e) {
            return [
                "success" => false,
                "message" => "Internal Server Error",
                "code" => 500,
                "exception" => $e->getMessage()
            ];
        }
    }

    /**
     * download a file
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function download(Request $request, Response $response, array $args): Response
    {
        $file = $request->getQueryParams()["file"] ?? null;
        $result = $this->checkFile($file);

        if (!$result["success"]) {
            if ($result["code"] === 404) {
                throw new HttpNotFoundException($request, $result['message']);
            } else {
                error_log("File upload error: " . ($result["exception"] ?? $result["message"]));

                throw new HttpInternalServerErrorException($request, $result['message']);
            }
        }

        $filepath = $result['path'];
        $size = filesize($filepath);
        $stream = (new StreamFactory())->createStreamFromFile($filepath, 'rb');

        return $response
            ->withBody($stream)
            ->withHeader('Content-Description', 'File Transfer')
            ->withHeader('Content-Type', 'application/pdf')
            ->withHeader('Content-Disposition', 'attachment; filename="' . basename($filepath) . '"')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate, post-check=0, pre-check=0')
            ->withHeader('Pragma', 'public')
            ->withHeader('Content-Length', (string) $size);

    }
}