?<?php
interface FlexBuilder
{
    /**
     * Builds flex message structure.
     *
     * @return array Built flex message structure
     */
    public function build();
}

class FlexMessageBuilder implements MessageBuilder
{

    /** @var string */
    private $altText;
    /** @var FlexBuilder */
    private $container;



    /**
     * FlexMessageBuilder constructor.
     *
     * @param             $altText
     * @param FlexBuilder $container
     */
    public function __construct($altText, FlexBuilder $container)
    {
        $this->altText = $altText;
        $this->container = $container;
    }

    public static function create(...$args)
    {
        return new static(...$args);
    }

    /**
     * Builds message structure.
     *
     * @return array Built message structure.
     */
    public function buildMessage()
    {
        return [
            [
                'type' => MessageType::FLEX,
                'altText' => $this->altText,
                'contents' => $this->container->build(),
            ]
        ];
    }
}
?>
