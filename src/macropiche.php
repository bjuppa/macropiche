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
     * @param $context array|mixed Optional data for the template parser
     * @return string HTML
     */
    function macropiche($path, $context = null)
    {
        $base_css_class = __FUNCTION__;

        // Parse the template file...
        $detected_language = 'html'; // Set language to HTML as default
        $file_contents = ''; // Set empty file contents as default
        $initial_ob_level = ob_get_level(); // Save the output buffer level for cleaning up afterwards
        try {
            $file_contents = file_get_contents($path, FILE_USE_INCLUDE_PATH);

            if (substr($path, -4) == '.php') {
                $detected_language = 'php';
            }

            // Declare the standard parser for PHP and HTML template files
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
            // Any file- or parsing-related failures will be echoed in the output
            $output = $e->getMessage();
        }
        // Clean up the output buffer
        while (ob_get_level() > $initial_ob_level) {
            ob_end_clean();
        }

        // Build the HTML...

        // Generate ids for elements
        $html_id = substr(sha1($path . serialize($context)), 0, 6);
        $html_code_id = $html_id . '-code';

        // The file path
        $html_parts[] = '<a href="#' . $html_id . '" class="' . htmlentities($base_css_class . '__path') . '"><code>' . htmlentities($path) . '</code></a>';
        // The file contents
        $html_parts[] = '<pre class="' . htmlentities($base_css_class . '__code') . '"><code class="language-' . htmlentities($detected_language) . '" title="File contents">' . htmlentities($file_contents) . '</code></pre>';
        // The HTML output
        if ($output != $file_contents) {
            $html_parts['htmloutput'] = '<samp class="' . htmlentities($base_css_class . '__code-output') . '"><pre><code class="language-html" title="HTML output">' . htmlentities($output) . '</code></pre></samp>';
        }
        // The raw output
        $html_parts[] = '<div class="' . htmlentities($base_css_class . '__output') . '">';
        $html_parts[] = '<!-- Start of ' . htmlentities($path) . ' output -->';
        $html_parts[] = $output;
        $html_parts[] = '<!-- End of ' . htmlentities($path) . ' output -->';
        $html_parts[] = '<div>';

        // Wrap the output parts in a div
        array_unshift($html_parts, '<div class="' . htmlentities($base_css_class) . '" id="' . $html_id . '">');
        array_push($html_parts, '</div>');

        return implode("\n", $html_parts);
    }
}