<?php

require_once __DIR__ . '/vendor/autoload.php';

class TCPDF extends \Com\Tecnick\Pdf\Tcpdf
{
    private string $currentFont = '';
    private float $pageHeight = 297;

    public function __construct(
        string $orientation = 'P',
        string $unit = 'mm',
        string $format = 'A4',
        bool $unicode = true,
        string $encoding = 'UTF-8',
        bool $diskcache = false
    ) {
        $allowedPaths = [];
        if (defined('APPPATH')) {
            $allowedPaths[] = realpath(dirname(APPPATH)) ?: dirname(APPPATH);
        }
        $allowedPaths[] = realpath(dirname(__DIR__, 3)) ?: dirname(__DIR__, 3);

        parent::__construct(
            unit: $unit,
            isunicode: $unicode,
            fileOptions: ['allowedPaths' => array_values(array_unique(array_filter($allowedPaths)))]
        );
    }

    public function SetProtection(array $permissions = [], string $userPassword = '', string $ownerPassword = '', int $mode = 0): void
    {
    }

    public function SetDefaultMonospacedFont(string $font): void
    {
    }

    public function SetMargins(float $left, float $top, float $right = -1, float $bottom = 0, bool $keep = false): void
    {
    }

    public function SetHeaderMargin(float $margin): void
    {
    }

    public function SetFooterMargin(float $margin): void
    {
    }

    public function SetAutoPageBreak(bool $auto, float $margin = 0): void
    {
    }

    public function setPrintHeader(bool $print): void
    {
    }

    public function setPrintFooter(bool $print): void
    {
    }

    public function setImageScale(float $scale): void
    {
    }

    public function setCellHeightRatio(float $ratio): void
    {
    }

    public function addPage(array $data = []): array
    {
        $this->currentFont = '';
        $this->font->insert($this->pon, 'helvetica', '', 10);
        $page = parent::addPage([
            'orientation' => $data['orientation'] ?? 'P',
            'format' => $data['format'] ?? 'A4',
        ]);
        $this->pageHeight = (float) $page['height'];
        $this->activateFont();
        return $page;
    }

    public function Image(
        string $file,
        float $x = 0,
        float $y = 0,
        float $w = 0,
        float $h = 0,
        string $type = '',
        string $link = '',
        string $align = '',
        bool $resize = false,
        int $dpi = 300,
        string $palign = '',
        bool $ismask = false,
        $imgmask = null,
        $border = 0,
        bool $fitbox = false,
        bool $hidden = false,
        bool $fitonpage = false
    ): void {
        if ($file === '' || !is_file($file) || $hidden) {
            return;
        }

        if ($w <= 0 || $h <= 0) {
            $info = getimagesize($file);
            if ($info === false) {
                return;
            }
            $ratio = $info[0] > 0 ? $info[1] / $info[0] : 1;
            if ($w <= 0 && $h <= 0) {
                $w = $info[0] / 3.779527559;
                $h = $w * $ratio;
            } elseif ($w <= 0) {
                $w = $h / $ratio;
            } else {
                $h = $w * $ratio;
            }
        }

        $imageId = $this->image->add($file);
        $content = $this->image->getSetImage(
            $imageId,
            $x,
            $y,
            $w,
            $h,
            $this->pageHeight
        );
        $this->page->addContent($content);
    }

    public function SetFont(string $family, string $style = '', float $size = 0, string $fontfile = '', bool $subset = true, bool $out = true): void
    {
        $family = $family === 'helvetica' ? 'helvetica' : 'helvetica';
        $this->currentFont = '';
    }

    public function MultiCell(
        float $w,
        float $h,
        string $txt,
        $border = 0,
        string $align = 'J',
        bool $fill = false,
        int $ln = 0,
        float $x = 0,
        float $y = 0,
        bool $reseth = true,
        float $stretch = 0,
        bool $ishtml = false,
        bool $autopadding = true,
        float $maxh = 0,
        string $valign = 'T',
        bool $fitcell = false
    ): void {
        $x = $x > 0 ? $x : null;
        $y = $y > 0 ? $y : null;

        if ($ishtml) {
            $this->addHTMLCell(
                html: $txt,
                posx: $x ?? 0.0,
                posy: $y ?? 0.0,
                width: $w,
                height: $h
            );
        }
    }

    public function lastPage(): void
    {
    }

    public function Output(string $name = 'document.pdf', string $dest = 'I')
    {
        $this->setPDFFilename($name);
        $pdf = $this->getOutPDFString();

        if ($dest === 'S') {
            return $pdf;
        }

        if ($dest === 'F') {
            file_put_contents($name, $pdf);
            return;
        }

        if ($dest === 'D') {
            $this->downloadPDF($pdf);
            return;
        }

        $this->renderPDF($pdf);
    }

    private function activateFont(): void
    {
        if ($this->currentFont !== '') {
            return;
        }

        $font = $this->font->insert($this->pon, 'helvetica', '', 10);
        $this->page->addContent($font['out']);
        $this->currentFont = $font['key'];
    }
}
