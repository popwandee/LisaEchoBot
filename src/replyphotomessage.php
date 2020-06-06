<?php
class ReplyPhotoMessage
{
    /**
     * Create  flex message
     *
     * @return \LINE\LINEBot\MessageBuilder\FlexMessageBuilder
     */
    public static function get($img_url0,$img_url1,$img_url2,$img_url3,$img_url4)
    {
        return FlexMessageBuilder::builder()
            ->setAltText('Lisa')
            ->setContents(
                BubbleContainerBuilder::builder()
                    ->setHero(self::createHeroBlock($img_url0))
                    ->setBody(self::createBodyBlock($img_url1,$img_url2,$img_url3))
                    ->setFooter(self::createFooterBlock($img_url4))
            );
    }
    private static function createHeroBlock($img_url0)
    {
        return ImageComponentBuilder::builder()
            ->setUrl($img_url0)
            ->setSize(ComponentImageSize::FULL)
            ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
            ->setAspectMode(ComponentImageAspectMode::FIT)
            ->setAction(new UriTemplateActionBuilder(null, 'https://res.cloudinary.com/dly6ftryr/image/upload/v1591162862/20200603-054101-1.jpg'));
    }
    private static function createBodyBlock($img_url1,$img_url2,$img_url3)
    {
        $textDetail = TextComponentBuilder::builder()
            ->setText('TextDetail')
            ->setSize(ComponentFontSize::LG)
            ->setColor('#000000')
            ->setMargin(ComponentMargin::MD)
	          ->setwrap(true)
            ->setFlex(2);
        $review = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            //->setLayout(ComponentLayout::BASELINE)
            ->setMargin(ComponentMargin::LG)
            //->setMargin(ComponentMargin::SM)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$textDetail]);


        $image = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::BASELINE)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([
              ImageComponentBuilder::builder()
                  ->setUrl($img_url1)
                  ->setSize(ComponentImageSize::FULL)
                  ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
                  ->setAspectMode(ComponentImageAspectMode::FIT)
                  ->setAction(new UriTemplateActionBuilder(null, 'https://res.cloudinary.com/dly6ftryr/image/upload/v1591162862/20200603-054101-1.jpg'))
        ,    ImageComponentBuilder::builder()
                  ->setUrl($img_url2)
                  ->setSize(ComponentImageSize::FULL)
                  ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
                  ->setAspectMode(ComponentImageAspectMode::FIT)
                  ->setAction(new UriTemplateActionBuilder(null, 'https://res.cloudinary.com/dly6ftryr/image/upload/v1591162862/20200603-054101-1.jpg'))
        ,    ImageComponentBuilder::builder()
                  ->setUrl($img_url3)
                  ->setSize(ComponentImageSize::FULL)
                  ->setAspectRatio(ComponentImageAspectRatio::R20TO13)
                  ->setAspectMode(ComponentImageAspectMode::FIT)
                  ->setAction(new UriTemplateActionBuilder(null, 'https://res.cloudinary.com/dly6ftryr/image/upload/v1591162862/20200603-054101-1.jpg'))

            ]);
        $detail = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::BASELINE)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([
                TextComponentBuilder::builder()
                    ->setText('Title')
                    ->setColor('#aaaaaa')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(1),
                TextComponentBuilder::builder()
                    ->setText('detail')
                    ->setWrap(true)
                    ->setColor('#666666')
                    ->setSize(ComponentFontSize::SM)
                    ->setFlex(5)
            ]);

        $info = BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setMargin(ComponentMargin::LG)
            ->setSpacing(ComponentSpacing::SM)
            ->setContents([$image, $detail]);
        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setContents([$review, $info]);
            //->setContents([$review]);
    }
    private static function createFooterBlock($img_url4)
    {

        $websiteButton = ButtonComponentBuilder::builder()
            ->setStyle(ComponentButtonStyle::LINK)
            ->setHeight(ComponentButtonHeight::SM)
            ->setFlex(0)
            ->setAction(new UriTemplateActionBuilder('เว็บรุ่น','https://lisaechobot.herokuapp.com'));
        $spacer = new SpacerComponentBuilder(ComponentSpaceSize::SM);
        return BoxComponentBuilder::builder()
            ->setLayout(ComponentLayout::VERTICAL)
            ->setSpacing(ComponentSpacing::SM)
            ->setFlex(0)
            ->setContents([$websiteButton, $spacer]);
    }
}

function tranlateLang($source, $target, $text_parameter)
{
    $text = str_replace($source,"", $text_parameter);
    $text = str_replace($target,"", $text);
    $trans = new GoogleTranslate();
    $result = $trans->translate($source, $target, $text);
    return $result;
}
?>
