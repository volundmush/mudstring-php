<?php

namespace MudString;

class Color {
    public int $number;

    public function __construct(int $num) {
        $this->number = $num;
    }

    public function is_xterm(): bool
    {
        return $this->number > 15;
    }
}

class Style {
    public ?Style $ancestor = null;
    public ?array $children = null;
    public ?Color $color = null;
    public ?Color $bgcolor = null;
    public ?bool $bold = null;
    public ?bool $dim = null;
    public ?bool $italic = null;
    public ?bool $underline = null;
    public ?bool $blink = null;
    public ?bool $blink2 = null;
    public ?bool $reverse = null;
    public ?bool $conceal = null;
    public ?bool $strike = null;
    public ?bool $underline2 = null;
    public ?bool $frame = null;
    public ?bool $encircle = null;
    public ?bool $overline = null;
    public ?string $tag = null;
    public ?array $attrs = null;

    public function __construct(?Style &$ancestor = null, ?Color $color = null, ?Color $bgcolor = null, ?bool $bold = null, ?bool $dim = null, ?bool $italic = null,
    ?bool $underline = null, ?bool $blink = null, ?bool $blink2 = null, ?bool $reverse = null, ?bool $conceal = null, ?bool $strike = null, ?bool $underline2 = null,
    ?bool $frame = null, ?bool $encircle = null, ?bool $overline = null, ?string $tag = null, ?array $attrs = null) {

        if(!is_null($ancestor)) {
            $this->ancestor = $ancestor;
            if(is_null($ancestor->children)) {
                $ancestor->children = array();
            } else {
                array_push($ancestor->children, $this);
            }
        }

        if(!is_null($color)) {
            $this->color = $color;
        }

        if(!is_null($bgcolor)) {
            $this->bgcolor = $bgcolor;
        }
        if(!is_null($bold)) {
            $this->bold = $bold;
        }

        if(!is_null($dim)) {
            $this->dim = $dim;
        }

        if(!is_null($italic)) {
            $this->italic = $italic;
        }

        if(!is_null($underline)) {
            $this->underline = $underline;
        }

        if(!is_null($blink)) {
            $this->blink = $blink;
        }

        if(!is_null($blink2)) {
            $this->blink2 = $blink2;
        }

        if(!is_null($reverse)) {
            $this->reverse = $reverse;
        }

        if(!is_null($conceal)) {
            $this->conceal = $conceal;
        }

        if(!is_null($strike)) {
            $this->strike = $strike;
        }

        if(!is_null($underline2)) {
            $this->underline2 = $underline2;
        }

        if(!is_null($frame)) {
            $this->frame = $frame;
        }

        if(!is_null($encircle)) {
            $this->encircle = $encircle;
        }

        if(!is_null($overline)) {
            $this->overline = $overline;
        }

        if(!is_null($tag)) {
            $this->tag = $tag;
        }

        if(!is_null($attrs)) {
            $this->attrs = $attrs;
        }

    }

    public function reset() {
        $this->color = null;
        $this->bgcolor = null;
        $this->bold = null;
        $this->dim = null;
        $this->italic = null;
        $this->underline = null;
        $this->blink = null;
        $this->blink2 = null;
        $this->reverse = null;
        $this->conceal = null;
        $this->strike = null;
        $this->underline2 = null;
        $this->frame = null;
        $this->encircle = null;
        $this->overline = null;
        $this->tag = null;
        $this->attrs = null;
    }

    public function inherit() {
        if(is_null($this->ancestor)) {
            return;
        }
        $this->color = $this->ancestor->color;
        $this->bgcolor = $this->ancestor->bgcolor;
        $this->bold = $this->ancestor->bold;
        $this->dim = $this->ancestor->dim;
        $this->italic = $this->ancestor->italic;
        $this->underline = $this->ancestor->underline;
        $this->blink = $this->ancestor->blink;
        $this->blink2 = $this->ancestor->blink2;
        $this->reverse = $this->ancestor->reverse;
        $this->conceal = $this->ancestor->conceal;
        $this->strike = $this->ancestor->strike;
        $this->underline2 = $this->ancestor->underline2;
        $this->frame = $this->ancestor->frame;
        $this->encircle = $this->ancestor->encircle;
        $this->overline = $this->ancestor->overline;
        $this->tag = $this->ancestor->tag;
        $this->attrs = $this->ancestor->attrs;
    }

