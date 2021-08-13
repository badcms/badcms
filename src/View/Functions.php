<?php

namespace BadCMS\View;

//Dirty hack for autoload
use function BadCMS\Application\app;
use function BadCMS\Http\redirect;
use function BadCMS\Router\route;

class Functions
{
    const load = 1;
}

define('VIEW_CACHE_DIR', STORAGE_ROOT."cache/views");
function compileLayout($content)
{
    $args = str_replace(".", "/", trim($content, "(')\""));
    ob_start();
    $layout = file_get_contents(view_path($args.".php"));
    $layout = preg_replace('/{{[\s\t]*(\$\w*)[\s\t]*}}/imx', '<?php echo $1;?>', $layout);

    return $layout;
}

function formField($type = "text", $name = "", $label = "", $value = null, $options = [])
{
    $required = isset($options['required']) && $options['required'] ? " required" : "";
    $checked = $type == "checkbox" && ($value || $value == "on") ? " checked" : "";
    $autocomplete =
        isset($options['autocomplete']) && $options['autocomplete'] ? " autocomplete='{$options['autocomplete']}'" : "";
    $readonly = isset($options['readonly']) && $options['readonly'] ? " readonly" : "";

    return render("forms/field",
        compact('type', 'name', 'label', 'value', 'options', 'required', 'checked', 'autocomplete', 'readonly'));
}

function parseToken($token)
{
    [$id, $content] = $token;

    if ($id == T_INLINE_HTML) {
        if (preg_match('/\B@(@?\w+(?:::\w+)?)([ \t]*)(\( ( (?>[^()]+) | (?3) )* \))?/x', $content, $match)) {
            $statement = $match[1];
            if ($statement == 'extends') {
                $content = compileLayout($match[3]);
            }
        }
    }

    return $content;
}

function cache($file, $callback)
{
    $tokens = token_get_all(file_get_contents($file), TOKEN_PARSE);
    $result = '';
    $layout = '';
    $content = '';
    foreach (token_get_all(file_get_contents($file)) as $token) {
        if ($token[0] != T_INLINE_HTML) {
            dd($token);
        }
        $result .= is_array($token) ? parseToken($token) : $token;
    }
    dd($result);

    return $result;
}

function render(string $name, array $params = []): string
{
    $viewFile = file_exists($name) ? $name : view_path($name.".php");
    if (file_exists($viewFile)) {
        /**
         *  View can access application data via $app variable
         */
        $app = app();
        $renderFunc = function ($viewFile) use ($app, $params) {
            extract($params);
            ob_start();
            include($viewFile);
            return ob_get_clean();
        };

        return $renderFunc($viewFile);
    } else {
        flash("message", "View `$name` not found");
        redirect(route('404'));
    }

    return "";
}
