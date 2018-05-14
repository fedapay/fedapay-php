<?php

namespace FedaPay\Util;

/**
 * Class Inflector
 *
 * @package FedaPay\Util
 */
abstract class Inflector
{
    /**
     * Plural inflector rules
     *
     * @var array
     */
    protected static $_plural = [
        '/(s)tatus$/i' => '\1tatuses',
        '/(quiz)$/i' => '\1zes',
        '/^(ox)$/i' => '\1\2en',
        '/([m|l])ouse$/i' => '\1ice',
        '/(matr|vert|ind)(ix|ex)$/i' => '\1ices',
        '/(x|ch|ss|sh)$/i' => '\1es',
        '/([^aeiouy]|qu)y$/i' => '\1ies',
        '/(hive)$/i' => '\1s',
        '/(chef)$/i' => '\1s',
        '/(?:([^f])fe|([lre])f)$/i' => '\1\2ves',
        '/sis$/i' => 'ses',
        '/([ti])um$/i' => '\1a',
        '/(p)erson$/i' => '\1eople',
        '/(?<!u)(m)an$/i' => '\1en',
        '/(c)hild$/i' => '\1hildren',
        '/(buffal|tomat)o$/i' => '\1\2oes',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin)us$/i' => '\1i',
        '/us$/i' => 'uses',
        '/(alias)$/i' => '\1es',
        '/(ax|cris|test)is$/i' => '\1es',
        '/s$/' => 's',
        '/^$/' => '',
        '/$/' => 's',
    ];
    /**
     * Singular inflector rules
     *
     * @var array
     */
    protected static $_singular = [
        '/(s)tatuses$/i' => '\1\2tatus',
        '/^(.*)(menu)s$/i' => '\1\2',
        '/(quiz)zes$/i' => '\\1',
        '/(matr)ices$/i' => '\1ix',
        '/(vert|ind)ices$/i' => '\1ex',
        '/^(ox)en/i' => '\1',
        '/(alias)(es)*$/i' => '\1',
        '/(alumn|bacill|cact|foc|fung|nucle|radi|stimul|syllab|termin|viri?)i$/i' => '\1us',
        '/([ftw]ax)es/i' => '\1',
        '/(cris|ax|test)es$/i' => '\1is',
        '/(shoe)s$/i' => '\1',
        '/(o)es$/i' => '\1',
        '/ouses$/' => 'ouse',
        '/([^a])uses$/' => '\1us',
        '/([m|l])ice$/i' => '\1ouse',
        '/(x|ch|ss|sh)es$/i' => '\1',
        '/(m)ovies$/i' => '\1\2ovie',
        '/(s)eries$/i' => '\1\2eries',
        '/([^aeiouy]|qu)ies$/i' => '\1y',
        '/(tive)s$/i' => '\1',
        '/(hive)s$/i' => '\1',
        '/(drive)s$/i' => '\1',
        '/([le])ves$/i' => '\1f',
        '/([^rfoa])ves$/i' => '\1fe',
        '/(^analy)ses$/i' => '\1sis',
        '/(analy|diagno|^ba|(p)arenthe|(p)rogno|(s)ynop|(t)he)ses$/i' => '\1\2sis',
        '/([ti])a$/i' => '\1um',
        '/(p)eople$/i' => '\1\2erson',
        '/(m)en$/i' => '\1an',
        '/(c)hildren$/i' => '\1\2hild',
        '/(n)ews$/i' => '\1\2ews',
        '/eaus$/' => 'eau',
        '/^(.*us)$/' => '\\1',
        '/s$/i' => ''
    ];
    /**
     * Irregular rules
     *
     * @var array
     */
    protected static $_irregular = [
        'atlas' => 'atlases',
        'beef' => 'beefs',
        'brief' => 'briefs',
        'brother' => 'brothers',
        'cafe' => 'cafes',
        'child' => 'children',
        'cookie' => 'cookies',
        'corpus' => 'corpuses',
        'cow' => 'cows',
        'criterion' => 'criteria',
        'ganglion' => 'ganglions',
        'genie' => 'genies',
        'genus' => 'genera',
        'graffito' => 'graffiti',
        'hoof' => 'hoofs',
        'loaf' => 'loaves',
        'man' => 'men',
        'money' => 'monies',
        'mongoose' => 'mongooses',
        'move' => 'moves',
        'mythos' => 'mythoi',
        'niche' => 'niches',
        'numen' => 'numina',
        'occiput' => 'occiputs',
        'octopus' => 'octopuses',
        'opus' => 'opuses',
        'ox' => 'oxen',
        'penis' => 'penises',
        'person' => 'people',
        'sex' => 'sexes',
        'soliloquy' => 'soliloquies',
        'testis' => 'testes',
        'trilby' => 'trilbys',
        'turf' => 'turfs',
        'potato' => 'potatoes',
        'hero' => 'heroes',
        'tooth' => 'teeth',
        'goose' => 'geese',
        'foot' => 'feet',
        'foe' => 'foes',
        'sieve' => 'sieves'
    ];
    /**
     * Words that should not be inflected
     *
     * @var array
     */
    protected static $_uninflected = [
        '.*[nrlm]ese', '.*data', '.*deer', '.*fish', '.*measles', '.*ois',
        '.*pox', '.*sheep', 'people', 'feedback', 'stadia', '.*?media',
        'chassis', 'clippers', 'debris', 'diabetes', 'equipment', 'gallows',
        'graffiti', 'headquarters', 'information', 'innings', 'news', 'nexus',
        'pokemon', 'proceedings', 'research', 'sea[- ]bass', 'series', 'species', 'weather'
    ];

    /**
     * Method cache array.
     *
     * @var array
     */
    protected static $_cache = [];

    /**
     * Return $word in plural form.
     *
     * @param  string $word Word in singular
     * @return string Word in plural
     */
    public static function pluralize($word)
    {
        if (isset(static::$_cache['pluralize'][$word])) {
            return static::$_cache['pluralize'][$word];
        }

        if (!isset(static::$_cache['irregular']['pluralize'])) {
            static::$_cache['irregular']['pluralize'] = '(?:' . implode('|', array_keys(static::$_irregular)) . ')';
        }

        if (preg_match('/(.*?(?:\\b|_))(' . static::$_cache['irregular']['pluralize'] . ')$/i', $word, $regs)) {
            static::$_cache['pluralize'][$word] = $regs[1] . substr($regs[2], 0, 1) .
                substr(static::$_irregular[strtolower($regs[2])], 1);
            return static::$_cache['pluralize'][$word];
        }

        if (!isset(static::$_cache['uninflected'])) {
            static::$_cache['uninflected'] = '(?:' . implode('|', static::$_uninflected) . ')';
        }

        if (preg_match('/^(' . static::$_cache['uninflected'] . ')$/i', $word, $regs)) {
            static::$_cache['pluralize'][$word] = $word;
            return $word;
        }

        foreach (static::$_plural as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                static::$_cache['pluralize'][$word] = preg_replace($rule, $replacement, $word);
                return static::$_cache['pluralize'][$word];
            }
        }
    }

    /**
     * Return $word in singular form.
     *
     * @param  string $word Word in plural
     * @return string Word in singular
     * @link   https://book.cakephp.org/3.0/en/core-libraries/inflector.html#creating-plural-singular-forms
     */
    public static function singularize($word)
    {
        if (isset(static::$_cache['singularize'][$word])) {
            return static::$_cache['singularize'][$word];
        }

        if (!isset(static::$_cache['irregular']['singular'])) {
            static::$_cache['irregular']['singular'] = '(?:' . implode('|', static::$_irregular) . ')';
        }

        if (preg_match('/(.*?(?:\\b|_))(' . static::$_cache['irregular']['singular'] . ')$/i', $word, $regs)) {
            static::$_cache['singularize'][$word] = $regs[1] . substr($regs[2], 0, 1) .
                substr(array_search(strtolower($regs[2]), static::$_irregular), 1);
            return static::$_cache['singularize'][$word];
        }
        if (!isset(static::$_cache['uninflected'])) {
            static::$_cache['uninflected'] = '(?:' . implode('|', static::$_uninflected) . ')';
        }

        if (preg_match('/^(' . static::$_cache['uninflected'] . ')$/i', $word, $regs)) {
            static::$_cache['pluralize'][$word] = $word;
            return $word;
        }

        foreach (static::$_singular as $rule => $replacement) {
            if (preg_match($rule, $word)) {
                static::$_cache['singularize'][$word] = preg_replace($rule, $replacement, $word);
                return static::$_cache['singularize'][$word];
            }
        }

        static::$_cache['singularize'][$word] = $word;
        return $word;
    }
}
