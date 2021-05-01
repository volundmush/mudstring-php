<?php


class Color {
    public int $number;

    public function __construct(int $num) {
        $this->number = $num;
    }

    public function is_xterm(): bool
    {
        return $this->number > 15;
    }

    // Yoinked from: https://github.com/benighted/php-ansi-rgb
    private static function _convertToAnsi ($r, $g, $b) {
        $grads = array(0x00, 0x5f, 0x87, 0xaf, 0xd7, 0xff);
        $comps = array($r, $g, $b);

        for ($c = 0; $c < 3; $c++) {
            for ($i = 0; $i < 5; $i++) {
                if ($grads[$i] > $comps[$c]) continue;
                if ($grads[$i + 1] >= $comps[$c]) {
                    $comps[$c] = $comps[$c] - $grads[$i] <
                    $grads[$i + 1] - $comps[$c] ? $i : $i + 1;
                    break;
                }
            }
        }

        return 16 + ($comps[0] * 36) + ($comps[1] * 6) + $comps[2];
    }

    public static function fromRGB(string $src) : Color {
        // Ssrc must be a HEX code in string notation as in the XTERM array below.
        print("FROM RGB: " . $src . "\n");
        $check = strtolower($src);
        foreach(Color::$XTERM as $k=>$v) {
            if($v==$check) {
                return new Color(intval($k));
            }
        }

        // No exact match? Then we'll need to calculate nearest.
        $r = hexdec(substr($src, 1, 2));
        $g = hexdec(substr($src, 3, 2));
        $b = hexdec(substr($src, 5, 2));

        return new Color(Color::_convertToAnsi($r, $g, $b));

    }

