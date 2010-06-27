
<!-- saved from url=(0055)http://github.com/rgrove/jsmin-php/raw/master/jsmin.php -->
<HTML><BODY><PRE style="word-wrap: break-word; white-space: pre-wrap;">&lt;?php
/**
 * jsmin.php - PHP implementation of Douglas Crockford's JSMin.
 *
 * This is pretty much a direct port of jsmin.c to PHP with just a few
 * PHP-specific performance tweaks. Also, whereas jsmin.c reads from stdin and
 * outputs to stdout, this library accepts a string as input and returns another
 * string as output.
 *
 * PHP 5 or higher is required.
 *
 * Permission is hereby granted to use this version of the library under the
 * same terms as jsmin.c, which has the following license:
 *
 * --
 * Copyright (c) 2002 Douglas Crockford  (www.crockford.com)
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies
 * of the Software, and to permit persons to whom the Software is furnished to do
 * so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * The Software shall be used for Good, not Evil.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 * --
 *
 * @package JSMin
 * @author Ryan Grove &lt;ryan@wonko.com&gt;
 * @copyright 2002 Douglas Crockford &lt;douglas@crockford.com&gt; (jsmin.c)
 * @copyright 2008 Ryan Grove &lt;ryan@wonko.com&gt; (PHP port)
 * @license http://opensource.org/licenses/mit-license.php MIT License
 * @version 1.1.1 (2008-03-02)
 * @link http://code.google.com/p/jsmin-php/
 */

class JSMin {
  const ORD_LF    = 10;
  const ORD_SPACE = 32;

  protected $a           = '';
  protected $b           = '';
  protected $input       = '';
  protected $inputIndex  = 0;
  protected $inputLength = 0;
  protected $lookAhead   = null;
  protected $output      = '';

  // -- Public Static Methods --------------------------------------------------

  public static function minify($js) {
    $jsmin = new JSMin($js);
    return $jsmin-&gt;min();
  }

  // -- Public Instance Methods ------------------------------------------------

  public function __construct($input) {
    $this-&gt;input       = str_replace("\r\n", "\n", $input);
    $this-&gt;inputLength = strlen($this-&gt;input);
  }

  // -- Protected Instance Methods ---------------------------------------------

  protected function action($d) {
    switch($d) {
      case 1:
        $this-&gt;output .= $this-&gt;a;

      case 2:
        $this-&gt;a = $this-&gt;b;

        if ($this-&gt;a === "'" || $this-&gt;a === '"') {
          for (;;) {
            $this-&gt;output .= $this-&gt;a;
            $this-&gt;a       = $this-&gt;get();

            if ($this-&gt;a === $this-&gt;b) {
              break;
            }

            if (ord($this-&gt;a) &lt;= self::ORD_LF) {
              throw new JSMinException('Unterminated string literal.');
            }

            if ($this-&gt;a === '\\') {
              $this-&gt;output .= $this-&gt;a;
              $this-&gt;a       = $this-&gt;get();
            }
          }
        }

      case 3:
        $this-&gt;b = $this-&gt;next();

        if ($this-&gt;b === '/' &amp;&amp; (
            $this-&gt;a === '(' || $this-&gt;a === ',' || $this-&gt;a === '=' ||
            $this-&gt;a === ':' || $this-&gt;a === '[' || $this-&gt;a === '!' ||
            $this-&gt;a === '&amp;' || $this-&gt;a === '|' || $this-&gt;a === '?')) {

          $this-&gt;output .= $this-&gt;a . $this-&gt;b;

          for (;;) {
            $this-&gt;a = $this-&gt;get();

            if ($this-&gt;a === '/') {
              break;
            } elseif ($this-&gt;a === '\\') {
              $this-&gt;output .= $this-&gt;a;
              $this-&gt;a       = $this-&gt;get();
            } elseif (ord($this-&gt;a) &lt;= self::ORD_LF) {
              throw new JSMinException('Unterminated regular expression '.
                  'literal.');
            }

            $this-&gt;output .= $this-&gt;a;
          }

          $this-&gt;b = $this-&gt;next();
        }
    }
  }

  protected function get() {
    $c = $this-&gt;lookAhead;
    $this-&gt;lookAhead = null;

    if ($c === null) {
      if ($this-&gt;inputIndex &lt; $this-&gt;inputLength) {
        $c = substr($this-&gt;input, $this-&gt;inputIndex, 1);
        $this-&gt;inputIndex += 1;
      } else {
        $c = null;
      }
    }

    if ($c === "\r") {
      return "\n";
    }

    if ($c === null || $c === "\n" || ord($c) &gt;= self::ORD_SPACE) {
      return $c;
    }

    return ' ';
  }

  protected function isAlphaNum($c) {
    return ord($c) &gt; 126 || $c === '\\' || preg_match('/^[\w\$]$/', $c) === 1;
  }

  protected function min() {
    $this-&gt;a = "\n";
    $this-&gt;action(3);

    while ($this-&gt;a !== null) {
      switch ($this-&gt;a) {
        case ' ':
          if ($this-&gt;isAlphaNum($this-&gt;b)) {
            $this-&gt;action(1);
          } else {
            $this-&gt;action(2);
          }
          break;

        case "\n":
          switch ($this-&gt;b) {
            case '{':
            case '[':
            case '(':
            case '+':
            case '-':
              $this-&gt;action(1);
              break;

            case ' ':
              $this-&gt;action(3);
              break;

            default:
              if ($this-&gt;isAlphaNum($this-&gt;b)) {
                $this-&gt;action(1);
              }
              else {
                $this-&gt;action(2);
              }
          }
          break;

        default:
          switch ($this-&gt;b) {
            case ' ':
              if ($this-&gt;isAlphaNum($this-&gt;a)) {
                $this-&gt;action(1);
                break;
              }

              $this-&gt;action(3);
              break;

            case "\n":
              switch ($this-&gt;a) {
                case '}':
                case ']':
                case ')':
                case '+':
                case '-':
                case '"':
                case "'":
                  $this-&gt;action(1);
                  break;

                default:
                  if ($this-&gt;isAlphaNum($this-&gt;a)) {
                    $this-&gt;action(1);
                  }
                  else {
                    $this-&gt;action(3);
                  }
              }
              break;

            default:
              $this-&gt;action(1);
              break;
          }
      }
    }

    return $this-&gt;output;
  }

  protected function next() {
    $c = $this-&gt;get();

    if ($c === '/') {
      switch($this-&gt;peek()) {
        case '/':
          for (;;) {
            $c = $this-&gt;get();

            if (ord($c) &lt;= self::ORD_LF) {
              return $c;
            }
          }

        case '*':
          $this-&gt;get();

          for (;;) {
            switch($this-&gt;get()) {
              case '*':
                if ($this-&gt;peek() === '/') {
                  $this-&gt;get();
                  return ' ';
                }
                break;

              case null:
                throw new JSMinException('Unterminated comment.');
            }
          }

        default:
          return $c;
      }
    }

    return $c;
  }

  protected function peek() {
    $this-&gt;lookAhead = $this-&gt;get();
    return $this-&gt;lookAhead;
  }
}

// -- Exceptions ---------------------------------------------------------------
class JSMinException extends Exception {}
?&gt;</PRE></BODY></HTML>