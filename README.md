# LanguageDetector
Simple language detector that uses your own rules from the JSON files.

##Example

### 1. Create instance
```php
$detector = new \inspektorsowa\LanguageDetector\LanguageDetector();
```

### 2. Load language rules
```php
$detector->loadRulesFromDirectory(realpath('../src/lang-rules'));
```

### 3. Run detection on string $content
```php
$result = $detector->detect($content);
```

### 4. This is the most probably language:
```php
var_dump($result['lang']);
```

### Loading lanaguge rules

#### From JSON files
Rules can be loaded from the JSON files which name is
a language name + `.json`. For example `en.json`.

```php
$detector->loadRulesFromDirectory(realpath('../src/lang-rules'));
```

#### From array
```php
$detector->registerLanguageRules('en', $rulesArray);
```