<?php

/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace BookingsVendor\Monolog\Formatter;

use BookingsVendor\Monolog\Logger;
use BookingsVendor\Monolog\Utils;
/**
 * Formats incoming records into an HTML table
 *
 * This is especially useful for html email logging
 *
 * @author Tiago Brito <tlfbrito@gmail.com>
 */
class HtmlFormatter extends \BookingsVendor\Monolog\Formatter\NormalizerFormatter
{
    /**
     * Translates Monolog log levels to html color priorities.
     */
    protected $logLevels = array(\BookingsVendor\Monolog\Logger::DEBUG => '#cccccc', \BookingsVendor\Monolog\Logger::INFO => '#468847', \BookingsVendor\Monolog\Logger::NOTICE => '#3a87ad', \BookingsVendor\Monolog\Logger::WARNING => '#c09853', \BookingsVendor\Monolog\Logger::ERROR => '#f0ad4e', \BookingsVendor\Monolog\Logger::CRITICAL => '#FF7708', \BookingsVendor\Monolog\Logger::ALERT => '#C12A19', \BookingsVendor\Monolog\Logger::EMERGENCY => '#000000');
    /**
     * @param string $dateFormat The format of the timestamp: one supported by DateTime::format
     */
    public function __construct($dateFormat = null)
    {
        parent::__construct($dateFormat);
    }
    /**
     * Creates an HTML table row
     *
     * @param  string $th       Row header content
     * @param  string $td       Row standard cell content
     * @param  bool   $escapeTd false if td content must not be html escaped
     * @return string
     */
    protected function addRow($th, $td = ' ', $escapeTd = \true)
    {
        $th = \htmlspecialchars($th, \ENT_NOQUOTES, 'UTF-8');
        if ($escapeTd) {
            $td = '<pre>' . \htmlspecialchars($td, \ENT_NOQUOTES, 'UTF-8') . '</pre>';
        }
        return "<tr style=\"padding: 4px;text-align: left;\">\n<th style=\"vertical-align: top;background: #ccc;color: #000\" width=\"100\">{$th}:</th>\n<td style=\"padding: 4px;text-align: left;vertical-align: top;background: #eee;color: #000\">" . $td . "</td>\n</tr>";
    }
    /**
     * Create a HTML h1 tag
     *
     * @param  string $title Text to be in the h1
     * @param  int    $level Error level
     * @return string
     */
    protected function addTitle($title, $level)
    {
        $title = \htmlspecialchars($title, \ENT_NOQUOTES, 'UTF-8');
        return '<h1 style="background: ' . $this->logLevels[$level] . ';color: #ffffff;padding: 5px;" class="monolog-output">' . $title . '</h1>';
    }
    /**
     * Formats a log record.
     *
     * @param  array $record A record to format
     * @return mixed The formatted record
     */
    public function format(array $record)
    {
        $output = $this->addTitle($record['level_name'], $record['level']);
        $output .= '<table cellspacing="1" width="100%" class="monolog-output">';
        $output .= $this->addRow('Message', (string) $record['message']);
        $output .= $this->addRow('Time', $record['datetime']->format($this->dateFormat));
        $output .= $this->addRow('Channel', $record['channel']);
        if ($record['context']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['context'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('Context', $embeddedTable, \false);
        }
        if ($record['extra']) {
            $embeddedTable = '<table cellspacing="1" width="100%">';
            foreach ($record['extra'] as $key => $value) {
                $embeddedTable .= $this->addRow($key, $this->convertToString($value));
            }
            $embeddedTable .= '</table>';
            $output .= $this->addRow('Extra', $embeddedTable, \false);
        }
        return $output . '</table>';
    }
    /**
     * Formats a set of log records.
     *
     * @param  array $records A set of records to format
     * @return mixed The formatted set of records
     */
    public function formatBatch(array $records)
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }
        return $message;
    }
    protected function convertToString($data)
    {
        if (null === $data || \is_scalar($data)) {
            return (string) $data;
        }
        $data = $this->normalize($data);
        if (\version_compare(\PHP_VERSION, '5.4.0', '>=')) {
            return \BookingsVendor\Monolog\Utils::jsonEncode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES | \JSON_UNESCAPED_UNICODE, \true);
        }
        return \str_replace('\\/', '/', \BookingsVendor\Monolog\Utils::jsonEncode($data, null, \true));
    }
}