    public function codes(bool $xterm = false, bool $downgrade = false) {
        $total = array();

        if(!is_null($this->color)) {
            if($this->color->is_xterm()) {

            } else {
                array_push($total, strval(30 + $this->color->number));
            }
        }

        if(!is_null($this->bgcolor)) {
            if($this->bgcolor->is_xterm()) {

            } else {
                array_push($total, strval(40 + $this->bgcolor->number));
            }
        }

        if($this->bold) {
            array_push($total, "1");
        }

        if($this->dim) {
            array_push($total, "2");
        }

        if($this->italic) {
            array_push($total, "3");
        }

        if($this->underline) {
            array_push($total, "4");
        }

        if($this->blink) {
            array_push($total, "5");
        }

        if($this->blink2) {
            array_push($total, "6");
        }

        if($this->reverse) {
            array_push($total, "7");
        }

        if($this->conceal) {
            array_push($total, "8");
        }

        if($this->strike) {
            array_push($total, "9");
        }

        if($this->underline2) {
            array_push($total, "21");
        }

        if($this->frame) {
            array_push($total, "51");
        }

        if($this->encircle) {
            array_push($total, "52");
        }

        if($this->overline) {
            array_push($total, "53");
        }

        return implode(";", $total);


    }

    public function render(string $src, bool $ansi = false, bool $xterm = false, bool $downgrade = false, bool $mxp = false) : string {
        if($xterm) {
            $ansi = true;
        }

        $out_text = $src;
        if($mxp) {
            $out_text = htmlspecialchars($src);
        }

        $rendered = $out_text;
        if($ansi) {
            $codes = $this->codes($xterm, $downgrade);
            if(strlen($codes)) {
                $rendered = "\x1b[" . $codes . "m" . $rendered . "\x1b[0m";
            }
        }

        if($mxp & !is_null($this->tag)) {
            if(!is_null($this->attrs)) {

            } else {
                $rendered = "\x1b]4z<" . $this->tag . ">" . $rendered . "\x1b[4z</" . $this->tag . ">";
            }
        }

        return $rendered;

    }

}

class Segment {
    public ?Style $style = null;
    public string $str;

    public function __construct(string $s, ?Style &$style = null) {
        $this->str = $s;
        $this->style = $style;
    }

    public function render(bool $ansi = false, bool $xterm = false, bool $downgrade = false, bool $mxp = false) : string {
        if(!is_null($this->style)) {
            return $this->style->render($this->str, $ansi, $xterm, $downgrade, $mxp);
        } else {
            if($mxp) {
                return htmlspecialchars($this->str);
            } else {
                return $this->str;
            }

        }
    }
}

class Span {
    public int $start = 0;
    public int $end = 0;
    public ?Style $style = null;

    public function __construct(int $start, int $end, ?Style &$style = null) {
        $this->start = $start;
        $this->end = $end;
        $this->style = $style;
    }
}


class Text {
    public string $clean = "";
    public array $spans = array();

    public static function fromPlain(string $src) : Text {
        $out = new Text;
        $out->clean = $src;
        array_push($out->spans, new Span(0, strlen($src)));
        return $out;
    }

    public static function fromSegments(array $segments) : Text {
        $out = new Text;
        $current = strlen($out->clean);
        foreach($segments as $seg) {
            $length = strlen($seg->str);
            $out->clean = $out->clean . $seg->str;
            array_push($out->spans, new Span($current, $current+$length, $seg->style));
            $current = strlen($out->clean);
        }
        return $out;
    }

    public function toSegments() : array {
        $out = array();

        foreach($this->spans as $sp) {
            array_push($out, new Segment(substr($this->clean, $sp->start, $sp->end-$sp->start), $sp->style));
        }

        return $out;
    }

    public function render(bool $ansi = false, bool $xterm = false, bool $downgrade = false, bool $mxp = false) : string {
        $out = "";
        foreach($this->toSegments() as $seg) {
            $out = $out . $seg->render($ansi, $xterm, $downgrade, $mxp);
        }

        return $out;
    }

}



?>