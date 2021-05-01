<?php

require_once __DIR__ . "/mudstring.php";

$PENN_ANSI_MATCH = [
    "letters" => "<(?i)^(?P<data>[a-z ]+)\b>",
    "numbers" => "<(?i)^(?P<data>\d+)\b>",
    "rgb" => "<(?i)^<(?P<red>\d{1,3})\s+(?P<green>\d{1,3})\s+(?P<blue>\d{1,3})>(\b)?>",
    "hex1" => "<(?i)^#(?P<data>[0-9A-F]{6})\b>",
    "hex2" => "<(?i)^<#(?P<data>[0-9A-F]{6})>(\b)?>",
    "name" => "<(?i)^\+(?P<data>\w+)\b>"
];

$PENN_BG = ['/', '!'];

$PENN_COLORS = json_decode(file_get_contents(__DIR__ . "/colors.json"));

function penn_separate_codes(string $codes) : Generator {
    global $PENN_ANSI_MATCH;
    global $PENN_BG;

    while(strlen($codes)) {
        if(in_array($codes[0], $PENN_BG)) {
            $codes = substr($codes, 1);
            if(!strlen($codes)) {
                break;
            }
            if(ctype_space($codes[0])) {
                $codes = substr($codes, 1);
                continue;
            }
            elseif(in_array($codes[0], $PENN_BG)) {
                continue;
            }
            else {
                $matched = false;
                $matches = array();
                foreach($PENN_ANSI_MATCH as $k=>$v) {
                    if($k == "letters") {
                        // letters are not allowed in background mode.
                        continue;
                    }
                    if(preg_match($v, $codes, $matches)) {
                        $matched = true;
                        $codes = substr($codes, strlen($matches[0]));
                        switch($k) {
                            case "numbers":
                                $data = $matches[1];
                                $number = abs(intval($data));
                                if($number > 255) {
                                    throw $matches[1];
                                }
                                yield [$k, "bgcolor", $number, $matches[0]];
                                break;
                            case "name":
                                yield [$k, "bgcolor", strtolower($matches[1]), $matches[0]];
                                break;
                            case "hex1":
                            case "hex2":
                                yield [$k, "bgcolor", strtoupper($matches[1]), $matches[0]];
                                break;
                            case "rgb":
                                $hex = "#" . dechex(intval($matches[1])) . dechex(intval($matches[2])) . dechex(intval($matches[3]));
                                yield [$k, "bgcolor", $hex, $matches[0]];
                                break;
                        }
                    }
                }
                if(!$matched) {
                    throw $codes;
                }
            }
        }
        elseif(ctype_space($codes[0])) {
            $codes = substr($codes, 1);
            continue;
        } else {
            $matched = false;
            $matches = array();
            foreach($PENN_ANSI_MATCH as $k=>$v) {
                if(preg_match($v, $codes, $matches)) {
                    $matched = true;
                    $codes = substr($codes, strlen($matches[0]));
                    switch($k) {
                        case "letters":
                            yield [$k, null, $matches[1], $matches[0]];
                            break;
                        case "numbers":
                            $data = $matches[1];
                            $number = abs(intval($data));
                            if($number > 255) {
                                throw $matches[1];
                            }
                            yield [$k, "color", $number, $matches[0]];
                            break;
                        case "name":
                            yield [$k, "color", strtolower($matches[1]), $matches[0]];
                            break;
                        case "hex1":
                        case "hex2":
                            yield [$k, "color", strtoupper($matches[1]), $matches[0]];
                            break;
                        case "rgb":
                            $hex = "#" . dechex(intval($matches[1])) . dechex(intval($matches[2])) . dechex(intval($matches[3]));
                            yield [$k, "color", $hex, $matches[0]];
                            break;
                    }
                }
            }
            if(!$matched) {
                throw $codes;
            }
        }
    }
    return;
}

function penn_test_separate(string $codes) {
    foreach(penn_separate_codes($codes) as $code) {
        print_r($code);
    }
}

$PENN_DEFAULT = ['n', 'N'];

