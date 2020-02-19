 <?php
 require_once "vendor/autoload.php";

 use App\Text;

 $text = 'Текст превью (или прехедер) —  это небольшой кусочек текста!
Который «подтягивается» из тела сообщения, и обычно он отображается 
под полями с именем отправителя и темой сообщения.';

 $textC = new Text();
 $textC->lengthTextPreview = 15;
 $textC->addStopWords('str');
//
 var_dump($textC->preview($text));
