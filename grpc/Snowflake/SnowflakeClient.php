<?php
// GENERATED CODE -- DO NOT EDIT!

namespace Snowflake;

/**
 */
class SnowflakeClient extends \Grpc\BaseStub {

    /**
     * @param string $hostname hostname
     * @param array $opts channel options
     * @param \Grpc\Channel $channel (optional) re-use channel object
     */
    public function __construct($hostname, $opts, $channel = null) {
        parent::__construct($hostname, $opts, $channel);
    }

    /**
     * @param \Snowflake\NextRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function Next(\Snowflake\NextRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/snowflake.Snowflake/Next',
        $argument,
        ['\Snowflake\NextReply', 'decode'],
        $metadata, $options);
    }

    /**
     * @param \Snowflake\ParseRequest $argument input argument
     * @param array $metadata metadata
     * @param array $options call options
     */
    public function Parse(\Snowflake\ParseRequest $argument,
      $metadata = [], $options = []) {
        return $this->_simpleRequest('/snowflake.Snowflake/Parse',
        $argument,
        ['\Snowflake\ParseReply', 'decode'],
        $metadata, $options);
    }

}
