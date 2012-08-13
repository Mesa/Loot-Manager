<?php

namespace JackAssPHP\Helper;

class RoundTime
{

    protected $time_from = 0;
    protected $time_until = 0;
    protected $time_diff = 0;
    protected $rounded_time = 0;
    protected $string = "";

    public function __construct ()
    {
        $this->steps["year"] = 60 * 60 * 24 * 365;
        $this->steps["month"] = 60 * 60 * 24 * 30;
        $this->steps["day"] = 60 * 60 * 24;
        $this->steps["hour"] = 60 * 60;
        $this->steps["minute"] = 60;
        $this->steps["second"] = 1;

        $this->text["year"] = "Jahr(en)";
        $this->text["month"] = "Monat(en)";
        $this->text["day"] = "Tag(en)";
        $this->text["hour"] = "Stunde(n)";
        $this->text["minute"] = "Minute(n)";
        $this->text["second"] = "Sekunde(n)";
    }

    public function getDifference ( $time_from, $time_until )
    {
        $this->time_from = (int) $time_from;
        $this->time_until = (int) $time_until;

        $this->time_diff = $time_until - $time_from;

        foreach ( $this->steps as $key => $value ) {
            if ( $this->time_diff / $value > 1 ) {
                $this->rounded_time = round($this->time_diff / $value);
                $this->string = $this->text[$key];
                break;
            }
        }
    }

    public function getString ()
    {
        if ( $this->rounded_time == 1) {
            $this->string = preg_replace("/\(\w+\)/", "", $this->string);
        } else {
            $search[] = "(";
            $search[] = ")";
            $replace[] = "";
            $replace[] = "";
            $this->string = str_replace($search, $replace, $this->string);
        }
        return $this->rounded_time . " " . $this->string;
    }

}