$PENN_STYLINGS = [
    "f"=>"flash",
    "h"=>"bold",
    "i"=>"reverse",
    "u"=>"underline"
];

$PENN_COLOR_LETTERS = [
    "d"=>-1,
    "x"=>0,
    "r"=>1,
    "g"=>2,
    "y"=>3,
    "b"=>4,
    "m"=>5,
    "c"=>6,
    "w"=>7
];

function penn_apply_ansi_letters(Style &$style, string $letters) {
    global $PENN_DEFAULT;
    global $PENN_STYLINGS;
    global $PENN_COLOR_LETTERS;

    foreach(str_split($letters) as $c) {
        if(in_array($c, $PENN_DEFAULT)) {
            $style->reset();
        } else {
            if(array_key_exists($c, $PENN_STYLINGS)) {
                $option = $PENN_STYLINGS[$c];
                $style->$option = true;
            }
            elseif(array_key_exists(strtolower($c), $PENN_STYLINGS)) {
                $option = $PENN_STYLINGS[$c];
                $style->$option = false;
            }
            elseif(array_key_exists($c, $PENN_COLOR_LETTERS)) {
                $code = $PENN_COLOR_LETTERS[$c];
                if($code == -1) {
                    $style->color = null;
                } else {
                    $style->color = new Color($code);
                }
            }
            elseif(array_key_exists(strtolower($c), $PENN_COLOR_LETTERS)) {
                $code = $PENN_COLOR_LETTERS[$c];
                if($code == -1) {
                    $style->bgcolor = null;
                } else {
                    $style->bgcolor = new Color($code);
                }
            }
        }
    }
}

function penn_apply_ansi_rule(Style &$style, array &$code) {
    global $PENN_COLORS;
    $mode = $code[0];
    $ground = $code[1];
    $data = $code[2];
    $original = $code[3];

    if($mode == "letters") {
        penn_apply_ansi_letters($style, $data);
    }

}

function penn_apply_ansi_rules(Style &$style, string $data) {
    foreach(penn_separate_codes($data) as $code) {
        penn_apply_ansi_rule($style, $code);
    }
}

function penn_apply_mxp_rules(Style &$style, string $data) {

}

function penn_decode(string $remaining) : Text {
    $current = new Style();
    $state = 0;
    $segments = array();
    $tag = "";

    while(strlen($remaining)) {
        switch($state) {
            case 0:
                $idx_start = strpos($remaining, "\002");
                if($idx_start === false) {
                    // no tag found.
                    array_push($segments, new Segment($remaining, $current));
                    $remaining = "";
                } else {
                    array_push($segments, new Segment(substr($remaining, 0, $idx_start), $current));
                    $remaining = substr($remaining, $idx_start+1);
                    $state = 1;
                }
                break;
            case 1:
                $tag = $remaining[0];
                $remaining = substr($remaining, 1);
                $state = 2;
                break;
            case 2:
                $idx_end = strpos($remaining, "\003");
                $opening = true;
                if($idx_end === false) {
                    // malformed data.
                    break;
                } else {
                    $tag_data = substr($remaining, 0, $idx_end);
                    $remaining = substr($remaining, $idx_end+1);
                    if(strlen($tag_data) && $tag_data[0] == '/') {
                        $opening = false;
                        $tag_data = substr($tag_data, 1);
                    }
                    if($opening) {
                        $current = new Style($current);
                        $current->inherit();
                        switch($tag) {
                            case 'p':
                                penn_apply_mxp_rules($current, $tag_data);
                                break;
                            case 'c':
                                penn_apply_ansi_rules($current, $tag_data);
                        }
                    } else {
                        $current = $current->ancestor;
                    }
                }
                $state = 0;
                break;
        }
    }
    return Text::fromSegments($segments);
}

function penn_ansi_function(string $codes, string $src) : Text {
    $s = new Style;
    penn_apply_ansi_rules($s, $codes);
    return Text::fromSegments([new Segment($src, $s)]);
}

?>