    private static array $XTERM = [
        "0"=>"#000000",
        "1"=>"#800000",
        "2"=>"#008000",
        "3"=>"#808000",
        "4"=>"#000080",
        "5"=>"#800080",
        "6"=>"#008080",
        "7"=>"#c0c0c0",
        "8"=>"#808080",
        "9"=>"#ff0000",
        "10"=>"#00ff00",
        "11"=>"#ffff00",
        "12"=>"#0000ff",
        "13"=>"#ff00ff",
        "14"=>"#00ffff",
        "15"=>"#ffffff",
        "16"=>"#000000",
        "17"=>"#00005f",
        "18"=>"#000087",
        "19"=>"#0000af",
        "20"=>"#0000d7",
        "21"=>"#0000ff",
        "22"=>"#005f00",
        "23"=>"#005f5f",
        "24"=>"#005f87",
        "25"=>"#005faf",
        "26"=>"#005fd7",
        "27"=>"#005fff",
        "28"=>"#008700",
        "29"=>"#00875f",
        "30"=>"#008787",
        "31"=>"#0087af",
        "32"=>"#0087d7",
        "33"=>"#0087ff",
        "34"=>"#00af00",
        "35"=>"#00af5f",
        "36"=>"#00af87",
        "37"=>"#00afaf",
        "38"=>"#00afd7",
        "39"=>"#00afff",
        "40"=>"#00d700",
        "41"=>"#00d75f",
        "42"=>"#00d787",
        "43"=>"#00d7af",
        "44"=>"#00d7d7",
        "45"=>"#00d7ff",
        "46"=>"#00ff00",
        "47"=>"#00ff5f",
        "48"=>"#00ff87",
        "49"=>"#00ffaf",
        "50"=>"#00ffd7",
        "51"=>"#00ffff",
        "52"=>"#5f0000",
        "53"=>"#5f005f",
        "54"=>"#5f0087",
        "55"=>"#5f00af",
        "56"=>"#5f00d7",
        "57"=>"#5f00ff",
        "58"=>"#5f5f00",
        "59"=>"#5f5f5f",
        "60"=>"#5f5f87",
        "61"=>"#5f5faf",
        "62"=>"#5f5fd7",
        "63"=>"#5f5fff",
        "64"=>"#5f8700",
        "65"=>"#5f875f",
        "66"=>"#5f8787",
        "67"=>"#5f87af",
        "68"=>"#5f87d7",
        "69"=>"#5f87ff",
        "70"=>"#5faf00",
        "71"=>"#5faf5f",
        "72"=>"#5faf87",
        "73"=>"#5fafaf",
        "74"=>"#5fafd7",
        "75"=>"#5fafff",
        "76"=>"#5fd700",
        "77"=>"#5fd75f",
        "78"=>"#5fd787",
        "79"=>"#5fd7af",
        "80"=>"#5fd7d7",
        "81"=>"#5fd7ff",
        "82"=>"#5fff00",
        "83"=>"#5fff5f",
        "84"=>"#5fff87",
        "85"=>"#5fffaf",
        "86"=>"#5fffd7",
        "87"=>"#5fffff",
        "88"=>"#870000",
        "89"=>"#87005f",
        "90"=>"#870087",
        "91"=>"#8700af",
        "92"=>"#8700d7",
        "93"=>"#8700ff",
        "94"=>"#875f00",
        "95"=>"#875f5f",
        "96"=>"#875f87",
        "97"=>"#875faf",
        "98"=>"#875fd7",
        "99"=>"#875fff",
        "100"=>"#878700",
        "101"=>"#87875f",
        "102"=>"#878787",
        "103"=>"#8787af",
        "104"=>"#8787d7",
        "105"=>"#8787ff",
        "106"=>"#87af00",
        "107"=>"#87af5f",
        "108"=>"#87af87",
        "109"=>"#87afaf",
        "110"=>"#87afd7",
        "111"=>"#87afff",
        "112"=>"#87d700",
        "113"=>"#87d75f",
        "114"=>"#87d787",
        "115"=>"#87d7af",
        "116"=>"#87d7d7",
        "117"=>"#87d7ff",
        "118"=>"#87ff00",
        "119"=>"#87ff5f",
        "120"=>"#87ff87",
        "121"=>"#87ffaf",
        "122"=>"#87ffd7",
        "123"=>"#87ffff",
        "124"=>"#af0000",
        "125"=>"#af005f",
        "126"=>"#af0087",
        "127"=>"#af00af",
        "128"=>"#af00d7",
        "129"=>"#af00ff",
        "130"=>"#af5f00",
        "131"=>"#af5f5f",
        "132"=>"#af5f87",
        "133"=>"#af5faf",
        "134"=>"#af5fd7",
        "135"=>"#af5fff",
        "136"=>"#af8700",
        "137"=>"#af875f",
        "138"=>"#af8787",
        "139"=>"#af87af",
        "140"=>"#af87d7",
        "141"=>"#af87ff",
        "142"=>"#afaf00",
        "143"=>"#afaf5f",
        "144"=>"#afaf87",
        "145"=>"#afafaf",
        "146"=>"#afafd7",
        "147"=>"#afafff",
        "148"=>"#afd700",
        "149"=>"#afd75f",
        "150"=>"#afd787",
        "151"=>"#afd7af",
        "152"=>"#afd7d7",
        "153"=>"#afd7ff",
        "154"=>"#afff00",
        "155"=>"#afff5f",
        "156"=>"#afff87",
        "157"=>"#afffaf",
        "158"=>"#afffd7",
        "159"=>"#afffff",
        "160"=>"#d70000",
        "161"=>"#d7005f",
        "162"=>"#d70087",
        "163"=>"#d700af",
        "164"=>"#d700d7",
        "165"=>"#d700ff",
        "166"=>"#d75f00",
        "167"=>"#d75f5f",
        "168"=>"#d75f87",
        "169"=>"#d75faf",
        "170"=>"#d75fd7",
        "171"=>"#d75fff",
        "172"=>"#d78700",
        "173"=>"#d7875f",
        "174"=>"#d78787",
        "175"=>"#d787af",
        "176"=>"#d787d7",
        "177"=>"#d787ff",
        "178"=>"#d7af00",
        "179"=>"#d7af5f",
        "180"=>"#d7af87",
        "181"=>"#d7afaf",
        "182"=>"#d7afd7",
        "183"=>"#d7afff",
        "184"=>"#d7d700",
        "185"=>"#d7d75f",
        "186"=>"#d7d787",
        "187"=>"#d7d7af",
        "188"=>"#d7d7d7",
        "189"=>"#d7d7ff",
        "190"=>"#d7ff00",
        "191"=>"#d7ff5f",
        "192"=>"#d7ff87",
        "193"=>"#d7ffaf",
        "194"=>"#d7ffd7",
        "195"=>"#d7ffff",
        "196"=>"#ff0000",
        "197"=>"#ff005f",
        "198"=>"#ff0087",
        "199"=>"#ff00af",
        "200"=>"#ff00d7",
        "201"=>"#ff00ff",
        "202"=>"#ff5f00",
        "203"=>"#ff5f5f",
        "204"=>"#ff5f87",
        "205"=>"#ff5faf",
        "206"=>"#ff5fd7",
        "207"=>"#ff5fff",
        "208"=>"#ff8700",
        "209"=>"#ff875f",
        "210"=>"#ff8787",
        "211"=>"#ff87af",
        "212"=>"#ff87d7",
        "213"=>"#ff87ff",
        "214"=>"#ffaf00",
        "215"=>"#ffaf5f",
        "216"=>"#ffaf87",
        "217"=>"#ffafaf",
        "218"=>"#ffafd7",
        "219"=>"#ffafff",
        "220"=>"#ffd700",
        "221"=>"#ffd75f",
        "222"=>"#ffd787",
        "223"=>"#ffd7af",
        "224"=>"#ffd7d7",
        "225"=>"#ffd7ff",
        "226"=>"#ffff00",
        "227"=>"#ffff5f",
        "228"=>"#ffff87",
        "229"=>"#ffffaf",
        "230"=>"#ffffd7",
        "231"=>"#ffffff",
        "232"=>"#080808",
        "233"=>"#121212",
        "234"=>"#1c1c1c",
        "235"=>"#262626",
        "236"=>"#303030",
        "237"=>"#3a3a3a",
        "238"=>"#444444",
        "239"=>"#4e4e4e",
        "240"=>"#585858",
        "241"=>"#626262",
        "242"=>"#6c6c6c",
        "243"=>"#767676",
        "244"=>"#808080",
        "245"=>"#8a8a8a",
        "246"=>"#949494",
        "247"=>"#9e9e9e",
        "248"=>"#a8a8a8",
        "249"=>"#b2b2b2",
        "250"=>"#bcbcbc",
        "251"=>"#c6c6c6",
        "252"=>"#d0d0d0",
        "253"=>"#dadada",
        "254"=>"#e4e4e4",
        "255"=>"#eeeeee"
    ];

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
                array_push($total, "38;5;" . strval($this->color->number));
            } else {
                array_push($total, strval(30 + $this->color->number));
            }
        }

        if(!is_null($this->bgcolor)) {
            if($this->bgcolor->is_xterm()) {
                array_push($total, "48;5;" . strval($this->bgcolor->number));
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