<?php 

class TextCest
{
    private $testValidText = "12345 6789 1011 1213 1415 1617 1819 2021 2223 2425 2627";

    public function _before(UnitTester $I)
    {
    }

    public function testTextEmpty(UnitTester $I)
    {
        $I->assertSame('', (new \App\Text())->preview());
    }

    public function testTextNullLength(UnitTester $I)
    {
        $text = new \App\Text();
        $text->lengthTextPreview = 0;
        $I->assertSame('', $text->preview($this->testValidText));
    }

    public function testTextFull(UnitTester $I)
    {
        $text = new \App\Text();
        $text->lengthTextPreview = strlen($this->testValidText);
        $I->assertSame($this->testValidText, $text->preview($this->testValidText));
    }

    public function testTextLengthOk(UnitTester $I)
    {
        $text = new \App\Text();
        $text->lengthTextPreview = 15;
        $I->assertSame('12345 6789', $text->preview($this->testValidText));
    }

    public function testTextValidateHtmlChars(UnitTester $I)
    {
        $text=new \App\Text();
        $testText = $this->testValidText.'<br><hr><p></p><?php?><script></script><script>';
        $text->lengthTextPreview=strlen($testText);
        $I->assertSame($this->testValidText, $text->preview($testText));
    }

    public function testTextValidateSym(UnitTester $I)
    {
        $text=new \App\Text();
        $testText = "Text! ОК";
        $text->lengthTextPreview=6;

        $I->assertSame("Text!", $text->preview($testText));

        $text->lengthTextPreview=6;
        $testText = "Text, ok";
        $I->assertSame("Text", $text->preview($testText));
    }

    public function testTextStopWords(UnitTester $I)
    {
        $text=new \App\Text();
        $text->addStopWord('чича');
        $text->addStopWord('3');

        $I->assertSame("Текст", $text->preview("Текст чича".$this->testValidText));
        $I->assertSame("Текст", $text->preview("Текст 3 чича".$this->testValidText));
        $I->assertSame("Текст", $text->preview("Текст чича 3".$this->testValidText));
    }
    
}
