<?php

if (!function_exists('macropiche')) {

    /**
     * Generate and return a string of HTML displaying:
     * - the file path
     * - the original file contents
     * - the processed file output as code (unless equal to original file content)
     * - the processed file output as HTML
     *
     * @param $path string Relative or absolute path to template/view file
     * @param $context mixed Optional data for the template parser
     * @return string HTML
     */
    function macropiche($path, $context = null)
    {
        $base_css_class = __FUNCTION__;

        $ob_level = ob_get_level();
        // Parse the template file
        try {
            $file_contents = file_get_contents($path, FILE_USE_INCLUDE_PATH);

            // Standard parser for PHP and HTML template files
            $parser = function ($path, $context) {
                if (!is_array($context)) {
                    $context = (array)$context;
                }
                extract($context);
                ob_start();
                include($path);
                $parsed_content = ob_get_clean();

                return $parsed_content;
            };

            // Render the output using the parser
            $output = call_user_func_array($parser, compact('path', 'context'));
        } catch (Exception $e) {
            while (ob_get_level() > $ob_level) {
                ob_end_clean();
            }
            // Any file- or parsing-related failures will be echoed in the output
            $output = $e->getMessage();
        }

        // The file path
        $html_parts[] = '<code class="' . htmlentities($base_css_class . '__path') . '"><em>' . htmlentities($path) . '</em></code>';
        // The file contents
        $html_parts[] = '<pre class="' . htmlentities($base_css_class . '__code') . '"><code class="language-[DETECTED LANGUAGE]" title="File contents">' . htmlentities($file_contents) . '</code></pre>';
        // The HTML output
        if ($output != $file_contents) {
            $html_parts['htmloutput'] = '<pre class="' . htmlentities($base_css_class . '__code-output') . '"><samp class="language-html" title="HTML output">' . htmlentities($output) . '</samp></pre>';
        }
        // The raw output
        $html_parts[] = '<div class="' . htmlentities($base_css_class . '__output') . '">';
        $html_parts[] = '<!-- Start of ' . htmlentities($path) . ' output -->';
        $html_parts[] = $output;
        $html_parts[] = '<!-- End of ' . htmlentities($path) . ' output -->';
        $html_parts[] = '<div>';

        // Wrap the output parts in a div
        array_unshift($html_parts, '<div class="' . htmlentities($base_css_class) . '">');
        array_push($html_parts, '</div>');

        return implode("\n", $html_parts);
    }
}