<?php

namespace app\Services;

class Template
{
    private string $directory;
    private ?string $layout;
    private array $yield;
    private ?string $section;

    public function __construct($directory)
    {
        $this->setDirectory($directory);
        $this->yield = [];
        $this->layout = null;
        $this->section = null;
    }

    private function setDirectory(string $directory)
    {
        $this->directory = $directory;
    }

    private function resolvePath(string $path) : string
    {
        return $this->directory . '/' . $path . '.php';
    }

    public function include(string $view_name)
    {
        ob_start(); // bắt đầu
        include_once $this->resolvePath($view_name);
        $content = ob_get_contents(); // lấy nội dung
        ob_end_clean(); // đóng
        echo $content;
    }

    public function section(string $name, string $value = '')
    {
        if (empty($value)) {
            $this->section = $name;
        } else {
            $this->yield[$name] = $value;
        }
        ob_start(); // bắt đầu
    }

    public function end()
    {
        $content = ob_get_contents(); // lấy nội dung
        ob_end_clean(); // đóng lại
        $this->yield[$this->section] = $content;
        $this->section = null;
    }

    public function layout(string $layout)
    {
        $layout = str_replace('.', '/', $layout);
        $this->layout = $layout;
    }

    public function yield(string $name)
    {
        if (isset($this->yield[$name]))
        {
            return $this->yield[$name];
        }
        return false;
    }

    public function render(string $view_name, array $data = [])
    {
        $view_name = str_replace('.', '/', $view_name);
        if (is_array($data)) {
            extract($data);
        }

        ob_start();
        include_once $this->resolvePath($view_name);
        $content = ob_get_clean();
        $content = $this->convertCharacter($content);
        $content = $this->convertForeach($content);
        $content = $this->convertIfCondition($content);
        if (empty($this->layout)) {
            eval(' ?> '.$content.' <?php ');
            return;
        }
        ob_clean();

        include_once $this->resolvePath($this->layout);
        $output = ob_get_contents();
        ob_end_clean();
        $output = $this->convertCharacter($output);
        $output = $this->convertForeach($output);
        $output = $this->convertIfCondition($output);
        echo eval(' ?> '.$output.' <?php ');
    }

    // tim kiếm và thay thế
    private function convertCharacter(string $output)
    {
        preg_match_all('~{{\s*(.+?)\s*}}~is', $output, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $output = str_replace($matches[0][$key], "<?php echo $value ?>", $output);
            }
        }
        preg_match_all('~{!\s*(.+?)\s*!}~is', $output, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $output = str_replace($matches[0][$key], "<?php $value ?>", $output);
            }
        }
        return $output;
    }

    // tim kiếm và thay thế
    private function convertForeach(string $output)
    {
        preg_match_all('/@foreach\s*\(\s*(.+)\s*\)/', $output, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $output = str_replace($matches[0][$key], "<?php foreach (".$value.") : ?>", $output);
            }
        }
        preg_match_all('/@endforeach/', $output, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $output = str_replace($matches[0][$key], '<?php endforeach; ?>', $output);
            }
        }
        return $output;
    }

    // tim kiếm và thay thế
    private function convertIfCondition(string $output)
    {
        preg_match_all('/@if\s*\(\s*(.+)\s*\)/', $output, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $output = str_replace($matches[0][$key], "<?php if ($value) : ?>", $output);
            }
        }
        preg_match_all('/@elseif\s*\(\s*(.+)\s*\)/', $output, $matches);
        if (!empty($matches[1])) {
            foreach ($matches[1] as $key => $value) {
                $output = str_replace($matches[0][$key], "<?php elseif ($value) : ?>", $output);
            }
        }
        preg_match_all('/@else/', $output, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $output = str_replace($matches[0][$key], '<?php else : ?>', $output);
            }
        }
        preg_match_all('/@endif/', $output, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $output = str_replace($matches[0][$key], '<?php endif; ?>', $output);
            }
        }
        preg_match_all('/@auth/', $output, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $output = str_replace($matches[0][$key], '<?php if (!empty($_SESSION["SESSION_AUTH"])) : ?>', $output);
            }
        }
        preg_match_all('/@endauth/', $output, $matches);
        if (!empty($matches[0])) {
            foreach ($matches[0] as $key => $value) {
                $output = str_replace($matches[0][$key], '<?php endif; ?>', $output);
            }
        }
        return $output;
    }
}