<?php

class Math {

    public function __construct() {}

    public function __destruct() {}

    public function cmmdc($a, $b) {
        // Compute the greatest common divisor using Euclid's algorithm
        while ($b) {
            $r = $a % $b;
            $a = $b;
            $b = $r;   
        }
        return $a;
    }

    public function submult($n) {
        $out = "";           
        $vec = array_fill(0, $n, 0); // Initialize array with 0s

        do {
            $vec[$n - 1]++;
            for ($j = $n - 1; $j >= 1; $j--) {
                if ($vec[$j] > 1) {
                    $vec[$j] -= 2;
                    $vec[$j - 1] += 1;                            
                } 
            }
            $s = array_sum($vec);
            for ($i = 0; $i < $n; $i++) {
                if ($vec[$i]) { 
                    $s++;
                }
            }
            for ($k = $n - 1; $k >= 0; $k--) {
                if ($vec[$k]) {
                    $out .= ($k + 1) . ' ';
                }
            }
            $out .= "\n"; 
        } while ($s < $n);

        return nl2br($out);
    }

    public function eratosthenes($n) {
        $stack = array_fill(2, $n - 1, 1); // Initialize sieve array
        $arr = [];

        for ($j = 2; ($j * $j) <= $n; $j++) {
            if ($stack[$j]) {
                $k = 2;
                while (($j * $k) <= $n) {
                    $stack[$j * $k] = 0;
                    $k++;
                }
            }
        }

        // Collect prime numbers from sieve
        for ($i = 2; $i <= $n; $i++) {
            if ($stack[$i]) {
                $arr[] = $i;
            }
        }

        return $arr;
    }

    public function toBin($n) {
        return str_pad(decbin($n), 16, '0', STR_PAD_LEFT);
    }

    public function bubblesort($arr) {
        if (func_num_args() > 1) { $arr = func_get_args(); }
        $n = count($arr);
        do {
            $sorted = false;
            for ($i = 0; $i < $n - 1; $i++) {
                if ($arr[$i] > $arr[$i + 1]) {
                    list($arr[$i], $arr[$i + 1]) = [$arr[$i + 1], $arr[$i]]; // Swap
                    $sorted = true;  
                }
            }
        } while ($sorted);
        return $arr; 
    }

    public function deliciousbadge($username = 'thinkphp', $amount = 10, $tag = '') {
        $url = 'http://feeds.delicious.com/v2/json/' . $username . ($tag ? '/' . $tag : '') . '?count=' . $amount;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if(curl_errno($ch)) {
            return 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $data = json_decode($output);
        if ($data === null) {
            return 'Error: Invalid response from Delicious API';
        }

        $ul = '<ul>';
        foreach ($data as $del) {
            $ul .= '<li><a href="' . $del->u . '">' . $del->d . '</a></li>';
        }
        $ul .= '</ul>';
        return $ul;
    }

    public function produit_cartesien($cards) {
        if (func_num_args() > 1) { $cards = func_get_args(); }
        $n = count($cards);
        $v = array_fill(0, $n, 1);
        $out = '';

        do {
            $out .= implode(' ', $v) . " \n ";
            $i = $n - 1;
            while ($i >= 0) { 
                if ($v[$i] == $cards[$i]) {
                    $v[$i] = 1;
                    $i--; 
                } else {
                    $v[$i]++;
                    $i = -2; 
                }  
            }
        } while ($i != -1); 

        return nl2br($out);
    }

    public function getTweets($username, $amount = 5, $linkify = false) {
        $api = 'http://twitter.com/statuses/user_timeline/' . $username . '.json?count=' . $amount;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $api);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        if(curl_errno($ch)) {
            return 'Error: ' . curl_error($ch);
        }
        curl_close($ch);

        $tweets = json_decode($output);
        if ($tweets === null) {
            return 'Error: Invalid response from Twitter API';
        }

        $out = '<ul>';
        foreach ($tweets as $tweet) {
            if ($linkify) { 
                $tweet = preg_replace("/(https?:\/\/[\w\-:;?&=+.%#\/]+)/i", '<a href="$1">$1</a>', $tweet->text);
                $tweet = preg_replace("/(^|\W)@(\w+)/i", '$1@<a href="http://twitter.com/$2">$2</a>', $tweet);
                $tweet = preg_replace("/(^|\W)#(\w+)/i", '$1#<a href="http://search.twitter.com/search?q=%23$2">$2</a>', $tweet);
                $out .= '<li>' . $tweet . '</li>';
            } else {
                $out .= '<li>' . $tweet->text . '</li>';
            }
        }
        $out .= '</ul>'; 
        return $out;
    }
}

?>
