<?php

if (!function_exists('macropiche')) {

    /**
     * Generate and return a string of HTML displaying:
     * - the file path
     * - the original file contents
     * - the processed file output as code (unless equal to original file content)
     * - the processed file output as HTML
     *
     * @param $file    string Relative or absolute path to template/view file
     * @param $context array|mixed Optional data for the template parser
     * @return string HTML
     */
    function macropiche($file, $context = null)
    {
        $path = $file;
        $base_css_class = __FUNCTION__;

        // Parse the template file...
        $detected_language = 'html'; // Set language to HTML as default
        $file_contents = ''; // Set empty file contents as default
        $initial_ob_level = ob_get_level(); // Save the output buffer level for cleaning up afterwards

        try {
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

            // Attempt to use a Blade parser if available
            if (interface_exists('Illuminate\Contracts\View\Factory')) {
                if (function_exists('view') and view() instanceof Illuminate\Contracts\View\Factory) {
                    $blade = view();
                } elseif (function_exists('macropiche_blade_view')) {
                    $blade = macropiche_blade_view();
                }

                if (!empty($blade)) {
                    // Transform a blade view reference to a full path if possible
                    if (is_callable([$blade, 'getFinder',]) and
                        $blade->getFinder() instanceof \Illuminate\View\ViewFinderInterface and
                        $blade->exists($file)
                    ) {
                        $path = $blade->getFinder()->find($file);
                    }

                    // Try to verify if blade can handle the path
                    if (is_callable([$blade, 'getEngineFromPath'])) {
                        try {
                            $blade->getEngineFromPath($path);
                            $blade_can_handle_path = true;
                        } catch (Exception $e) {
                            $blade_can_handle_path = false;
                        }
                    }

                    if (!empty($blade_can_handle_path) or substr($path, -10) == '.blade.php') {
                        $parser = function ($path, $context) use ($blade) {
                            return $blade->file($path, $context ?: []);
                        };
                    }
                }
            }

            if (substr($path, -4) == '.php') {
                $detected_language = 'php';
            }

            if (substr($path, -10) == '.blade.php') {
                $detected_language = 'php'; // Until there is a good syntax highlighter for blade
            }

            $file_contents = @file_get_contents($path, FILE_USE_INCLUDE_PATH);
            if ($file_contents === false) {
                $error = error_get_last();
                throw new Exception($error['message']);
            }

            // Render the output using the parser
            $output = call_user_func_array($parser, compact('path', 'context'));
        } catch
        (Exception $e) {
            // Any file- or parsing-related failures will be echoed in the output
            $output = '<samp class="macropiche__error" title="Error message">' . $e->getMessage() . '</samp>';
        }
        // Clean up the output buffer
        while (ob_get_level() > $initial_ob_level) {
            ob_end_clean();
        }

        //Prefix element ids with a letter to avoid selector starting with digit
        $html_id = 'm';
        //Make id reasonable unique for template and context by hashing
        $html_id .= substr(sha1($path . serialize($context)), 0, 6);
        //Pick up to 3 last segments of the template path, excluding the file ending
        preg_match("/(([^\\/.]+\\/){0,2}[^.\\/]+)[^\\/]*$/", $path, $matches);
        $html_id .= '-' . $matches[1];
        $html_code_id = $html_id . '-code';

        // Build the HTML...

        // The file path
        $html_parts[] = '<a href="#' . $html_id . '" class="' . htmlentities($base_css_class . '__path') . '" title="Link to this section"><code>' . htmlentities($file) . '</code></a>';
        if ($file_contents) {
            // Anchor for source output (The tag is empty because only relevant with special styling anyway)
            $html_parts[] = '<a href="#' . $html_code_id . '" class="' . htmlentities($base_css_class . '__source-anchor') . '" id="' . $html_code_id . '" title="Source"></a>';
            // The file contents
            $html_parts[] = '<pre class="' . htmlentities($base_css_class . '__code') . '"><code class="language-' . htmlentities($detected_language) . '" title="File contents">' . htmlentities($file_contents) . '</code></pre>';
            // The HTML output
            if ($output != $file_contents) {
                $html_parts['htmloutput'] = '<pre class="' . htmlentities($base_css_class . '__code-output') . '"><code class="language-html" title="HTML output"><samp>' . htmlentities($output) . '</samp></code></pre>';
            }
        }
        // The raw output
        $html_parts[] = '<hr>';
        $html_parts[] = '<div class="' . htmlentities($base_css_class . '__output') . '">';
        $html_parts[] = '<!-- Start of ' . htmlentities($path) . ' output -->';
        $html_parts[] = $output;
        $html_parts[] = '<!-- End of ' . htmlentities($path) . ' output -->';
        $html_parts[] = '</div>';
        $html_parts[] = '<hr>';

        // Wrap the output parts in a div
        array_unshift($html_parts, '<div class="' . htmlentities($base_css_class) . '" id="' . $html_id . '">');
        array_push($html_parts, '</div>');

        return implode("\n", $html_parts);
    }
}