<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;
use App\Helpers\Logger;

/**
 * Endpoint de reporting CSP — reçoit les violations remontées par le navigateur.
 *
 * Le navigateur envoie un POST avec :
 *  - Content-Type: application/csp-report           (format legacy report-uri)
 *  - Content-Type: application/reports+json         (format moderne report-to)
 *
 * Les deux formats sont supportés. On normalise et on log via Logger::warning.
 */
class ReportController extends Controller
{
    /** Taille max acceptée pour un body de rapport. */
    private const MAX_BODY_SIZE = 64 * 1024;

    public function csp(): void
    {
        $raw = @file_get_contents('php://input', false, null, 0, self::MAX_BODY_SIZE);

        if ($raw !== false && $raw !== '') {
            $data = json_decode($raw, true);
            if (is_array($data)) {
                Logger::warning('csp_violation', $this->normalize($data));
            }
        }

        // 204 No Content — le navigateur n'a rien à recevoir, on a juste accusé réception.
        http_response_code(204);
    }

    /**
     * Normalise le rapport entre les deux formats navigateur.
     *
     * Legacy report-uri  : { "csp-report": { "violated-directive": "...", ... } }
     * Moderne report-to  : [ { "type": "csp-violation", "body": { "effectiveDirective": "...", ... } } ]
     */
    private function normalize(array $data): array
    {
        $report = $data['csp-report']
            ?? $data[0]['body']
            ?? $data;

        return [
            'directive'   => $report['violated-directive']  ?? $report['effectiveDirective']  ?? '?',
            'blocked'     => $report['blocked-uri']         ?? $report['blockedURL']          ?? '?',
            'document'    => $report['document-uri']        ?? $report['documentURL']         ?? '?',
            'source_file' => $report['source-file']         ?? $report['sourceFile']          ?? null,
            'line'        => $report['line-number']         ?? $report['lineNumber']          ?? null,
            'sample'      => mb_substr((string) ($report['script-sample'] ?? $report['sample'] ?? ''), 0, 200) ?: null,
        ];
    }
}
