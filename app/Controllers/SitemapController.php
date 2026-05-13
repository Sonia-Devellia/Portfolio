<?php

declare(strict_types=1);

namespace App\Controllers;

use Core\Controller;

class SitemapController extends Controller
{
    /** Pages statiques du site, avec leur priorité et fréquence de mise à jour. */
    private const STATIC_PAGES = [
        ['/',                              '1.0', 'weekly'],
        ['/projets',                       '0.9', 'monthly'],
        ['/tarifs',                        '0.8', 'monthly'],
        ['/contact',                       '0.7', 'yearly'],
        ['/case-studies/amanea-voyages',   '0.8', 'yearly'],
        ['/case-studies/triage-support',   '0.8', 'yearly'],
    ];

    public function index(): void
    {
        $base    = base_url();
        $lastmod = date('Y-m-d');

        header('Content-Type: application/xml; charset=UTF-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach (self::STATIC_PAGES as [$path, $priority, $changefreq]) {
            echo $this->urlEntry($base . $path, $lastmod, $changefreq, $priority);
        }

        // Pages géo locales — source unique : GeoController::slugs()
        foreach (GeoController::slugs() as $slug) {
            echo $this->urlEntry($base . '/dev-freelance/' . $slug, $lastmod, 'monthly', '0.6');
        }

        echo '</urlset>';
    }

    private function urlEntry(string $loc, string $lastmod, string $changefreq, string $priority): string
    {
        return "  <url>\n"
             . "    <loc>" . htmlspecialchars($loc, ENT_XML1, 'UTF-8') . "</loc>\n"
             . "    <lastmod>{$lastmod}</lastmod>\n"
             . "    <changefreq>{$changefreq}</changefreq>\n"
             . "    <priority>{$priority}</priority>\n"
             . "  </url>\n";
    }
}
