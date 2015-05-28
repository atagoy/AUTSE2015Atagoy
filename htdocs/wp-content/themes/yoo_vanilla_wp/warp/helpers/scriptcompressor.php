<?php
/**
* @package   Warp Theme Framework
* @author    YOOtheme http://www.yootheme.com
* @copyright Copyright (C) YOOtheme GmbH
* @license   YOOtheme Proprietary Use License (http://www.yootheme.com/license)
*/

/*
	Class: WarpHelperScriptCompressor
		Javascript compressor helper class, minifies javascript
		Based on JSMin (http://code.google.com/p/jsmin-php, 2008 Ryan Grove <ryan@wonko.com>, MIT License)
*/
class WarpHelperScriptCompressor extends WarpHelper {

    var $ORD_LF    = 10;
    var $ORD_SPACE = 32;

    var $a           = '';
    var $b           = '';
    var $input       = '';
    var $inputIndex  = 0;
    var $inputLength = 0;
    var $lookAhead   = null;
    var $output      = '';
    var $error       = false;

    /**
     * Minify a Javascript string
     * 
     * @param string $script
     * 
     * @return string
     */
    function process($script) {
      $this->input       = str_replace("\r\n", "\n", $script);
      $this->inputLength = strlen($this->input);
      $this->a           = '';
      $this->b           = '';
      $this->inputIndex  = 0;
      $this->lookAhead   = null;
      $this->output      = '';
      $this->error       = false;
      
	  $minified = trim($this->min());
	  
      return $this->error ? $script : $minified;
    }

    // -- Instance Methods ---------------------------------------------

    function action($d) {
        switch($d) {
            case 1:
                $this->output .= $this->a;

            case 2:
                $this->a = $this->b;

                if ($this->a === "'" || $this->a === '"') {
                    for (;;) {
                        $this->output .= $this->a;
                        $this->a       = $this->get();

                        if ($this->a === $this->b) {
                            break;
                        }

                        if (ord($this->a) <= $this->ORD_LF) {
                            //Unterminated string literal.
							$this->error = true;
							return;
                        }

                        if ($this->a === '\\') {
                            $this->output .= $this->a;
                            $this->a       = $this->get();
                        }
                    }
                }

            case 3:
                $this->b = $this->next();

                if ($this->b === '/' && (
                        $this->a === '(' || $this->a === ',' || $this->a === '=' ||
                                $this->a === ':' || $this->a === '[' || $this->a === '!' ||
                                $this->a === '&' || $this->a === '|' || $this->a === '?')) {

                    $this->output .= $this->a . $this->b;

                    for (;;) {
                        $this->a = $this->get();

                        if ($this->a === '/') {
                            break;
                        } elseif ($this->a === '\\') {
                            $this->output .= $this->a;
                            $this->a       = $this->get();
                        } elseif (ord($this->a) <= $this->ORD_LF) {
                            //Unterminated regular expression literal.
							$this->error = true;
							return;
                        }

                        $this->output .= $this->a;
                    }

                    $this->b = $this->next();
                }
        }
    }

    function get() {
        $c = $this->lookAhead;
        $this->lookAhead = null;

        if ($c === null) {
            if ($this->inputIndex < $this->inputLength) {
                $c = substr($this->input, $this->inputIndex, 1);
                $this->inputIndex += 1;
            } else {
                $c = null;
            }
        }

        if ($c === "\r") {
            return "\n";
        }

        if ($c === null || $c === "\n" || ord($c) >= $this->ORD_SPACE) {
            return $c;
        }

        return ' ';
    }

    function isAlphaNum($c) {
        return ord($c) > 126 || $c === '\\' || preg_match('/^[\w\$]$/', $c) === 1;
    }

    function min() {
        $this->a = "\n";
        $this->action(3);

        while ($this->a !== null && !$this->error) {
            switch ($this->a) {
                case ' ':
                    if ($this->isAlphaNum($this->b)) {
                        $this->action(1);
                    } else {
                        $this->action(2);
                    }
                    break;

                case "\n":
                    switch ($this->b) {
                        case '{':
                        case '[':
                        case '(':
                        case '+':
                        case '-':
                            $this->action(1);
                            break;

                        case ' ':
                            $this->action(3);
                            break;

                        default:
                            if ($this->isAlphaNum($this->b)) {
                                $this->action(1);
                            }
                            else {
                                $this->action(2);
                            }
                    }
                    break;

                default:
                    switch ($this->b) {
                        case ' ':
                            if ($this->isAlphaNum($this->a)) {
                                $this->action(1);
                                break;
                            }

                            $this->action(3);
                            break;

                        case "\n":
                            switch ($this->a) {
                                case '}':
                                case ']':
                                case ')':
                                case '+':
                                case '-':
                                case '"':
                                case "'":
                                    $this->action(1);
                                    break;

                                default:
                                    if ($this->isAlphaNum($this->a)) {
                                        $this->action(1);
                                    }
                                    else {
                                        $this->action(3);
                                    }
                            }
                            break;

                        default:
                            $this->action(1);
                            break;
                    }
            }
        }

        return $this->output;
    }

    function next() {
        $c = $this->get();

        if ($c === '/') {
            switch($this->peek()) {
                case '/':
                    for (;;) {
                        $c = $this->get();

                        if (ord($c) <= $this->ORD_LF) {
                            return $c;
                        }
                    }

                case '*':
                    $this->get();

                    for (;;) {
                        switch($this->get()) {
                            case '*':
                                if ($this->peek() === '/') {
                                    $this->get();
                                    return ' ';
                                }
                                break;

                            case null:
                                //Unterminated comment.
								$this->error = true;
								return;
                        }
                    }

                default:
                    return $c;
            }
        }

        return $c;
    }

    function peek() {
        $this->lookAhead = $this->get();
        return $this->lookAhead;
    }

}