<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit9320b324395ed9749d1d3791a53a94fe
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPDesk\\Bookings\\' => 16,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPDesk\\Bookings\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src/Plugin',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static $classMap = array (
        'BookingsVendor\\Monolog\\ErrorHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/ErrorHandler.php',
        'BookingsVendor\\Monolog\\Formatter\\ChromePHPFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/ChromePHPFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\ElasticaFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/ElasticaFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\FlowdockFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/FlowdockFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\FluentdFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/FluentdFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\FormatterInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/FormatterInterface.php',
        'BookingsVendor\\Monolog\\Formatter\\GelfMessageFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/GelfMessageFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\HtmlFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/HtmlFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\JsonFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/JsonFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\LineFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/LineFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\LogglyFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/LogglyFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\LogstashFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/LogstashFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\MongoDBFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/MongoDBFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\NormalizerFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/NormalizerFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\ScalarFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/ScalarFormatter.php',
        'BookingsVendor\\Monolog\\Formatter\\WildfireFormatter' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Formatter/WildfireFormatter.php',
        'BookingsVendor\\Monolog\\Handler\\AbstractHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/AbstractHandler.php',
        'BookingsVendor\\Monolog\\Handler\\AbstractProcessingHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/AbstractProcessingHandler.php',
        'BookingsVendor\\Monolog\\Handler\\AbstractSyslogHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/AbstractSyslogHandler.php',
        'BookingsVendor\\Monolog\\Handler\\AmqpHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/AmqpHandler.php',
        'BookingsVendor\\Monolog\\Handler\\BrowserConsoleHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/BrowserConsoleHandler.php',
        'BookingsVendor\\Monolog\\Handler\\BufferHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/BufferHandler.php',
        'BookingsVendor\\Monolog\\Handler\\ChromePHPHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ChromePHPHandler.php',
        'BookingsVendor\\Monolog\\Handler\\CouchDBHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/CouchDBHandler.php',
        'BookingsVendor\\Monolog\\Handler\\CubeHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/CubeHandler.php',
        'BookingsVendor\\Monolog\\Handler\\Curl\\Util' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/Curl/Util.php',
        'BookingsVendor\\Monolog\\Handler\\DeduplicationHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/DeduplicationHandler.php',
        'BookingsVendor\\Monolog\\Handler\\DoctrineCouchDBHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/DoctrineCouchDBHandler.php',
        'BookingsVendor\\Monolog\\Handler\\DynamoDbHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/DynamoDbHandler.php',
        'BookingsVendor\\Monolog\\Handler\\ElasticSearchHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ElasticSearchHandler.php',
        'BookingsVendor\\Monolog\\Handler\\ErrorLogHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ErrorLogHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FilterHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FilterHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FingersCrossedHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FingersCrossedHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FingersCrossed\\ActivationStrategyInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FingersCrossed/ActivationStrategyInterface.php',
        'BookingsVendor\\Monolog\\Handler\\FingersCrossed\\ChannelLevelActivationStrategy' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FingersCrossed/ChannelLevelActivationStrategy.php',
        'BookingsVendor\\Monolog\\Handler\\FingersCrossed\\ErrorLevelActivationStrategy' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FingersCrossed/ErrorLevelActivationStrategy.php',
        'BookingsVendor\\Monolog\\Handler\\FirePHPHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FirePHPHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FleepHookHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FleepHookHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FlowdockHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FlowdockHandler.php',
        'BookingsVendor\\Monolog\\Handler\\FormattableHandlerInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FormattableHandlerInterface.php',
        'BookingsVendor\\Monolog\\Handler\\FormattableHandlerTrait' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/FormattableHandlerTrait.php',
        'BookingsVendor\\Monolog\\Handler\\GelfHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/GelfHandler.php',
        'BookingsVendor\\Monolog\\Handler\\GroupHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/GroupHandler.php',
        'BookingsVendor\\Monolog\\Handler\\HandlerInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/HandlerInterface.php',
        'BookingsVendor\\Monolog\\Handler\\HandlerWrapper' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/HandlerWrapper.php',
        'BookingsVendor\\Monolog\\Handler\\HipChatHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/HipChatHandler.php',
        'BookingsVendor\\Monolog\\Handler\\IFTTTHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/IFTTTHandler.php',
        'BookingsVendor\\Monolog\\Handler\\InsightOpsHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/InsightOpsHandler.php',
        'BookingsVendor\\Monolog\\Handler\\LogEntriesHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/LogEntriesHandler.php',
        'BookingsVendor\\Monolog\\Handler\\LogglyHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/LogglyHandler.php',
        'BookingsVendor\\Monolog\\Handler\\MailHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/MailHandler.php',
        'BookingsVendor\\Monolog\\Handler\\MandrillHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/MandrillHandler.php',
        'BookingsVendor\\Monolog\\Handler\\MissingExtensionException' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/MissingExtensionException.php',
        'BookingsVendor\\Monolog\\Handler\\MongoDBHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/MongoDBHandler.php',
        'BookingsVendor\\Monolog\\Handler\\NativeMailerHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/NativeMailerHandler.php',
        'BookingsVendor\\Monolog\\Handler\\NewRelicHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/NewRelicHandler.php',
        'BookingsVendor\\Monolog\\Handler\\NullHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/NullHandler.php',
        'BookingsVendor\\Monolog\\Handler\\PHPConsoleHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/PHPConsoleHandler.php',
        'BookingsVendor\\Monolog\\Handler\\ProcessableHandlerInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ProcessableHandlerInterface.php',
        'BookingsVendor\\Monolog\\Handler\\ProcessableHandlerTrait' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ProcessableHandlerTrait.php',
        'BookingsVendor\\Monolog\\Handler\\PsrHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/PsrHandler.php',
        'BookingsVendor\\Monolog\\Handler\\PushoverHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/PushoverHandler.php',
        'BookingsVendor\\Monolog\\Handler\\RavenHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/RavenHandler.php',
        'BookingsVendor\\Monolog\\Handler\\RedisHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/RedisHandler.php',
        'BookingsVendor\\Monolog\\Handler\\RollbarHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/RollbarHandler.php',
        'BookingsVendor\\Monolog\\Handler\\RotatingFileHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/RotatingFileHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SamplingHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SamplingHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SlackHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SlackHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SlackWebhookHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SlackWebhookHandler.php',
        'BookingsVendor\\Monolog\\Handler\\Slack\\SlackRecord' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/Slack/SlackRecord.php',
        'BookingsVendor\\Monolog\\Handler\\SlackbotHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SlackbotHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SocketHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SocketHandler.php',
        'BookingsVendor\\Monolog\\Handler\\StreamHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/StreamHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SwiftMailerHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SwiftMailerHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SyslogHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SyslogHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SyslogUdpHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SyslogUdpHandler.php',
        'BookingsVendor\\Monolog\\Handler\\SyslogUdp\\UdpSocket' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/SyslogUdp/UdpSocket.php',
        'BookingsVendor\\Monolog\\Handler\\TestHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/TestHandler.php',
        'BookingsVendor\\Monolog\\Handler\\WhatFailureGroupHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/WhatFailureGroupHandler.php',
        'BookingsVendor\\Monolog\\Handler\\ZendMonitorHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Handler/ZendMonitorHandler.php',
        'BookingsVendor\\Monolog\\Logger' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Logger.php',
        'BookingsVendor\\Monolog\\Processor\\GitProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/GitProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\IntrospectionProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/IntrospectionProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\MemoryPeakUsageProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/MemoryPeakUsageProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\MemoryProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/MemoryProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\MemoryUsageProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/MemoryUsageProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\MercurialProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/MercurialProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\ProcessIdProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/ProcessIdProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\ProcessorInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/ProcessorInterface.php',
        'BookingsVendor\\Monolog\\Processor\\PsrLogMessageProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/PsrLogMessageProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\TagProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/TagProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\UidProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/UidProcessor.php',
        'BookingsVendor\\Monolog\\Processor\\WebProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Processor/WebProcessor.php',
        'BookingsVendor\\Monolog\\Registry' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Registry.php',
        'BookingsVendor\\Monolog\\ResettableInterface' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/ResettableInterface.php',
        'BookingsVendor\\Monolog\\SignalHandler' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/SignalHandler.php',
        'BookingsVendor\\Monolog\\Utils' => __DIR__ . '/../..' . '/vendor_prefixed/monolog/monolog/src/Monolog/Utils.php',
        'BookingsVendor\\WPDesk\\Helper\\Debug\\LibraryDebug' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Debug/LibraryDebug.php',
        'BookingsVendor\\WPDesk\\Helper\\HelperRemoveInfo' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/HelperRemoveNotice.php',
        'BookingsVendor\\WPDesk\\Helper\\HelperRemover' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/HelperRemover.php',
        'BookingsVendor\\WPDesk\\Helper\\Integration\\LicenseIntegration' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Integration/LicenseIntegration.php',
        'BookingsVendor\\WPDesk\\Helper\\Integration\\LogsIntegration' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Integration/LogsIntegration.php',
        'BookingsVendor\\WPDesk\\Helper\\Integration\\SettingsIntegration' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Integration/SettingsIntegration.php',
        'BookingsVendor\\WPDesk\\Helper\\Integration\\TrackerIntegration' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Integration/TrackerIntegration.php',
        'BookingsVendor\\WPDesk\\Helper\\Logs\\LibraryInfoProcessor' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Logs/LibraryInfoProcessor.php',
        'BookingsVendor\\WPDesk\\Helper\\Page\\LibraryDebugPage' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Page/LibraryDebugPage.php',
        'BookingsVendor\\WPDesk\\Helper\\Page\\SettingsPage' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/Page/SettingsPage.php',
        'BookingsVendor\\WPDesk\\Helper\\PrefixedHelperAsLibrary' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/PrefixedHelperAsLibrary.php',
        'BookingsVendor\\WPDesk\\Helper\\UpgradeSoonNotice' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-wpdesk-helper/src/UpgradeSoonNotice.php',
        'BookingsVendor\\WPDesk\\Logger\\BasicLoggerFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/BasicLoggerFactory.php',
        'BookingsVendor\\WPDesk\\Logger\\LoggerFacade' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/LoggerFacade.php',
        'BookingsVendor\\WPDesk\\Logger\\LoggerFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/LoggerFactory.php',
        'BookingsVendor\\WPDesk\\Logger\\WC\\Exception\\WCLoggerAlreadyCaptured' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WC/Exception/WCLoggerAlreadyCaptured.php',
        'BookingsVendor\\WPDesk\\Logger\\WC\\WooCommerceCapture' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WC/WooCommerceCapture.php',
        'BookingsVendor\\WPDesk\\Logger\\WC\\WooCommerceHandler' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WC/WooCommerceHandler.php',
        'BookingsVendor\\WPDesk\\Logger\\WC\\WooCommerceMonologPlugin' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WC/WooCommerceMonologPlugin.php',
        'BookingsVendor\\WPDesk\\Logger\\WPDeskLoggerFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WPDeskLoggerFactory.php',
        'BookingsVendor\\WPDesk\\Logger\\WP\\WPCapture' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/WP/WPCapture.php',
        'BookingsVendor\\WPDesk\\Notice\\AjaxHandler' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-notice/src/WPDesk/Notice/AjaxHandler.php',
        'BookingsVendor\\WPDesk\\Notice\\Factory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-notice/src/WPDesk/Notice/Factory.php',
        'BookingsVendor\\WPDesk\\Notice\\Notice' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-notice/src/WPDesk/Notice/Notice.php',
        'BookingsVendor\\WPDesk\\Notice\\PermanentDismissibleNotice' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-notice/src/WPDesk/Notice/PermanentDismissibleNotice.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\BuildDirector\\LegacyBuildDirector' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/BuildDirector/LegacyBuildDirector.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Builder\\AbstractBuilder' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Builder/AbstractBuilder.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Builder\\InfoActivationBuilder' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Builder/InfoActivationBuilder.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Builder\\InfoBuilder' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Builder/InfoBuilder.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\AbstractPlugin' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/AbstractPlugin.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\Activateable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/Activateable.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\ActivationAware' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/ActivationAware.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\ActivationTracker' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/ActivationTracker.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\Conditional' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/Conditional.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\Deactivateable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/Deactivateable.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\Hookable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/Hookable.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\HookableCollection' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/HookableCollection.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\HookableParent' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/HookableParent.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\HookablePluginDependant' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/HookablePluginDependant.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\PluginAccess' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/PluginAccess.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\SlimPlugin' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/SlimPlugin.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Plugin\\TemplateLoad' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/TemplateLoad.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\Exception\\ClassAlreadyExists' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/Exception/ClassAlreadyExists.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\Exception\\ClassNotExists' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/Exception/ClassNotExists.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\PluginStorage' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/PluginStorage.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\StaticStorage' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/StaticStorage.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\StorageFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/StorageFactory.php',
        'BookingsVendor\\WPDesk\\PluginBuilder\\Storage\\WordpressFilterStorage' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Storage/WordpressFilterStorage.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\BuilderTrait' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/BuilderTrait.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\InitializationFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/InitializationFactory.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\InitializationStrategy' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/InitializationStrategy.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\PluginDisablerByFileTrait' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/PluginDisablerByFileTrait.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\Simple\\HelperInstanceAsFilterTrait' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/HelperInstanceAsFilterTrait.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\Simple\\SimpleFactory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/Simple/SimpleFactory.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\Simple\\SimpleFreeStrategy' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/Simple/SimpleFreeStrategy.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\Simple\\SimplePaidStrategy' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/Simple/SimplePaidStrategy.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\Initialization\\Simple\\TrackerInstanceAsFilterTrait' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/Initialization/TrackerInstanceAsFilterTrait.php',
        'BookingsVendor\\WPDesk\\Plugin\\Flow\\PluginBootstrap' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-plugin-flow/src/PluginBootstrap.php',
        'BookingsVendor\\WPDesk_Basic_Requirement_Checker' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-basic-requirements/src/Basic_Requirement_Checker.php',
        'BookingsVendor\\WPDesk_Basic_Requirement_Checker_Factory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-basic-requirements/src/Basic_Requirement_Checker_Factory.php',
        'BookingsVendor\\WPDesk_Basic_Requirement_Checker_With_Update_Disable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-basic-requirements/src/Basic_Requirement_Checker_With_Update_Disable.php',
        'BookingsVendor\\WPDesk_Buildable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/WithoutNamespace/Buildable.php',
        'BookingsVendor\\WPDesk_Has_Plugin_Info' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/WithoutNamespace/Has_Plugin_Info.php',
        'BookingsVendor\\WPDesk_Logger' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/deprecated/wpdesk-logger.php',
        'BookingsVendor\\WPDesk_Logger_Factory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-logs/src/deprecated/wpdesk-logger-factory.php',
        'BookingsVendor\\WPDesk_Plugin_Info' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/WithoutNamespace/Plugin_Info.php',
        'BookingsVendor\\WPDesk_Requirement_Checker' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-basic-requirements/src/Requirement_Checker.php',
        'BookingsVendor\\WPDesk_Requirement_Checker_Factory' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-basic-requirements/src/Requirement_Checker_Factory.php',
        'BookingsVendor\\WPDesk_Translable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/WithoutNamespace/Translable.php',
        'BookingsVendor\\WPDesk_Translatable' => __DIR__ . '/../..' . '/vendor_prefixed/wpdesk/wp-builder/src/Plugin/WithoutNamespace/Translatable.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/DummyTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\TestLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/TestLogger.php',
        'WPDesk\\Bookings\\Admin\\MainAdmin' => __DIR__ . '/../..' . '/src/Plugin/Admin/MainAdmin.php',
        'WPDesk\\Bookings\\Admin\\WC_Order\\Order' => __DIR__ . '/../..' . '/src/Plugin/Admin/WC_Order/Order.php',
        'WPDesk\\Bookings\\Admin\\WC_Product\\PriceListDataTab' => __DIR__ . '/../..' . '/src/Plugin/Admin/WC_Product/PriceListDataTab.php',
        'WPDesk\\Bookings\\Core\\Database' => __DIR__ . '/../..' . '/src/Plugin/Core/Database.php',
        'WPDesk\\Bookings\\Frontend\\DatePicker' => __DIR__ . '/../..' . '/src/Plugin/Frontend/DatePicker.php',
        'WPDesk\\Bookings\\Frontend\\Frontend' => __DIR__ . '/../..' . '/src/Plugin/Frontend/Frontend.php',
        'WPDesk\\Bookings\\Frontend\\Order' => __DIR__ . '/../..' . '/src/Plugin/Frontend/Order.php',
        'WPDesk\\Bookings\\Frontend\\SingleProduct' => __DIR__ . '/../..' . '/src/Plugin/Frontend/SingleProduct.php',
        'WPDesk\\Bookings\\Helpers\\Booking' => __DIR__ . '/../..' . '/src/Plugin/Helpers/Booking.php',
        'WPDesk\\Bookings\\Helpers\\Common' => __DIR__ . '/../..' . '/src/Plugin/Helpers/Common.php',
        'WPDesk\\Bookings\\Helpers\\PriceList' => __DIR__ . '/../..' . '/src/Plugin/Helpers/PriceList.php',
        'WPDesk\\Bookings\\Plugin' => __DIR__ . '/../..' . '/src/Plugin/Plugin.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit9320b324395ed9749d1d3791a53a94fe::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit9320b324395ed9749d1d3791a53a94fe::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit9320b324395ed9749d1d3791a53a94fe::$classMap;

        }, null, ClassLoader::class);
    }
}
