<?php

namespace inspektorsowa\LanguageDetector;

class LanguageDetector {

    protected $rules = [];

    public function __construct(array $rules = []) {
        $this->rules = $rules;
    }

    public function loadRulesFromDirectory(string $path): self {
        foreach (new \DirectoryIterator($path) as $file) {
            if (!$file->isDot() AND $file->isFile() AND preg_match('/\.json$/', $file->getFilename())) {
                $lang = str_replace('.json', '', $file->getFilename());
                $rules = json_decode(file_get_contents($file->getRealPath()));
                if (empty($this->rules[$lang])) {
                    $this->rules[$lang] = [];
                }
                $this->rules[$lang] =  array_merge($this->rules[$lang], $rules);
            }
        }

        return $this;
    }

    public function registerLanguageRules(string $lang, array $rules): self {
        $this->rules[$lang] = $rules;

        return $this;
    }

    public function detect(string $content): array {
        $stats = [];
        $results = [];

        foreach ($this->rules as $lang => $rules) {
            if (empty($stats[$lang])) {
                $stats[$lang] = [];
                $results[$lang] = 0;
            }
            foreach ($rules as $rule) {
                if (empty($stats[$lang][$rule])) {
                    $stats[$lang][$rule] = 0;
                }
                if (strpos($content, $rule) !== false) {
                    $stats[$lang][$rule]++;
                    $results[$lang]++;
                }
            }
        }

        $maxScore = max($results);
        $detectedLocale = array_flip($results)[$maxScore];

        return [
            'lang' => $detectedLocale,
            'maxScore' => $maxScore,
            'results' => $results,
            'stats' => $stats,
        ];
    }
}