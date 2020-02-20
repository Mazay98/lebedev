<?php
namespace App;

class Text
{
    public $lengthTextPreview = 300;
    private $stopWords=[];
    private $text;

    /**
     * Создаем preview текста
     * @param $text string Изначальный текст до кадрирования
     * @return string
     */
    public function preview($text = ''):string
    {
        $this->text= $text;

        if (!$this->textValidate()) {
            return 'Error !, sorry this text is does not valid!';
        }

        if (mb_strlen($this->text) <= $this->lengthTextPreview) {
            $this->checkStringOnStopWords();
            return $this->text;
        }

        $this->converString();

        $this->checkStringOnStopWords();

        return $this->text;

    }

    /**
     * Добавляем стоп-слова
     * @param  $string string Слово которое необходимо сделать стоп-словом
     * @return boolean
     */
    public function addStopWord($string):bool
    {
        if (gettype($string) != 'string') {
            return false;
        }
        $string = trim($string);

        if (!in_array($string,$this->stopWords))
        {
            $this->stopWords[] = $string;
            return true;
        }
        return false;
    }

    /**
     * Проверяем строку на стоп-слова
     * @return boolean
     */
    private function checkStringOnStopWords():bool
    {
        if (count($this->stopWords) === 0) {
            return false;
        }
        if ($this->getPositionWords()) {
            $this->text= trim(substr ( $this->text , 0, $this->getPositionWords()));
            $this->checkStringOnStopWords();
        }
        return false;
    }

    /**
     * Получаем позицию стоп-слова
     * @return mixed
     */
    private function getPositionWords()
    {
        foreach ($this->stopWords as $word){
            $pos = stripos($this->text, $word);
            if ($pos){
                return $pos;
            }
        }
        return false;
    }

    /**
     * Кадрирование строки, убираем все ненужное
     * @return boolean
     */
    private function converString():bool
    {
        $this->text =  mb_substr($this->text, 0, $this->lengthTextPreview);
        $this->lengthTextPreview =  mb_strripos($this->text, ' ');

        $endWord = mb_substr($this->text, $this->lengthTextPreview  - 1, 1);

        if (in_array($endWord, array('.', '!', '?'))) {
            $this->text =  mb_substr($this->text, 0, $this->lengthTextPreview);
        } elseif (in_array($endWord, array(',', ':', ';', '«', '»', '…', '(', ')', '—', '–', '-'))) {
            $this->text = trim(mb_substr($this->text, 0,  $this->lengthTextPreview - 1));
        } else {
            $this->text = trim(mb_substr($this->text, 0, $this->lengthTextPreview));
        }
        return true;
    }

    /**
     * Валидация текста
     * @return boolean
     */
    private function textValidate():bool
    {
        $text = $this->text;
        //Удаляем экранирование символов
        $text = stripslashes($text);
        //Преобразуем html сущности в символы
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        //Удаляем переносы строк
        $text = str_ireplace(array('<br>', '<br />', '<br/>'), ' ', $text);
        //Удаляем теги из строки
        $text = strip_tags($text);
        $this->text = trim($text);
        return true;
    }

}