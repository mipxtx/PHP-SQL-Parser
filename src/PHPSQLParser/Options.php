<?php
/**
 * @author     mfris
 *
 */

namespace PHPSQLParser;

/**
 *
 * @author  mfris
 * @package PHPSQLParser
 */
final class Options
{

    /**
     * @var array
     */
    private $options;

    /**
     * @const string
     */
    const CONSISTENT_SUB_TREES = 'consistent_sub_trees';

    /**
     * @const string
     */
    const ANSI_QUOTES = 'ansi_quotes';

    /**
     * @const string
     */
    const QUERY_DELIMITER = 'query_delimiter';

    /**
     * Options constructor.
     *
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return bool
     */
    public function getConsistentSubtrees()
    {
        return (isset($this->options[self::CONSISTENT_SUB_TREES]) && $this->options[self::CONSISTENT_SUB_TREES]);
    }

    /**
     * @return bool
     */
    public function getANSIQuotes()
    {
        return (isset($this->options[self::ANSI_QUOTES]) && $this->options[self::ANSI_QUOTES]);
    }

    public function getDelimiter()
    {
        if (isset($this->options[self::QUERY_DELIMITER])) {
            return $this->options[self::QUERY_DELIMITER];
        } else {
            return ";";
        }
    }
}
